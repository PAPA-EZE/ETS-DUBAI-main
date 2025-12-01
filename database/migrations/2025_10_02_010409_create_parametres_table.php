<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('cle')->unique();
            $table->text('valeur')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, json
            $table->string('categorie')->default('general'); // general, entreprise, tva, caisse
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insérer les paramètres par défaut
        DB::table('parametres')->insert([
            // Entreprise
            [
                'cle' => 'entreprise_nom',
                'valeur' => 'Mon Entreprise',
                'type' => 'text',
                'categorie' => 'entreprise',
                'description' => 'Nom de l\'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cle' => 'entreprise_adresse',
                'valeur' => 'Sangmelima, Cameroun',
                'type' => 'text',
                'categorie' => 'entreprise',
                'description' => 'Adresse de l\'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cle' => 'entreprise_telephone',
                'valeur' => '+237 6XX XXX XXX',
                'type' => 'text',
                'categorie' => 'entreprise',
                'description' => 'Téléphone de l\'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cle' => 'entreprise_email',
                'valeur' => 'contact@entreprise.cm',
                'type' => 'text',
                'categorie' => 'entreprise',
                'description' => 'Email de l\'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // TVA
            [
                'cle' => 'tva_taux_defaut',
                'valeur' => '19.25',
                'type' => 'number',
                'categorie' => 'tva',
                'description' => 'Taux de TVA par défaut (%)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cle' => 'tva_numero',
                'valeur' => '',
                'type' => 'text',
                'categorie' => 'tva',
                'description' => 'Numéro de contribuable',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Caisse
            [
                'cle' => 'caisse_fond_defaut',
                'valeur' => '50000',
                'type' => 'number',
                'categorie' => 'caisse',
                'description' => 'Fond de caisse par défaut',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Général
            [
                'cle' => 'devise',
                'valeur' => 'FCFA',
                'type' => 'text',
                'categorie' => 'general',
                'description' => 'Devise utilisée',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cle' => 'stock_alerte_actif',
                'valeur' => '1',
                'type' => 'boolean',
                'categorie' => 'general',
                'description' => 'Activer les alertes de stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
