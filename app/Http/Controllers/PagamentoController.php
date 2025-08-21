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
use Illuminate\Support\Facades\Log;

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
                'horas_trabalhadas' => 'N/A',
                'status_pagamento' => 'Pendente',
            ];

            $pagamentoExistente = $colaborador->pagamentos()
                ->whereYear('periodo_ref', $periodo->year)
                ->whereMonth('periodo_ref', $periodo->month)
                ->first();

            if ($pagamentoExistente) {
                $dados['status_pagamento'] = ucfirst($pagamentoExistente->status);
                $dados['valor_calculado'] = $pagamentoExistente->valor_total;
                if ($colaborador->tipo_contrato === 'PJ Horista') {
                    $totalHorasDecimal = $pagamentoExistente->apontamentos()->sum('horas_gastas_decimal');
                    $dados['horas_trabalhadas'] = $this->convertDecimalToTime($totalHorasDecimal);
                }
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

                        $totalHorasDecimal = $apontamentos->sum('horas_gastas_decimal');
                        $dados['horas_trabalhadas'] = $this->convertDecimalToTime($totalHorasDecimal);
                        $dados['valor_calculado'] = $totalHorasDecimal * ($colaborador->valor_hora ?? 0);
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
        $errosDetalhados = [];

        foreach ($colaboradores as $colaborador) {
            try {
                DB::transaction(function () use ($colaborador, $periodo, $asaasService, &$sucessos, &$falhas, &$errosDetalhados) {
                    if ($colaborador->pagamentos()->whereYear('periodo_ref', $periodo->year)->whereMonth('periodo_ref', $periodo->month)->exists()) {
                        return;
                    }

                    $valorAPagar = 0;
                    $apontamentosIds = [];
                    $observacaoErro = 'Falha na comunicação com a API Asaas.';

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

                            if ($apontamentos->isEmpty()) {
                                return;
                            }
                            
                            $totalHoras = $apontamentos->sum('horas_gastas_decimal');
                            $valorAPagar = $totalHoras * ($colaborador->valor_hora ?? 0);
                            $apontamentosIds = $apontamentos->pluck('id')->toArray();
                            break;
                    }

                    if ($valorAPagar <= 0) {
                        return;
                    }
                    
                    $transferencia = null;
                    if (empty($colaborador->chave_pix) || empty($colaborador->tipo_chave_pix)) {
                        $observacaoErro = 'Chave PIX ou tipo de chave não cadastrado para o colaborador.';
                    } else {
                        $transferencia = $asaasService->criarTransferenciaPix($colaborador, $valorAPagar);
                        if (!$transferencia) {
                             $observacaoErro = 'A API do Asaas recusou a transferência. Verifique os logs.';
                        }
                    }

                    $pagamento = Pagamento::create([
                        'user_id' => $colaborador->id,
                        'periodo_ref' => $periodo,
                        'valor_total' => $valorAPagar,
                        'status' => $transferencia ? 'pago' : 'erro',
                        'asaas_transfer_id' => $transferencia['id'] ?? null,
                        'observacoes' => !$transferencia ? $observacaoErro : null,
                        'processado_por' => Auth::id(),
                    ]);

                    if ($transferencia) {
                        if (!empty($apontamentosIds)) {
                            $pagamento->apontamentos()->attach($apontamentosIds);
                        }
                        $sucessos++;
                    } else {
                        $falhas++;
                        $errosDetalhados[] = "{$colaborador->nome} {$colaborador->sobrenome}: {$observacaoErro}";
                    }
                });
            } catch (\Exception $e) {
                $falhas++;
                $nomeCompleto = $colaborador->nome . ' ' . $colaborador->sobrenome;
                $errosDetalhados[] = "{$nomeCompleto}: Erro inesperado durante a transação. {$e->getMessage()}";
                Log::error("Erro ao processar pagamento para {$nomeCompleto} (ID: {$colaborador->id})", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        $mensagem = "Processamento concluído. {$sucessos} pagamentos realizados com sucesso e {$falhas} falharam.";
        
        $redirect = redirect()->route('pagamentos.index', ['mes' => $mes, 'ano' => $ano]);

        if ($falhas > 0) {
            return $redirect->with('error', $mensagem)->with('detalhes_erros', $errosDetalhados);
        }

        return $redirect->with('success', $mensagem);
    }

    private function convertDecimalToTime(?float $decimalHours): string
    {
        if ($decimalHours === null || $decimalHours <= 0) {
            return '00:00';
        }

        $hours = floor($decimalHours);
        $minutesDecimal = ($decimalHours - $hours) * 60;
        $minutes = round($minutesDecimal);

        if ($minutes == 60) {
            $hours++;
            $minutes = 0;
        }

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
