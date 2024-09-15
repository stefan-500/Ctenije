<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Porudzbina;

class PotvrdaPorudzbine extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    /**
     * instanca Porudzbine.
     *
     * @var \App\Models\Porudzbina
     */
    public $porudzbina; // Zbog dostupnosti u view-u

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Porudzbina $porudzbina
     */
    public function __construct(Porudzbina $porudzbina)
    {
        $this->porudzbina = $porudzbina;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Potvrda Porudžbine'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.potvrda-porudzbine',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
