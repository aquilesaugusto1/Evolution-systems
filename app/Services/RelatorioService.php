<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ConvertsTime;

class RelatorioService
{
    use ConvertsTime;

    public function getFiltrosAlocacao(): array
    {
        $consultoresPJ = User::where('funcao', 'consultor')
            ->whereIn('tipo_contrato', ['PJ Mensal', 'PJ Horista'])
            ->where('status', 'Ativo')
            ->orderBy('nome')
            ->get();

        return ['consultores' => $consultoresPJ];
    }

    public function gerarRelatorioAlocacao(array $filtros): array
    {
        $inicioPeriodo = Carbon::parse($filtros['data_inicio'])->startOfDay();
        $fimPeriodo = Carbon::parse($filtros['data_fim'])->endOfDay();

        $diasUteis = $this->getDiasUteisNoPeriodo($inicioPeriodo, $fimPeriodo);
        $horasUteisDoPeriodo = $diasUteis * 8;

        $consultores = User::whereIn('id', $filtros['consultores_id'])->get();
        $resultados = [];

        foreach ($consultores as $consultor) {
            $apontamentos = Apontamento::where('consultor_id', $consultor->id)
                ->whereBetween('data_apontamento', [$inicioPeriodo, $fimPeriodo])
                ->get();

            $horasApontadas = $apontamentos->sum('horas_gastas_decimal');
            
            $numeroDeAgendas = Agenda::where('consultor_id', $consultor->id)
                ->whereBetween('data_hora', [$inicioPeriodo, $fimPeriodo])
                ->where('status', '!=', 'Cancelada')
                ->count();

            $horasUteisRestantes = $horasUteisDoPeriodo - $horasApontadas;

            $resultados[] = [
                'consultor' => $consultor,
                'horas_apontadas' => $this->decimalToTime($horasApontadas),
                'numero_agendas' => $numeroDeAgendas,
                'horas_uteis_periodo' => $this->decimalToTime($horasUteisDoPeriodo),
                'horas_uteis_restantes' => $this->decimalToTime($horasUteisRestantes),
            ];
        }

        return ['resultados' => $resultados, 'dias_uteis' => $diasUteis];
    }

    private function getDiasUteisNoPeriodo(Carbon $inicio, Carbon $fim): int
    {
        $diasUteis = 0;
        $feriados = $this->getFeriados(range($inicio->year, $fim->year));
        $dataAtual = $inicio->copy();

        while ($dataAtual <= $fim) {
            if ($dataAtual->isWeekday() && ! in_array($dataAtual->format('Y-m-d'), $feriados)) {
                $diasUteis++;
            }
            $dataAtual->addDay();
        }

        return $diasUteis;
    }

    private function getFeriados(array $anos): array
    {
        $feriados = [];
        foreach ($anos as $ano) {
            $feriados[] = "{$ano}-01-01";
            $feriados[] = "{$ano}-04-21";
            $feriados[] = "{$ano}-05-01";
            $feriados[] = "{$ano}-09-07";
            $feriados[] = "{$ano}-10-12";
            $feriados[] = "{$ano}-11-02";
            $feriados[] = "{$ano}-11-15";
            $feriados[] = "{$ano}-12-25";

            $pascoaTimestamp = easter_date($ano);
            $feriados[] = date('Y-m-d', strtotime('-2 days', $pascoaTimestamp));
            $feriados[] = date('Y-m-d', strtotime('-47 days', $pascoaTimestamp));
            $feriados[] = date('Y-m-d', strtotime('+60 days', $pascoaTimestamp));
        }

        return $feriados;
    }

    public function getFiltrosContratos(): array
    {
        $contratos = Contrato::where('status', 'Ativo')->with('empresaParceira')->orderBy('numero_contrato')->get();

        return compact('contratos');
    }

    public function gerarRelatorioContratos(array $filtros): array
    {
        $contratos = Contrato::with('empresaParceira')->whereIn('id', $filtros['contratos_id'])->get();
        $resultados = [];

        foreach ($contratos as $contrato) {
            $horasOriginais = $contrato->baseline_horas_original_decimal;
            $horasRestantes = $contrato->baseline_horas_mes_decimal;
            $horasGastas = $horasOriginais - $horasRestantes;
            $percentualGasto = ($horasOriginais > 0) ? ($horasGastas / $horasOriginais) * 100 : 0;

            $resultados[] = [
                'contrato' => $contrato,
                'horas_gastas' => $this->decimalToTime($horasGastas),
                'saldo_horas' => $this->decimalToTime($horasRestantes),
                'percentual_gasto' => round($percentualGasto),
            ];
        }

        return ['resultados' => $resultados];
    }

    public function getFiltrosHistoricoTechLeads(): array
    {
        $contratos = Contrato::where('status', 'Ativo')->with('empresaParceira')->orderBy('numero_contrato')->get();

        return compact('contratos');
    }

    public function gerarRelatorioHistoricoTechLeads(array $filtros): array
    {
        $contrato = Contrato::with('empresaParceira')->findOrFail($filtros['contrato_id']);

        $historico = DB::table('contrato_historico_techleads as historico')
            ->join('usuarios', 'historico.tech_lead_id', '=', 'usuarios.id')
            ->where('historico.contrato_id', $contrato->id)
            ->select('usuarios.nome as tech_lead_nome', 'historico.data_inicio', 'historico.data_fim')
            ->orderBy('historico.data_inicio', 'desc')
            ->get();

        return [
            'contrato' => $contrato,
            'historico' => $historico,
        ];
    }
}