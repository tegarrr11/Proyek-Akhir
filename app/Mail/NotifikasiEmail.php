<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class NotifikasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $judul;
    public $pesan;

    public function __construct($judul, $pesan)
    {
        $this->judul = $judul;
        $this->pesan = $pesan;
    }

    public function build()
    {
        return $this->subject($this->judul)
                    ->view('components.notifikasi')
                    ->with(['pesan' => $this->pesan]);
    }
}