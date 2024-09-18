<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrajetController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->conducteur) {
            $trajets = Trajet::where('conducteur_id', $user->conducteur->id)
                ->with('conducteur.user', 'vehicule', 'reservations')
                ->get();
        } else {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        return response()->json($trajets, 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $conducteur = $user->conducteur;

        // Vérifiez si le conducteur a un véhicule
        if (!$conducteur || !$conducteur->vehicule) {
            return response()->json(['error' => 'Vous devez ajouter un véhicule avant de pouvoir publier un trajet'], 400);
        }

        $validator = Validator::make($request->all(), [
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'nombre_places' => 'required|integer|min:1',
            'heure_depart' => 'required|date_format:H:i:s',
            'date_depart' => 'required|date',
            'prix' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $trajet = new Trajet();
        $trajet->fill($request->all());

        // Assignez le conducteur actuel
        $trajet->conducteur_id = $conducteur->id;
        $trajet->vehicule_id = $conducteur->vehicule->id; // Assurez-vous que c'est bien vehicule et non vehicules
        $trajet->save();

        return response()->json($trajet, 201);
    }

    public function show(Trajet $trajet)
    {
        if ($trajet->conducteur_id != Auth::user()->conducteur->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Charge les relations nécessaires
        $trajet->load('conducteur.user', 'vehicule', 'reservations');
        return response()->json($trajet, 200);
    }

    public function update(Request $request, Trajet $trajet)
    {
        $validator = Validator::make($request->all(), [
            'lieu_depart' => 'sometimes|required|string|max:255',
            'lieu_arrivee' => 'sometimes|required|string|max:255',
            'nombre_places' => 'sometimes|required|integer|min:1',
            'heure_depart' => 'sometimes|required|date_format:H:i:s',
            'date_depart' => 'sometimes|required|date',
            'prix' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($trajet->conducteur_id != Auth::user()->conducteur->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $trajet->fill($request->all());
        $trajet->save();

        return response()->json($trajet, 200);
    }

    public function destroy(Trajet $trajet)
    {
        if ($trajet->conducteur_id != Auth::user()->conducteur->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $trajet->delete();
        return response()->json(null, 204);
    }

    public function rechercherTrajets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'date_depart' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $lieu_depart = $request->lieu_depart;
        $lieu_arrivee = $request->lieu_arrivee;
        $date_depart = $request->date_depart;

        $trajets = Trajet::where('lieu_depart', $lieu_depart)
            ->where('lieu_arrivee', $lieu_arrivee)
            ->whereDate('date_depart', $date_depart)
            ->with('conducteur.user', 'vehicule', 'reservations')
            ->get();

        if ($trajets->isEmpty()) {
            return response()->json(['message' => 'Désolé, nous n\'avons pas trouvé de trajet correspondant à votre recherche.'], 404);
        }

        return response()->json($trajets, 200);
    }
        public function getReservations($id)
    {
        $trajet = Trajet::with(['reservations.passager.user'])->find($id);

        if (!$trajet) {
            return response()->json(['message' => 'Trajet non trouvé'], 404);
        }

        if (auth()->user()->id !== $trajet->conducteur_id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        return response()->json($trajet->reservations);
    }
}
