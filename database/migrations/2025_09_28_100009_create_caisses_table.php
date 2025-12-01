<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caisses', function (Blueprint $table) {
            $table->id();
            $table->string('nom_caisse'); // Ex: "Caisse 1", "Caisse Principale"
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Responsable

            $table->timestamp('date_ouverture');
            $table->timestamp('date_fermeture')->nullable();

            // Fonds de caisse
            $table->decimal('fond_ouverture', 12, 2)->default(0);
            $table->decimal('fond_fermeture', 12, 2)->default(0);

            // Totaux de la session
            $table->decimal('total_ventes', 12, 2)->default(0);
            $table->decimal('total_especes', 12, 2)->default(0);
            $table->decimal('total_mobile_money', 12, 2)->default(0);
            $table->decimal('total_credit', 12, 2)->default(0);

            $table->integer('nombre_transactions')->default(0);

            // Écart théorique vs réel
            $table->decimal('montant_theorique', 12, 2)->default(0);
            $table->decimal('montant_reel', 12, 2)->default(0);
            $table->decimal('ecart', 12, 2)->default(0);

            $table->enum('statut', ['ouverte', 'fermee'])->default('ouverte');
            $table->text('notes_ouverture')->nullable();
            $table->text('notes_fermeture')->nullable();

            $table->timestamps();

            // Index
            $table->index(['date_ouverture', 'statut']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caisses');
    }
};
