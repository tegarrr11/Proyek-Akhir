<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class NotifikasiEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userId;
    public $judul;
    public $pesan;

    public function __construct($userId, $judul, $pesan)
    {
        $this->userId = $userId;
        $this->judul = $judul;
        $this->pesan = $pesan;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifikasi.' . $this->userId);
    }

    public function broadcastWith()
    {
        return [
            'judul' => $this->judul,
            'pesan' => $this->pesan,
        ];
    }
}