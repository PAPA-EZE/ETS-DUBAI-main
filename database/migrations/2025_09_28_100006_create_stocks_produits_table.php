<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('point_vente_id')->constrained('points_vente')->onDelete('cascade');
            $table->integer('quantite')->default(0);
            $table->timestamps();

            // Un produit ne peut avoir qu'une ligne de stock par point
            $table->unique(['produit_id', 'point_vente_id']);
        });

        // Migrer les stocks existants vers le point central
        $produits = DB::table('produits')->get();

        foreach ($produits as $produit) {
            DB::table('stocks_produits')->insert([
                'produit_id' => $produit->id,
                'point_vente_id' => 1, // Dubai Magasin (central)
                'quantite' => $produit->stock_actuel ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks_produits');
    }
};
