<?php

namespace App\Notifications;

use App\Models\Contrato;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContratoHorasBaixas extends Notification
{
    use Queueable;

    public $contrato;
    public $percentualRestante;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contrato $contrato, float $percentualRestante)
    {
        $this->contrato = $contrato;
        $this->percentualRestante = round($percentualRestante);
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
        $cliente = $this->contrato->empresaParceira->nome_fantasia;
        $mensagem = "Atenção: O contrato {$this->contrato->numero_contrato} ({$cliente}) atingiu {$this->percentualRestante}% do saldo de horas.";

        return [
            'contrato_id' => $this->contrato->id,
            'mensagem' => $mensagem,
            'url' => route('contratos.show', $this->contrato),
        ];
    }
}
