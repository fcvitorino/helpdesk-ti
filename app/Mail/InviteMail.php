<?php

namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convite para o VitDesk',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite',
        );
    }
}