<?php

namespace App\Services;

use App\Models\Djsession;
use App\Models\User;
use App\Jobs\DeleteDjsessionJob;
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
                    $this->deactivate($oldSession);
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

    public function deactivate(Djsession $djsession)
    {
        $this->deactivateTransaction($djsession);
        broadcast(
            new \App\Events\DjsessionUpdate($djsession->id, [
                'current_users' => 0,
                'active' => false
            ])
        );
    }

    public function scheduleDeletion(Djsession $djsession)
    {
        $this->preDelete($djsession);
        DeleteDjsessionJob::dispatch($djsession)->delay(now()->addSeconds(10)->afterCommit());
    }

    public function preDelete(Djsession $djsession)
    {
        $this->deactivateTransaction($djsession);
        $djsession->user_id = null; // Desvincular al DJ de la sesión
        $djsession->save();
        broadcast(
            new \App\Events\DjsessionDeleted($djsession->id)
        );
    }

    protected function deactivateTransaction(Djsession $djsession)
    {
        DB::transaction(function () use ($djsession) {
            // Marcar como no activa
            $djsession->active = false;
            $djsession->end_time = now(); // Establecer la hora de finalización
            $djsession->current_users = 0; // Reiniciar el contador de usuarios
            $djsession->save();

            // Quitar la sesión al DJ y a los usuarios conectados
            User::where('djsession_id', $djsession->id)->update(['djsession_id' => null]);
        });
    }

    public function join(Djsession $djsession, User $user)
    {
        Log::info('Joining session', ['session_id' => $djsession->id, 'user_id' => $user->id]);
        DB::transaction(function () use ($djsession, $user) {
            // Desconectar al usuario de su sesión activa (si aplica)
            $oldSession = $user->djsessionActive;
            if ($oldSession) {
                $oldSession->current_users--;
                $oldSession->save();
            }

            // Unir al usuario a la nueva sesión
            $user->djsession_id = $djsession->id;
            $user->save();

            // Incrementar el contador de usuarios en la sesión
            $djsession->current_users++;
            if ($djsession->current_users > $djsession->peak_users) {
                $djsession->peak_users = $djsession->current_users;
            }
            $djsession->save();
        });
        broadcast(
            new \App\Events\DjsessionUpdate($djsession->id, [
                'current_users' => $djsession->current_users
            ])
        );
    }

    public function leave(Djsession $djsession, User $user)
    {
        DB::transaction(function () use ($djsession, $user) {
            // Desconectar al usuario de la sesión
            $user->djsession_id = null;
            $user->save();

            // Decrementar el contador de usuarios en la sesión
            $djsession->current_users--;
            $djsession->save();
        });
        broadcast(
            new \App\Events\DjsessionUpdate($djsession->id, [
                'current_users' => $djsession->current_users
            ])
        );
    }
}

