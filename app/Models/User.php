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
use App\Services\DjsessionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Laravel\Cashier\Billable
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens;
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
        'stripe_id',
        'stripe_account_id',
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

    public function djsessions()
    {
        return $this->hasMany(Djsession::class);
    }

    public function djsessionActive()
    {
        return $this->belongsTo(Djsession::class, 'djsession_id');
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function tips()
    {
        return $this->hasMany(Tip::class, 'user_id');
    }

    public function rafflesCreated()
    {
        return $this->hasMany(Raffle::class, 'dj_id');
    }

    public function rafflesWon()
    {
        return $this->morphMany(Raffle::class, 'winner');
    }

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
            'last_request_at' => 'datetime'
        ];
    }

    /**
     * Get the user's profile photo URL.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset($this->profile_photo_path)
            : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return 'storage/users/default.png';
    }

    /**
     * Delete the Stripe customer for this user.
     *
     * Jetstream may call deleteStripeCustomer() when deleting an account.
     * Provide a safe fallback so tests don't try to call Stripe and so
     * the method exists even if Cashier internals differ between versions.
     *
     * @return bool
     */
    public function deleteStripeCustomer(): bool
    {
        // In tests, avoid contacting Stripe at all.
        if (app()->runningUnitTests()) {
            return true;
        }

        // If Cashier provides a helper to delete the Stripe customer, use it.
        // Newer Cashier versions include helper methods; check and call them safely.
        if (method_exists($this, 'deleteAsStripeCustomer')) {
            try {
                $this->deleteAsStripeCustomer();
                return true;
            } catch (\Throwable $e) {
                Log::warning('Failed to delete Stripe customer: '.$e->getMessage());
                return false;
            }
        }

        // Fallback: attempt to retrieve Stripe customer object and delete it.
        if (method_exists($this, 'asStripeCustomer')) {
            try {
                $customer = $this->asStripeCustomer();
                if ($customer) {
                    $customer->delete();
                }
                return true;
            } catch (\Throwable $e) {
                Log::warning('Failed to delete Stripe customer (fallback): '.$e->getMessage());
                return false;
            }
        }

        // If nothing else available, return true to avoid blocking account deletion.
        return true;
    }

    /**
     * Cambia el rol de un usuario de, eliminando los datos asociados a cada rol.
     * La operación se envuelve en una transacción de base de datos.
     *
     * @return void
     */
    public function changeRole()
    {
        if ($this->role == 'dj') {
            DB::transaction(function () {
                $djsession = $this->djsessionActive;
                if($djsession){
                    app(DjsessionService::class)->deactivate($djsession);
                }
                $this->role = 'user';
                $this->save();
        });
        }
        else{
            DB::transaction(function () {
                $djsession = $this->djsessionActive;
                if($djsession){
                    app(DjsessionService::class)->leave($djsession, $this);
                }
                $this->role = 'dj';
                $this->save();
            });
        }
    }



    //------------------EVENTOS------------------//
}
