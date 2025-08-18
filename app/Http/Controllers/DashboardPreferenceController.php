<?php

namespace App\Http\Controllers;

use App\Models\DashboardPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardPreferenceController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'layout' => 'required|array|size:2',
            'layout.*' => 'array',
            'layout.*.*.id' => ['required', 'string', Rule::in($this->getAvailableWidgetIds())],
            'layout.*.*.component' => 'required|string',
        ]);

        DashboardPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            ['widgets' => $validated['layout']]
        );

        return response()->json(['success' => true, 'message' => 'Dashboard salva com sucesso!']);
    }

    /**
     * Retorna uma lista de todos os IDs de widgets válidos.
     * @return string[]
     */
    private function getAvailableWidgetIds(): array
    {
        return [
            'admin_stats',
            'consultor_stats',
            'agendas_chart',
            'active_consultants',
            'last_agendas',
        ];
    }
}
