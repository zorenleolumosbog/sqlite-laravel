<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClosingContest extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email_body;

    /**
     * Create a new message instance.
     */
    public function __construct($contest)
    {
        $this->name = $contest['name'];
        $this->email_body = $contest['email_body'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Epic Transformation Challenge - Closing Contest',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.closingcontest',
            with: [
                'name' => $this->name,
                'email_body' => $this->email_body,
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
        return [];
    }
}
