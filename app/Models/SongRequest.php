<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{

    protected $fillable = [
        'djsession_id',
        'song_id',
        'custom_title',
        'custom_artist',
        'score'
    ];
    //

    //------------------RELACIONES------------------//

    // Canción solicitada
    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    // Sesión a la que pertenece la petición
    public function djsession()
    {
        return $this->belongsTo(Djsession::class);
    }

    //------------------METODOS------------------//


    //------------------EVENTOS------------------//
}
