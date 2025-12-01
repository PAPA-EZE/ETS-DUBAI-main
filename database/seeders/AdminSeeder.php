<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PointVente;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  public function run(): void
  {
    // Créer le point de vente central
    $point = PointVente::firstOrCreate(
      ['code' => 'DXB-MAG'],
      [
        'nom' => 'Dubai Magasin',
        'type' => 'central',
        'adresse' => 'Yaoundé, Cameroun',
        'telephone' => '+237 XXX XXX XXX',
        'actif' => true,
      ]
    );

    // Créer l'admin
    User::firstOrCreate(
      ['email' => 'admin@etsdubai.cm'],
      [
        'name' => 'Administrateur',
        'password' => Hash::make('Admin@2025!'),
        'role' => 'admin',
        'point_vente_id' => $point->id,
        'actif' => true,
        'email_verified_at' => now(),
      ]
    );

    $this->command->info('✅ Administrateur créé avec succès !');
  }
}
