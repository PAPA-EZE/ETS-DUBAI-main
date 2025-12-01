<?php

namespace App\Http\Controllers;

use App\Models\TransfertStock;
use App\Models\LigneTransfert;
use App\Models\PointVente;
use App\Models\Produit;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransfertStockController extends Controller
{
  /**
   * Liste des transferts
   */
  public function index(Request $request): View
  {
    $query = TransfertStock::with(['pointSource', 'pointDestination', 'lignes.produit', 'demandeur', 'valideur']);

    $user = auth()->user();

    // Filtrage selon le rôle
    if ($user->isVendeur()) {
      // Vendeur : voir uniquement les transferts concernant son point
      $query->where(function ($q) use ($user) {
        $q->where('point_source_id', $user->point_vente_id)
          ->orWhere('point_destination_id', $user->point_vente_id);
      });
    }

    // Filtres
    if ($request->filled('statut')) {
      $query->where('statut', $request->get('statut'));
    }

    if ($request->filled('point_source')) {
      $query->where('point_source_id', $request->get('point_source'));
    }

    if ($request->filled('point_destination')) {
      $query->where('point_destination_id', $request->get('point_destination'));
    }

    if ($request->filled('search')) {
      $search = $request->get('search');
      $query->where(function ($q) use ($search) {
        $q->where('numero_transfert', 'like', "%{$search}%")
          ->orWhereHas('lignes.produit', function ($sq) use ($search) {
            $sq->where('nom', 'like', "%{$search}%");
          });
      });
    }

    // Tri
    $transferts = $query->orderByDesc('created_at')->paginate(15);

    // Pour les filtres
    $points = PointVente::actif()->orderBy('nom')->get();

    return view('transferts.index', compact('transferts', 'points'));
  }

  /**
   * Formulaire de création
   */
  public function create(): View|RedirectResponse
  {
    $user = auth()->user();

    // Admin/Responsable : peut choisir les points
    if ($user->isAdmin() || $user->isResponsable()) {
      $points = PointVente::actif()->orderBy('nom')->get();
      $pointSource = null;
      $pointDestination = null;
    }
    // Vendeur : demande pour son point uniquement
    else {
      $pointDestination = $user->pointVente;

      // Vérifier que ce n'est pas le magasin central
      if ($pointDestination->type === 'central') {
        return redirect()->route('transferts.index')
          ->with('error', 'Le magasin central ne peut pas demander de transfert.');
      }

      // Le point source est automatiquement le central
      $pointSource = PointVente::where('type', 'central')->first();
      $points = PointVente::actif()->orderBy('nom')->get();
    }

    // Produits avec stock
    $produits = Produit::actif()
      ->with('categorie')
      ->orderBy('nom')
      ->get();

    return view('transferts.create', compact('pointSource', 'pointDestination', 'points', 'produits'));
  }

  /**
   * Enregistrer un transfert
   */
  public function store(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'point_source_id' => 'required|exists:points_vente,id',
      'point_destination_id' => 'required|exists:points_vente,id|different:point_source_id',
      'motif' => 'required|string|min:10',
      'produits' => 'required|array|min:1',
      'produits.*.produit_id' => 'required|exists:produits,id',
      'produits.*.quantite' => 'required|integer|min:1',
      'produits.*.type_conditionnement' => 'required|in:casier,pack,unite',
    ], [
      'point_destination_id.different' => 'Le point de destination doit être différent du point source.',
      'motif.required' => 'Le motif est obligatoire.',
      'motif.min' => 'Le motif doit contenir au moins 10 caractères.',
      'produits.required' => 'Vous devez ajouter au moins un produit.',
      'produits.min' => 'Vous devez ajouter au moins un produit.',
    ]);

    $user = auth()->user();

    // Vérifier les autorisations
    if ($user->isVendeur()) {
      // Vendeur : destination = son point uniquement
      if ($validated['point_destination_id'] != $user->point_vente_id) {
        return back()
          ->withInput()
          ->with('error', 'Vous ne pouvez créer des transferts que pour votre point de vente.');
      }
    }

    \DB::beginTransaction();

    try {
      // Créer le transfert
      $transfert = TransfertStock::create([
        'numero_transfert' => TransfertStock::genererNumero(),
        'point_source_id' => $validated['point_source_id'],
        'point_destination_id' => $validated['point_destination_id'],
        'demande_par' => $user->id,
        'statut' => 'en_attente',
        'date_demande' => now(),
        'motif' => $validated['motif'],
      ]);

      // Créer les lignes
      foreach ($validated['produits'] as $produitData) {
        $produit = Produit::findOrFail($produitData['produit_id']);

        // Calculer la quantité en unités
        $quantiteUnites = $produitData['quantite'];
        if ($produitData['type_conditionnement'] === 'casier') {
          $quantiteUnites *= $produit->unites_par_conditionnement;
        } elseif ($produitData['type_conditionnement'] === 'pack') {
          $quantiteUnites *= $produit->unites_par_pack;
        }

        // Vérifier le stock disponible
        $stockDisponible = StockProduit::getStock($produit->id, $validated['point_source_id']);

        if ($stockDisponible < $quantiteUnites) {
          \DB::rollBack();
          return back()
            ->withInput()
            ->with('error', "Stock insuffisant pour {$produit->nom}. Disponible : " . $produit->afficherStock($stockDisponible));
        }

        // Créer la ligne
        LigneTransfert::create([
          'transfert_stock_id' => $transfert->id,
          'produit_id' => $produit->id,
          'quantite_demandee' => $quantiteUnites,
          'type_conditionnement' => $produitData['type_conditionnement'],
        ]);
      }

      \DB::commit();

      return redirect()->route('transferts.show', $transfert)
        ->with('success', 'Demande de transfert créée avec succès.');
    } catch (\Exception $e) {
      \DB::rollBack();
      return back()
        ->withInput()
        ->with('error', 'Erreur lors de la création du transfert : ' . $e->getMessage());
    }
  }

  /**
   * Afficher un transfert
   */
  public function show(TransfertStock $transfert): View
  {
    $transfert->load([
      'pointSource',
      'pointDestination',
      'lignes.produit.categorie',
      'demandeur',
      'valideur'
    ]);

    return view('transferts.show', compact('transfert'));
  }

  /**
   * Valider un transfert
   */
  public function valider(TransfertStock $transfert): RedirectResponse
  {
    if (!auth()->user()->isAdmin() && !auth()->user()->isResponsable()) {
      return back()->with('error', 'Seuls les administrateurs et responsables peuvent valider les transferts.');
    }

    if ($transfert->valider(auth()->user())) {
      return back()->with('success', 'Transfert validé avec succès.');
    }

    return back()->with('error', 'Impossible de valider le transfert. Vérifiez le stock disponible.');
  }

  /**
   * Expédier un transfert
   */
  public function expedier(TransfertStock $transfert): RedirectResponse
  {
    if (!auth()->user()->isAdmin() && !auth()->user()->isResponsable()) {
      return back()->with('error', 'Seuls les administrateurs et responsables peuvent expédier les transferts.');
    }

    if ($transfert->expedier(auth()->user())) {
      return back()->with('success', 'Transfert expédié. Les stocks ont été mis à jour.');
    }

    return back()->with('error', 'Impossible d\'expédier ce transfert.');
  }

  /**
   * Réceptionner un transfert
   */
  public function receptionner(Request $request, TransfertStock $transfert): RedirectResponse
  {
    $validated = $request->validate([
      'quantites' => 'required|array',
      'quantites.*' => 'required|integer|min:0',
    ]);

    if ($transfert->receptionner($validated['quantites'])) {
      return back()->with('success', 'Transfert réceptionné avec succès. Les stocks ont été mis à jour.');
    }

    return back()->with('error', 'Impossible de réceptionner ce transfert.');
  }

  /**
   * Refuser un transfert
   */
  public function refuser(Request $request, TransfertStock $transfert): RedirectResponse
  {
    if (!auth()->user()->isAdmin() && !auth()->user()->isResponsable()) {
      return back()->with('error', 'Seuls les administrateurs et responsables peuvent refuser les transferts.');
    }

    $validated = $request->validate([
      'motif' => 'required|string|min:10',
    ]);

    if ($transfert->refuser(auth()->user(), $validated['motif'])) {
      return back()->with('success', 'Transfert refusé.');
    }

    return back()->with('error', 'Impossible de refuser ce transfert.');
  }

  /**
   * Annuler un transfert
   */
  public function annuler(TransfertStock $transfert): RedirectResponse
  {
    // Seul le demandeur ou un admin peut annuler
    if (!auth()->user()->isAdmin() && $transfert->demande_par !== auth()->id()) {
      return back()->with('error', 'Vous ne pouvez pas annuler ce transfert.');
    }

    if ($transfert->annuler()) {
      return back()->with('success', 'Transfert annulé.');
    }

    return back()->with('error', 'Impossible d\'annuler ce transfert.');
  }

  /**
   * Obtenir le stock disponible (AJAX)
   */
  public function getStock(Request $request)
  {
    $produitId = $request->get('produit_id');
    $pointId = $request->get('point_id');

    if (!$produitId || !$pointId) {
      return response()->json(['stock' => 0]);
    }

    $stock = StockProduit::getStock($produitId, $pointId);
    $produit = Produit::find($produitId);

    return response()->json([
      'stock' => $stock,
      'affichage' => $produit ? $produit->afficherStock($stock) : $stock,
      'unites_par_conditionnement' => $produit ? $produit->unites_par_conditionnement : 1,
      'unites_par_pack' => $produit ? $produit->unites_par_pack : 1,
    ]);
  }
}
