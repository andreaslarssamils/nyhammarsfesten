<?php

namespace App\Mail;

use App\Models\ShirtOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShirtOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ShirtOrder $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Din t-shirtbeställning {$this->order->reference} — Nyhammarsfesten",
        );
    }

    public function content(): Content
    {
        // text:, inte view: — vyn är skriven som ren text, och som HTML
        // kollapsar radbrytningarna till en enda klump.
        return new Content(text: 'emails.shirt-confirmation');
    }
}
