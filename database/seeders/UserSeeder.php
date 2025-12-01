<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PointVente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les points de vente
        $dubaiMagasin = PointVente::where('code', 'DXB-MAG')->first();
        $dubaiCave = PointVente::where('code', 'DXB-CAV')->first();
        $dubaiLounge = PointVente::where('code', 'DXB-LOU')->first();
        $dubaiMeyoma = PointVente::where('code', 'DXB-MEY')->first();

        $users = [
            // Administrateur Système
            [
                'name' => 'Administrateur Système',
                'email' => 'admin@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'point_vente_id' => null, // Admin n'a pas de point de vente spécifique
                'actif' => true,
                'email_verified_at' => now(),
            ],

            // Responsable - Dubai Magasin (Point Central/Source)
            [
                'name' => 'Responsable Dépôt',
                'email' => 'responsable@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'responsable',
                'point_vente_id' => $dubaiMagasin->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],

            // Vendeurs - Dubai Magasin (Central)
            [
                'name' => 'Jean Dupont',
                'email' => 'jean@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiMagasin->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Marie Kamga',
                'email' => 'marie@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiMagasin->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],

            // Vendeurs - Dubai Cave
            [
                'name' => 'Paul Mbida',
                'email' => 'paul@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiCave->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sophie Ngo',
                'email' => 'sophie@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiCave->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],

            // Vendeurs - Dubai Lounge Plus
            [
                'name' => 'Eric Fotso',
                'email' => 'eric@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiLounge->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],

            // Vendeurs - Dubai Meyomadjom
            [
                'name' => 'Claire Njoya',
                'email' => 'claire@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiMeyoma->id,
                'actif' => true,
                'email_verified_at' => now(),
            ],

            // Vendeur inactif (pour test)
            [
                'name' => 'Ancien Vendeur',
                'email' => 'ancien@depot.cm',
                'password' => Hash::make('password'),
                'role' => 'vendeur',
                'point_vente_id' => $dubaiMagasin->id,
                'actif' => false,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('✅ ' . count($users) . ' utilisateurs créés (1 admin, 1 responsable, 6 vendeurs actifs, 1 inactif)');
    }
}
