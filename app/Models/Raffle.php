<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Djsession;
use App\Models\SocialUser;
use Illuminate\Support\Facades\Log;

/**
 * @mixin IdeHelperRaffle
 */
class Raffle extends Model
{
    use HasFactory;

    protected $fillable = [
        'dj_id',
        'djsession_id',
        'winner_id',
        'winner_type',
        'prize_name',
        'prize_quantity',
        'prize_image',
        'description',
        'is_current',
        'status',
        'participants_count',
    ];

    protected $casts = [
        'prize_quantity' => 'integer',
        'participants_count' => 'integer',
    ];

    // ENUM de estados válidos
    public const STATUS_DRAFT  = 'draft';
    public const STATUS_OPEN   = 'open';
    public const STATUS_CLOSED  = 'closed';
    public const STATUS_TERMINATED = 'terminated';
    public const USER_APP = 'App\Models\User';
    public const USER_SOCIAL = 'App\Models\SocialUser';

    public static function getValidStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_OPEN,
            self::STATUS_CLOSED,
            self::STATUS_TERMINATED,
        ];
    }

    // Relaciones

    public function dj()
    {
        return $this->belongsTo(User::class, 'dj_id');
    }

    public function djsession()
    {
        return $this->belongsTo(Djsession::class);
    }

    public function winner()
    {
        return $this->morphTo();
    }

    public function appParticipants()
    {
        return $this->belongsToMany(User::class, 'raffle_user');
    }

    public function socialParticipants()
    {
        return $this->belongsToMany(SocialUser::class, 'raffle_social_user');
    }

    public function hasAppParticipant($id)
    {
        return $this->appParticipants()->where('user_id', $id)->exists();
    }

    public function hasSocialParticipant($id)
    {
        return $this->socialParticipants()->where('social_user_id', $id)->exists();
    }

    //Utilidades de participación
    public function participateApp($user_id){
        $user = User::find($user_id);
        if ($user && $this->isOpen() && !$this->hasAppParticipant($user_id)) {
            $this->appParticipants()->attach($user_id);
            $this->participants_count++;
            $this->save();
            broadcast(new \App\Events\NewRaffleParticipant($this->djsession_id, $this->id, $user->name));
        }
    }

    public function participateSocial($social_user_id){
        $user = SocialUser::find($social_user_id);
        if ($user && $this->isOpen() && !$this->hasAppParticipant($social_user_id)) {
            $this->socialParticipants()->attach($social_user_id);
            $this->participants_count++;
            $this->save();
            broadcast(new \App\Events\NewRaffleParticipant($this->djsession_id, $this->id, $user->name));
        }
    }

    public function detachParticipants(): void
    {
        $this->appParticipants()->detach();
        $this->socialParticipants()->detach();
    }

    public function participantsCount()
    {
        return $this->participants_count ?? $this->appParticipants()->count() + $this->socialParticipants()->count();
    }

    // Modificadores de estado
    public function setCurrent($current = true): void
    {
        if ($current) {
            if($this->djsession && $this->djsession->currentRaffle){
                $this->djsession->currentRaffle->setCurrent(false);
            }
            if($this->status=== self::STATUS_TERMINATED){
                $this->status = self::STATUS_DRAFT;
                $this->participants_count = 0;
            }
            $this->is_current = true;
            $this->save();
            broadcast(new \App\Events\RaffleOperation($this->djsession_id, $this->id, 'set_current'));
        }
        else {
            $this->is_current = false;
            $this->save();
        }
    }

    public function open(): void
    {
        if ($this->isCurrent()) {
            $this->status = self::STATUS_OPEN;
            $this->save();
            broadcast(new \App\Events\RaffleOperation($this->djsession_id, $this->id, self::STATUS_OPEN));
        }
    }

    public function close(): void
    {
        if ($this->isCurrent()) {
            $this->status = self::STATUS_CLOSED;
            $this->save();
            broadcast(new \App\Events\RaffleOperation($this->djsession_id, $this->id, self::STATUS_CLOSED));
            Log::info("Raffle {$this->id} set as CLOSED for Djsession {$this->djsession_id}");
        }
    }

    public function draw(): void
    {
        if ($this->participantsCount() > 0) {
            $participants = $this->appParticipants->concat($this->socialParticipants);
            $winner = $participants->random();
            if ($winner) {
                $this->winner()->associate($winner);
                $this->status = self::STATUS_CLOSED;
                $this->save();
                if($this->isCurrent()){
                    Log::info("Raffle {$this->id} winner event: {$winner->name} (ID: {$winner->id}, Type: " . get_class($winner) . ")");
                    broadcast(new \App\Events\RaffleWinner(djsessionId: $this->djsession_id, raffleId: $this->id, winnerId: $winner->id, winnerType: get_class($winner)));
                }
            }
        }
    }

    public function terminate(): void
    {
        $this->status = self::STATUS_TERMINATED;
        if($this->isCurrent()){
            $this->is_current = false;
            Log::info("Raffle {$this->id} terminated for Djsession {$this->djsession_id}");
            broadcast(new \App\Events\RaffleOperation($this->djsession_id, $this->id, self::STATUS_TERMINATED));
        }
        $this->detachParticipants();
        $this->save();
    }
    
    public function getPrizeImageUrlAttribute()
    {
        if ($this->prize_image) {
            return asset('storage/' . $this->prize_image);
        }
        return asset(Raffle::getDefaultImagePath());
    }

    public static function getDefaultImagePath()
    {
        return 'storage/raffles/default.png';
    }

    // Observadores de estado
    public function isCurrent(): bool
    {
        return $this->is_current;
    }
    
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isDrawn(): bool
    {
        return $this->winner !== null;
    }

    public function isTerminated(): bool
    {
        return $this->status === self::STATUS_TERMINATED;
    }

}

