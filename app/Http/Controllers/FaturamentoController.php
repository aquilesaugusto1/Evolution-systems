<?php

namespace App\Http\Controllers;

use App\Enums\FaturaStatusEnum;
use App\Mail\FaturaGeradaMail;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\Fatura;
use App\Traits\ConvertsTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'apontamento_ids' => 'required|array|min:1',
            'apontamento_ids.*' => 'exists:apontamentos,id',
        ]);

        $fatura = null;
        try {
            DB::beginTransaction();

            $contrato = Contrato::with('empresaParceira')->findOrFail($validated['contrato_id']);
            $apontamentos = Apontamento::whereIn('id', $validated['apontamento_ids'])->get();

            $totalHorasDecimal = $apontamentos->reduce(function ($carry, $item) {
                return $carry + abs($item->horas_gastas_decimal);
            }, 0);

            $valorTotalFatura = $totalHorasDecimal * ($contrato->valor_hora ?? 0);
            $anoMes = now()->format('Y-m');
            $ultimoNumero = Fatura::where('numero_fatura', 'like', "FAT-{$anoMes}-%")->count();
            $novoNumero = 'FAT-'.$anoMes.'-'.str_pad((string) ($ultimoNumero + 1), 4, '0', STR_PAD_LEFT);

            $fatura = Fatura::create([
                'contrato_id' => $contrato->id,
                'numero_fatura' => $novoNumero,
                'data_emissao' => now(),
                'data_vencimento' => now()->addDays(15),
                'valor_total' => $valorTotalFatura,
                'status' => FaturaStatusEnum::EM_ABERTO,
            ]);

            Apontamento::whereIn('id', $validated['apontamento_ids'])->update(['fatura_id' => $fatura->id]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao gerar a fatura: '.$e->getMessage());

            return back()->with('error', 'Erro ao gerar a fatura: '.$e->getMessage());
        }

        // Envio do e-mail após a transação ser bem-sucedida
        if ($fatura) {
            try {
                $fatura->load('contrato.empresaParceira', 'apontamentos.consultor');
                $contatoComercial = $fatura->contrato->empresaParceira->contato_comercial;

                if (isset($contatoComercial['email']) && filter_var($contatoComercial['email'], FILTER_VALIDATE_EMAIL)) {
                    Mail::to($contatoComercial['email'])->send(new FaturaGeradaMail($fatura));
                    Log::info("E-mail de fatura {$fatura->numero_fatura} enviado para {$contatoComercial['email']}");

                    return redirect()->route('faturamento.show', $fatura)->with('success', 'Fatura gerada e enviada ao cliente com sucesso!');
                } else {
                    Log::warning("Fatura {$fatura->numero_fatura} gerada, mas o cliente não possui um e-mail comercial válido para envio.");

                    return redirect()->route('faturamento.show', $fatura)->with('success', 'Fatura gerada com sucesso, mas não foi enviada por e-mail (cliente sem contato comercial válido).');
                }
            } catch (\Exception $e) {
                Log::error("Falha ao enviar e-mail da fatura {$fatura->numero_fatura}: ".$e->getMessage());

                return redirect()->route('faturamento.show', $fatura)->with('error', 'Fatura gerada, mas houve um erro ao enviar o e-mail.');
            }
        }

        return back()->with('error', 'Ocorreu um erro inesperado e a fatura não pôde ser processada.');
    }

    public function show(Fatura $fatura): View
    {
        $fatura->load('contrato.empresaParceira', 'apontamentos.consultor', 'creator');

        return view('faturamento.show', compact('fatura'));
    }

    public function destroy(Fatura $fatura): RedirectResponse
    {
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
