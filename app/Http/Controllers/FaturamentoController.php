<?php

namespace App\Http\Controllers;

use App\Enums\FaturaStatusEnum;
use App\Mail\FaturaGeradaMail;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\Fatura;
use App\Services\AsaasService;
use App\Traits\ConvertsTime;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class FaturamentoController extends Controller
{
    use ConvertsTime;

    public function index(): View
    {
        $faturas = Fatura::with('contrato.empresaParceira')
            ->latest()
            ->paginate(15);

        return view('faturamento.index', compact('faturas'));
    }

    public function create(Request $request): View
    {
        $contratos = Contrato::where('status', 'Ativo')->orderBy('numero_contrato')->get();
        $selectedContratoId = $request->query('contrato_id');
        $apontamentos = collect();
        $totalHoras = '00:00';
        $valorTotal = 0.00;
        $contratoSelecionado = null;

        if ($selectedContratoId && $request->query('data_inicio') && $request->query('data_fim')) {
            $dataInicio = Carbon::parse($request->query('data_inicio'));
            $dataFim = Carbon::parse($request->query('data_fim'));

            $contratoSelecionado = Contrato::findOrFail($selectedContratoId);

            $apontamentos = Apontamento::where('contrato_id', $selectedContratoId)
                ->where('status', 'Aprovado')
                ->where('faturavel', true)
                ->whereNull('fatura_id')
                ->whereBetween('data_apontamento', [$dataInicio, $dataFim])
                ->with('consultor')
                ->orderBy('data_apontamento')
                ->get();

            $totalHorasDecimal = $apontamentos->reduce(function ($carry, $item) {
                return $carry + abs($item->horas_gastas_decimal);
            }, 0);

            if ($totalHorasDecimal > 0) {
                $valorTotal = $totalHorasDecimal * ($contratoSelecionado->valor_hora ?? 0);
                $totalHoras = self::decimalToTime($totalHorasDecimal);
            }
        }

        return view('faturamento.create', compact('contratos', 'apontamentos', 'totalHoras', 'valorTotal', 'contratoSelecionado'));
    }

    public function store(Request $request, AsaasService $asaasService): RedirectResponse
    {
        $validated = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'apontamento_ids' => 'required|array|min:1',
            'apontamento_ids.*' => 'exists:apontamentos,id',
        ]);

        try {
            $fatura = DB::transaction(function () use ($validated, $asaasService) {
                $contrato = Contrato::with('empresaParceira')->findOrFail($validated['contrato_id']);
                $apontamentos = Apontamento::whereIn('id', $validated['apontamento_ids'])->get();

                // CORREÇÃO: Garante que a soma das horas seja sempre positiva usando abs().
                $totalHorasDecimal = $apontamentos->reduce(function ($carry, $item) {
                    return $carry + abs($item->horas_gastas_decimal);
                }, 0);

                $valorTotalFatura = round($totalHorasDecimal * ($contrato->valor_hora ?? 0), 2);

                $anoMes = now()->format('Y-m');
                $ultimoNumero = Fatura::where('numero_fatura', 'like', "FAT-{$anoMes}-%")->count();
                $novoNumero = 'FAT-'.$anoMes.'-'.str_pad((string) ($ultimoNumero + 1), 4, '0', STR_PAD_LEFT);

                // 1. Cria a fatura local
                $fatura = Fatura::create([
                    'contrato_id' => $contrato->id,
                    'numero_fatura' => $novoNumero,
                    'data_emissao' => now(),
                    'data_vencimento' => now()->addDays(15),
                    'valor_total' => $valorTotalFatura,
                    'status' => FaturaStatusEnum::EM_ABERTO,
                ]);

                // 2. Vincula os apontamentos à fatura
                Apontamento::whereIn('id', $validated['apontamento_ids'])->update(['fatura_id' => $fatura->id]);

                // 3. Cria a cobrança no Asaas
                $cobrancaAsaas = $asaasService->criarCobranca($fatura);

                if (! $cobrancaAsaas) {
                    throw new \Exception('Não foi possível gerar a cobrança no gateway de pagamento.');
                }

                // 4. Atualiza a fatura local com os dados do Asaas
                $fatura->update([
                    'asaas_payment_id' => $cobrancaAsaas['id'],
                    'asaas_payment_url' => $cobrancaAsaas['invoiceUrl'],
                    'asaas_pix_qrcode' => $cobrancaAsaas['pixQrCode']['encodedImage'] ?? null,
                    'asaas_pix_payload' => $cobrancaAsaas['pixQrCode']['payload'] ?? null,
                ]);

                return $fatura;
            });

            // 5. Se tudo deu certo, envia o e-mail
            if ($fatura) {
                $this->enviarEmailFatura($fatura);
                return redirect()->route('faturamento.show', $fatura)->with('success', 'Fatura gerada com sucesso e registrada no Asaas!');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao gerar a fatura e registrar no Asaas: '.$e->getMessage());
            return back()->with('error', 'Erro ao gerar a fatura: '.$e->getMessage())->withInput();
        }

        return back()->with('error', 'Ocorreu um erro inesperado e a fatura não pôde ser processada.')->withInput();
    }

    private function enviarEmailFatura(Fatura $fatura)
    {
        try {
            $fatura->load('contrato.empresaParceira');
            $empresa = $fatura->contrato->empresaParceira;
            $contatoFinanceiro = $empresa->contato_financeiro;
            $contatoComercial = $empresa->contato_comercial;

            $emailDestino = null;
            if (isset($contatoFinanceiro['email']) && filter_var($contatoFinanceiro['email'], FILTER_VALIDATE_EMAIL)) {
                $emailDestino = $contatoFinanceiro['email'];
            } elseif (isset($contatoComercial['email']) && filter_var($contatoComercial['email'], FILTER_VALIDATE_EMAIL)) {
                $emailDestino = $contatoComercial['email'];
            }

            if ($emailDestino) {
                Mail::to($emailDestino)->send(new FaturaGeradaMail($fatura));
                Log::info("E-mail de fatura {$fatura->numero_fatura} enviado para {$emailDestino}");
            } else {
                Log::warning("Fatura {$fatura->numero_fatura} gerada, mas o cliente não possui um e-mail válido para envio.");
            }
        } catch (\Exception $e) {
            Log::error("Falha ao enviar e-mail da fatura {$fatura->numero_fatura}: ".$e->getMessage());
            // Não retorna erro para o usuário, apenas loga.
        }
    }


    public function show(Fatura $fatura): View
    {
        $fatura->load('contrato.empresaParceira', 'apontamentos.consultor', 'creator');

        return view('faturamento.show', compact('fatura'));
    }

    public function downloadPdf(Fatura $fatura): Response
    {
        $fatura->load('contrato.empresaParceira', 'apontamentos.consultor');
        $pdf = Pdf::loadView('faturamento.pdf', compact('fatura'));

        return $pdf->download('fatura-'.$fatura->numero_fatura.'.pdf');
    }

    public function destroy(Fatura $fatura): RedirectResponse
    {
        // Futuramente, podemos adicionar a lógica para cancelar a cobrança no Asaas aqui.
        try {
            DB::beginTransaction();

            Apontamento::where('fatura_id', $fatura->id)->update(['fatura_id' => null]);
            $fatura->update(['status' => FaturaStatusEnum::CANCELADA]);
            $fatura->delete();

            DB::commit();

            return redirect()->route('faturamento.index')->with('success', 'Fatura cancelada e apontamentos liberados.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao cancelar a fatura: '.$e->getMessage());
        }
    }
}
