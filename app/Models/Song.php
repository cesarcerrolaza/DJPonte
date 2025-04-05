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

    // Reconocer canción y artista de un comentario
    public static function getSongDataFromComment($comment)
    {
        $songData = [
            'songId' => null,
            'title' => null,
            'artist' => null
        ];

        $comment = self::cleanComment($comment);
        
        $songInfo = self::searchWithStandardFormat($comment);
        if ($songInfo) {
            $song = self::where('title', 'like', '%' . $songInfo['title'] . '%')
            ->where('artist', 'like', '%' . $songInfo['artist'] . '%')
            ->first();
        }
        else {
            // Busca el nombre de la canción
            $song = self::where('title', 'like', '%' . $comment . '%')->first();
        }
        if ($song) {
            $songData['songId'] = $song->id;
            $songData['title'] = $song->title;
            $songData['artist'] = $song->artist;
        }
        elseif ($songInfo) {
            $songData['title'] = $songInfo['title'];
            $songData['artist'] = $songInfo['artist'];
        }

        return $songData;
    }

    // 🔹 Limpiar comentario de caracteres innecesarios
public static function cleanComment($comment)
{
    // Convertir a minúsculas
    $comment = strtolower($comment);

    // Eliminar caracteres especiales y espacios extra
    $comment = preg_replace('/[^a-z0-9\s\-]/', '', $comment);
    $comment = preg_replace('/\s+/', ' ', trim($comment));

    // Normalizar conectores y separadores comunes
    $comment = str_replace(
        [' ft ', ' feat ', ' featuring ', ' x ', ' & ', ' and ', ' – ', ' — ', ' / ', ' \\ ', ' | '],
        ' ',
        $comment
    );

    return $comment;
}

    // Reconocer patrón con formato estándar: "djponte Cancion - Artista"
    public static function searchWithStandardFormat($comment)
    {
        $pattern = '/^djponte\s+(.+)\s+-\s+(.+)/';
        $matches = [];
        if (preg_match($pattern, $comment, $matches)) {
            $title = $matches[1];
            $artist = $matches[2];
            return  ['title' => $title, 'artist' => $artist];
        }
        return null;
    }



    //------------------EVENTOS------------------//

}
