<?php

namespace App\Mail;

use App\Models\Fatura;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FaturaGeradaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Fatura $fatura,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sua Fatura Evolution Systems Chegou - Nº '.$this->fatura->numero_fatura,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.fatura.gerada',
            with: [
                'nomeCliente' => $this->fatura->contrato->empresaParceira->nome_fantasia,
                'numeroFatura' => $this->fatura->numero_fatura,
                'valorFatura' => number_format($this->fatura->valor_total, 2, ',', '.'),
                'vencimentoFatura' => $this->fatura->data_vencimento->format('d/m/Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('faturamento.pdf', ['fatura' => $this->fatura]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Fatura-'.$this->fatura->numero_fatura.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
