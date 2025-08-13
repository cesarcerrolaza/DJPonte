<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTip
 */
class Tip extends Model
{
    protected $fillable = [
        'user_id',           // quien da la propina
        'dj_id',             // a quien se le da la propina
        'djsession_id',      // en qué djsession se da
        'song_id',           // cancion asociada a la propina
        'custom_title',     // título de la canción
        'custom_artist',    // artista de la canción
        'amount',            // importe en céntimos
        'currency',         // p.ej. 'eur'
        'stripe_session_id', // ID de la sesión de Stripe
        'status',           // estado de la propina (pending, paid, failed)
        'description',      // descripción de la propina
    ];

    protected $casts = [
        'amount' => 'integer', // almacenar como entero (céntimos)
        'status' => 'string',  // almacenar como cadena
    ];

    //------------------RELACIONES------------------//
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function djsession()
    {
        return $this->belongsTo(Djsession::class);
    }
    public function song()
    {
        return $this->belongsTo(Song::class);
    }


    //------------------ACCESORES------------------//
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount / 100, 2, '.', '') . ' ' . strtoupper($this->currency);
    }
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'paid' => 'Pagada',
            'failed' => 'Fallida',
            default => 'Desconocido',
        };
    }



}
