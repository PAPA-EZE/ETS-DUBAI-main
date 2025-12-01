<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Vente;
use App\Models\PointVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyseVendeurController extends Controller
{
    /**
     * Dashboard d'analyse
     */
    public function index(Request $request)
    {
        // Seuls les admins peuvent accéder
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Accès non autorisé.');
        }

        $periode = $request->get('periode', '7'); // 7 jours par défaut
        $userId = $request->get('user_id');
        $pointId = $request->get('point_id');

        $dateDebut = now()->subDays((int)$periode);

        // Statistiques globales
        $stats = [
            'total_actions' => ActivityLog::where('created_at', '>=', $dateDebut)->count(),
            'total_anomalies' => ActivityLog::anomalies()->where('created_at', '>=', $dateDebut)->count(),
            'anomalies_critiques' => ActivityLog::anomalies()
                ->where('severity', 'critical')
                ->where('created_at', '>=', $dateDebut)
                ->count(),
            'vendeurs_actifs' => ActivityLog::where('created_at', '>=', $dateDebut)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        // Anomalies récentes
        $anomaliesRecentes = ActivityLog::with(['user', 'pointVente'])
            ->anomalies()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($pointId, fn($q) => $q->where('point_vente_id', $pointId))
            ->where('created_at', '>=', $dateDebut)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Top vendeurs par nombre d'actions
        $topVendeurs = ActivityLog::select('user_id', DB::raw('count(*) as total'))
            ->where('created_at', '>=', $dateDebut)
            ->when($pointId, fn($q) => $q->where('point_vente_id', $pointId))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('user')
            ->get();

        // Répartition par type d'action
        $actionsByType = ActivityLog::select('action_type', DB::raw('count(*) as total'))
            ->where('created_at', '>=', $dateDebut)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($pointId, fn($q) => $q->where('point_vente_id', $pointId))
            ->groupBy('action_type')
            ->get();

        // Pour les filtres
        $vendeurs = User::where('role', 'vendeur')->orderBy('name')->get();
        $points = PointVente::orderBy('nom')->get();

        return view('analyses.index', compact(
            'stats',
            'anomaliesRecentes',
            'topVendeurs',
            'actionsByType',
            'vendeurs',
            'points',
            'periode',
            'userId',
            'pointId'
        ));
    }

    /**
     * Détails d'un vendeur
     */
    public function vendeur(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Accès non autorisé.');
        }

        $periode = request()->get('periode', '30');
        $dateDebut = now()->subDays((int)$periode);

        // Activités récentes
        $activites = ActivityLog::with('pointVente')
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $dateDebut)
            ->orderByDesc('created_at')
            ->paginate(50);

        // Anomalies
        $anomalies = ActivityLog::anomalies()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $dateDebut)
            ->orderByDesc('created_at')
            ->get();

        // Statistiques
        $stats = [
            'total_ventes' => Vente::where('user_id', $user->id)
                ->where('created_at', '>=', $dateDebut)
                ->count(),
            'ca_total' => Vente::where('user_id', $user->id)
                ->where('created_at', '>=', $dateDebut)
                ->sum('montant_total'),
            'panier_moyen' => Vente::where('user_id', $user->id)
                ->where('created_at', '>=', $dateDebut)
                ->avg('montant_total'),
            'total_anomalies' => $anomalies->count(),
        ];

        // Performance par jour
        $performanceJour = Vente::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as nombre'),
            DB::raw('SUM(montant_total) as montant')
        )
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $dateDebut)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('analyses.vendeur', compact(
            'user',
            'activites',
            'anomalies',
            'stats',
            'performanceJour',
            'periode'
        ));
    }

    /**
     * Journal complet des activités
     */
    public function logs(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Accès non autorisé.');
        }

        $query = ActivityLog::with(['user', 'pointVente']);

        // Filtres
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('point_id')) {
            $query->where('point_vente_id', $request->point_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('is_anomaly')) {
            $query->where('is_anomaly', $request->is_anomaly === '1');
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $logs = $query->orderByDesc('created_at')->paginate(50);

        // Pour les filtres
        $vendeurs = User::where('role', 'vendeur')->orderBy('name')->get();
        $points = PointVente::orderBy('nom')->get();

        return view('analyses.logs', compact('logs', 'vendeurs', 'points'));
    }
}
