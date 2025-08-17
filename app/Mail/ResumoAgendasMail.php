<?php

namespace App\Mail;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ResumoAgendasMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Collection<int, Agenda>
     */
    public Collection $agendas;

    public string $recado;

    public User $remetente;

    /**
     * @param  Collection<int, Agenda>  $agendas
     */
    public function __construct(Collection $agendas, string $recado, User $remetente)
    {
        $this->agendas = $agendas;
        $this->recado = $recado;
        $this->remetente = $remetente;
    }

    public function envelope(): Envelope
    {
        $address = config('mail.from.address', 'nao-responda@progmud.com.br');
        $name = config('mail.from.name', 'Progmud');

        return new Envelope(
            from: new Address($address, $name),
            subject: 'Sua Agenda de Atividades da Semana',
        );
    }

    public function content(): Content
    {
        // Aponta para a view que exibe a lista de agendas
        return new Content(
            markdown: 'emails.agendas',
        );
    }

    /**
     * @return array{}
     */
    public function attachments(): array
    {
        return [];
    }
}
