<?php

namespace App\Mail;

use App\Models\ShirtOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShirtOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ShirtOrder $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Ny t-shirtbeställning: {$this->order->reference}",
            replyTo: [$this->order->email],
        );
    }

    public function content(): Content
    {
        return new Content(text: 'emails.shirt-notification');
    }
}
