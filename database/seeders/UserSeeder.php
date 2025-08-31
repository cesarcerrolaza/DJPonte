<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear DJ sin djsession_id
        $dj = User::create([
            'name' => 'DjTest',
            'email' => 'djtest.mail@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'dj',
            'profile_photo_path' => 'storage/users/djtest.jpg',
        ]);

        // Guardamos el DJ temporalmente en el seeder para que DjsessionSeeder lo use
        $this->command->getOutput()->writeln("DJ creado con ID: {$dj->id}");

        // Crear User normal sin djsession_id aún (lo actualizaremos después si hace falta)
        User::create([
            'name' => 'UserTest',
            'email' => 'cesarcerrolaza@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
            'profile_photo_path' => 'storage/users/default.png',
        ]);
    }
}
