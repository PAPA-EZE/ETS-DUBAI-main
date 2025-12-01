<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits');
            $table->integer('quantite_commandee');
            $table->integer('quantite_livree')->default(0);
            $table->decimal('prix_unitaire_ht', 10, 2);
            $table->decimal('prix_unitaire_ttc', 10, 2);
            $table->decimal('montant_ligne_ht', 12, 2);
            $table->decimal('montant_ligne_ttc', 12, 2);
            $table->decimal('taux_tva', 5, 2)->default(19.25); // TVA Cameroun
            $table->text('notes_item')->nullable();
            $table->timestamps();

            // Index pour performance
            $table->index(['commande_id']);
            $table->index(['produit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_items');
    }
};
