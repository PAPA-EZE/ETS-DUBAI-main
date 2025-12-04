<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Caisse;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VenteController extends Controller
{
    /**
     * Afficher la liste des ventes
     */
    public function index(Request $request)
    {
        $query = Vente::with(['client', 'user', 'lignes.produit']);

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero_vente', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par type de paiement
        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        // Filtre par pÃ©riode
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_vente', [$request->date_debut, $request->date_fin]);
        }

        // Filtre par vendeur (admin only)
        if ($request->filled('user_id') && Auth::user()->canViewAllReports()) {
            $query->where('user_id', $request->user_id);
        }

        // Si pas admin/responsable, voir seulement ses ventes
        if (!Auth::user()->canViewAllReports()) {
            $query->where('user_id', Auth::id());
        }

        // Tri
        $sortField = $request->get('sort', 'date_vente');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $ventes = $query->paginate(15)->withQueryString();

        // Statistiques pour la pÃ©riode affichÃ©e
        $statsQuery = Vente::where('statut', 'completee');

        // Filtrer par vendeur si nÃ©cessaire
        if (!Auth::user()->canViewAllReports()) {
            $statsQuery->where('user_id', Auth::id());
        }

        $stats = [
            'total_ventes' => $ventes->total(),
            'ca_total' => $statsQuery->sum('montant_total') ?? 0,
            'ca_moyen' => $statsQuery->avg('montant_total') ?? 0,
            'especes' => Vente::where('statut', 'completee')->where('type_paiement', 'especes')->sum('montant_total') ?? 0,
            'mobile_money' => Vente::where('statut', 'completee')->where('type_paiement', 'mobile_money')->sum('montant_total') ?? 0,
            'credit' => Vente::where('statut', 'completee')->where('type_paiement', 'credit')->sum('montant_total') ?? 0,
        ];

        return view('ventes.index', compact('ventes', 'stats'));
    }

    /**
     * Afficher le formulaire de nouvelle vente (POS)
     */
    public function create()
    {
        // Permission
        if (!auth()->user()->canMakeVentes()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de faire des ventes.');
        }

        // VÃ©rifier si l'utilisateur a une caisse ouverte
        $caisseOuverte = Caisse::caisseOuverteParUtilisateur(Auth::id());

        if (!$caisseOuverte) {
            return redirect()->route('caisses.create')
                ->with('error', 'Vous devez d\'abord ouvrir une caisse pour effectuer des ventes.');
        }

        // RÃ©cupÃ©rer le point de vente de l'utilisateur
        $pointVenteId = Auth::user()->point_vente_id;

        if (!$pointVenteId) {
            return redirect()->route('dashboard')
                ->with('error', 'Aucun point de vente assignÃ© Ã  votre compte.');
        }

        // RÃ©cupÃ©rer les produits actifs avec leur stock rÃ©el
        $produits = Produit::with(['categorie', 'fournisseur'])
            ->actif()
            ->get()
            ->map(function ($produit) use ($pointVenteId) {
                // Calculer le stock disponible au point de vente de l'utilisateur
                $stock = StockProduit::where('produit_id', $produit->id)
                    ->where('point_vente_id', $pointVenteId)
                    ->sum('quantite');

                $produit->stock_disponible = $stock;
                return $produit;
            })
            ->sortBy(function ($produit) {
                // Tri : d'abord les produits en stock (alphabÃ©tique), puis les Ã©puisÃ©s
                return [
                    $produit->stock_disponible > 0 ? 0 : 1,
                    strtolower($produit->nom)
                ];
            })
            ->values();
            // dd($produits);

        $clients = Client::actif()->orderBy('nom')->get();

        return view('ventes.create', compact('produits', 'clients', 'caisseOuverte'));
    }

    /**
     * Enregistrer une nouvelle vente (BROUILLON)
     */
    public function store(Request $request)
    {
        // Permission
        if (!auth()->user()->canMakeVentes()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de faire des ventes.');
        }

        try {
            $validated = $request->validate([
                'client_id' => 'nullable|exists:clients,id',
                'items' => 'required|array|min:1',
                'items.*.produit_id' => 'required|exists:produits,id',
                'items.*.quantite' => 'required|integer|min:1',
                'items.*.prix_unitaire' => 'required|numeric|min:0',
                'remise_globale' => 'nullable|numeric|min:0|max:100',
                'type_paiement' => 'required|in:especes,mobile_money,credit,mixte',
                'montant_paye' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        DB::beginTransaction();

        try {
            // VÃ©rifier la caisse ouverte
            $caisse = Caisse::caisseOuverteParUtilisateur(Auth::id());
            if (!$caisse) {
                throw new \Exception('Aucune caisse ouverte. Veuillez ouvrir une caisse d\'abord.');
            }

            $pointVenteId = Auth::user()->point_vente_id;

            // CrÃ©er la vente en BROUILLON
            $vente = Vente::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth::id(),
                'caisse_id' => $caisse->id,
                'point_vente_id' => $pointVenteId,
                'date_vente' => now(),
                'type_paiement' => $validated['type_paiement'],
                'montant_paye' => $validated['montant_paye'],
                'remise_globale' => $validated['remise_globale'] ?? 0,
                'notes' => $validated['notes'],
                'statut' => 'brouillon',
            ]);

            // CrÃ©er les lignes de vente
            $montantTotalHT = 0;
            $montantTotalTVA = 0;

            foreach ($validated['items'] as $itemData) {
                $produit = Produit::findOrFail($itemData['produit_id']);

                // VÃ©rifier le stock (mais ne pas le retirer)
                $stockDisponible = StockProduit::getStock($produit->id, $pointVenteId);

                if ($stockDisponible < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock disponible: {$stockDisponible} unitÃ©s");
                }

                // Prix et quantitÃ©
                $quantite = $itemData['quantite'];
                $prixUnitaireHT = $itemData['prix_unitaire'];
                $tauxTVA = $produit->tva_applicable ? $produit->taux_tva : 0;

                // Calculs
                $montantHT = $prixUnitaireHT * $quantite;
                $montantTVA = $montantHT * ($tauxTVA / 100);
                $prixUnitaireTTC = $prixUnitaireHT + ($prixUnitaireHT * $tauxTVA / 100);
                $montantTotal = $montantHT + $montantTVA;

                // CrÃ©er la ligne
                LigneVente::create([
                    'vente_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'quantite' => $quantite,
                    'unite' => 'bouteille',
                    'prix_unitaire_ht' => $prixUnitaireHT,
                    'prix_unitaire_ttc' => $prixUnitaireTTC,
                    'montant_ht' => $montantHT,
                    'montant_tva' => $montantTVA,
                    'montant_total' => $montantTotal,
                    'taux_tva' => $tauxTVA,
                ]);

                $montantTotalHT += $montantHT;
                $montantTotalTVA += $montantTVA;
            }

            // Appliquer remise globale
            if ($validated['remise_globale'] > 0) {
                $tauxRemise = $validated['remise_globale'] / 100;
                $montantTotalHT -= ($montantTotalHT * $tauxRemise);
                $montantTotalTVA -= ($montantTotalTVA * $tauxRemise);
            }

            // Mettre Ã  jour les montants de la vente
            $montantFinal = $montantTotalHT + $montantTotalTVA;
            $montantRendu = max(0, $validated['montant_paye'] - $montantFinal);

            $vente->update([
                'montant_ht' => round($montantTotalHT, 2),
                'montant_tva' => round($montantTotalTVA, 2),
                'montant_total' => round($montantFinal, 2),
                'montant_rendu' => round($montantRendu, 2),
            ]);

            DB::commit();

            // Retour JSON pour AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Vente {$vente->numero_vente} créer en brouillon. Validez-la pour mettre à jour les stocks.",
                    'vente_id' => $vente->id,
                    'numero_vente' => $vente->numero_vente,
                    'redirect' => route('ventes.show', $vente)
                ]);
            }

            return redirect()->route('ventes.show', $vente)
                ->with('success', "Vente {$vente->numero_vente} créer en brouillon. Validez-la pour finaliser.");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur crÃ©ation vente', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $request->all(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les details d'une vente
     */
    public function show(Vente $vente)
    {
        $vente->load(['client', 'user', 'caisse', 'lignes.produit.categorie', 'pointVente', 'validateur']);

        return view('ventes.show', compact('vente'));
    }

    /**
     * Valider une vente brouillon
     */
    public function valider(Vente $vente)
    {
        // VÃ©rifier que c'est le crÃ©ateur ou un admin/responsable
        if ($vente->user_id !== Auth::id() && !Auth::user()->canViewAllReports()) {
            abort(403, 'Vous ne pouvez valider que vos propres ventes.');
        }

        if (!$vente->peutEtreValidee()) {
            return back()->with('error', 'Cette vente ne peut pas être valider.');
        }

        if ($vente->valider(Auth::id())) {
            return redirect()->route('ventes.show', $vente)
                ->with('success', "Vente {$vente->numero_vente} validé avec succés ! Les stocks ont été mis à  jour.");
        }

        return back()->with('error', 'Erreur lors de la validation de la vente.');
    }

    /**
     * Annuler une vente brouillon
     */
    public function annuler(Request $request, Vente $vente)
    {
        // VÃ©rifier que c'est le crÃ©ateur ou un admin/responsable
        if ($vente->user_id !== Auth::id() && !Auth::user()->canViewAllReports()) {
            abort(403, 'Vous ne pouvez annuler que vos propres ventes.');
        }

        $validated = $request->validate([
            'motif' => 'required|string|max:500',
        ]);

        if (!$vente->peutEtreAnnulee()) {
            return back()->with('error', 'Cette vente ne peut pas être annuler.');
        }

        DB::beginTransaction();
        try {
            // Annuler (pas de restauration de stock car jamais retirer si brouillon)
            $vente->update([
                'statut' => 'annulee',
                'notes' => ($vente->notes ? $vente->notes . "\n" : '') . "Annuler: " . $validated['motif']
            ]);

            DB::commit();

            return redirect()->route('ventes.index')
                ->with('success', 'Vente annuler avec succés.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Imprimer le ticket de vente
     */
    public function imprimer(Vente $vente)
    {
        $vente->load(['client', 'user', 'lignes.produit', 'pointVente']);

        return view('ventes.ticket', compact('vente'));
    }
}