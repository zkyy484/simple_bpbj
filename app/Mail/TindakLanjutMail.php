<?php

namespace App\Mail;

use App\Models\Tamu;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TindakLanjutMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tamu;

    public function __construct(Tamu $tamu)
    {
        $this->tamu = $tamu;
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Informasi Tindak Lanjut Buku Tamu'
        );
    }

    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            markdown: 'emails.tindak_lanjut'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}