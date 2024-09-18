<?php

namespace App\Http\Controllers;

use App\Models\Conducteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConducteurController extends Controller
{
    public function index()
    {
        $conducteurs = Conducteur::with('user', 'vehicule', 'trajets')->get();
        return response()->json($conducteurs, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'numero_permis' => 'required|string|max:255',
            'CIN' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $conducteur = Conducteur::create($request->all());

        return response()->json($conducteur, 201);
    }

    public function show(Conducteur $conducteur)
    {
        $conducteur->load('user', 'vehicule', 'trajets');
        return response()->json($conducteur, 200);
    }

    public function update(Request $request, Conducteur $conducteur)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|required|exists:users,id',
            'numero_permis' => 'sometimes|required|string|max:255',
            'CIN' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $conducteur->update($request->all());

        return response()->json($conducteur, 200);
    }

    public function destroy(Conducteur $conducteur)
    {
        $conducteur->delete();
        return response()->json(null, 204);
    }
}
