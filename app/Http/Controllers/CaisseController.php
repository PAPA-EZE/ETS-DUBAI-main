<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
{
    /**
     * Afficher la liste des caisses
     */
    public function index(Request $request)
    {
        $query = Caisse::with('responsable');

        // Filtre par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'ouverte') {
                $query->ouvertes();
            } else {
                $query->fermees();
            }
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('date_ouverture', $request->date);
        }

        // Filtre par utilisateur (pour admins)
        if ($request->filled('user_id') && Auth::user()->isAdmin()) {
            $query->where('user_id', $request->user_id);
        }

        // Par défaut, afficher les caisses de l'utilisateur connecté
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $caisses = $query->orderBy('date_ouverture', 'desc')->paginate(15)->withQueryString();

        // Caisse ouverte actuelle de l'utilisateur
        $caisseOuverte = Caisse::caisseOuverteParUtilisateur(Auth::id());

        return view('caisses.index', compact('caisses', 'caisseOuverte'));
    }

    /**
     * Afficher le formulaire d'ouverture de caisse
     */
    public function create()
    {
        // Vérifier si l'utilisateur a déjà une caisse ouverte
        $caisseExistante = Caisse::caisseOuverteParUtilisateur(Auth::id());

        if ($caisseExistante) {
            return redirect()->route('caisses.show', $caisseExistante)
                ->with('error', 'Vous avez déjà une caisse ouverte. Fermez-la avant d\'en ouvrir une nouvelle.');
        }

        return view('caisses.create');
    }

    /**
     * Enregistrer l'ouverture d'une caisse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_caisse' => 'nullable|string|max:255',
            'fond_ouverture' => 'required|numeric|min:0',
            'notes_ouverture' => 'nullable|string|max:1000',
        ]);

        // Vérifier qu'aucune caisse n'est déjà ouverte
        $caisseExistante = Caisse::caisseOuverteParUtilisateur(Auth::id());
        if ($caisseExistante) {
            return back()->with('error', 'Vous avez déjà une caisse ouverte.');
        }

        $caisse = Caisse::ouvrirNouvelleCaisse(
            Auth::id(),
            $validated['fond_ouverture'],
            $validated['notes_ouverture'] ?? null
        );

        if (!empty($validated['nom_caisse'])) {
            $caisse->nom_caisse = $validated['nom_caisse'];
            $caisse->save();
        }

        return redirect()->route('caisses.show', $caisse)
            ->with('success', 'Caisse ouverte avec succès!');
    }

    /**
     * Afficher les détails d'une caisse
     */
    public function show(Caisse $caisse)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $caisse->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $caisse->load(['responsable', 'ventes.lignes.produit']);

        // Statistiques détaillées
        $ventesCompletees = $caisse->ventes()->completees();

        $stats = [
            'nombre_ventes' => $ventesCompletees->count(),
            'nombre_annulations' => $caisse->ventes()->annulees()->count(),
            'ca_total' => $ventesCompletees->sum('montant_total') ?? 0,
            'montant_moyen' => $ventesCompletees->avg('montant_total') ?? 0,
            'especes' => $ventesCompletees->where('type_paiement', 'especes')->sum('montant_total') ?? 0,
            'mobile_money' => $ventesCompletees->where('type_paiement', 'mobile_money')->sum('montant_total') ?? 0,
            'credit' => $ventesCompletees->where('type_paiement', 'credit')->sum('montant_total') ?? 0,
        ];

        // Ventes récentes
        $ventesRecentes = $caisse->ventes()
            ->with(['client', 'lignes'])
            ->orderBy('date_vente', 'desc')
            ->limit(10)
            ->get();

        return view('caisses.show', compact('caisse', 'stats', 'ventesRecentes'));
    }

    /**
     * Afficher le formulaire de fermeture de caisse
     */
    public function fermer(Caisse $caisse)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $caisse->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        if (!$caisse->estOuverte()) {
            return redirect()->route('caisses.show', $caisse)
                ->with('error', 'Cette caisse est déjà fermée.');
        }

        // Mettre à jour les totaux avant fermeture
        $caisse->mettreAJourTotaux();

        return view('caisses.fermer', compact('caisse'));
    }

    /**
     * Enregistrer la fermeture de caisse
     */
    public function fermerStore(Request $request, Caisse $caisse)
    {
        // Vérifier les permissions
        if (!Auth::user()->isAdmin() && $caisse->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'montant_reel' => 'required|numeric|min:0',
            'notes_fermeture' => 'nullable|string|max:1000',
        ]);

        if ($caisse->fermer($validated['montant_reel'], $validated['notes_fermeture'] ?? null)) {
            return redirect()->route('caisses.show', $caisse)
                ->with('success', 'Caisse fermée avec succès!');
        }

        return back()->with('error', 'Erreur lors de la fermeture de la caisse.');
    }

    /**
     * Supprimer une caisse (soft delete)
     */
    public function destroy(Caisse $caisse)
    {
        // Seuls les admins peuvent supprimer
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        // Ne pas supprimer une caisse ouverte
        if ($caisse->estOuverte()) {
            return back()->with('error', 'Impossible de supprimer une caisse ouverte. Fermez-la d\'abord.');
        }

        $caisse->delete();

        return redirect()->route('caisses.index')
            ->with('success', 'Caisse supprimée avec succès.');
    }
}
