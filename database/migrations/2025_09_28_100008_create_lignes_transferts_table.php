<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes_transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfert_stock_id')
                ->constrained('transferts_stocks')  // ← Pluriel
                ->onDelete('cascade');
            $table->foreignId('produit_id')
                ->constrained('produits')
                ->onDelete('cascade');
            $table->integer('quantite_demandee');
            $table->integer('quantite_expedie')->default(0);
            $table->integer('quantite_recue')->default(0);
            $table->enum('type_conditionnement', ['casier', 'pack', 'unite'])->default('casier');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_transferts');
    }
};
