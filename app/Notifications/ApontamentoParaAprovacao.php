<?php

namespace App\Notifications;

use App\Models\Apontamento;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApontamentoParaAprovacao extends Notification
{
    use Queueable;

    public $apontamento;
    public $consultor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Apontamento $apontamento, User $consultor)
    {
        $this->apontamento = $apontamento;
        $this->consultor = $consultor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Por enquanto, salvaremos apenas no banco de dados.
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'apontamento_id' => $this->apontamento->id,
            'consultor_nome' => $this->consultor->nome,
            'horas' => $this->apontamento->horas_gastas,
            'data' => $this->apontamento->data_apontamento->format('d/m/Y'),
            'mensagem' => "{$this->consultor->nome} submeteu um novo apontamento de {$this->apontamento->horas_gastas} para aprovação.",
            'url' => route('aprovacoes.index'),
        ];
    }
}
