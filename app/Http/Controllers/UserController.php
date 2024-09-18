<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware('role:Administrateur')->except('show');
    }

    // Lister tous les utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // Créer un nouvel utilisateur
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Conducteur,Passager,Administrateur',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    // Afficher les détails d'un utilisateur
    // public function show($id)
    // {
    //     $user = User::find($id);
    //     if (!$user) {
    //         return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    //     }
    //     return response()->json($user, 200);
    // }


    public function show($id)
{
    $user = User::with('conducteur.vehicule')->find($id); // Charger les relations

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }


    
    return response()->json($user, 200);
}


    // Mettre à jour les informations d'un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|required|string|in:Conducteur,Passager,Administrateur',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->update($request->all());

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user, 200);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();
        return response()->json(null, 204);
    }

    // public function showProfile()
    // {
    //     // Récupérer l'utilisateur authentifié
    //     $user = auth()->user();

    //     // Retourner une réponse JSON avec les informations de l'utilisateur
    //     return response()->json($user);
    // }


    public function showProfile()
{
    // Récupérer l'utilisateur authentifié avec ses relations basées sur le rôle
    $user = User::with(['conducteur']) // Charger les relations conducteur et passager
                ->findOrFail(auth()->id());

    // Si l'utilisateur est un conducteur, ajouter cin et numero_permis
    if ($user->role === 'Conducteur') {
        $user->CIN = $user->conducteur->CIN;
        $user->numero_permis = $user->conducteur->numero_permis;
    }

    // Vous pouvez ajouter d'autres logiques ici pour les passagers ou administrateurs si nécessaire
    // if ($user->role === 'Passager' && $user->passager) {
    //     // Ajouter des informations spécifiques au passager si nécessaire
    // }

    // Retourner les informations de l'utilisateur
    return response()->json($user, 200);
}



    // Mettre à jour le profil de l'utilisateur connecté
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|required|string|max:20|unique:users,telephone,' . $user->id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->update($request->all());

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json($user, 200);
    }

    public function toggleBlock(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked; // Inverse l'état de blocage
        $user->save();

        $status = $user->is_blocked ? 'bloqué' : 'débloqué';
        return response()->json(['message' => "Utilisateur $status avec succès.", 'user' => $user]);
    }

}
