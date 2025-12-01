<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Fournisseur;
use App\Models\Produit;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// Routes API pour les commandes
Route::middleware(['auth'])->group(function () {
  // Récupérer les produits d'un fournisseur
  Route::get('/fournisseurs/{fournisseur}/produits', function (Fournisseur $fournisseur) {
    return $fournisseur->produits()
      ->actif()
      ->select('id', 'nom', 'reference', 'prix_achat', 'stock_actuel', 'unite')
      ->orderBy('nom')
      ->get();
  });

  // Recherche de produits pour autocomplete
  Route::get('/produits/search', function (Request $request) {
    $query = $request->get('q', '');
    $fournisseur_id = $request->get('fournisseur_id');

    $produits = Produit::query()
      ->actif()
      ->when($fournisseur_id, function ($q) use ($fournisseur_id) {
        $q->where('fournisseur_id', $fournisseur_id);
      })
      ->when($query, function ($q) use ($query) {
        $q->where(function ($sq) use ($query) {
          $sq->where('nom', 'like', "%{$query}%")
            ->orWhere('reference', 'like', "%{$query}%")
            ->orWhere('code_barre', 'like', "%{$query}%");
        });
      })
      ->with(['fournisseur:id,nom', 'categorie:id,nom'])
      ->select('id', 'nom', 'reference', 'prix_achat', 'stock_actuel', 'unite', 'fournisseur_id', 'categorie_id')
      ->limit(20)
      ->get();

    return $produits;
  });
});
