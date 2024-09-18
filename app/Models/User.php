<?php

// User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'photo_profil',
        'role',
        'is_blocked', // Ajouter ce champ
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean', // Assurez-vous que ce champ est casté en boolean
    ];

    public function passager()
    {
        return $this->hasOne(Passager::class);
    }

    public function conducteur()
    {
        return $this->hasOne(Conducteur::class);
    }

    public function administrateur()
    {
        return $this->hasOne(Administrateur::class);
    }

    public function isConducteur()
    {
        return $this->role === 'Conducteur';
    }

    public function isPassager()
    {
        return $this->role === 'Passager';
    }

    public function isAdministrateur()
    {
        return $this->role === 'Administrateur';
    }

    public function isBlocked()
    {
        return $this->is_blocked;
    }
}
