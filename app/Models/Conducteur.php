<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conducteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_permis',
        'CIN',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicule()
    {
        return $this->hasOne(Vehicule::class);
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

}
