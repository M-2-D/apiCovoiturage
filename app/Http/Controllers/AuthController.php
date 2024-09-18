<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Conducteur;
use App\Models\Passager;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    
    /**
     * Gérer l'inscription des utilisateurs.
     */
    public function register(Request $request)
    {
        try {
            // Validation des champs de la requête
            $request->validate([
                'nom' => 'required|string|max:255', // Le nom doit être une chaîne de caractères, ne pas dépasser 255 caractères et est requis
                'prenom' => 'required|string|max:255', // Le prénom doit être une chaîne de caractères, ne pas dépasser 255 caractères et est requis
                'telephone' => 'required|string|max:20|unique:users', // Le numéro de téléphone doit être une chaîne de caractères, ne pas dépasser 20 caractères et est requis
                'email' => 'required|string|email|max:255|unique:users', // L'email doit être une chaîne de caractères, un email valide, ne pas dépasser 255 caractères, est requis et doit être unique dans la table des utilisateurs
                'password' => 'required|string|min:6', // Le mot de passe doit être une chaîne de caractères, d'au moins 6 caractères et est requis
                'role' => 'required|in:Administrateur,Conducteur,Passager' // Le rôle doit être l'un des trois : Administrateur, Conducteur ou Passager
            ]);

            // Création d'un nouvel utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hachage du mot de passe
                'role' => $request->role
            ]);

            // Création du modèle spécifique au rôle
            if ($request->role === 'Conducteur') {
                Conducteur::create([
                    'user_id' => $user->id,
                    'numero_permis' => $request->numero_permis, // Champ requis pour Conducteur
                    'CIN' => $request->CIN // Champ requis pour Conducteur
                ]);
            } elseif ($request->role === 'Passager') {
                Passager::create([
                    'user_id' => $user->id,
                    // Ajouter d'autres champs spécifiques au modèle Passager si nécessaire
                ]);
            } elseif ($request->role === 'Administrateur') {
                Administrateur::create([
                    'user_id' => $user->id,
                    // Ajouter d'autres champs spécifiques au modèle Administrateur si nécessaire
                ]);
            }

            // Retourner les données de l'utilisateur en JSON avec un code HTTP 201 (créé)
            return response()->json(['user' => $user], 201);
        } catch (ValidationException $e) {
            // Retourner les erreurs de validation avec un code HTTP 422 (Entité Non Traitable)
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Retourner un message d'erreur général avec un code HTTP 500 (Erreur Interne du Serveur)
            return response()->json(['message' => 'Une erreur est survenue lors de l\'inscription', 'error' => $e->getMessage()], 500);
        }
    }
 
    /**
     * Gérer la connexion des utilisateurs.
     */
    public function login(Request $request)
    {
        // Validation de la requête entrante
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
     
        // Tentative d'authentification de l'utilisateur
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Retourner une réponse d'erreur avec un code 401 si l'authentification échoue
            return response()->json(['message' => 'Détails de connexion invalides'], 401);
        }
     
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
     
        // Vérifier si l'utilisateur est bloqué
        if ($user->isBlocked()) {
            // Déconnecter l'utilisateur
            Auth::logout();
     
            // Retourner une réponse d'erreur indiquant que le compte est bloqué
            return response()->json(['message' => 'Votre compte est bloqué.'], 403);
        }
     
        // Créer un nouveau token pour l'utilisateur
        $token = $user->createToken('authToken')->plainTextToken;
     
        // Retourner les données de l'utilisateur et le token en JSON
        return response()->json(['user' => $user, 'token' => $token]);
    }
     
    /**
     * Gérer la déconnexion des utilisateurs.
     */
    public function logout(Request $request)
    {
        // Supprimer le token d'accès actuel
        $request->user()->currentAccessToken()->delete();

        // Retourner une réponse de succès
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Gérer l'inscription des administrateurs.
     */
    public function registerAdmin(Request $request)
    {
        try {
            // Validation des champs de la requête
            $request->validate([
                'nom' => 'required|string|max:255', // Le nom doit être une chaîne de caractères, ne pas dépasser 255 caractères et est requis
                'prenom' => 'required|string|max:255', // Le prénom doit être une chaîne de caractères, ne pas dépasser 255 caractères et est requis
                'telephone' => 'required|string|max:20|unique:users', // Le numéro de téléphone doit être une chaîne de caractères, ne pas dépasser 20 caractères et est requis
                'email' => 'required|string|email|max:255|unique:users', // L'email doit être une chaîne de caractères, un email valide, ne pas dépasser 255 caractères, est requis et doit être unique dans la table des utilisateurs
                'password' => 'required|string|min:6', // Le mot de passe doit être une chaîne de caractères, d'au moins 6 caractères et est requis
            ]);

            // Création d'un nouvel utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hachage du mot de passe
                'role' => 'Administrateur' 
            ]);

            // Création du modèle Administrateur
            Administrateur::create([
                'user_id' => $user->id,
                // Ajouter d'autres champs spécifiques au modèle Administrateur si nécessaire
            ]);
            
            // Retourner les données de l'utilisateur en JSON avec un code HTTP 201 (créé)
            return response()->json(['user' => $user], 201);
        } catch (ValidationException $e) {
            // Retourner les erreurs de validation avec un code HTTP 422 (Entité Non Traitable)
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Retourner un message d'erreur général avec un code HTTP 500 (Erreur Interne du Serveur)
            return response()->json(['message' => 'Une erreur est survenue lors de l\'inscription de l\'administrateur', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Gérer l'inscription des conducteurs.
     */
    public function registerConducteur(Request $request)
    {
        try {
            // Validation des champs de la requête
            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:20|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'numero_permis' => 'required|string|max:255', // Champ requis pour Conducteur
                'CIN' => 'required|string|max:255', // Champ requis pour Conducteur
            ]);

            // Création d'un nouvel utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hachage du mot de passe
                'role' => 'Conducteur', // Attribution du rôle
            ]);

            // Création du modèle Conducteur
            Conducteur::create([
                'user_id' => $user->id,
                'numero_permis' => $request->numero_permis,
                'CIN' => $request->CIN,
            ]);

            // Retourner les données de l'utilisateur en JSON avec un code HTTP 201 (créé)
            return response()->json(['user' => $user], 201);
        } catch (ValidationException $e) {
            // Retourner les erreurs de validation avec un code HTTP 422 (Entité Non Traitable)
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Retourner un message d'erreur général avec un code HTTP 500 (Erreur Interne du Serveur)
            return response()->json(['message' => 'Une erreur est survenue lors de l\'inscription du conducteur', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Gérer l'inscription des passagers.
     */
    public function registerPassager(Request $request)
    {
        try {
            // Validation des champs de la requête
            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:20|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                // Ajouter d'autres champs spécifiques au modèle Passager si nécessaire
            ]);

            // Création d'un nouvel utilisateur
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hachage du mot de passe
                'role' => 'Passager', // Attribution du rôle
            ]);

            // Création du modèle Passager
            Passager::create([
                'user_id' => $user->id,
                // Ajouter d'autres champs spécifiques au modèle Passager si nécessaire
            ]);

            // Retourner les données de l'utilisateur en JSON avec un code HTTP 201 (créé)
            return response()->json(['user' => $user], 201);
        } catch (ValidationException $e) {
            // Retourner les erreurs de validation avec un code HTTP 422 (Entité Non Traitable)
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Retourner un message d'erreur général avec un code HTTP 500 (Erreur Interne du Serveur)
            return response()->json(['message' => 'Une erreur est survenue lors de l\'inscription du passager', 'error' => $e->getMessage()], 500);
        }
    }
}
