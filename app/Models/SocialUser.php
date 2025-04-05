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

    public static function connectToDjSession($username, $djsessionId, $platform)
    {
        $socialUser = self::where('username', $username)
            ->where('platform', $platform)
            ->first();
        if ($socialUser) {
            $socialUser->djsession_id = $djsessionId;
        } else {
            $socialUser = new SocialUser([
                'username' => $username,
                'platform' => $platform,
                'djsession_id' => $djsessionId
            ]);
        }
        $socialUser->save();
        return $socialUser;
    }


    //------------------EVENTOS------------------//

}
