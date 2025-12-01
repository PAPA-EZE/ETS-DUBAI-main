<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Commande;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller
{
    /**
     * Page principale des rapports
     */
    public function index()
    {
        return view('rapports.index');
    }

    /**
     * Rapport des ventes
     */
    public function ventes(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'format' => 'nullable|in:pdf,excel',
        ]);

        $dateDebut = Carbon::parse($validated['date_debut']);
        $dateFin = Carbon::parse($validated['date_fin']);

        // Recuperer les donnees
        $ventes = Vente::with(['client', 'user', 'lignes.produit'])
            ->where('statut', 'validee')
            ->whereBetween('date_vente', [$dateDebut, $dateFin])
            ->orderBy('date_vente', 'desc')
            ->get();

        // Statistiques globales
        $stats = [
            'nombre_ventes' => $ventes->count(),
            'ca_total' => $ventes->sum('montant_total'),
            'ca_moyen' => $ventes->avg('montant_total'),
            'articles_vendus' => $ventes->sum(fn($v) => $v->lignes->sum('quantite')),

            // Par type de paiement
            'especes' => $ventes->where('type_paiement', 'especes')->sum('montant_total'),
            'mobile_money' => $ventes->where('type_paiement', 'mobile_money')->sum('montant_total'),
            'credit' => $ventes->where('type_paiement', 'credit')->sum('montant_total'),

            // Par vendeur
            'par_vendeur' => $ventes->groupBy('user_id')->map(function ($groupe) {
                return [
                    'vendeur' => $groupe->first()->user->name,
                    'nombre' => $groupe->count(),
                    'montant' => $groupe->sum('montant_total'),
                ];
            }),
        ];

        // Top produits
        $topProduits = DB::table('lignes_vente')
            ->join('ventes', 'lignes_vente.vente_id', '=', 'ventes.id')
            ->join('produits', 'lignes_vente.produit_id', '=', 'produits.id')
            ->whereBetween('ventes.date_vente', [$dateDebut, $dateFin])
            ->where('ventes.statut', 'validee')
            ->select(
                'produits.nom',
                DB::raw('SUM(lignes_vente.quantite) as total_quantite'),
                DB::raw('SUM(lignes_vente.montant_total) as total_ca')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderBy('total_ca', 'desc')
            ->limit(10)
            ->get();

        // Top clients
        $topClients = DB::table('ventes')
            ->leftJoin('clients', 'ventes.client_id', '=', 'clients.id')
            ->whereBetween('ventes.date_vente', [$dateDebut, $dateFin])
            ->where('ventes.statut', 'validee')
            ->whereNotNull('ventes.client_id')
            ->select(
                'clients.nom',
                DB::raw('COUNT(*) as nombre_achats'),
                DB::raw('SUM(ventes.montant_total) as total_ca')
            )
            ->groupBy('clients.id', 'clients.nom')
            ->orderBy('total_ca', 'desc')
            ->limit(10)
            ->get();

        // Evolution journaliere
        $evolutionJournaliere = DB::table('ventes')
            ->whereBetween('date_vente', [$dateDebut, $dateFin])
            ->where('statut', 'validee')
            ->select(
                DB::raw('DATE(date_vente) as date'),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(montant_total) as ca')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = compact('dateDebut', 'dateFin', 'ventes', 'stats', 'topProduits', 'topClients', 'evolutionJournaliere');

        // Generer selon le format demande
        if ($request->filled('format')) {
            return $this->exporterVentes($data, $validated['format']);
        }

        return view('rapports.ventes', $data);
    }

    /**
     * Rapport des stocks - AVEC DECENTRALISATION
     */
    public function stocks()
    {
        $user = Auth::user();
        $isAdminOrResponsable = $user->canManageProduits();

        if ($isAdminOrResponsable) {
            // ADMIN/RESPONSABLE : Vue globale avec details par point
            $produits = Produit::with(['categorie', 'fournisseur', 'stocks.pointVente'])
                ->actif()
                ->orderBy('nom')
                ->get();

            // Calculer les stats globales
            $stats = [
                'total_produits' => $produits->count(),
                'en_rupture' => $produits->where('stock_actuel', '<=', 0)->count(),
                'stock_faible' => $produits->filter(function ($p) {
                    return $p->stock_actuel > 0 && $p->stock_actuel <= $p->stock_minimum;
                })->count(),
                // CORRECTION : Utiliser prix_achat_unite au lieu de prix_achat
                'valeur_stock' => $produits->sum(fn($p) => $p->stock_actuel * $p->prix_achat_unite),
            ];

            // Par categorie
            $parCategorie = $produits->groupBy('categorie.nom')->map(function ($groupe) {
                return [
                    'nombre' => $groupe->count(),
                    'quantite_totale' => $groupe->sum('stock_actuel'),
                    // CORRECTION : Utiliser prix_achat_unite
                    'valeur' => $groupe->sum(fn($p) => $p->stock_actuel * $p->prix_achat_unite),
                ];
            });

            // Ajouter les infos de stock par point pour chaque produit
            $produits->transform(function ($produit) {
                $produit->stocks_par_point = $produit->stocks->mapWithKeys(function ($stock) use ($produit) {
                    return [
                        $stock->pointVente->nom => [
                            'quantite' => $stock->quantite,
                            'valeur' => $stock->quantite * $produit->prix_achat_unite
                        ]
                    ];
                });
                return $produit;
            });
        } else {
            // VENDEUR : Seulement son point de vente
            $pointVenteId = $user->point_vente_id;

            $produits = Produit::with(['categorie', 'fournisseur'])
                ->actif()
                ->orderBy('nom')
                ->get()
                ->map(function ($produit) use ($pointVenteId) {
                    // Calculer le stock du point de vente
                    $stockDuPoint = StockProduit::where('produit_id', $produit->id)
                        ->where('point_vente_id', $pointVenteId)
                        ->sum('quantite');

                    $produit->stock_point_vente = $stockDuPoint;
                    $produit->valeur_stock_point = $stockDuPoint * $produit->prix_achat_unite;

                    return $produit;
                });

            // Stats pour le vendeur (basees sur son stock uniquement)
            $stats = [
                'total_produits' => $produits->count(),
                'en_rupture' => $produits->where('stock_point_vente', '<=', 0)->count(),
                'stock_faible' => $produits->filter(function ($p) {
                    return $p->stock_point_vente > 0 && $p->stock_point_vente <= $p->stock_minimum;
                })->count(),
                'valeur_stock' => $produits->sum('valeur_stock_point'),
            ];

            // Par categorie (pour le vendeur)
            $parCategorie = $produits->groupBy('categorie.nom')->map(function ($groupe) {
                return [
                    'nombre' => $groupe->count(),
                    'quantite_totale' => $groupe->sum('stock_point_vente'),
                    'valeur' => $groupe->sum('valeur_stock_point'),
                ];
            });
        }

        return view('rapports.stocks', compact('produits', 'stats', 'parCategorie', 'isAdminOrResponsable'));
    }

    /**
     * Rapport des achats (commandes fournisseurs)
     */
    public function achats(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $dateDebut = Carbon::parse($validated['date_debut']);
        $dateFin = Carbon::parse($validated['date_fin']);

        // Commandes de la periode
        $commandes = Commande::with(['fournisseur', 'items.produit'])
            ->whereIn('statut', ['livree', 'partiellement_livree'])
            ->whereBetween('date_livraison_reelle', [$dateDebut, $dateFin])
            ->orderBy('date_livraison_reelle', 'desc')
            ->get();

        // Stats
        $stats = [
            'nombre_commandes' => $commandes->count(),
            'montant_total_ht' => $commandes->sum('montant_ht'),
            'montant_total_ttc' => $commandes->sum('montant_ttc'),
            'ristournes_prevues' => $commandes->sum('ristourne_prevue'),
        ];

        // Par fournisseur
        $parFournisseur = $commandes->groupBy('fournisseur.nom')->map(function ($groupe) {
            return [
                'nombre' => $groupe->count(),
                'montant_ht' => $groupe->sum('montant_ht'),
                'ristourne' => $groupe->sum('ristourne_prevue'),
            ];
        });

        return view('rapports.achats', compact('dateDebut', 'dateFin', 'commandes', 'stats', 'parFournisseur'));
    }

    /**
     * Tableau de bord financier
     */
    public function financier(Request $request)
    {
        $mois = $request->get('mois', now()->month);
        $annee = $request->get('annee', now()->year);

        $debut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $fin = $debut->copy()->endOfMonth();

        // Chiffre d'affaires
        $caVentes = Vente::where('statut', 'validee')
            ->whereBetween('date_vente', [$debut, $fin])
            ->sum('montant_total');

        // Achats
        $montantAchats = Commande::whereIn('statut', ['livree', 'partiellement_livree'])
            ->whereBetween('date_livraison_reelle', [$debut, $fin])
            ->sum('montant_ttc');

        // Marge brute
        $margeBrute = $caVentes - $montantAchats;
        $tauxMarge = $caVentes > 0 ? ($margeBrute / $caVentes) * 100 : 0;

        // Credit clients
        $creditUtilise = Client::sum('solde_actuel');
        $creditDisponible = Client::sum('credit_limite') - $creditUtilise;

        $stats = compact('caVentes', 'montantAchats', 'margeBrute', 'tauxMarge', 'creditUtilise', 'creditDisponible');

        // Evolution mensuelle (12 derniers mois)
        $evolutionMensuelle = [];
        for ($i = 11; $i >= 0; $i--) {
            $moisDebut = now()->subMonths($i)->startOfMonth();
            $moisFin = $moisDebut->copy()->endOfMonth();

            $evolutionMensuelle[] = [
                'mois' => $moisDebut->format('M Y'),
                'ventes' => Vente::where('statut', 'validee')
                    ->whereBetween('date_vente', [$moisDebut, $moisFin])
                    ->sum('montant_total'),
                'achats' => Commande::whereIn('statut', ['livree', 'partiellement_livree'])
                    ->whereBetween('date_livraison_reelle', [$moisDebut, $moisFin])
                    ->sum('montant_ttc'),
            ];
        }

        return view('rapports.financier', compact('debut', 'fin', 'stats', 'evolutionMensuelle'));
    }

    /**
     * Exporter rapport ventes
     */
    private function exporterVentes($data, $format)
    {
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('rapports.exports.ventes-pdf', $data);
            return $pdf->download('rapport-ventes-' . now()->format('Y-m-d') . '.pdf');
        }

        // TODO: Implementer export Excel si necessaire
        return back()->with('info', 'Export Excel a venir');
    }
}
