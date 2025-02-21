<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialUser extends Model
{
    //
    protected $fillable = [
        'username',
        'platform',
        'djsession_id'
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
