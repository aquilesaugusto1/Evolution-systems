<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\User;
use App\Services\AsaasService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PagamentoController extends Controller
{
    public function index(Request $request): View
    {
        $mes = (int) $request->input('mes', Carbon::now()->month);
        $ano = (int) $request->input('ano', Carbon::now()->year);
        $periodo = Carbon::createFromDate($ano, $mes, 1);

        $colaboradores = User::where('status', 'ativo')
            ->whereIn('tipo_contrato', ['CLT', 'PJ Mensal', 'PJ Horista', 'Estágio'])
            ->orderBy('nome')
            ->get();

        $dadosPagamento = $colaboradores->map(function ($colaborador) use ($periodo) {
            $dados = [
                'id' => $colaborador->id,
                'nome' => $colaborador->nome . ' ' . $colaborador->sobrenome,
                'tipo_contrato' => $colaborador->tipo_contrato,
                'valor_calculado' => 0,
                'horas_trabalhadas' => 0,
                'status_pagamento' => 'Pendente',
            ];

            $pagamentoExistente = $colaborador->pagamentos()
                ->whereYear('periodo_ref', $periodo->year)
                ->whereMonth('periodo_ref', $periodo->month)
                ->first();

            if ($pagamentoExistente) {
                $dados['status_pagamento'] = ucfirst($pagamentoExistente->status);
                $dados['valor_calculado'] = $pagamentoExistente->valor_total;
            } else {
                switch ($colaborador->tipo_contrato) {
                    case 'CLT':
                    case 'PJ Mensal':
                    case 'Estágio':
                        $dados['valor_calculado'] = $colaborador->salario_mensal ?? 0;
                        break;
                    case 'PJ Horista':
                        $apontamentos = $colaborador->apontamentos()
                            ->where('status', 'Aprovado')
                            ->whereDoesntHave('pagamentos')
                            ->whereYear('data_apontamento', $periodo->year)
                            ->whereMonth('data_apontamento', $periodo->month)
                            ->get();

                        $totalHoras = $apontamentos->sum('horas_gastas_decimal');
                        $dados['horas_trabalhadas'] = $totalHoras;
                        $dados['valor_calculado'] = $totalHoras * ($colaborador->valor_hora ?? 0);
                        break;
                }
            }
            
            return $dados;
        });

        return view('pagamentos.index', [
            'dadosPagamento' => $dadosPagamento,
            'mes' => $mes,
            'ano' => $ano,
        ]);
    }

    public function processar(Request $request, AsaasService $asaasService): RedirectResponse
    {
        $mes = (int) $request->input('mes');
        $ano = (int) $request->input('ano');
        $periodo = Carbon::createFromDate($ano, $mes, 1);

        $colaboradores = User::where('status', 'ativo')
            ->whereIn('tipo_contrato', ['CLT', 'PJ Mensal', 'PJ Horista', 'Estágio'])
            ->get();

        $sucessos = 0;
        $falhas = 0;

        foreach ($colaboradores as $colaborador) {
            DB::transaction(function () use ($colaborador, $periodo, $asaasService, &$sucessos, &$falhas) {
                if ($colaborador->pagamentos()->whereYear('periodo_ref', $periodo->year)->whereMonth('periodo_ref', $periodo->month)->exists()) {
                    return;
                }

                $valorAPagar = 0;
                $apontamentosIds = [];

                switch ($colaborador->tipo_contrato) {
                    case 'CLT':
                    case 'PJ Mensal':
                    case 'Estágio':
                        $valorAPagar = $colaborador->salario_mensal ?? 0;
                        break;
                    case 'PJ Horista':
                        $apontamentos = $colaborador->apontamentos()
                            ->where('status', 'Aprovado')
                            ->whereDoesntHave('pagamentos')
                            ->whereYear('data_apontamento', $periodo->year)
                            ->whereMonth('data_apontamento', $periodo->month)
                            ->get();

                        if ($apontamentos->isEmpty()) return;
                        
                        $totalHoras = $apontamentos->sum('horas_gastas_decimal');
                        $valorAPagar = $totalHoras * ($colaborador->valor_hora ?? 0);
                        $apontamentosIds = $apontamentos->pluck('id')->toArray();
                        break;
                }

                if ($valorAPagar <= 0) {
                    return;
                }

                $transferencia = $asaasService->criarTransferenciaPix($colaborador, $valorAPagar);

                $pagamento = Pagamento::create([
                    'user_id' => $colaborador->id,
                    'periodo_ref' => $periodo,
                    'valor_total' => $valorAPagar,
                    'status' => $transferencia ? 'pago' : 'erro',
                    'asaas_transfer_id' => $transferencia['id'] ?? null,
                    'observacoes' => !$transferencia ? 'Falha na comunicação com a API Asaas.' : null,
                    'processado_por' => Auth::id(),
                ]);

                if ($transferencia) {
                    if (!empty($apontamentosIds)) {
                        $pagamento->apontamentos()->attach($apontamentosIds);
                    }
                    $sucessos++;
                } else {
                    $falhas++;
                }
            });
        }

        $mensagem = "Processamento concluído. {$sucessos} pagamentos realizados com sucesso e {$falhas} falharam.";
        return redirect()->route('pagamentos.index', ['mes' => $mes, 'ano' => $ano])->with('success', $mensagem);
    }
}
