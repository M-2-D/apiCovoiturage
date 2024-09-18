<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail']; // Choisir les canaux de notification, e.g., 'mail', 'database', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Une nouvelle réservation a été effectuée pour votre trajet.')
                    ->line('Nom du passager : ' . $this->reservation->passager->user->name)
                    ->line('Nombre de places réservées : ' . $this->reservation->nombre_places)
                    ->line('Total à payer : ' . ($this->reservation->nombre_places * $this->reservation->trajet->prix))
                    ->action('Voir les réservations', url('/reservations'))
                    ->line('Merci d\'utiliser notre application!');
    }
}
