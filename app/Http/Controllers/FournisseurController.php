<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Fournisseur::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('actif', $request->get('statut') === 'actif');
        }

        // Tri
        $sort = $request->get('sort', 'nom');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $fournisseurs = $query->withCount('produits')->paginate(10);

        return view('fournisseurs.index', compact('fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('fournisseurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'conditions_paiement' => 'required|in:comptant,30_jours,60_jours,90_jours',
            'taux_ristourne_defaut' => 'required|numeric|min:0|max:100',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');

        Fournisseur::create($validated);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur): View
    {
        $fournisseur->load(['produits', 'commandes' => function ($query) {
            $query->latest()->limit(5);
        }]);

        return view('fournisseurs.show', compact('fournisseur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur): View
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string',
            'conditions_paiement' => 'required|in:comptant,30_jours,60_jours,90_jours',
            'taux_ristourne_defaut' => 'required|numeric|min:0|max:100',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');

        $fournisseur->update($validated);

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur): RedirectResponse
    {
        if ($fournisseur->produits()->count() > 0) {
            return redirect()->route('fournisseurs.index')
                ->with('error', 'Impossible de supprimer un fournisseur ayant des produits associés.');
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
}
