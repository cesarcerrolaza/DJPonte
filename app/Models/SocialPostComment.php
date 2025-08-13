<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSocialPostComment
 */
class SocialPostComment extends Model
{
    //
    protected $fillable = [
        'social_post_id',
        'social_user_id',
        'media_id'
    ];

    //------------------RELACIONES------------------//

    // Post
    public function post()
    {
        return $this->belongsTo(SocialPost::class);
    }

    // Usuario
    public function user()
    {
        return $this->belongsTo(SocialUser::class);
    }


    //------------------METODOS------------------//

    
    //------------------EVENTOS------------------//
}
