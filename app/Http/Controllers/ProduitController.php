<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\PointVente;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $isAdminOrResponsable = $user->canManageProduits();

        $query = Produit::with(['categorie', 'fournisseur', 'stocks.pointVente']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('code_barre', 'like', "%{$search}%");
            });
        }

        // Filtrage par categorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->get('categorie'));
        }

        // Filtrage par fournisseur
        if ($request->filled('fournisseur')) {
            $query->where('fournisseur_id', $request->get('fournisseur'));
        }

        // Filtrage par statut stock
        if ($request->filled('stock_statut')) {
            $statut = $request->get('stock_statut');

            if ($isAdminOrResponsable) {
                // Admin/Responsable : filtrer sur stock global
                if ($statut === 'rupture') {
                    $query->where('stock_actuel', '<=', 0);
                } elseif ($statut === 'faible') {
                    $query->whereColumn('stock_actuel', '<=', 'stock_minimum')
                        ->where('stock_actuel', '>', 0);
                } elseif ($statut === 'normal') {
                    $query->whereColumn('stock_actuel', '>', 'stock_minimum');
                }
            } else {
                // Vendeur : filtrer sur stock de son point de vente
                $pointVenteId = $user->point_vente_id;
                if ($statut === 'rupture') {
                    $query->whereDoesntHave('stocks', function ($q) use ($pointVenteId) {
                        $q->where('point_vente_id', $pointVenteId)
                            ->where('quantite', '>', 0);
                    });
                } elseif ($statut === 'faible' || $statut === 'normal') {
                    $query->whereHas('stocks', function ($q) use ($pointVenteId, $statut) {
                        $q->where('point_vente_id', $pointVenteId);
                        if ($statut === 'faible') {
                            $q->whereBetween('quantite', [1, 10]);
                        } else {
                            $q->where('quantite', '>', 10);
                        }
                    });
                }
            }
        }

        // Tri
        $sort = $request->get('sort', 'nom');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $produits = $query->paginate(15);

        // Ajouter le stock par point de vente pour chaque produit
        $produits->getCollection()->transform(function ($produit) use ($user, $isAdminOrResponsable) {
            if ($isAdminOrResponsable) {
                // Admin/Responsable : Calculer stock global et par point
                $produit->stock_global = $produit->stock_actuel;
                $produit->stocks_par_point = $produit->stocks->mapWithKeys(function ($stock) {
                    return [$stock->pointVente->nom => $stock->quantite];
                });
            } else {
                // Vendeur : Seulement le stock de son point de vente
                $stockDuPoint = $produit->stocks->where('point_vente_id', $user->point_vente_id)->first();
                $produit->stock_point_vente = $stockDuPoint ? $stockDuPoint->quantite : 0;
            }
            return $produit;
        });

        // Pour les filtres
        $categories = Categorie::actif()->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();
        $pointsVente = PointVente::orderBy('nom')->get();

        return view('produits.index', compact('produits', 'categories', 'fournisseurs', 'pointsVente', 'isAdminOrResponsable'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        // PERMISSION : Seuls admin et responsable peuvent creer des produits
        if (!auth()->user()->canManageProduits()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de creer des produits.');
        }

        $categories = Categorie::actif()->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();

        $fournisseur_id = $request->get('fournisseur');

        return view('produits.create', compact('categories', 'fournisseurs', 'fournisseur_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // PERMISSION : Seuls admin et responsable peuvent creer des produits
        if (!auth()->user()->canManageProduits()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de creer des produits.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code_barre' => 'nullable|string|unique:produits,code_barre',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_conditionnement' => 'required|in:casier,palette,unite',
            'unites_par_conditionnement' => 'required|integer|min:1',
            'unites_par_pack' => 'required|integer|min:1',
            'prix_achat_conditionnement' => 'required|numeric|min:0',
            'prix_vente_conditionnement' => 'required|numeric|min:0',
            'prix_achat_unite' => 'required|numeric|min:0',
            'prix_vente_unite' => 'required|numeric|min:0',
            'marge_conditionnement' => 'nullable|numeric|min:0',
            'marge_unite' => 'nullable|numeric|min:0',
            'tva_applicable' => 'nullable|boolean',
            'taux_tva' => 'nullable|numeric|min:0',
            'stock_actuel' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);

        // Convertir les checkbox en boolean
        $validated['actif'] = $request->has('actif');
        $validated['tva_applicable'] = $request->has('tva_applicable');

        // Generer automatiquement la reference
        $validated['reference'] = $this->genererReference();

        // Remplir les anciennes colonnes pour compatibilite
        $validated['prix_achat'] = $validated['prix_achat_unite'];
        $validated['prix_vente'] = $validated['prix_vente_unite'];
        $validated['prix_achat_ht'] = $validated['prix_achat_conditionnement'];
        $validated['prix_vente_ttc'] = $validated['prix_vente_conditionnement'];

        // Creer le produit
        $produit = Produit::create($validated);

        // Creer le stock initial au point central (Dubai Magasin)
        $pointCentral = PointVente::where('type', 'central')->first();
        if ($pointCentral && ($validated['stock_actuel'] ?? 0) > 0) {
            StockProduit::create([
                'produit_id' => $produit->id,
                'point_vente_id' => $pointCentral->id,
                'quantite' => $validated['stock_actuel'],
            ]);
        }

        return redirect()->route('produits.index')
            ->with('success', 'Produit cree avec succes !');
    }

    /**
     * Generer une reference unique
     */
    private function genererReference(): string
    {
        $dernier = Produit::latest('id')->first();
        $numero = $dernier ? $dernier->id + 1 : 1;
        return 'PROD-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit): View
    {
        $user = auth()->user();
        $isAdminOrResponsable = $user->canManageProduits();

        $produit->load(['categorie', 'fournisseur', 'stocks.pointVente']);

        if ($isAdminOrResponsable) {
            // Admin/Responsable : Calculer stock global et par point
            $produit->stock_global = $produit->stock_actuel;
            $produit->stocks_par_point = $produit->stocks->mapWithKeys(function ($stock) {
                return [$stock->pointVente->nom => $stock->quantite];
            });
        } else {
            // Vendeur : Seulement le stock de son point de vente
            $stockDuPoint = $produit->stocks->where('point_vente_id', $user->point_vente_id)->first();
            $produit->stock_point_vente = $stockDuPoint ? $stockDuPoint->quantite : 0;
        }

        return view('produits.show', compact('produit', 'isAdminOrResponsable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit): View
    {
        // PERMISSION : Seuls admin et responsable peuvent modifier des produits
        if (!auth()->user()->canManageProduits()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de modifier des produits.');
        }

        $categories = Categorie::actif()->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();

        return view('produits.edit', compact('produit', 'categories', 'fournisseurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit): RedirectResponse
    {
        // PERMISSION : Seuls admin et responsable peuvent modifier des produits
        if (!auth()->user()->canManageProduits()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de modifier des produits.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'reference' => 'required|string|unique:produits,reference,' . $produit->id,
            'description' => 'nullable|string',
            'code_barre' => 'nullable|string|unique:produits,code_barre,' . $produit->id,
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_conditionnement' => 'required|in:casier,palette,unite',
            'unites_par_conditionnement' => 'required|integer|min:1',
            'unites_par_pack' => 'required|integer|min:1',
            'prix_achat_conditionnement' => 'required|numeric|min:0',
            'prix_vente_conditionnement' => 'required|numeric|min:0',
            'prix_achat_unite' => 'required|numeric|min:0',
            'prix_vente_unite' => 'required|numeric|min:0',
            'marge_conditionnement' => 'nullable|numeric|min:0',
            'marge_unite' => 'nullable|numeric|min:0',
            'tva_applicable' => 'nullable|boolean',
            'taux_tva' => 'nullable|numeric|min:0',
            'stock_actuel' => 'nullable|integer|min:0',
            'stock_minimum' => 'nullable|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['tva_applicable'] = $request->has('tva_applicable');

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis a jour avec succes.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit): RedirectResponse
    {
        // PERMISSION : Seuls admin et responsable peuvent supprimer des produits
        if (!auth()->user()->canManageProduits()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer des produits.');
        }

        // Verifier s'il y a des ventes associees
        if ($produit->lignesVente()->count() > 0) {
            return redirect()->route('produits.index')
                ->with('error', 'Impossible de supprimer un produit ayant des ventes associees.');
        }

        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprime avec succes.');
    }
}
