<?php

namespace Database\Seeders;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\StockProduit;
use App\Models\PointVente;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
  public function run(): void
  {
    // Récupérer les catégories
    $biere = Categorie::where('nom', 'Bières')->first();
    $vin = Categorie::where('nom', 'Vins')->first();
    $spiritueux = Categorie::where('nom', 'Spiritueux')->first();
    $sodas = Categorie::where('nom', 'Sodas')->first();
    $jus = Categorie::where('nom', 'Jus')->first();
    $eaux = Categorie::where('nom', 'Eaux')->first();
    $energisants = Categorie::where('nom', 'Energisants')->first();

    // Récupérer les fournisseurs
    $sabc = Fournisseur::where('nom', 'LIKE', '%SABC%')->first();
    $guinness = Fournisseur::where('nom', 'LIKE', '%Guinness%')->first();
    $ucb = Fournisseur::where('nom', 'LIKE', '%UCB%')->first();
    $cocacola = Fournisseur::where('nom', 'LIKE', '%Coca-Cola%')->first();
    $sourcePays = Fournisseur::where('nom', 'LIKE', '%Source du Pays%')->first();
    $topJus = Fournisseur::where('nom', 'LIKE', '%Top Jus%')->first();
    $redbull = Fournisseur::where('nom', 'LIKE', '%Red Bull%')->first();
    $wsi = Fournisseur::where('nom', 'LIKE', '%Wines%')->first();

    $produits = [
      // BIÈRES
      [
        'nom' => 'Heineken 33cl',
        'reference' => 'BEER-HEI-33',
        'code_barre' => '8710103883012',
        'description' => 'Bière blonde premium internationale 33cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $sabc->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 12000,
        'prix_vente_conditionnement' => 18000,
        'prix_achat_unite' => 500,
        'prix_vente_unite' => 750,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 480,
        'stock_minimum' => 48,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => '33 Export 33cl',
        'reference' => 'BEER-33E-33',
        'code_barre' => '6001087002011',
        'description' => 'Bière blonde locale premium 33cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $sabc->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 10000,
        'prix_vente_conditionnement' => 15000,
        'prix_achat_unite' => 417,
        'prix_vente_unite' => 625,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 720,
        'stock_minimum' => 72,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => 'Guinness 33cl',
        'reference' => 'BEER-GUI-33',
        'code_barre' => '5000213101025',
        'description' => 'Bière brune irlandaise 33cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $guinness->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 14000,
        'prix_vente_conditionnement' => 21000,
        'prix_achat_unite' => 583,
        'prix_vente_unite' => 875,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 240,
        'stock_minimum' => 48,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => 'Castel Beer 65cl',
        'reference' => 'BEER-CAS-65',
        'code_barre' => '3760074220014',
        'description' => 'Bière blonde grande format 65cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $sabc->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 12, // ← 12 bouteilles
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 10800,
        'prix_vente_conditionnement' => 16200,
        'prix_achat_unite' => 900,
        'prix_vente_unite' => 1350,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 120,
        'stock_minimum' => 24,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => 'Beaufort 33cl',
        'reference' => 'BEER-BEA-33',
        'code_barre' => '6001087004015',
        'description' => 'Bière blonde économique 33cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $ucb->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 8000,
        'prix_vente_conditionnement' => 12000,
        'prix_achat_unite' => 333,
        'prix_vente_unite' => 500,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 960,
        'stock_minimum' => 96,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],

      // SODAS
      [
        'nom' => 'Coca-Cola 50cl',
        'reference' => 'SODA-COC-50',
        'code_barre' => '5449000000996',
        'description' => 'Coca-Cola original 50cl',
        'categorie_id' => $sodas->id,
        'fournisseur_id' => $cocacola->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 12,
        'prix_achat_conditionnement' => 9600,
        'prix_vente_conditionnement' => 14400,
        'prix_achat_unite' => 400,
        'prix_vente_unite' => 600,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 480,
        'stock_minimum' => 48,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => 'Fanta Orange 50cl',
        'reference' => 'SODA-FAN-50',
        'code_barre' => '5449000017863',
        'description' => 'Fanta Orange 50cl',
        'categorie_id' => $sodas->id,
        'fournisseur_id' => $cocacola->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 12,
        'prix_achat_conditionnement' => 9600,
        'prix_vente_conditionnement' => 14400,
        'prix_achat_unite' => 400,
        'prix_vente_unite' => 600,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 360,
        'stock_minimum' => 48,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],

      // JUS
      [
        'nom' => 'Top Ananas 1L',
        'reference' => 'JUS-TOP-ANA-1L',
        'code_barre' => '6001234567890',
        'description' => 'Jus d\'ananas Top 1L',
        'categorie_id' => $jus->id,
        'fournisseur_id' => $topJus->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 12,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 7200,
        'prix_vente_conditionnement' => 10800,
        'prix_achat_unite' => 600,
        'prix_vente_unite' => 900,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 144,
        'stock_minimum' => 24,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],

      // EAUX
      [
        'nom' => 'Source du Pays 1.5L',
        'reference' => 'EAU-SDP-1.5L',
        'code_barre' => '6001098765432',
        'description' => 'Eau minérale naturelle 1.5L',
        'categorie_id' => $eaux->id,
        'fournisseur_id' => $sourcePays->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 12,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 3600,
        'prix_vente_conditionnement' => 5400,
        'prix_achat_unite' => 300,
        'prix_vente_unite' => 450,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 240,
        'stock_minimum' => 36,
        'tva_applicable' => false,
        'taux_tva' => 0,
        'actif' => true,
      ],

      // ÉNERGISANTS
      [
        'nom' => 'Red Bull 25cl',
        'reference' => 'ENER-RB-25',
        'code_barre' => '9002490100015',
        'description' => 'Boisson énergisante Red Bull 25cl',
        'categorie_id' => $energisants->id,
        'fournisseur_id' => $redbull->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 12,
        'prix_achat_conditionnement' => 19200,
        'prix_vente_conditionnement' => 28800,
        'prix_achat_unite' => 800,
        'prix_vente_unite' => 1200,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 120,
        'stock_minimum' => 24,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],

      // VINS
      [
        'nom' => 'Château Bordeaux 75cl',
        'reference' => 'VIN-BOR-75',
        'code_barre' => '3245678901234',
        'description' => 'Vin rouge Bordeaux AOC 75cl',
        'categorie_id' => $vin->id,
        'fournisseur_id' => $wsi->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 6,
        'unites_par_pack' => 3,
        'prix_achat_conditionnement' => 24000,
        'prix_vente_conditionnement' => 36000,
        'prix_achat_unite' => 4000,
        'prix_vente_unite' => 6000,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 36,
        'stock_minimum' => 12,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      // Ajouter après Red Bull, avant Château Bordeaux
      [
        'nom' => 'Mutzig 33cl',
        'reference' => 'BEER-MUT-33',
        'code_barre' => '6001087003012',
        'description' => 'Bière blonde alsacienne 33cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $sabc->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 11000,
        'prix_vente_conditionnement' => 16500,
        'prix_achat_unite' => 458,
        'prix_vente_unite' => 687,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 360,
        'stock_minimum' => 48,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => 'Sprite 50cl',
        'reference' => 'SODA-SPR-50',
        'code_barre' => '5449000000439',
        'description' => 'Sprite citron 50cl',
        'categorie_id' => $sodas->id,
        'fournisseur_id' => $cocacola->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 12,
        'prix_achat_conditionnement' => 9600,
        'prix_vente_conditionnement' => 14400,
        'prix_achat_unite' => 400,
        'prix_vente_unite' => 600,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 288,
        'stock_minimum' => 48,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
      [
        'nom' => 'Tangui 50cl',
        'reference' => 'EAU-TAN-50',
        'code_barre' => '6001098765433',
        'description' => 'Eau minérale Tangui 50cl',
        'categorie_id' => $eaux->id,
        'fournisseur_id' => $sourcePays->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 12,
        'prix_achat_conditionnement' => 4800,
        'prix_vente_conditionnement' => 7200,
        'prix_achat_unite' => 200,
        'prix_vente_unite' => 300,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 480,
        'stock_minimum' => 48,
        'tva_applicable' => false,
        'taux_tva' => 0,
        'actif' => true,
      ],
      [
        'nom' => 'Castel Beer 33cl',
        'reference' => 'BEER-CAS-33',
        'code_barre' => '3760074220013',
        'description' => 'Bière blonde Castel 33cl',
        'categorie_id' => $biere->id,
        'fournisseur_id' => $sabc->id,
        'type_conditionnement' => 'casier',
        'unites_par_conditionnement' => 24,
        'unites_par_pack' => 6,
        'prix_achat_conditionnement' => 9600,
        'prix_vente_conditionnement' => 14400,
        'prix_achat_unite' => 400,
        'prix_vente_unite' => 600,
        'marge_conditionnement' => 50,
        'marge_unite' => 50,
        'stock_actuel' => 600,
        'stock_minimum' => 72,
        'tva_applicable' => true,
        'taux_tva' => 19.25,
        'actif' => true,
      ],
    ];

    $pointCentral = PointVente::where('type', 'central')->first();

    foreach ($produits as $produitData) {
      $stockActuel = $produitData['stock_actuel'];
      unset($produitData['stock_actuel']);

      $produit = Produit::create($produitData);

      // Créer le stock au point central
      if ($stockActuel > 0 && $pointCentral) {
        StockProduit::create([
          'produit_id' => $produit->id,
          'point_vente_id' => $pointCentral->id,
          'quantite' => $stockActuel,
        ]);
      }
    }

    $this->command->info('✅ ' . count($produits) . ' produits créés avec leurs stocks');
  }
}
