<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    //
    protected $fillable = [
        'djsession_id',
        'platform',
        'media_id'
    ];

    //------------------RELACIONES------------------//

    // Sesión de DJ
    public function djsession()
    {
        return $this->belongsTo(Djsession::class);
    }

    // Comentarios en un post
    public function comments()
    {
        return $this->hasMany(SocialPostComment::class);
    }


    //------------------METODOS------------------//


    //------------------EVENTOS------------------//
}
