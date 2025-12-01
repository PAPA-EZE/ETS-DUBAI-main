<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained();
            $table->foreignId('fournisseur_id')->constrained();
            $table->string('nom');
            $table->string('code_barre')->unique()->nullable();
            $table->string('reference')->unique();
            $table->text('description')->nullable();

            // Système de conditionnement
            $table->enum('type_conditionnement', ['casier', 'palette', 'unite'])->default('casier');
            $table->integer('unites_par_conditionnement')->default(12); // ← 12 par défaut
            $table->integer('unites_par_pack')->default(6); // ← AJOUTÉ : Pack/palette

            // Prix par conditionnement (casier/palette)
            $table->decimal('prix_achat_conditionnement', 10, 2)->default(0);
            $table->decimal('prix_vente_conditionnement', 10, 2)->default(0);

            // Prix par unité (bouteille)
            $table->decimal('prix_achat_unite', 10, 2)->default(0);
            $table->decimal('prix_vente_unite', 10, 2)->default(0);

            // Marges paramétrables (en %)
            $table->decimal('marge_conditionnement', 5, 2)->default(0);
            $table->decimal('marge_unite', 5, 2)->default(0);

            // TVA optionnelle
            $table->boolean('tva_applicable')->default(true);
            $table->decimal('taux_tva', 5, 2)->default(19.25);

            // Gestion du stock (toujours en unités/bouteilles)
            $table->integer('stock_actuel')->default(0);
            $table->integer('stock_minimum')->default(10);

            // Divers
            $table->string('image')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
