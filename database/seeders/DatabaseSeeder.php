<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer l'admin en premier
        $this->call(AdminSeeder::class);

        // 2. Créer les catégories (nécessaires pour les produits)
        $this->call(CategorieSeeder::class);

        // 3. Données de test seulement en local
        if (!app()->environment('production')) {
            // $this->call(DemoDataSeeder::class);
        }

        $this->command->info('');
        $this->command->info('🎉 Seeding terminé !');
        $this->command->info('');
        $this->command->info('📧 Email admin : admin@etsdubai.cm');
        $this->command->info('🔑 Mot de passe : Admin@2025!');
        $this->command->warn('⚠️  Changez ce mot de passe en production !');
    }
}
