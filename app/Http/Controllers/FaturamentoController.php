<?php

namespace App\Http\Controllers;

use App\Enums\FaturaStatusEnum;
use App\Mail\FaturaGeradaMail;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\Fatura;
use App\Models\Tax; // Adicionado
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
        $contratos = Contrato::with('empresaParceira')->where('status', 'Ativo')->orderBy('numero_contrato')->get();
        $impostos = Tax::where('ativo', true)->get(); // Adicionado para buscar os impostos
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
                ->whereNull('fatura_id')
                ->whereBetween('data_apontamento', [$dataInicio, $dataFim])
                ->whereHas('agenda', function ($query) {
                    $query->where('faturavel', true);
                })
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

        return view('faturamento.create', compact('contratos', 'apontamentos', 'totalHoras', 'valorTotal', 'contratoSelecionado', 'impostos')); // Passa os impostos para a view
    }

    public function store(Request $request, AsaasService $asaasService): RedirectResponse
    {
        $validated = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'apontamento_ids' => 'required|array|min:1',
            'apontamento_ids.*' => 'exists:apontamentos,id',
            'billing_type' => 'required|string|in:PIX,BOLETO,CREDIT_CARD,UNDEFINED',
            'impostos_ids' => 'nullable|array', // Validação para os impostos
            'impostos_ids.*' => 'exists:taxes,id', // Validação para os impostos
        ]);

        try {
            $fatura = DB::transaction(function () use ($validated, $asaasService) {
                $contrato = Contrato::with('empresaParceira')->findOrFail($validated['contrato_id']);
                $apontamentos = Apontamento::whereIn('id', $validated['apontamento_ids'])->get();

                $totalHorasDecimal = $apontamentos->reduce(function ($carry, $item) {
                    return $carry + abs($item->horas_gastas_decimal);
                }, 0);

                $valorBaseFatura = round($totalHorasDecimal * ($contrato->valor_hora ?? 0), 2);
                
                // --- Lógica de cálculo dos impostos ---
                $impostosSelecionados = Tax::whereIn('id', $validated['impostos_ids'] ?? [])->get();
                $impostosParaSalvar = [];
                $valorTotalImpostos = 0;

                foreach ($impostosSelecionados as $imposto) {
                    $valorCalculado = 0;
                    if ($imposto->tipo_aliquota == 'percentual') {
                        $valorCalculado = ($valorBaseFatura * $imposto->aliquota) / 100;
                    } else { // tipo_aliquota == 'fixa'
                        $valorCalculado = $imposto->aliquota;
                    }
                    $valorTotalImpostos += $valorCalculado;
                    $impostosParaSalvar[$imposto->id] = ['valor_imposto' => round($valorCalculado, 2)];
                }
                
                $valorTotalFatura = $valorBaseFatura + $valorTotalImpostos;
                // --- Fim da lógica dos impostos ---

                $anoMes = now()->format('Y-m');
                $ultimoNumero = Fatura::where('numero_fatura', 'like', "FAT-{$anoMes}-%")->count();
                $novoNumero = 'FAT-'.$anoMes.'-'.str_pad((string) ($ultimoNumero + 1), 4, '0', STR_PAD_LEFT);

                $fatura = Fatura::create([
                    'contrato_id' => $contrato->id,
                    'numero_fatura' => $novoNumero,
                    'data_emissao' => now(),
                    'data_vencimento' => now()->addDays(15),
                    'valor_total' => round($valorTotalFatura, 2), // Salva o valor final com impostos
                    'status' => FaturaStatusEnum::EM_ABERTO,
                    'billing_type' => $validated['billing_type'],
                ]);

                // Salva os impostos na tabela pivot
                if (!empty($impostosParaSalvar)) {
                    $fatura->impostos()->sync($impostosParaSalvar);
                }

                Apontamento::whereIn('id', $validated['apontamento_ids'])->update(['fatura_id' => $fatura->id]);

                $cobrancaAsaas = $asaasService->criarCobranca($fatura, $validated['billing_type']);

                if (! $cobrancaAsaas) {
                    throw new \Exception('Não foi possível gerar a cobrança no gateway de pagamento.');
                }

                $fatura->update([
                    'asaas_payment_id' => $cobrancaAsaas['id'],
                    'asaas_payment_url' => $cobrancaAsaas['invoiceUrl'],
                    'asaas_pix_qrcode' => $cobrancaAsaas['pixQrCode']['encodedImage'] ?? null,
                    'asaas_pix_payload' => $cobrancaAsaas['pixQrCode']['payload'] ?? null,
                    'asaas_boleto_url' => $cobrancaAsaas['bankSlipUrl'] ?? null,
                    'asaas_boleto_barcode' => $cobrancaAsaas['identificationField'] ?? null,
                ]);

                return $fatura;
            });

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
        }
    }

    public function show(Fatura $fatura): View
    {
        $fatura->load('contrato.empresaParceira', 'apontamentos.consultor', 'creator', 'impostos'); // Carrega os impostos

        return view('faturamento.show', compact('fatura'));
    }

    public function downloadPdf(Fatura $fatura): Response
    {
        $fatura->load('contrato.empresaParceira', 'apontamentos.consultor', 'impostos'); // Carrega os impostos
        $pdf = Pdf::loadView('faturamento.pdf', compact('fatura'));

        return $pdf->download('fatura-'.$fatura->numero_fatura.'.pdf');
    }

    public function destroy(Fatura $fatura, AsaasService $asaasService): RedirectResponse
    {
        if ($fatura->asaas_payment_id) {
            $canceladoAsaas = $asaasService->cancelarCobranca($fatura->asaas_payment_id);
            if (! $canceladoAsaas) {
                return back()->with('error', 'Não foi possível cancelar a cobrança no gateway de pagamento. A fatura não foi alterada. Verifique os logs para mais detalhes.');
            }
        }

        try {
            DB::transaction(function () use ($fatura) {
                Apontamento::where('fatura_id', $fatura->id)->update(['fatura_id' => null]);
                $fatura->impostos()->sync([]); // Limpa os impostos relacionados
                $fatura->update(['status' => FaturaStatusEnum::CANCELADA]);
                $fatura->delete();
            });

            return redirect()->route('faturamento.index')->with('success', 'Fatura cancelada com sucesso no sistema e no Asaas.');
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar a fatura localmente após sucesso no Asaas: '.$e->getMessage());
            return back()->with('error', 'A cobrança foi cancelada no Asaas, mas ocorreu um erro ao atualizar o sistema local. Por favor, verifique.');
        }
    }
}