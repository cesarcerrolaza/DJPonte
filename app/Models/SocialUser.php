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

    // Sorteos ganados
    public function raffles()
    {
        return $this->morphMany(Raffle::class, 'winner');
    }
    
    // Sorteos en los que ha participado
    public function rafflesParticipated()
    {
        return $this->belongsToMany(Raffle::class, 'raffle_user')
                    ->withTimestamps();
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
