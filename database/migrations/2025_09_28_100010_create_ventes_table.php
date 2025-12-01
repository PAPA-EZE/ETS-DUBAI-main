<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_vente')->unique();

            $table->foreignId('point_vente_id')->constrained('points_vente')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('caisse_id')->nullable()->constrained('caisses')->onDelete('set null');

            // Date et type paiement
            $table->timestamp('date_vente')->useCurrent();
            $table->enum('type_paiement', ['especes', 'mobile_money', 'credit', 'mixte'])->default('especes');

            // Montants
            $table->decimal('montant_ht', 12, 2)->default(0);
            $table->decimal('montant_tva', 12, 2)->default(0);
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->decimal('remise_globale', 12, 2)->default(0);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->decimal('montant_rendu', 12, 2)->default(0);

            // ✅ MODIFIÉ : Ajout du statut "brouillon"
            $table->enum('statut', ['brouillon', 'completee', 'annulee'])->default('brouillon');

            // ✅ AJOUTÉ : Dates de validation
            $table->timestamp('date_validation')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->onDelete('set null');

            // Informations complémentaires
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour performance
            $table->index('numero_vente');
            $table->index('date_vente');
            $table->index('statut');
            $table->index('type_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
