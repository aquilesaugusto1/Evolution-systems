<?php

namespace App\Mail;

use App\Models\Agenda;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgendaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Ação realizada na agenda (ex: 'criada', 'atualizada').
     */
    public string $acao;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct(
        public Agenda $agenda,
        string $acao = 'criada'
    ) {
        $this->acao = $acao;
    }

    /**
     * Define o envelope da mensagem (remetente, assunto).
     */
    public function envelope(): Envelope
    {
        $address = config('mail.from.address', 'nao-responda@progmud.com.br');
        $name = config('mail.from.name', 'Progmud');
        $acaoCapitalizada = ucfirst($this->acao);

        return new Envelope(
            from: new Address($address, $name),
            subject: "Agenda {$this->acao}: {$this->agenda->assunto}",
        );
    }

    /**
     * Define o conteúdo da mensagem (a view).
     */
    public function content(): Content
    {
        // Aponta para a nova view singular que vamos criar a seguir.
        return new Content(
            markdown: 'emails.agenda',
        );
    }

    /**
     * Obtém os anexos da mensagem.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
