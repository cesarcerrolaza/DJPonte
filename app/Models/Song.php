<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

/**
 * @mixin IdeHelperSong
 */
class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist'
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
        $data = self::searchWithStandardFormat($comment);

        if ($data) {
            $song = self::where('title', $data['title'])
                ->where('artist', $data['artist'])
                ->first();

            return [
                'songId' => $song?->id,
                'title'  => $data['title'],
                'artist' => $data['artist'],
            ];
        }

        // fallback: búsqueda solo por título
        $title = trim(str_ireplace('djponte', '', $comment));
        if ($title) {
            $song = self::where('title', $title)->first();
            return [
                'songId' => $song?->id,
                'title'  => $title,
                'artist' => $song?->artist,
            ];
        }

        return [
            'songId' => null,
            'title'  => null,
            'artist' => null,
        ];
    }

    // 🔹 Buscar patrón con formato estándar: "djponte Cancion - Artista"
    public static function searchWithStandardFormat($comment)
    {

        $pattern = '/^(?:djponte\s+)?(.+?)\s*-\s*(.+)$/i';

        if (preg_match($pattern, trim($comment), $matches)) {
            return [
                'artist' => trim($matches[1]),
                'title'  => trim($matches[2]),
            ];
        }

        return null;
    }

    // 🔹 Intento de match en DB (normalizando artista y título)
    protected static function matchSongInDb($title, $artist)
    {
        return self::whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($title) . '%'])
            ->whereRaw('LOWER(artist) LIKE ?', ['%' . strtolower($artist) . '%'])
            ->first();
    }



    //------------------EVENTOS------------------//

}
