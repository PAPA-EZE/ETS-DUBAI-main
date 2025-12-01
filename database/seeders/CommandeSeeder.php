<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Fournisseur;
use App\Models\Produit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommandeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $sabc = Fournisseur::where('nom', 'like', '%SABC%')->first();
        $guinness = Fournisseur::where('nom', 'like', '%Guinness%')->first();
        $cocacola = Fournisseur::where('nom', 'like', '%Coca-Cola%')->first();
        $sourcePays = Fournisseur::where('nom', 'like', '%Source du Pays%')->first();

        if (!$user || !$sabc || !$guinness || !$cocacola || !$sourcePays) {
            $this->command->warn('Certains fournisseurs ou utilisateurs manquent. Exécutez d\'abord les autres seeders.');
            return;
        }

        // Commande 1: SABC - Livrée complètement
        $commande1 = Commande::create([
            'fournisseur_id' => $sabc->id,
            'user_id' => $user->id,
            'date_commande' => Carbon::now()->subDays(10),
            'date_livraison_prevue' => Carbon::now()->subDays(3),
            'date_livraison_reelle' => Carbon::now()->subDays(2),
            'statut' => 'livree',
            'notes' => 'Commande de réassort pour les bières Castel et Mutzig suite aux ventes importantes du weekend.',
        ]);

        // Articles commande 1
        $produitsCastel65 = Produit::where('nom', 'like', '%Castel%65cl%')->first();
        $produitsCastel33 = Produit::where('nom', 'like', '%Castel%33cl%')->first();
        $produitsMutzig = Produit::where('nom', 'like', '%Mutzig%')->first();

        if ($produitsCastel65) {
            CommandeItem::create([
                'commande_id' => $commande1->id,
                'produit_id' => $produitsCastel65->id,
                'quantite_commandee' => 100,
                'quantite_livree' => 100,
                'prix_unitaire_ht' => 450,
                'taux_tva' => 19.25,
            ]);
        }

        if ($produitsCastel33) {
            CommandeItem::create([
                'commande_id' => $commande1->id,
                'produit_id' => $produitsCastel33->id,
                'quantite_commandee' => 200,
                'quantite_livree' => 200,
                'prix_unitaire_ht' => 280,
                'taux_tva' => 19.25,
            ]);
        }

        if ($produitsMutzig) {
            CommandeItem::create([
                'commande_id' => $commande1->id,
                'produit_id' => $produitsMutzig->id,
                'quantite_commandee' => 50,
                'quantite_livree' => 50,
                'prix_unitaire_ht' => 420,
                'taux_tva' => 19.25,
            ]);
        }

        // Commande 2: Coca-Cola - En cours de livraison partielle
        $commande2 = Commande::create([
            'fournisseur_id' => $cocacola->id,
            'user_id' => $user->id,
            'date_commande' => Carbon::now()->subDays(5),
            'date_livraison_prevue' => Carbon::now()->addDays(2),
            'statut' => 'partiellement_livree',
            'notes' => 'Commande sodas pour la haute saison. Livraison en plusieurs fois selon la capacité du camion.',
            'notes_livraison' => 'Première livraison reçue le ' . Carbon::now()->subDays(2)->format('d/m/Y') . '. Coca et Fanta OK, Sprite en attente.',
        ]);

        $produitsCoca = Produit::where('nom', 'like', '%Coca-Cola%')->first();
        $produitsFanta = Produit::where('nom', 'like', '%Fanta%')->first();
        $produitsSprite = Produit::where('nom', 'like', '%Sprite%')->first();

        if ($produitsCoca) {
            CommandeItem::create([
                'commande_id' => $commande2->id,
                'produit_id' => $produitsCoca->id,
                'quantite_commandee' => 150,
                'quantite_livree' => 150, // Complètement livré
                'prix_unitaire_ht' => 200,
                'taux_tva' => 19.25,
            ]);
        }

        if ($produitsFanta) {
            CommandeItem::create([
                'commande_id' => $commande2->id,
                'produit_id' => $produitsFanta->id,
                'quantite_commandee' => 120,
                'quantite_livree' => 120, // Complètement livré
                'prix_unitaire_ht' => 180,
                'taux_tva' => 19.25,
            ]);
        }

        if ($produitsSprite) {
            CommandeItem::create([
                'commande_id' => $commande2->id,
                'produit_id' => $produitsSprite->id,
                'quantite_commandee' => 100,
                'quantite_livree' => 0, // Pas encore livré
                'prix_unitaire_ht' => 180,
                'taux_tva' => 19.25,
            ]);
        }

        // Commande 3: Guinness - En préparation
        $commande3 = Commande::create([
            'fournisseur_id' => $guinness->id,
            'user_id' => $user->id,
            'date_commande' => Carbon::now()->subDays(3),
            'date_livraison_prevue' => Carbon::now()->addDays(4),
            'statut' => 'en_preparation',
            'notes' => 'Commande spéciale Guinness et Beaufort pour événement client important.',
        ]);

        $produitsGuinness = Produit::where('nom', 'like', '%Guinness%')->first();
        $produitsBeaufort = Produit::where('nom', 'like', '%Beaufort%')->first();

        if ($produitsGuinness) {
            CommandeItem::create([
                'commande_id' => $commande3->id,
                'produit_id' => $produitsGuinness->id,
                'quantite_commandee' => 80,
                'quantite_livree' => 0,
                'prix_unitaire_ht' => 500,
                'taux_tva' => 19.25,
            ]);
        }

        if ($produitsBeaufort) {
            CommandeItem::create([
                'commande_id' => $commande3->id,
                'produit_id' => $produitsBeaufort->id,
                'quantite_commandee' => 60,
                'quantite_livree' => 0,
                'prix_unitaire_ht' => 380,
                'taux_tva' => 19.25,
            ]);
        }

        // Commande 4: Source du Pays - En attente de confirmation
        $commande4 = Commande::create([
            'fournisseur_id' => $sourcePays->id,
            'user_id' => $user->id,
            'date_commande' => Carbon::now()->subDays(1),
            'date_livraison_prevue' => Carbon::now()->addDays(7),
            'statut' => 'en_attente',
            'notes' => 'Réassort eau Tangui - stock bas détecté automatiquement.',
        ]);

        $produitsTangui15 = Produit::where('nom', 'like', '%Tangui%1.5L%')->first();
        $produitsTangui50 = Produit::where('nom', 'like', '%Tangui%50cl%')->first();

        if ($produitsTangui15) {
            CommandeItem::create([
                'commande_id' => $commande4->id,
                'produit_id' => $produitsTangui15->id,
                'quantite_commandee' => 200,
                'quantite_livree' => 0,
                'prix_unitaire_ht' => 150,
                'taux_tva' => 19.25,
            ]);
        }

        if ($produitsTangui50) {
            CommandeItem::create([
                'commande_id' => $commande4->id,
                'produit_id' => $produitsTangui50->id,
                'quantite_commandee' => 300,
                'quantite_livree' => 0,
                'prix_unitaire_ht' => 80,
                'taux_tva' => 19.25,
            ]);
        }

        // Commande 5: SABC - En retard (pour tester les alertes)
        $commande5 = Commande::create([
            'fournisseur_id' => $sabc->id,
            'user_id' => $user->id,
            'date_commande' => Carbon::now()->subDays(15),
            'date_livraison_prevue' => Carbon::now()->subDays(3), // En retard !
            'statut' => 'expediee',
            'notes' => 'Commande urgente - client important. Livraison promise pour hier !',
        ]);

        if ($produitsCastel65) {
            CommandeItem::create([
                'commande_id' => $commande5->id,
                'produit_id' => $produitsCastel65->id,
                'quantite_commandee' => 50,
                'quantite_livree' => 0,
                'prix_unitaire_ht' => 450,
                'taux_tva' => 19.25,
            ]);
        }

        // Commande 6: Brouillon - pour tester l'édition
        $commande6 = Commande::create([
            'fournisseur_id' => $cocacola->id,
            'user_id' => $user->id,
            'date_commande' => Carbon::now(),
            'date_livraison_prevue' => Carbon::now()->addDays(10),
            'statut' => 'brouillon',
            'notes' => 'Commande en cours de préparation - à finaliser demain matin.',
        ]);

        if ($produitsCoca) {
            CommandeItem::create([
                'commande_id' => $commande6->id,
                'produit_id' => $produitsCoca->id,
                'quantite_commandee' => 75,
                'quantite_livree' => 0,
                'prix_unitaire_ht' => 200,
                'taux_tva' => 19.25,
            ]);
        }

        // Recalculer les totaux pour toutes les commandes
        foreach ([$commande1, $commande2, $commande3, $commande4, $commande5, $commande6] as $commande) {
            $commande->calculerTotaux();
            $commande->calculerRistourne();
        }

        $this->command->info('✅ 6 commandes de test créées avec succès !');
        $this->command->info('   - 1 commande livrée complètement');
        $this->command->info('   - 1 commande partiellement livrée');
        $this->command->info('   - 1 commande en préparation');
        $this->command->info('   - 1 commande en attente');
        $this->command->info('   - 1 commande en retard (pour tester les alertes)');
        $this->command->info('   - 1 commande brouillon (pour tester l\'édition)');
    }
}
