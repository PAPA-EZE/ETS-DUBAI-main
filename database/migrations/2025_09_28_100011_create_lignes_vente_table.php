<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes_vente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vente_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained();

            // Quantités
            $table->integer('quantite');
            $table->string('unite')->default('bouteille'); // bouteille, casier, palette

            // Prix au moment de la vente
            $table->decimal('prix_unitaire_ht', 10, 2);
            $table->decimal('prix_unitaire_ttc', 10, 2);

            // Totaux de la ligne
            $table->decimal('montant_ht', 12, 2);
            $table->decimal('montant_tva', 12, 2);
            $table->decimal('montant_total', 12, 2);

            // TVA
            $table->decimal('taux_tva', 5, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_vente');
    }
};
