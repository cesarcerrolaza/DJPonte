<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\NewSongRequest;
use Illuminate\Support\Facades\Log;

/**
 * @mixin IdeHelperSongRequest
 */
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

    // Crear una nueva petición desde redes sociales
    public static function createSocialRequest($djsessionId, $comment)
    {

        Log::info(">>> INICIO createSocialRequest", [
            'djsessionId' => $djsessionId,
            'comment' => $comment,
        ]);
        
        try {
            $songData = Song::getSongDataFromComment($comment);
            Log::info(">>> Datos obtenidos del comentario:", $songData);
        } catch (\Exception $e) {
            Log::error(">>> Error en getSongDataFromComment: " . $e->getMessage());
            return;
        }

        self::createSongRequest($djsessionId, $songData);
    }


    // Crear una nueva petición
    public static function createSongRequest($djsessionId, $songData)
    {
        Log::info("Procesando petición de canción para la sesión ID: {$djsessionId}", [
            'songData' => $songData,
        ]);
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
            Log::info("Petición de canción personalizada recibida: {$songData['title']} - {$songData['artist']}");
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
                    Log::info("Creando nueva petición de canción personalizada: {$songData['title']} - {$songData['artist']}");
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
