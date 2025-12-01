<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Bières',
                'description' => 'Toutes les bières (locales et importées)',
                'couleur' => '#F59E0B',
                'actif' => true,
            ],
            [
                'nom' => 'Vins',
                'description' => 'Vins rouges, blancs et rosés',
                'couleur' => '#DC2626',
                'actif' => true,
            ],
            [
                'nom' => 'Spiritueux',
                'description' => 'Whiskys, vodkas, rhums, etc.',
                'couleur' => '#7C3AED',
                'actif' => true,
            ],
            [
                'nom' => 'Champagnes',
                'description' => 'Champagnes et vins mousseux',
                'couleur' => '#FBBF24',
                'actif' => true,
            ],
            [
                'nom' => 'Softs',
                'description' => 'Boissons sans alcool, sodas, jus',
                'couleur' => '#10B981',
                'actif' => true,
            ],
            [
                'nom' => 'Eaux',
                'description' => 'Eaux minérales et gazeuses',
                'couleur' => '#3B82F6',
                'actif' => true,
            ],
            [
                'nom' => 'Liqueurs',
                'description' => 'Liqueurs et crèmes',
                'couleur' => '#EC4899',
                'actif' => true,
            ],
            [
                'nom' => 'Apéritifs',
                'description' => 'Apéritifs et vermouth',
                'couleur' => '#EF4444',
                'actif' => true,
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::firstOrCreate(
                ['nom' => $categorie['nom']],
                $categorie
            );
        }

        $this->command->info('✅ Catégories créées avec succès !');
    }
}
