<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('song-requests', function () {
    return true;
});

Broadcast::channel('djsession.{id}', function ($user, $id) {
    return (int) $user->djsession_id === (int) $id;
});

Broadcast::channel('dj.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id; // Solo el DJ dueño del canal puede escuchar
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id; // Solo el usuario dueño del canal puede escuchar
});

