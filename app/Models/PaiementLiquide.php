<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementLiquide extends Paiement
{
    use HasFactory;

    protected $table = 'paiements_liquides';

    protected $fillable = [
        'paiement_id',
        // Ajoutez d'autres attributs spécifiques aux paiements en espèces si nécessaire
    ];
}
