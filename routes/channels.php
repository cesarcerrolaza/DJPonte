<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('song-requests', function () {
    return true;
});

Broadcast::channel('dj.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id; // Solo el DJ dueño del canal puede escuchar
});
