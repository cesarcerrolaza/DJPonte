<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Song::create([
            'title' => 'TestSong',
            'artist' => 'TestArtist'
        ]);

        Song::create([
            'title' => 'This is an example',
            'artist' => 'Example'
        ]);
    }
}
