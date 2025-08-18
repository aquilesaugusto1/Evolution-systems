<?php

namespace App\Http\Controllers;

use App\Models\Apontamento;
use App\Models\User;
use App\Notifications\ApontamentoStatusAlterado;
use App\Notifications\ContratoHorasBaixas;
use App\Traits\ConvertsTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use LogicException;

class AprovacaoController extends Controller
{
    use ConvertsTime;

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

        $apontamento->loadMissing(['agenda', 'contrato', 'consultor']);

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

                if ($apontamento->faturavel && ($contrato = $apontamento->contrato)) {
                    $horasContratoDecimal = $contrato->baseline_horas_mes_decimal;
                    $horasApontamentoDecimal = abs($apontamento->horas_gastas_decimal);
                    
                    $novoSaldoDecimal = $horasContratoDecimal - $horasApontamentoDecimal;
                    
                    $contrato->baseline_horas_mes = self::decimalToTime($novoSaldoDecimal);
                    $contrato->save();

                    // ** GATILHO DA NOTIFICAÇÃO DE SALDO DE HORAS **
                    $this->verificarSaldoContrato($contrato);
                }
            });

            if ($apontamento->consultor) {
                $apontamento->consultor->notify(new ApontamentoStatusAlterado($apontamento));
            }

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
        $apontamento->loadMissing('consultor');

        $apontamento->status = 'Rejeitado';
        $apontamento->faturavel = false;
        $apontamento->motivo_rejeicao = $validated['motivo_rejeicao'];
        $apontamento->aprovado_por_id = (int) Auth::id();
        $apontamento->data_aprovacao = now();
        $apontamento->save();

        if ($apontamento->consultor) {
            $apontamento->consultor->notify(new ApontamentoStatusAlterado($apontamento));
        }

        return redirect()->route('aprovacoes.index')->with('success', 'Apontamento rejeitado com sucesso.');
    }

    /**
     * Verifica o saldo de horas de um contrato e dispara notificação se estiver baixo.
     */
    private function verificarSaldoContrato($contrato): void
    {
        $horasOriginais = (float) $contrato->baseline_horas_original_decimal;
        if ($horasOriginais <= 0) {
            return;
        }

        $horasRestantes = (float) $contrato->baseline_horas_mes_decimal;
        $percentualRestante = ($horasRestantes / $horasOriginais) * 100;

        // Define o limite em 20%
        $limite = 20.0;

        if ($percentualRestante <= $limite) {
            $admins = User::where('funcao', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ContratoHorasBaixas($contrato, $percentualRestante));
            }
        }
    }
}
