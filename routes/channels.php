<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifikasi.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
