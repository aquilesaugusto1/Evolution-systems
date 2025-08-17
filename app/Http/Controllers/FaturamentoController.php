<?php

namespace App\Http\Controllers;

use App\Enums\FaturaStatusEnum;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;

class FaturamentoController extends Controller
{
    public function index(): View
    {
        $faturas = Fatura::with('contrato.empresaParceira')
            ->latest()
            ->paginate(15);

        return view('faturamento.index', compact('faturas'));
    }

    public function create(Request $request): View
    {
        // Linha de debug foi removida daqui

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

            $totalSegundos = 0;
            foreach ($apontamentos as $apontamento) {
                [$h, $m] = explode(':', $apontamento->horas_gastas);
                $totalSegundos += (int)$h * 3600 + (int)$m * 60;
            }

            if ($totalSegundos > 0) {
                $horas = floor($totalSegundos / 3600);
                $minutos = floor(($totalSegundos % 3600) / 60);
                $totalHoras = sprintf('%02d:%02d', $horas, $minutos);
                $valorTotal = ($totalSegundos / 3600) * $contratoSelecionado->valor_hora;
            }
        }

        return view('faturamento.create', compact('contratos', 'apontamentos', 'totalHoras', 'valorTotal', 'contratoSelecionado'));
    }

    // ... todos os outros métodos (store, show, destroy) continuam iguais ...
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'apontamento_ids' => 'required|array|min:1',
            'apontamento_ids.*' => 'exists:apontamentos,id',
        ]);

        $contrato = Contrato::findOrFail($validated['contrato_id']);
        $apontamentos = Apontamento::whereIn('id', $validated['apontamento_ids'])->get();

        $totalSegundos = 0;
        foreach ($apontamentos as $apontamento) {
            [$h, $m] = explode(':', $apontamento->horas_gastas);
            $totalSegundos += (int)$h * 3600 + (int)$m * 60;
        }
        $valorTotalFatura = ($totalSegundos / 3600) * $contrato->valor_hora;
        $anoMes = now()->format('Y-m');
        $ultimoNumero = Fatura::where('numero_fatura', 'like', "FAT-{$anoMes}-%")->count();
        $novoNumero = 'FAT-' . $anoMes . '-' . str_pad((string)($ultimoNumero + 1), 4, '0', STR_PAD_LEFT);

        try {
            DB::beginTransaction();

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

            return redirect()->route('faturamento.show', $fatura)->with('success', 'Fatura gerada com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao gerar a fatura: ' . $e->getMessage());
        }
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
            return back()->with('error', 'Erro ao cancelar a fatura: ' . $e->getMessage());
        }
    }
}