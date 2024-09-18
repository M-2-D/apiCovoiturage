<?php

namespace App\Http\Controllers;

use App\Models\Lieux;
use Illuminate\Http\Request;

class LieuxController extends Controller
{
    /**
     * Afficher la liste de tous les lieux.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $lieux = Lieux::all(); // Récupérer tous les lieux
        return response()->json($lieux);
    }

    /**
     * Afficher les détails d'un lieu spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $lieu = Lieux::find($id); // Trouver le lieu par ID
        if ($lieu) {
            return response()->json($lieu);
        } else {
            return response()->json(['message' => 'Lieu non trouvé'], 404);
        }
    }

    /**
     * Créer un nouveau lieu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:lieux,nom',
        ]);

        $lieu = Lieux::create([
            'nom' => $request->input('nom'),
        ]);

        return response()->json($lieu, 201);
    }

    /**
     * Mettre à jour un lieu existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $lieu = Lieux::find($id);

        if ($lieu) {
            $lieu->update([
                'nom' => $request->input('nom'),
            ]);

            return response()->json($lieu);
        } else {
            return response()->json(['message' => 'Lieu non trouvé'], 404);
        }
    }

    /**
     * Supprimer un lieu spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $lieu = Lieux::find($id);

        if ($lieu) {
            $lieu->delete();
            return response()->json(['message' => 'Lieu supprimé']);
        } else {
            return response()->json(['message' => 'Lieu non trouvé'], 404);
        }
    }
}
