<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\EmpresaParceira;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Retorna as estatísticas para o perfil de Admin.
     * @return array<string, int>
     */
    public function getAdminStats(): array
    {
        return [
            'Consultores' => User::where('funcao', 'consultor')->where('status', 'ativo')->count(),
            'Tech Leads' => User::where('funcao', 'techlead')->where('status', 'ativo')->count(),
            'Contratos Ativos' => Contrato::where('status', 'Ativo')->count(),
            'Clientes Ativos' => EmpresaParceira::where('status', 'Ativo')->count(),
        ];
    }

    /**
     * Retorna as estatísticas para o perfil de Consultor.
     * @return array<string, int>
     */
    public function getConsultorStats(User $user): array
    {
        return [
            'Minhas Agendas Hoje' => Agenda::where('consultor_id', $user->id)->whereDate('data_hora', today())->count(),
            'Meus Contratos' => $user->contratos()->where('status', 'Ativo')->count(),
            'Apontamentos Pendentes' => Apontamento::where('consultor_id', $user->id)->where('status', 'Pendente')->count(),
        ];
    }

    /**
     * Retorna a lista de consultores mais ativos nos últimos 30 dias.
     */
    public function getConsultoresAtivos()
    {
        return User::where('funcao', 'consultor')
            ->with(['apontamentos' => function ($query) {
                $query->where('data_apontamento', '>=', now()->subDays(30));
            }])
            ->get()
            ->map(function ($consultor) {
                $consultor->apontamentos_sum_horas_gastas = $consultor->apontamentos->sum('horas_gastas_decimal');
                return $consultor;
            })
            ->sortByDesc('apontamentos_sum_horas_gastas')
            ->take(5);
    }

    /**
     * Retorna as próximas 5 agendas de um consultor.
     */
    public function getUltimasAgendas(User $user)
    {
        return Agenda::where('consultor_id', $user->id)
            ->with(['contrato.empresaParceira'])
            ->where('data_hora', '>=', today())
            ->orderBy('data_hora', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Retorna os dados para o gráfico de agendas dos últimos 6 meses.
     * @return array<string, mixed>
     */
    public function getAgendasChartData(): array
    {
        $agendasPorMes = Agenda::select(
            DB::raw('DATE_FORMAT(data_hora, "%Y-%m") as mes'),
            'status',
            DB::raw('count(*) as total')
        )
            ->where('data_hora', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('mes', 'status')
            ->orderBy('mes', 'asc')
            ->get();

        $periodo = Carbon::now()->subMonths(5)->startOfMonth()->toPeriod(Carbon::now()->startOfMonth());
        $dadosGrafico = [];

        foreach ($periodo as $date) {
            $mes = $date->format('Y-m');
            $dadosGrafico[$mes] = ['Realizada' => 0, 'Agendada' => 0, 'Cancelada' => 0];
        }

        foreach ($agendasPorMes as $item) {
            if (isset($dadosGrafico[$item->mes])) {
                $dadosGrafico[$item->mes][$item->status] = $item->total;
            }
        }

        $chartLabels = [];
        $chartRealizadas = [];
        $chartAgendadas = [];
        $chartCanceladas = [];

        foreach ($dadosGrafico as $mes => $status) {
            $carbonDate = Carbon::createFromFormat('Y-m', $mes);
            if ($carbonDate) {
                $chartLabels[] = $carbonDate->translatedFormat('M/y');
                $chartRealizadas[] = $status['Realizada'];
                $chartAgendadas[] = $status['Agendada'];
                $chartCanceladas[] = $status['Cancelada'];
            }
        }

        return compact('chartLabels', 'chartRealizadas', 'chartAgendadas', 'chartCanceladas');
    }
}
