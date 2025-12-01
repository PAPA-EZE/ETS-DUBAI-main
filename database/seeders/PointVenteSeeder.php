<?php

namespace Database\Seeders;

use App\Models\PointVente;
use Illuminate\Database\Seeder;

class PointVenteSeeder extends Seeder
{
    public function run(): void
    {
        $points = [
            [
                'nom' => 'Dubai Magasin',
                'code' => 'DXB-MAG',
                'adresse' => 'Boulevard de la Liberté, Yaoundé',
                'telephone' => '+237 222 23 45 67',
                'type' => 'central',
                'actif' => true,
            ],
            [
                'nom' => 'Dubai Cave',
                'code' => 'DXB-CAV',
                'adresse' => 'Rue de Nachtigal, Yaoundé',
                'telephone' => '+237 222 23 45 68',
                'type' => 'vente',
                'actif' => true,
            ],
            [
                'nom' => 'Dubai Lounge Plus',
                'code' => 'DXB-LOU',
                'adresse' => 'Avenue Kennedy, Yaoundé',
                'telephone' => '+237 222 23 45 69',
                'type' => 'vente',
                'actif' => true,
            ],
            [
                'nom' => 'Dubai Meyomadjom',
                'code' => 'DXB-MEY',
                'adresse' => 'Quartier Meyomadjom, Yaoundé',
                'telephone' => '+237 222 23 45 70',
                'type' => 'vente',
                'actif' => true,
            ],
        ];

        $created = 0;
        foreach ($points as $point) {
            // Vérifier si le point existe déjà
            if (!PointVente::where('code', $point['code'])->exists()) {
                PointVente::create($point);
                $created++;
            }
        }

        if ($created > 0) {
            $this->command->info('✅ ' . $created . ' points de vente créés');
        } else {
            $this->command->info('ℹ️  Points de vente déjà existants (skip)');
        }
    }
}