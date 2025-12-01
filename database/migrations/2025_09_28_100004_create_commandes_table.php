<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande')->unique();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs');
            $table->foreignId('user_id')->constrained('users'); // Utilisateur qui a créé
            $table->date('date_commande');
            $table->date('date_livraison_prevue')->nullable();
            $table->date('date_livraison_reelle')->nullable();
            $table->enum('statut', [
                'brouillon',      // En cours de création
                'en_attente',     // Envoyée au fournisseur
                'confirmee',      // Confirmée par le fournisseur
                'en_preparation', // En cours de préparation
                'expediee',       // Expédiée
                'livree',         // Livrée complètement
                'partiellement_livree', // Livrée partiellement
                'annulee'         // Annulée
            ])->default('brouillon');
            $table->decimal('montant_ht', 12, 2)->default(0);
            $table->decimal('montant_tva', 12, 2)->default(0);
            $table->decimal('montant_ttc', 12, 2)->default(0);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->decimal('ristourne_prevue', 10, 2)->default(0);
            $table->decimal('ristourne_reelle', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('notes_livraison')->nullable();
            $table->string('bon_commande_path')->nullable(); // Chemin vers le PDF généré
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['fournisseur_id', 'statut']);
            $table->index(['date_commande']);
            $table->index(['statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
