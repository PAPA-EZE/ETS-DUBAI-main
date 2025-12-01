<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ristournes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();

            // Période
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('periode_libelle'); // Ex: "Janvier 2025", "T1 2025"

            // Montants
            $table->decimal('montant_achats_ht', 12, 2)->default(0); // Total achats HT sur la période
            $table->decimal('taux_ristourne', 5, 2)->default(0); // Taux appliqué (%)
            $table->decimal('montant_ristourne', 12, 2)->default(0); // Montant de ristourne calculé

            // Statut
            $table->enum('statut', ['calculee', 'validee', 'payee', 'annulee'])->default('calculee');
            $table->date('date_validation')->nullable();
            $table->date('date_paiement')->nullable();

            // Détails
            $table->text('details')->nullable(); // JSON avec détails du calcul
            $table->text('notes')->nullable();

            $table->timestamps();

            // Index
            $table->index(['fournisseur_id', 'date_debut', 'date_fin']);
            $table->index('statut');
        });

        // Table pour les barèmes de ristournes
        Schema::create('bareme_ristournes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();

            $table->string('nom'); // Ex: "Barème standard", "Barème promotionnel"
            $table->decimal('seuil_min', 12, 2); // Seuil minimum d'achat
            $table->decimal('seuil_max', 12, 2)->nullable(); // Seuil maximum (null = illimité)
            $table->decimal('taux', 5, 2); // Taux de ristourne (%)

            $table->enum('type_periode', ['mensuel', 'trimestriel', 'semestriel', 'annuel'])->default('mensuel');
            $table->boolean('actif')->default(true);

            $table->date('date_debut')->nullable(); // Validité du barème
            $table->date('date_fin')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            // Index
            $table->index(['fournisseur_id', 'actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bareme_ristournes');
        Schema::dropIfExists('ristournes');
    }
};
