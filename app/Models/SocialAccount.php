<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'platform',
        'account_id',
        'username'
    ];

    //------------------RELACIONES------------------//

    // Usuario al que pertenece la cuenta social
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //------------------METODOS------------------//

    //------------------EVENTOS------------------//
}
