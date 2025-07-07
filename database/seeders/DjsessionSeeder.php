<?php

namespace Database\Seeders;

use App\Models\Djsession;
use App\Models\User;
use Illuminate\Database\Seeder;

class DjsessionSeeder extends Seeder
{
    public function run(): void
    {
        // Recuperar DJ
        $dj = User::where('email', 'djponte.mail@gmail.com')->first();

        // Crear la sesión del DJ
        $djsession = Djsession::create([
            'code' => 'TEST',
            'name' => 'Sesión de prueba',
            'description' => 'Sesión de prueba para testear la aplicación',
            'venue' => 'Prueba',
            'address' => 'Calle de la prueba, 123',
            'city' => 'Prueba City',
            'active' => true,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'song_request_timeout' => 40,
            'current_users' => 1,
            'peak_users' => 1,
            'user_id' => $dj->id,
        ]);

        //Crear djsession no activa
        Djsession::create([
            'code' => 'MOMART25',
            'name' => 'Sesión de prueba 2',
            'description' => 'Sesión de prueba 2 para testear la aplicación',
            'venue' => 'Prueba 2',
            'address' => 'Calle de la prueba 2, 123',
            'city' => 'Prueba City 2',
            'active' => false,
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'song_request_timeout' => 30,
            'current_users' => 0,
            'peak_users' => 0,
            'user_id' => $dj->id,
        ]);

        // Asignar la sesión activa al DJ
        $dj->update(['djsession_id' => $djsession->id]);

        // Asignar también la misma sesión al usuario normal (opcional)
        $user = User::where('email', 'cesarcerrolaza@gmail.com')->first();
        $user->update(['djsession_id' => $djsession->id]);
    }
}
