<?php

namespace App\Mail;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KontrakAkanHabisMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Penyewaan $penyewaan,
        public int $daysRemaining
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan: Kontrak Kos Akan Berakhir (' . $this->daysRemaining . ' Hari Lagi)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kontrak-akan-habis',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
