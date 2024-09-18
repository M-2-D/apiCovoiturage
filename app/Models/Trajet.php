<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'conducteur_id',
        'vehicule_id',
        'lieu_depart',
        'lieu_arrivee',
        'heure_depart',
        'date_depart',
        'nombre_places',
        'prix',
    ];

    protected $casts = [
        'heure_depart' => 'datetime:H:i:s', // Cast heure_depart to datetime with the desired format
        'date_trajet' => 'date:Y-m-d',
    ];

    public function conducteur()
    {
        return $this->belongsTo(Conducteur::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
