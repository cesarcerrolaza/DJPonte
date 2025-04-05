<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\NewSongRequest;

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

    // Crear una nueva petición
    public static function createSocialRequest($djsessionId, $comment)
    {
        //  Obtiene canción y artista del comentario
        $songData = Song::getSongDataFromComment($comment);

        // Crea la petición
        if ($songData['songId']) {
            $songRequest = self::where('djsession_id', $djsessionId)
                ->where('song_id', $songData['songId'])
                ->first();
            if ($songRequest) {
                $songRequest->score += 1;
            } else {
                $songRequest = new SongRequest([
                    'djsession_id' => $djsessionId,
                    'song_id' => $songData['songId'],
                    'score' => 1
                ]);
            }
            
        } else {
            if (empty($songData['title'])) {
                return;
            }
            else {
                $songRequest = self::where('djsession_id', $djsessionId)
                    ->where('custom_title', $songData['title'])
                    ->where('custom_artist', $songData['artist'])
                    ->first();
            
                if ($songRequest) {
                    $songRequest->score += 1;
                } else {
                    $songRequest = new SongRequest([
                        'djsession_id' => $djsessionId,
                        'custom_title' => $songData['title'],
                        'custom_artist' => $songData['artist'],
                        'score' => 1
                    ]);
                }
            }
        }
        $songRequest->save();
        $songRequest->load('song');

        broadcast(new NewSongRequest($songRequest));
    }

    //------------------EVENTOS------------------//
}
