<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $order;
    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $items)
    {
        $this->order = $order;
        $this->items = $items;
        
        // ✅ HAPUS generate PDF di constructor
        // PDF akan di-generate saat email dikirim, bukan saat job dibuat
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - ' . $this->order['kode_invoice'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'home.mail.order_confirmation',
            with: [
                'order' => $this->order,
                'items' => $this->items
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
        // ✅ Generate PDF di sini (saat email akan dikirim)
        $pdf = Pdf::loadView('home.mail.invoice', [
            'order' => $this->order,
            'items' => $this->items
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Invoice-' . $this->order['kode_invoice'] . '.pdf')
                ->withMime('application/pdf')
        ];
    }
}