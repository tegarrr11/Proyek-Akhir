<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Notifikasi;
use App\Events\NotifikasiEvent;
use App\Mail\NotifikasiEmail;
use Illuminate\Support\Facades\Mail;

class NotifikasiHelper
{
    public static function kirimKeRole($role, $judul, $pesan)
    {
        $users = User::where('role', $role)->get();

        foreach ($users as $user) {
            Notifikasi::create([
                'user_id' => $user->id,
                'judul' => $judul,
                'pesan' => $pesan
            ]);

            event(new NotifikasiEvent($user->id, $judul, $pesan));

            if (env('NOTIF_EMAIL')) {
                Mail::to($user->email)->send(new NotifikasiEmail($judul, $pesan));
            }
        }
    }

    public static function kirimKeUser($user, $judul, $pesan)
    {
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'pesan' => $pesan
        ]);

        event(new NotifikasiEvent($user->id, $judul, $pesan));

        if (env('NOTIF_EMAIL')) {
            Mail::to($user->email)->send(new NotifikasiEmail($judul, $pesan));
        }
    }

    public static function kirimKeRoles(array $roles, $judul, $pesan)
{
    $users = \App\Models\User::whereIn('role', $roles)->get();

    foreach ($users as $user) {
        Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'pesan' => $pesan
        ]);

        event(new \App\Events\NotifikasiEvent($user->id, $judul, $pesan));

        if (env('NOTIF_EMAIL')) {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(
                new \App\Mail\NotifikasiEmail($judul, $pesan)
            );
        }
    }
}
}
