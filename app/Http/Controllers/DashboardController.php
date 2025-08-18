<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $preferences = $user->dashboardPreference;

        $widgetsLayout = $preferences ? $preferences->widgets : $this->getDefaultWidgetsForUser($user);
        $availableWidgets = $this->getAvailableWidgetsForUser($user);

        $widgetData = [];
        // Carrega os dados para todos os widgets disponíveis, para que a personalização funcione
        foreach ($availableWidgets as $widget) {
            $widgetData[$widget['id']] = $this->getWidgetData($widget['id'], $user);
        }

        return view('dashboard', [
            'widgetsLayout' => $widgetsLayout,
            'widgetData' => $widgetData,
            'availableWidgets' => $availableWidgets,
        ]);
    }

    /**
     * @return array<int, array<int, array<string, string>>>
     */
    private function getDefaultWidgetsForUser(User $user): array
    {
        if ($user->funcao === 'admin') {
            return [
                [['id' => 'admin_stats', 'component' => 'stats-group'], ['id' => 'agendas_chart', 'component' => 'agendas-chart']],
                [['id' => 'active_consultants', 'component' => 'active-consultants']],
            ];
        }
        return [
            [['id' => 'consultor_stats', 'component' => 'stats-group'], ['id' => 'last_agendas', 'component' => 'last-agendas']],
            [],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function getAvailableWidgetsForUser(User $user): array
    {
        $adminWidgets = [
            ['id' => 'admin_stats', 'name' => 'Estatísticas Gerais', 'component' => 'stats-group'],
            ['id' => 'agendas_chart', 'name' => 'Gráfico de Agendas', 'component' => 'agendas-chart'],
            ['id' => 'active_consultants', 'name' => 'Consultores Ativos', 'component' => 'active-consultants'],
        ];

        $consultorWidgets = [
            ['id' => 'consultor_stats', 'name' => 'Minhas Estatísticas', 'component' => 'stats-group'],
            ['id' => 'last_agendas', 'name' => 'Próximas Agendas', 'component' => 'last-agendas'],
        ];

        if ($user->funcao === 'admin') {
            return $adminWidgets;
        }
        return $consultorWidgets;
    }

    private function getWidgetData(string $widgetId, User $user): mixed
    {
        return match ($widgetId) {
            'admin_stats' => $this->dashboardService->getAdminStats(),
            'consultor_stats' => $this->dashboardService->getConsultorStats($user),
            'active_consultants' => $this->dashboardService->getConsultoresAtivos(),
            'last_agendas' => $this->dashboardService->getUltimasAgendas($user),
            'agendas_chart' => $this->dashboardService->getAgendasChartData(),
            default => [],
        };
    }
}
