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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->decimal('montant', 10, 2); // Montant du paiement
            $table->date('date_paiement');
            $table->string('type'); // Type de paiement (liquide ou moyen de transfert)
            $table->timestamps();
        });

        // Table pour les paiements en espèces
        Schema::create('paiements_liquides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained('paiements');
            $table->timestamps();
            // Ajoutez d'autres attributs spécifiques aux paiements en espèces si nécessaire
        });

        // Table pour les paiements par moyen de transfert
        Schema::create('paiements_moyen_de_transfert', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained('paiements');
            $table->timestamps();
            // Ajoutez d'autres attributs spécifiques aux paiements par moyen de transfert si nécessaire
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements_moyen_de_transfert');
        Schema::dropIfExists('paiements_liquides');
        Schema::dropIfExists('paiements');
    }
};
