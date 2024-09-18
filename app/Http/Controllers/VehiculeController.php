<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VehiculeController extends Controller
{
    public function index()
    {
        $vehicules = Vehicule::all();
        return response()->json($vehicules);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $conducteur = $user->conducteur;

        if (!$conducteur) {
            return response()->json(['error' => 'Vous devez être un conducteur pour ajouter un véhicule'], 403);
        }

        $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'immatriculation' => 'required|string|max:20',
            'couleur' => 'required|string|max:50',
            'types_confort' => 'required|string|max:100',
        ]);

        // Vérifiez si un véhicule avec la même immatriculation existe déjà
        $existingVehicule = Vehicule::where('immatriculation', $request->immatriculation)->first();
        if ($existingVehicule) {
            return response()->json(['error' => 'Ce véhicule appartient déjà à quelqu\'un d\'autre'], 409);
        }

        try {
            $vehicule = new Vehicule();
            $vehicule->fill($request->all());
            $vehicule->conducteur_id = $conducteur->id;
            $vehicule->save();

            return response()->json($vehicule, 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du véhicule: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création du véhicule.'], 500);
        }
    }

    public function show($id)
    {
        $vehicule = Vehicule::find($id);
        if (!$vehicule) {
            return response()->json(['message' => 'Véhicule non trouvé'], 404);
        }
        return response()->json($vehicule);
    }

    public function update(Request $request, $id)
    {
        $vehicule = Vehicule::find($id);
        if (!$vehicule) {
            return response()->json(['message' => 'Véhicule non trouvé'], 404);
        }

        $user = Auth::user();
        $conducteur = $user->conducteur;

        if (!$conducteur || $vehicule->conducteur_id != $conducteur->id) {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à mettre à jour ce véhicule'], 403);
        }

        $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'immatriculation' => 'required|string|max:20|unique:vehicules,immatriculation,' . $vehicule->id,
            'couleur' => 'required|string|max:50',
            'types_confort' => 'required|string|max:100',
        ]);

        $vehicule->update($request->all());

        return response()->json($vehicule);
    }

    public function destroy($id)
    {
        $vehicule = Vehicule::find($id);
        if (!$vehicule) {
            return response()->json(['message' => 'Véhicule non trouvé'], 404);
        }

        $user = Auth::user();
        $conducteur = $user->conducteur;

        if (!$conducteur || $vehicule->conducteur_id != $conducteur->id) {
            return response()->json(['error' => 'Vous n\'êtes pas autorisé à supprimer ce véhicule'], 403);
        }

        $vehicule->delete();

        return response()->json(['message' => 'Véhicule supprimé avec succès']);
    }
}
