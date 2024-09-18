<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'montant',
        'date_paiement',
        'type',
    ];

    protected $dates = [
        'date_paiement',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function paiementLiquide()
    {
        return $this->hasOne(PaiementLiquide::class);
    }

    public function paiementMoyenDeTransfert()
    {
        return $this->hasOne(PaiementMoyenDeTransfert::class);
    }
}
