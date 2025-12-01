<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produit;
use App\Models\Vente;
use App\Models\PointVente;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Rediriger vers le dashboard approprié selon le rôle
        if ($user->isAdmin()) {
            return $this->dashboardAdmin();
        } else {
            return $this->dashboardVendeur();
        }
    }

    /**
     * Dashboard Admin - Vue globale
     */
    private function dashboardAdmin()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistiques globales
        $stats = [
            'ventes_jour' => Vente::whereDate('created_at', $today)->sum('montant_total') ?? 0,
            'ventes_mois' => Vente::whereDate('created_at', '>=', $thisMonth)->sum('montant_total') ?? 0,
            'nombre_ventes_jour' => Vente::whereDate('created_at', $today)->count(),
            'nombre_ventes_mois' => Vente::whereDate('created_at', '>=', $thisMonth)->count(),
            'produits_total' => Produit::actif()->count(),
            'produits_rupture' => Produit::enRupture()->count(),
            'produits_faible' => Produit::stockFaible()->count(),
            'utilisateurs_actifs' => User::where('actif', true)->count(),
        ];

        // Performance par point de vente (aujourd'hui) - RÉCUPÉRATION DYNAMIQUE DEPUIS LA BD
        $performancePoints = PointVente::actif()
            ->withCount(['ventes as ventes_jour' => function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            }])
            ->withSum(['ventes as ca_jour' => function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            }], 'montant_total')
            ->orderByDesc('ca_jour')
            ->get()
            ->map(function ($point) {
                return [
                    'nom' => $point->nom,
                    'ventes' => $point->ventes_jour ?? 0,
                    'ca' => $point->ca_jour ?? 0,
                    'type' => $point->type,
                    'code' => $point->code,
                ];
            });

        // Top 10 produits les plus vendus (ce mois)
        $topProduits = DB::table('lignes_vente')
            ->join('ventes', 'lignes_vente.vente_id', '=', 'ventes.id')
            ->join('produits', 'lignes_vente.produit_id', '=', 'produits.id')
            ->whereDate('ventes.created_at', '>=', $thisMonth)
            ->select(
                'produits.nom',
                DB::raw('SUM(lignes_vente.quantite) as total_quantite'),
                DB::raw('SUM(lignes_vente.montant_total) as total_ca')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('total_ca')
            ->limit(10)
            ->get();

        // Alertes stock - avec tri par priorité
        $alertesStock = Produit::with('categorie')
            ->where(function ($query) {
                $query->where('stock_actuel', '<=', 0)
                    ->orWhereColumn('stock_actuel', '<=', 'stock_minimum');
            })
            ->orderByRaw('CASE WHEN stock_actuel <= 0 THEN 0 ELSE 1 END')
            ->orderBy('stock_actuel', 'asc')
            ->limit(10)
            ->get();

        // Évolution CA sur 7 derniers jours
        $evolutionCA = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $evolutionCA->push([
                'date' => $date->format('d/m'),
                'montant' => Vente::whereDate('created_at', $date)->sum('montant_total') ?? 0
            ]);
        }

        // Vendeurs les plus performants (ce mois)
        $topVendeurs = User::withCount(['ventes as ventes_mois' => function ($query) use ($thisMonth) {
            $query->whereDate('created_at', '>=', $thisMonth);
        }])
            ->withSum(['ventes as ca_mois' => function ($query) use ($thisMonth) {
                $query->whereDate('created_at', '>=', $thisMonth);
            }], 'montant_total')
            ->where('role', 'vendeur')
            ->where('actif', true)
            ->orderByDesc('ca_mois')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'performancePoints',
            'topProduits',
            'alertesStock',
            'evolutionCA',
            'topVendeurs'
        ));
    }

    /**
     * Dashboard Vendeur - Vue restreinte à son point
     */
    private function dashboardVendeur()
    {
        $user = auth()->user();
        $pointVente = $user->pointVente;

        // Vérifier que l'utilisateur a un point de vente assigné
        if (!$pointVente) {
            return redirect()->route('login')->with('error', 'Aucun point de vente assigné. Contactez l\'administrateur.');
        }

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistiques du point de vente
        $stats = [
            'ventes_jour' => Vente::where('point_vente_id', $pointVente->id)
                ->whereDate('created_at', $today)
                ->sum('montant_total') ?? 0,
            'ventes_mois' => Vente::where('point_vente_id', $pointVente->id)
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('montant_total') ?? 0,
            'nombre_ventes_jour' => Vente::where('point_vente_id', $pointVente->id)
                ->whereDate('created_at', $today)
                ->count(),
            'nombre_ventes_mois' => Vente::where('point_vente_id', $pointVente->id)
                ->whereDate('created_at', '>=', $thisMonth)
                ->count(),
            'produits_disponibles' => StockProduit::where('point_vente_id', $pointVente->id)
                ->where('quantite', '>', 0)
                ->count(),
            'produits_rupture' => StockProduit::where('point_vente_id', $pointVente->id)
                ->where('quantite', '<=', 0)
                ->count(),
        ];

        // Mes ventes personnelles
        $mesVentes = [
            'jour' => Vente::where('point_vente_id', $pointVente->id)
                ->where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->sum('montant_total') ?? 0,
            'mois' => Vente::where('point_vente_id', $pointVente->id)
                ->where('user_id', $user->id)
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('montant_total') ?? 0,
            'nombre_jour' => Vente::where('point_vente_id', $pointVente->id)
                ->where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->count(),
        ];

        // Top 5 produits vendus dans ce point (ce mois)
        $topProduits = DB::table('lignes_vente')
            ->join('ventes', 'lignes_vente.vente_id', '=', 'ventes.id')
            ->join('produits', 'lignes_vente.produit_id', '=', 'produits.id')
            ->where('ventes.point_vente_id', $pointVente->id)
            ->whereDate('ventes.created_at', '>=', $thisMonth)
            ->select(
                'produits.nom',
                DB::raw('SUM(lignes_vente.quantite) as total_quantite'),
                DB::raw('SUM(lignes_vente.montant_total) as total_ca')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('total_quantite')
            ->limit(5)
            ->get();

        // Stock de mon point
        $stockPoint = StockProduit::with('produit.categorie')
            ->where('point_vente_id', $pointVente->id)
            ->whereHas('produit', function ($query) {
                $query->where('actif', true);
            })
            ->orderBy('quantite', 'asc')
            ->limit(10)
            ->get();

        // Évolution de mes ventes sur 7 derniers jours
        $evolutionVentes = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $evolutionVentes->push([
                'date' => $date->format('d/m'),
                'montant' => Vente::where('point_vente_id', $pointVente->id)
                    ->where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->sum('montant_total') ?? 0
            ]);
        }

        // Dernières ventes
        $dernieresVentes = Vente::with(['client', 'user'])
            ->where('point_vente_id', $pointVente->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.vendeur', compact(
            'stats',
            'mesVentes',
            'topProduits',
            'stockPoint',
            'evolutionVentes',
            'dernieresVentes',
            'pointVente'
        ));
    }
}
