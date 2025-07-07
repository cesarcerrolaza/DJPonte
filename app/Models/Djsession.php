<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Djsession extends Model
{
    protected $fillable = [
        'code',
        'name',
        'image',
        'active',
        'venue',
        'address',
        'city',
        'description',
        'start_time',
        'end_time',
        'song_request_timeout'
        ];

    //------------------RELACIONES------------------//

    // DJ creador de la sesión
    public function dj()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Usuarios que se conectan a la sesión
    public function users()
    {
        return $this->hasMany(User::class, 'djsession_id');
    }

    //Usuarios que han comentado en una publicación de la sesión
    public function socialUsers()
    {
        return $this->hasMany(SocialUser::class);
    }

    // Publicaciones en redes sociales
    public function socialPosts()
    {
        return $this->hasMany(SocialPost::class);
    }
    

    // Canciones de la sesión
    public function songRequests()
    {
        return $this->hasMany(SongRequest::class);
    }

    // Donaciones de la sesión
    public function tips()
    {
        return $this->hasMany(Tip::class);
    }

    // Donantes de la sesión
    public function donors()
    {
        return $this->hasMany(Donor::class);
    }

    // Rifas de la sesión
    public function raffles()
    {
        return $this->hasMany(Raffle::class);
    }

    // Rifa actual
    public function currentRaffle()
    {
        return $this->hasOne(Raffle::class)->where('is_current', true);
    }
    

    //------------------METODOS------------------//

    // Añadir canción a la lista de peticiones
    public function addSongRequest($song)
    {
        //$this->songRequests()->create($song); TODO: Implementar
    }

    //Concatenación de localizacion
    public function fullLocation()
    {
        $fullLocation = $this->venue;
        if ($this->address) {
            $fullLocation .= ', ' . $this->address;
        }
        if ($this->city) {
            $fullLocation .= ', ' . $this->city;
        }
        return $fullLocation;
    }

    // Usuario abandona la sesión
    public function userLeaved()
    {
        $this->current_users--;
        $this->save();
    }

    // Usuario se une a la sesión
    public function userJoined()
    {
        $this->current_users++;
        if ($this->current_users > $this->peak_users) {
            $this->peak_users = $this->max_users;
        }
        $this->save();
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset(Djsession::getDefaultImagePath());
    }

    public static function getDefaultImagePath()
    {
        return 'storage/djsessions/default.jpg';
    }

    //------------------EVENTOS------------------//




    
}
