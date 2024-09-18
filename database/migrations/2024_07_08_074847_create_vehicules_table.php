<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conducteur_id')->constrained('conducteurs')->onDelete('cascade');
            $table->string('marque');
            $table->string('modele');
            $table->string('immatriculation')->unique();
            $table->string('couleur')->nullable(); // Ajout de la couleur du véhicule
            $table->string('types_confort')->nullable(); // Ajout des types de confort du véhicule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
