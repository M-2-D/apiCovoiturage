<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\Vehicule; // Assurez-vous que vous importez le modèle Vehicule si vous en avez besoin
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
    
        if ($user->conducteur) {
            // Vérifiez les réservations pour les trajets du conducteur avec les informations du trajet, du conducteur, et du véhicule
            $reservations = Reservation::with(['trajet.conducteur.user', 'trajet.vehicule'])
                ->whereHas('trajet', function($query) use ($user) {
                    $query->where('conducteur_id', $user->conducteur->id);
                })
                ->get();
        // } elseif ($user->passager) {
        //     // Vérifiez les réservations pour le passager connecté avec les informations du trajet, du conducteur, et du véhicule
        //     $reservations = Reservation::with(['trajet.conducteur.user', 'trajet.vehicule'])
        //         ->where('passager_id', $user->passager->id)
        //         ->get();
        } 
        elseif ($user->passager) {
            // Récupère les réservations pour le passager connecté avec les informations du trajet, conducteur, et véhicule
            $reservations = Reservation::with(['trajet.conducteur.user', 'trajet.vehicule', 'passager.user'])
                ->where('passager_id', $user->passager->id)
                ->get();}
        else {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Ajoute le prix total pour chaque réservation
        foreach ($reservations as $reservation) {
            $reservation->prix_total = $reservation->nombre_places * $reservation->trajet->prix;
        }

        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trajet_id' => 'required|exists:trajets,id',
            'nombre_places' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trajet = Trajet::findOrFail($request->trajet_id);
        $user = Auth::user();
        $passagerId = $user->passager->id;

        if ($trajet->nombre_places >= $request->nombre_places) {
            $reservation = Reservation::create([
                'trajet_id' => $request->trajet_id,
                'passager_id' => $passagerId,
                'nombre_places' => $request->nombre_places,
                'date_reservation' => now(),
                'etat_reservation' => 'En attente',
            ]);

            $trajet->nombre_places -= $request->nombre_places;
            $trajet->save();

            // Ajoute le prix total à la réponse
            $reservation->prix_total = $request->nombre_places * $trajet->prix;

            return response()->json($reservation, 201);
        } else {
            return response()->json(['message' => 'Nombre de places insuffisant'], 400);
        }
    }

    public function show($id)
    {
        $reservation = Reservation::with('trajet.conducteur.user', 'trajet.vehicule')->findOrFail($id);
        $user = Auth::user();

        if ($user->conducteur && $reservation->trajet->conducteur_id != $user->conducteur->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($user->passager && $reservation->passager_id != $user->passager->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Ajoute le prix total à la réponse
        $reservation->prix_total = $reservation->nombre_places * $reservation->trajet->prix;

        return response()->json($reservation);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = Auth::user();

        // Vérifiez les autorisations
        if ($user->conducteur && $reservation->trajet->conducteur_id != $user->conducteur->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($user->passager && $reservation->passager_id != $user->passager->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nombre_places' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trajet = $reservation->trajet;
        $difference = $request->nombre_places - $reservation->nombre_places;

        if ($trajet->nombre_places + $reservation->nombre_places >= $request->nombre_places) {
            $reservation->nombre_places = $request->nombre_places;
            $reservation->save();

            $trajet->nombre_places -= $difference;
            $trajet->save();

            // Ajoute le prix total à la réponse
            $reservation->prix_total = $reservation->nombre_places * $trajet->prix;

            return response()->json($reservation);
        } else {
            return response()->json(['message' => 'Nombre de places insuffisant'], 400);
        }
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = Auth::user();

        if ($user->conducteur && $reservation->trajet->conducteur_id != $user->conducteur->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($user->passager && $reservation->passager_id != $user->passager->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $trajet = $reservation->trajet;
        $trajet->nombre_places += $reservation->nombre_places;
        $trajet->save();

        $reservation->delete();

        return response()->json(['message' => 'Réservation annulée'], 200);
    }

    public function confirm($id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = Auth::user();

        if ($user->conducteur && $reservation->trajet->conducteur_id != $user->conducteur->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($user->passager && $reservation->passager_id != $user->passager->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        if ($reservation->etat_reservation == 'En attente') {
            $reservation->etat_reservation = 'Confirmée';
            $reservation->save();

            return response()->json(['message' => 'Réservation confirmée'], 200);
        } else {
            return response()->json(['message' => 'Réservation ne peut pas être confirmée'], 400);
        }
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = Auth::user();

        // Vérifiez si l'utilisateur est le conducteur du trajet
        if ($user->conducteur && $reservation->trajet->conducteur_id != $user->conducteur->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Vérifiez l'état actuel de la réservation
        if ($reservation->etat_reservation == 'Annulée') {
            return response()->json(['message' => 'La réservation est déjà annulée'], 400);
        }

        // Annuler la réservation
        $reservation->etat_reservation = 'Annulée';
        $reservation->save();

        // Remettre les places dans le trajet
        $trajet = $reservation->trajet;
        $trajet->nombre_places += $reservation->nombre_places;
        $trajet->save();

        return response()->json(['message' => 'Réservation annulée avec succès'], 200);
    }

    // app/Http/Controllers/ReservationController.php

    // ReservationController.php
public function getReservationsForConducteur($id)
{
    // Assurez-vous que l'utilisateur est authentifié
    $user = auth()->user();

    // Vérifiez que l'utilisateur est un conducteur
    if ($user->role !== 'Conducteur') {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    // Récupérez les réservations pour les trajets du conducteur spécifié
    $reservations = Reservation::whereHas('trajet', function ($query) use ($id) {
        $query->where('conducteur_id', $id);
    })->with('passager', 'trajet') // Assurez-vous d'inclure les relations nécessaires
    ->get();

    return response()->json($reservations);
}

// ReservationController.php

public function getPassengersForConducteur($conducteurId)
{
    $reservations = Reservation::whereHas('trajet', function($query) use ($conducteurId) {
        $query->where('conducteur_id', $conducteurId);
    })->with('passager')->get();

    return response()->json($reservations);
}
public function getReservationsByConducteur($conducteurId)
{
    $reservations = Reservation::with(['trajet', 'passager'])
        ->whereHas('trajet', function ($query) use ($conducteurId) {
            $query->where('conducteur_id', $conducteurId);
        })
        ->get();

    return response()->json($reservations);
}



}
