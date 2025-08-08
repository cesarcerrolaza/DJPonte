<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'account_id',
        'username',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    //------------------RELACIONES------------------//

    /**
     * Relación inversa con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el modelo SocialPost.
     */
    public function socialPosts(){
        return $this->hasMany(SocialPost::class);
    }

    //------------------METODOS------------------//

    /**
     * Accesor para obtener el access_token desencriptado.
     */
    public function getAccessTokenAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Mutador para guardar el access_token encriptado.
     */
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Igual para el refresh_token.
     */
    public function getRefreshTokenAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setRefreshTokenAttribute($value)
    {
        $this->attributes['refresh_token'] = $value ? Crypt::encryptString($value) : null;
    }
}