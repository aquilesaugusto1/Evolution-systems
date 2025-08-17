<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\EmpresaParceira;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use LogicException;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $stats = [];
        $contratosCriticos = collect();
        $consultoresAtivos = collect();
        $ultimas_agendas = collect();
        $chartLabels = [];
        $chartRealizadas = [];
        $chartAgendadas = [];
        $chartCanceladas = [];

        if ($user->funcao === 'admin') {
            $stats = [
                'Consultores' => User::where('funcao', 'consultor')->count(),
                'Tech Leads' => User::where('funcao', 'techlead')->count(),
                'Contratos' => Contrato::count(),
                'Clientes' => EmpresaParceira::count(),
            ];

            $consultoresAtivos = User::where('funcao', 'consultor')
                ->withSum(['apontamentos' => function ($query) {
                    $query->where('data_apontamento', '>=', now()->subDays(30));
                }], 'horas_gastas')
                ->get()
                ->sortByDesc('apontamentos_sum_horas_gastas');

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
                /** @var \Carbon\Carbon $date */
                $mes = $date->format('Y-m');
                $dadosGrafico[$mes] = ['Realizada' => 0, 'Agendada' => 0, 'Cancelada' => 0];
            }

            foreach ($agendasPorMes as $item) {
                /** @var object{mes: string, status: string, total: int} $item */
                if (isset($dadosGrafico[$item->mes])) {
                    $dadosGrafico[$item->mes][$item->status] = $item->total;
                }
            }

            foreach ($dadosGrafico as $mes => $status) {
                $carbonDate = Carbon::createFromFormat('Y-m', $mes);
                if ($carbonDate) {
                    $chartLabels[] = $carbonDate->format('M/y');
                    $chartRealizadas[] = $status['Realizada'];
                    $chartAgendadas[] = $status['Agendada'];
                    $chartCanceladas[] = $status['Cancelada'];
                }
            }

        } else {
            $stats = [
                'Minhas Agendas Hoje' => Agenda::where('consultor_id', $user->id)->whereDate('data_hora', today())->count(),
                'Meus Contratos' => $user->contratos()->count(),
                'Apontamentos Pendentes' => Apontamento::where('consultor_id', $user->id)->where('status', 'Pendente')->count(),
            ];

            $ultimas_agendas = Agenda::where('consultor_id', $user->id)
                ->with(['contrato.empresaParceira'])
                ->where('data_hora', '>=', today())
                ->orderBy('data_hora', 'asc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact(
            'stats',
            'contratosCriticos',
            'consultoresAtivos',
            'ultimas_agendas',
            'chartLabels',
            'chartRealizadas',
            'chartAgendadas',
            'chartCanceladas'
        ));
    }
}
