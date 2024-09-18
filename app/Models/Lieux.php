<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lieux extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'lieux';

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'nom',
    ];
}
