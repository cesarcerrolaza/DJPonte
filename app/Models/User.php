<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Laravel\Cashier\Billable
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //------------------RELACIONES------------------//

    // Sesiones DJ creadas por el usuario dj
    public function djsessions()
    {
        return $this->hasMany(Djsession::class);
    }

    // Sesion a la que se ha unido el usuario
    public function djsessionActive()
    {
        return $this->belongsTo(Djsession::class, 'djsession_id');
    }

    // Cuentas de redes sociales asociadas al usuario
    public function socialUsers()
    {
        return $this->hasMany(SocialAccount::class);
    }

    // Donaciones del usuario
    public function tips()
    {
        return $this->hasMany(Tip::class, 'user_id');
    }

    // Sorteos creados como DJ
    public function rafflesCreated()
    {
        return $this->hasMany(Raffle::class, 'dj_id');
    }

    // Sorteos ganados
    public function rafflesWon()
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //------------------EVENTOS------------------//
}
