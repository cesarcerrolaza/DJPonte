<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDonor
 */
class Donor extends Model
{
    protected $fillable = [
        'user_id',           // quien da las propinas
        'djsession_id',      // en qué djsession se dan
        'amount',            // importe en céntimos
        'currency',         // p.ej. 'eur'
    ];

    protected $casts = [
        'amount' => 'integer', // almacenar como entero (céntimos)
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
}
