<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{

    protected $fillable = [
        'title',
        'artist'
        /*
        'album',
        'year',
        'genre',
        'duration',
        'spotify_id',
        'deezer_id',
        'youtube_id',
        'soundcloud_id',
        'custom_url',
        'custom_image',
        'custom_preview',
        'custom_title',
        'custom_artist',
        'custom_album',
        'custom_genre',
        'custom_duration',
        'custom_spotify_id',
        'custom_deezer_id',
        'custom_youtube_id',
        'custom_soundcloud_id',
        'custom_custom_url',
        'custom_custom_image',
        'custom_custom_preview',
        'custom_score'
        */
    ];


    //------------------RELACIONES------------------//

    // Canciones solicitadas
    public function songRequests()
    {
        return $this->hasMany(SongRequest::class);
    }

    //------------------METODOS------------------//


    //------------------EVENTOS------------------//

}
