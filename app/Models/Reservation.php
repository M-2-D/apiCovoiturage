<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ReservationNotification; // Import de la notification

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'passager_id',
        'trajet_id',
        'date_reservation',
        'etat_reservation',
        'nombre_places',
    ];

    protected $casts = [
        'date_reservation' => 'datetime',
    ];

    // Constantes pour les états de réservation
    const ETAT_EN_ATTENTE = 'en attente';
    const ETAT_CONFIRMEE = 'confirmée';
    const ETAT_ANNULEE = 'annulée';

    public function passager()
    {
        return $this->belongsTo(Passager::class);
    }

    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }

    public function getConducteurAttribute()
    {
        return $this->trajet->conducteur;
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Méthode boot pour envoyer la notification après création d'une réservation
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reservation) {
            $conducteur = $reservation->trajet->conducteur; // Vérifie la relation
            if ($conducteur && $conducteur->user) {
                $conducteur->user->notify(new ReservationNotification($reservation));
            }
        });
    }
}
