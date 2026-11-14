<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $prospect;
    public $registrationUrl;
    public $token;

    public function __construct($prospect, $token)
    {
        $this->prospect = $prospect;
        $this->token = $token;
        $this->registrationUrl = url('/completar-registro/' . $token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Completa tu registro en CHEF!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-link',
            with: [
                'prospect' => $this->prospect,
                'registrationUrl' => $this->registrationUrl,
            ],
        );
    }
}
