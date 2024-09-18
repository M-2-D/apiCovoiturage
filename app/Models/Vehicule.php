<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'conducteur_id',
        'marque',
        'modele',
        'immatriculation',
        'couleur',
        'types_confort',
    ];

    public function conducteur()
    {
        return $this->belongsTo(Conducteur::class);
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
}
