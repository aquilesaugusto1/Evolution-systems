<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Apontamento;
use App\Models\User;
use App\Notifications\ApontamentoParaAprovacao;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use LogicException;

class ApontamentoController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Apontamento::class);

        return view('apontamentos.index');
    }

    public function events(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Apontamento::class);

        $user = Auth::user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $start = Carbon::parse($request->string('start')->toString())->toDateTimeString();
        $end = Carbon::parse($request->string('end')->toString())->toDateTimeString();

        $query = Agenda::with(['consultor', 'contrato.empresaParceira', 'apontamento'])
            ->whereBetween('data_hora', [$start, $end]);

        if ($user->isConsultor()) {
            $query->where('consultor_id', $user->id);
        } elseif ($user->isTechLead()) {
            $consultor_ids = $user->consultoresLiderados()->pluck('usuarios.id');
            $query->whereIn('consultor_id', $consultor_ids);
        }

        $agendas = $query->get();

        return response()->json($this->formatEvents($agendas));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'agenda_id' => 'required|exists:agendas,id',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'descricao' => 'required|string|max:2000',
            'anexo' => ['nullable', 'file', 'mimes:pdf,jpg,png,jpeg', 'max:2048'],
            'faturavel' => 'nullable|boolean',
        ]);

        $agenda = Agenda::findOrFail($validated['agenda_id']);
        $apontamento = Apontamento::firstOrNew(['agenda_id' => $agenda->id]);

        if ($apontamento->exists) {
            $this->authorize('update', $apontamento);
        } else {
            $this->authorize('create', Apontamento::class);
        }

        $inicio = Carbon::createFromTimeString($validated['hora_inicio']);
        $fim = Carbon::createFromTimeString($validated['hora_fim']);

        $apontamento->consultor_id = $agenda->consultor_id;
        $apontamento->contrato_id = $agenda->contrato_id;
        $apontamento->data_apontamento = $agenda->data_hora;
        $apontamento->hora_inicio = $validated['hora_inicio'];
        $apontamento->hora_fim = $validated['hora_fim'];
        $apontamento->horas_gastas = round($fim->diffInMinutes($inicio) / 60, 2);
        $apontamento->descricao = $validated['descricao'];
        $apontamento->status = 'Pendente';
        $apontamento->faturavel = $request->boolean('faturavel');
        $apontamento->motivo_rejeicao = null;

        if ($request->hasFile('anexo')) {
            if ($apontamento->caminho_anexo) {
                Storage::disk('public')->delete($apontamento->caminho_anexo);
            }
            $path = $request->file('anexo')->store('anexos', 'public');
            if ($path !== false) {
                $apontamento->caminho_anexo = $path;
            }
        }

        $apontamento->save();

        // ** GATILHO DA NOTIFICAÇÃO ATUALIZADO **
        $consultor = $apontamento->consultor;
        if ($consultor) {
            $techLeads = $consultor->techLeads;
            $admins = User::where('funcao', 'admin')->get();
            
            // Junta as duas coleções e remove duplicados para não notificar duas vezes
            $destinatarios = $techLeads->merge($admins)->unique('id');

            if ($destinatarios->isNotEmpty()) {
                Notification::send($destinatarios, new ApontamentoParaAprovacao($apontamento, $consultor));
            }
        }

        return response()->json(['message' => 'Apontamento salvo e enviado para aprovação!']);
    }

    private function formatEvents(Collection $agendas): SupportCollection
    {
        return $agendas->map(function (Agenda $agenda) {
            $apontamento = $agenda->apontamento;
            $status = 'Não Apontado';
            $color = '#6B7280'; // Cinza

            if ($agenda->status === 'Cancelada') {
                $status = 'Cancelada';
                $color = '#EF4444'; // Vermelho
            } elseif ($apontamento) {
                $status = $apontamento->status;
                switch ($status) {
                    case 'Pendente':
                        $color = '#F59E0B'; // Ambar
                        break;
                    case 'Aprovado':
                        $color = '#10B981'; // Verde
                        break;
                    case 'Rejeitado':
                        $color = '#EF4444'; // Vermelho
                        break;
                }
            } else {
                $status = 'Agendada';
                $color = '#3B82F6'; // Azul
            }

            return [
                'id' => $agenda->id,
                'title' => $agenda->contrato?->empresaParceira?->nome_empresa,
                'start' => $agenda->data_hora,
                'color' => $color,
                'extendedProps' => [
                    'consultor' => $agenda->consultor?->nome,
                    'assunto' => $agenda->assunto,
                    'contrato' => ($agenda->contrato?->empresaParceira?->nome_empresa ?? 'Cliente N/A').' - '.($agenda->contrato?->numero_contrato ?? 'Contrato N/A'),
                    'status' => $status,
                    'hora_inicio' => $apontamento ? Carbon::parse($apontamento->hora_inicio)->format('H:i') : '',
                    'hora_fim' => $apontamento ? Carbon::parse($apontamento->hora_fim)->format('H:i') : '',
                    'descricao' => $apontamento->descricao ?? '',
                    'faturavel' => $apontamento->faturavel ?? true,
                    'anexo_url' => $apontamento && $apontamento->caminho_anexo ? Storage::url($apontamento->caminho_anexo) : null,
                    'motivo_rejeicao' => $apontamento->motivo_rejeicao ?? null,
                ],
            ];
        });
    }
}
