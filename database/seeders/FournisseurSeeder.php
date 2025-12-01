<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseurs = [
            [
                'nom' => 'SABC (Société Anonyme des Brasseries du Cameroun)',
                'telephone' => '+237 233 42 21 11',
                'email' => 'contact@sabc.cm',
                'adresse' => 'Rue Joffre, Sangmelima, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'Guinness Cameroun SA',
                'telephone' => '+237 233 40 27 27',
                'email' => 'info@guinness-cameroun.cm',
                'adresse' => 'Zone Industrielle, Sangmelima, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'UCB (Union des Brasseries du Cameroun)',
                'telephone' => '+237 233 43 56 78',
                'email' => 'commercial@ucb.cm',
                'adresse' => 'Boulevard de la Liberté, Sangmelima, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'Coca-Cola CCBM',
                'telephone' => '+237 233 42 88 88',
                'email' => 'contact@coca-cola.cm',
                'adresse' => 'Route de Bonabéri, Sangmelima, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'Source du Pays',
                'telephone' => '+237 233 45 67 89',
                'email' => 'vente@sourcedupays.cm',
                'adresse' => 'Yaoundé, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'Top Jus Cameroun',
                'telephone' => '+237 233 41 22 33',
                'email' => 'distribution@topjus.cm',
                'adresse' => 'Sangmelima, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'Red Bull Distribution Cameroun',
                'telephone' => '+237 233 44 55 66',
                'email' => 'info@redbull.cm',
                'adresse' => 'Sangmelima, Cameroun',
                'actif' => true,
            ],
            [
                'nom' => 'Wines & Spirits Import',
                'telephone' => '+237 233 46 78 90',
                'email' => 'contact@wsi.cm',
                'adresse' => 'Sangmelima, Cameroun',
                'actif' => true,
            ],
        ];

        foreach ($fournisseurs as $fournisseur) {
            Fournisseur::create($fournisseur);
        }

        $this->command->info('✅ ' . count($fournisseurs) . ' fournisseurs créés');
    }
}
