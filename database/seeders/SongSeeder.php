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
        //Song::factory(10)->create();

        Song::create(
            [
                'title' => 'TestSong',
                'artist' => 'TestArtist'
            ],
            [
                'title' => 'This is an',
                'artist' => 'Example'
            ]
        );

    }
}
