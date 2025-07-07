<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('song-requests', function () {
    return true;
});

Broadcast::channel('djsession.{id}', function ($user, $id) {
    $djsession = \App\Models\Djsession::find($id);
    if (!$djsession) {
        return false;
    }
    $isUserInSession = (int) $user->djsession_id === (int) $djsession->id;
    $isUserTheOwner = (int) $user->id === (int) $djsession->user_id;

    return true;;
});

Broadcast::channel('raffle.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('dj.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return true;
});

