<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Djsession extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
        'start_time',
        'end_time'
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

    //------------------METODOS------------------//

    // Añadir canción a la lista de peticiones
    public function addSongRequest($song)
    {
        //$this->songRequests()->create($song); TODO: Implementar
    }

    //Concatenación de localizacion
    public function fullLocation()
    {
        return $this->venue . ', ' . $this->address . ', ' . $this->city;
    }





    //------------------EVENTOS------------------//




    
}
