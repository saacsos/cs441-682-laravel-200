<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PointEarned extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $amount,
        public int $balance
    )
    { }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "คุณได้รับ {$this->amount} แต้ม",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.point-earned',
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
