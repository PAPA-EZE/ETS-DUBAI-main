<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_caisses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('caisses')->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie', 'vente']);
            $table->decimal('montant', 15, 2);
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->foreignId('vente_id')->nullable()->constrained('ventes')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_caisses');
    }
};
