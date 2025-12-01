<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Fournisseur;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Commande::with(['fournisseur', 'user', 'items'])
            ->withCount('items');

        // Recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero_commande', 'like', "%{$search}%")
                    ->orWhereHas('fournisseur', function ($fq) use ($search) {
                        $fq->where('nom', 'like', "%{$search}%");
                    });
            });
        }

        // Filtrage par fournisseur
        if ($request->filled('fournisseur')) {
            $query->where('fournisseur_id', $request->get('fournisseur'));
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        // Filtrage par période
        if ($request->filled('periode')) {
            $periode = $request->get('periode');
            switch ($periode) {
                case 'aujourd_hui':
                    $query->whereDate('date_commande', Carbon::today());
                    break;
                case 'cette_semaine':
                    $query->whereBetween('date_commande', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'ce_mois':
                    $query->whereMonth('date_commande', Carbon::now()->month)
                        ->whereYear('date_commande', Carbon::now()->year);
                    break;
                case 'en_retard':
                    $query->where('date_livraison_prevue', '<', Carbon::now())
                        ->whereNotIn('statut', ['livree', 'annulee']);
                    break;
            }
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $commandes = $query->paginate(15);

        // Données pour les filtres
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();

        // Statistiques rapides
        $stats = [
            'total' => Commande::count(),
            'en_cours' => Commande::enCours()->count(),
            'en_retard' => Commande::whereDate('date_livraison_prevue', '<', Carbon::now())
                ->whereNotIn('statut', ['livree', 'annulee'])
                ->count(),
            'ce_mois' => Commande::whereMonth('date_commande', Carbon::now()->month)
                ->whereYear('date_commande', Carbon::now()->year)
                ->sum('montant_ttc'),
        ];

        return view('commandes.index', compact('commandes', 'fournisseurs', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();
        $fournisseur_id = $request->get('fournisseur');

        // CORRECTION: Charger tous les produits actifs avec leurs relations
        $produits = Produit::actif()
            ->with(['categorie', 'fournisseur'])
            ->orderBy('nom')
            ->get();

        return view('commandes.create', compact('fournisseurs', 'fournisseur_id', 'produits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_commande' => 'required|date',
            'date_livraison_prevue' => 'nullable|date|after_or_equal:date_commande',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $commande = Commande::create([
            'fournisseur_id' => $validated['fournisseur_id'],
            'user_id' => auth()->id(),
            'date_commande' => $validated['date_commande'],
            'date_livraison_prevue' => $validated['date_livraison_prevue'],
            'notes' => $validated['notes'],
            'statut' => $request->has('envoyer') ? 'en_attente' : 'brouillon',
        ]);

        // Ajouter les articles
        foreach ($validated['items'] as $itemData) {
            CommandeItem::create([
                'commande_id' => $commande->id,
                'produit_id' => $itemData['produit_id'],
                'quantite_commandee' => $itemData['quantite'],
                'prix_unitaire_ht' => $itemData['prix_unitaire'],
                'taux_tva' => 19.25, // TVA Cameroun
            ]);
        }

        $message = $commande->statut === 'en_attente'
            ? 'Commande créée et envoyée au fournisseur avec succès.'
            : 'Commande sauvegardée en brouillon avec succès.';

        return redirect()->route('commandes.show', $commande)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Commande $commande): View
    {
        $commande->load(['fournisseur', 'user', 'items.produit']);

        return view('commandes.show', compact('commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande): View
    {
        // Seules les commandes en brouillon peuvent être modifiées
        if ($commande->statut !== 'brouillon') {
            abort(403, 'Cette commande ne peut plus être modifiée.');
        }

        $commande->load(['items.produit']);
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();

        // Charger les produits
        $produits = Produit::actif()
            ->with(['categorie', 'fournisseur'])
            ->orderBy('nom')
            ->get();

        return view('commandes.edit', compact('commande', 'fournisseurs', 'produits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande): RedirectResponse
    {
        if ($commande->statut !== 'brouillon') {
            return back()->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_commande' => 'required|date',
            'date_livraison_prevue' => 'nullable|date|after_or_equal:date_commande',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $commande->update([
            'fournisseur_id' => $validated['fournisseur_id'],
            'date_commande' => $validated['date_commande'],
            'date_livraison_prevue' => $validated['date_livraison_prevue'],
            'notes' => $validated['notes'],
            'statut' => $request->has('envoyer') ? 'en_attente' : 'brouillon',
        ]);

        // Supprimer les anciens items et recréer
        $commande->items()->delete();

        foreach ($validated['items'] as $itemData) {
            CommandeItem::create([
                'commande_id' => $commande->id,
                'produit_id' => $itemData['produit_id'],
                'quantite_commandee' => $itemData['quantite'],
                'prix_unitaire_ht' => $itemData['prix_unitaire'],
                'taux_tva' => 19.25,
            ]);
        }

        $message = $commande->statut === 'en_attente'
            ? 'Commande mise à jour et envoyée au fournisseur avec succès.'
            : 'Commande mise à jour et sauvegardée en brouillon avec succès.';

        return redirect()->route('commandes.show', $commande)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande): RedirectResponse
    {
        if (!in_array($commande->statut, ['brouillon', 'annulee'])) {
            return back()->with('error', 'Seules les commandes en brouillon ou annulées peuvent être supprimées.');
        }

        $commande->delete();

        return redirect()->route('commandes.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Changer le statut d'une commande
     */
    public function changerStatut(Request $request, Commande $commande): RedirectResponse
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,expediee,livree,partiellement_livree,annulee',
            'notes' => 'nullable|string',
        ]);

        // CORRECTION: Utiliser null si notes n'est pas fourni
        $commande->changerStatut($validated['statut'], $validated['notes'] ?? null);

        return back()->with('success', 'Statut de la commande mis à jour avec succès.');
    }

    /**
     * Réceptionner une livraison
     */
    public function livraison(Request $request, Commande $commande): View
    {
        if (!in_array($commande->statut, ['confirmee', 'en_preparation', 'expediee', 'partiellement_livree'])) {
            abort(403, 'Cette commande ne peut pas être réceptionnée.');
        }

        $commande->load(['items.produit']);

        return view('commandes.livraison', compact('commande'));
    }

    /**
     * Enregistrer une livraison
     */
    public function enregistrerLivraison(Request $request, Commande $commande): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.quantite_livree' => 'required|integer|min:0',
            'notes_livraison' => 'nullable|string',
        ]);

        $livraison_complete = true;

        foreach ($validated['items'] as $itemId => $data) {
            $item = $commande->items()->findOrFail($itemId);
            $quantite_livree = min($data['quantite_livree'], $item->quantite_restante);

            $item->update([
                'quantite_livree' => $item->quantite_livree + $quantite_livree
            ]);

            if ($item->quantite_livree < $item->quantite_commandee) {
                $livraison_complete = false;
            }
        }

        // Déterminer le nouveau statut
        $nouveau_statut = $livraison_complete ? 'livree' : 'partiellement_livree';

        $commande->changerStatut($nouveau_statut, $validated['notes_livraison']);

        $message = $livraison_complete
            ? 'Livraison complète enregistrée avec succès.'
            : 'Livraison partielle enregistrée avec succès.';

        return redirect()->route('commandes.show', $commande)
            ->with('success', $message);
    }
}
