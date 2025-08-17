<?php

namespace App\Http\Controllers;

use App\Models\Apontamento;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use LogicException;

class AprovacaoController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Apontamento::class);

        $user = Auth::user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $query = Apontamento::with(['consultor', 'contrato.empresaParceira'])
            ->where('status', 'Pendente');

        if ($user->isTechLead()) {
            $consultorIds = $user->consultoresLiderados()->allRelatedIds();
            $query->whereIn('consultor_id', $consultorIds);
        }

        $apontamentos = $query->latest()->paginate(15);

        return view('aprovacoes.index', compact('apontamentos'));
    }

    public function aprovar(Request $request, Apontamento $apontamento): RedirectResponse
    {
        $this->authorize('approve', $apontamento);

        $apontamento->loadMissing(['agenda', 'contrato']);

        try {
            DB::transaction(function () use ($apontamento) {
                $apontamento->status = 'Aprovado';
                $apontamento->aprovado_por_id = (int) Auth::id();
                $apontamento->data_aprovacao = now();
                $apontamento->motivo_rejeicao = null;
                $apontamento->save();

                if ($apontamento->agenda) {
                    $apontamento->agenda->status = 'Realizada';
                    $apontamento->agenda->save();
                }

                if ($apontamento->faturavel && $apontamento->contrato && $apontamento->contrato->baseline_horas_mes !== null) {
                    $horasASubtrair = abs($apontamento->horas_gastas);
                    $apontamento->contrato->decrement('baseline_horas_mes', $horasASubtrair);
                }
            });

            $message = $apontamento->faturavel
                ? 'Apontamento aprovado e faturado com sucesso!'
                : 'Apontamento aprovado com sucesso (horas não faturadas).';

            return redirect()->route('aprovacoes.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erro ao aprovar apontamento: '.$e->getMessage());

            return back()->withErrors('Ocorreu um erro inesperado ao tentar aprovar o apontamento.');
        }
    }

    public function rejeitar(Request $request, Apontamento $apontamento): RedirectResponse
    {
        $this->authorize('approve', $apontamento);

        $validated = $request->validate(['motivo_rejeicao' => 'required|string|max:500']);

        $apontamento->status = 'Rejeitado';
        $apontamento->faturavel = false;
        $apontamento->motivo_rejeicao = $validated['motivo_rejeicao'];
        $apontamento->aprovado_por_id = (int) Auth::id();
        $apontamento->data_aprovacao = now();
        $apontamento->save();

        return redirect()->route('aprovacoes.index')->with('success', 'Apontamento rejeitado com sucesso.');
    }
}
