<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transferts_stocks', function (Blueprint $table) {  // ← AVEC 's'
            $table->id();
            $table->string('numero_transfert')->unique();

            // Points de vente
            $table->foreignId('point_source_id')->constrained('points_vente');
            $table->foreignId('point_destination_id')->constrained('points_vente');

            // Utilisateurs
            $table->foreignId('demande_par')->constrained('users'); // Qui demande
            $table->foreignId('valide_par')->nullable()->constrained('users'); // Qui valide

            // Statuts
            $table->enum('statut', ['en_attente', 'valide', 'expedie', 'recu', 'refuse', 'annule'])->default('en_attente');

            // Dates
            $table->timestamp('date_demande')->useCurrent();
            $table->timestamp('date_validation')->nullable();
            $table->timestamp('date_expedition')->nullable();
            $table->timestamp('date_reception')->nullable();

            // Informations
            $table->text('motif')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('numero_transfert');
            $table->index('statut');
            $table->index('date_demande');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferts_stocks');  // ← AVEC 's'
    }
};
