<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementMoyenDeTransfert extends Paiement
{
    use HasFactory;

    protected $table = 'paiements_moyen_de_transfert';

    protected $fillable = [
        'paiement_id',
        // Ajoutez d'autres attributs spécifiques aux paiements par moyen de transfert si nécessaire
    ];
}
