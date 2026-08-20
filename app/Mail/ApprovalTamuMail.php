<?php

namespace App\Mail;

use App\Models\Tamu;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalTamuMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tamu;
    public $surveiUrl;

    public function __construct(Tamu $tamu)
    {
        $this->tamu = $tamu;
        $this->surveiUrl = 'http://127.0.0.1:8000/survei';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Persetujuan Kunjungan - Buku Tamu Digital (' . $this->tamu->kode_tiket . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval_tamu',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}