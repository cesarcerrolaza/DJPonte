<?php

namespace App\Services;

use App\Models\Djsession;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DjsessionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Activate a new DJ session and deactivate the old one.
     *
     * @param  Djsession  $newSession
     * @param  User  $dj
     * @return void
     */
    public function activate(Djsession $newSession, User $dj)
    {
        DB::transaction(function () use ($newSession, $dj) {
            // Desactivar la sesión anterior si la hay
            if ($dj->djsession_id) {
                $oldSession = Djsession::find($dj->djsession_id);
                if ($oldSession) {
                    $oldSession->active = false;
                    $oldSession->save();
                }
            }

            // Activar la nueva sesión
            $newSession->active = true;
            $newSession->start_time = now();
            $newSession->end_time = now()->addHours(6); // Establecer la duración de la sesión
            $newSession->current_users = 0;
            $newSession->save();

            // Asignar al DJ la nueva sesión
            $dj->djsession_id = $newSession->id;
            $dj->save();
        });
        broadcast(
            new \App\Events\DjsessionUpdate($newSession->id, [
                'current_users' => 0,
                'active' => true
            ])
        );
    }

    public function deactivate(Djsession $session)
    {
        DB::transaction(function () use ($session) {
            // Marcar como no activa
            $session->active = false;
            $session->end_time = now(); // Establecer la hora de finalización
            $session->current_users = 0; // Reiniciar el contador de usuarios
            $session->save();

            // Quitar la sesión al DJ y a los usuarios conectados
            User::where('djsession_id', $session->id)->update(['djsession_id' => null]);
        });
        broadcast(
            new \App\Events\DjsessionUpdate($session->id, [
                'current_users' => 0,
                'active' => false
            ])
        );
    }

    public function join(Djsession $session, User $user)
    {
        Log::info('Joining session', ['session_id' => $session->id, 'user_id' => $user->id]);
        DB::transaction(function () use ($session, $user) {
            // Desconectar al usuario de su sesión activa (si aplica)
            $oldSession = $user->djsessionActive;
            if ($oldSession) {
                $oldSession->current_users--;
                $oldSession->save();
            }

            // Unir al usuario a la nueva sesión
            $user->djsession_id = $session->id;
            $user->save();

            // Incrementar el contador de usuarios en la sesión
            $session->current_users++;
            if ($session->current_users > $session->peak_users) {
                $session->peak_users = $session->current_users;
            }
            $session->save();
        });
        broadcast(
            new \App\Events\DjsessionUpdate($session->id, [
                'current_users' => $session->current_users
            ])
        );
    }

    public function leave(Djsession $session, User $user)
    {
        DB::transaction(function () use ($session, $user) {
            // Desconectar al usuario de la sesión
            $user->djsession_id = null;
            $user->save();

            // Decrementar el contador de usuarios en la sesión
            $session->current_users--;
            $session->save();
        });
        broadcast(
            new \App\Events\DjsessionUpdate($session->id, [
                'current_users' => $session->current_users
            ])
        );
    }
}

