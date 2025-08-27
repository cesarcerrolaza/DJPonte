<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

/**
 * @mixin IdeHelperSocialPost
 */
class SocialPost extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'djsession_id',
        'social_account_id',
        'platform',
        'media_id',
        'is_active',
        'caption',
        'media_url',
        'permalink',
    ];

    //------------------RELACIONES------------------//

    // Sesión de DJ
    public function djsession()
    {
        return $this->belongsTo(Djsession::class);
    }

    // Cuenta social asociada
    public function socialAccount()
    {
        return $this->belongsTo(SocialAccount::class);
    }

    // Comentarios en un post
    public function comments()
    {
        return $this->hasMany(SocialPostComment::class);
    }


    //------------------METODOS------------------//


    //------------------EVENTOS------------------//
}
