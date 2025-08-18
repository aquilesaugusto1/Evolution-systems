<?php

namespace App\Notifications;

use App\Models\Apontamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApontamentoStatusAlterado extends Notification
{
    use Queueable;

    public $apontamento;

    /**
     * Create a new notification instance.
     */
    public function __construct(Apontamento $apontamento)
    {
        $this->apontamento = $apontamento;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = strtolower($this->apontamento->status);
        $cliente = $this->apontamento->contrato->empresaParceira->nome_fantasia;
        $data = $this->apontamento->data_apontamento->format('d/m/Y');

        $mensagem = "Seu apontamento de {$data} para o cliente {$cliente} foi {$status}.";
        if ($status === 'rejeitado') {
            $mensagem .= " Clique para ver o motivo.";
        }

        return [
            'apontamento_id' => $this->apontamento->id,
            'mensagem' => $mensagem,
            'url' => route('apontamentos.index'),
        ];
    }
}
