<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class PiutangReminder extends Mailable
{
    use Queueable, SerializesModels;
    public $piutangs;

    /**
     * Create a new message instance.
     */
    public function __construct($piutangs)
    {
        $this->piutangs = $piutangs;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $user = User::role('admin')->first();
        $fromEmail = $user->email;
        $fromName = $user->name;

        return new Envelope(
            from: new Address($fromEmail, $fromName),
            subject: 'Piutang Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.piutangs.piutangReminder',
            with: [
                'piutangs' => $this->piutangs,
                'user' => $this->piutangs->first()->user,
            ]
            // view: 'view.name',
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
