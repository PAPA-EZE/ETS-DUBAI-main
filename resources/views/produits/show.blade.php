@extends('layouts.app')

@section('title', 'Détails du Produit')
@section('page-title', $produit->nom)

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-500">
      <ol class="flex items-center space-x-2">
        <li>
          <a href="{{ route('produits.index') }}" class="hover:text-primary-600">Produits</a>
        </li>
        <li>
          <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
          </svg>
        </li>
        <li class="font-medium text-gray-900">{{ $produit->nom }}</li>
      </ol>
    </nav>

    <!-- En-tête avec actions -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $produit->nom }}</h2>
        <p class="text-gray-600 mt-1">{{ $produit->reference }}</p>
      </div>
      <div class="flex items-center gap-3">
        @if($isAdminOrResponsable)
          <a href="{{ route('produits.edit', $produit) }}" class="btn-primary">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
            Modifier
          </a>
        @endif
        <a href="{{ route('produits.index') }}" class="btn-secondary">
          Retour à la liste
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Colonne principale -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Informations générales -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informations Générales</h3>
          </div>
          <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <dt class="text-sm font-medium text-gray-500">Nom du produit</dt>
                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $produit->nom }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Référence</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $produit->reference }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                <dd class="mt-1">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $produit->categorie->nom }}
                  </span>
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Fournisseur</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $produit->fournisseur->nom }}</dd>
              </div>
              @if($produit->code_barre)
                <div>
                  <dt class="text-sm font-medium text-gray-500">Code-barre</dt>
                  <dd class="mt-1 text-sm font-mono text-gray-900">{{ $produit->code_barre }}</dd>
                </div>
              @endif
              <div>
                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                <dd class="mt-1">
                  @if($produit->actif)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                      Actif
                    </span>
                  @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                      Inactif
                    </span>
                  @endif
                </dd>
              </div>
              @if($produit->description)
                <div class="md:col-span-2">
                  <dt class="text-sm font-medium text-gray-500">Description</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ $produit->description }}</dd>
                </div>
              @endif
            </dl>
          </div>
        </div>

        <!-- Conditionnement -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Conditionnement</h3>
          </div>
          <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <dt class="text-sm font-medium text-gray-500">Type</dt>
                <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $produit->type_conditionnement }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Unités par conditionnement</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $produit->unites_par_conditionnement }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Unités par pack</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $produit->unites_par_pack }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Prix et Marges -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Prix et Marges</h3>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Prix Conditionnement -->
              <div class="space-y-4">
                <h4 class="font-medium text-gray-700">Conditionnement</h4>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Prix d'achat</dt>
                  <dd class="mt-1 text-lg font-semibold text-gray-900">
                    {{ number_format($produit->prix_achat_conditionnement, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Prix de vente</dt>
                  <dd class="mt-1 text-lg font-semibold text-green-600">
                    {{ number_format($produit->prix_vente_conditionnement, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Marge</dt>
                  <dd class="mt-1 text-sm font-medium text-blue-600">
                    {{ number_format($produit->marge_reelle_conditionnement, 2) }}%
                  </dd>
                </div>
              </div>

              <!-- Prix Unité -->
              <div class="space-y-4">
                <h4 class="font-medium text-gray-700">Unité</h4>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Prix d'achat</dt>
                  <dd class="mt-1 text-lg font-semibold text-gray-900">
                    {{ number_format($produit->prix_achat_unite, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Prix de vente</dt>
                  <dd class="mt-1 text-lg font-semibold text-green-600">
                    {{ number_format($produit->prix_vente_unite, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Marge</dt>
                  <dd class="mt-1 text-sm font-medium text-blue-600">
                    {{ number_format($produit->marge_reelle_unite, 2) }}%
                  </dd>
                </div>
              </div>
            </div>

            <!-- TVA -->
            <div class="mt-6 pt-6 border-t border-gray-200">
              <div class="flex items-center justify-between">
                <dt class="text-sm font-medium text-gray-500">TVA applicable</dt>
                <dd>
                  @if($produit->tva_applicable)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                      {{ number_format($produit->taux_tva, 2) }}%
                    </span>
                  @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                      Non soumis à TVA
                    </span>
                  @endif
                </dd>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Colonne latérale : Stock -->
      <div class="lg:col-span-1">
        <div class="card sticky top-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
              @if($isAdminOrResponsable)
                Stock Global
              @else
                Mon Stock
              @endif
            </h3>
          </div>
          <div class="p-6">
            @if($isAdminOrResponsable)
              {{-- ADMIN/RESPONSABLE : Stock global + détail par point --}}
              <div class="space-y-6">
                <!-- Stock global -->
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                  <p class="text-sm text-gray-600 mb-2">Stock Total</p>
                  @if($produit->stock_global > $produit->stock_minimum)
                    <p class="text-4xl font-bold text-green-600">{{ $produit->stock_global }}</p>
                    <p class="text-sm text-green-600 mt-1">En stock</p>
                  @elseif($produit->stock_global > 0)
                    <p class="text-4xl font-bold text-orange-600">{{ $produit->stock_global }}</p>
                    <p class="text-sm text-orange-600 mt-1">Stock faible</p>
                  @else
                    <p class="text-4xl font-bold text-red-600">{{ $produit->stock_global }}</p>
                    <p class="text-sm text-red-600 mt-1">Rupture</p>
                  @endif
                  <p class="text-xs text-gray-500 mt-2">
                    Stock minimum: {{ $produit->stock_minimum }}
                  </p>
                </div>

                <!-- Répartition par point de vente -->
                <div>
                  <h4 class="font-medium text-gray-700 mb-3">Répartition par point de vente</h4>
                  @if($produit->stocks_par_point->count() > 0)
                    <div class="space-y-3">
                      @foreach($produit->stocks_par_point as $point => $quantite)
                        <div class="border border-gray-200 rounded-lg p-4">
                          <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">{{ $point }}</span>
                            @if($quantite > 10)
                              <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                En stock
                              </span>
                            @elseif($quantite > 0)
                              <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                Stock faible
                              </span>
                            @else
                              <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Rupture
                              </span>
                            @endif
                          </div>
                          <div class="flex items-baseline">
                            <span class="text-2xl font-bold text-gray-900">{{ $quantite }}</span>
                            <span class="text-sm text-gray-500 ml-2">unités</span>
                          </div>
                          <!-- Barre de progression -->
                          @if($produit->stock_global > 0)
                            <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
                              <div class="h-full bg-blue-600 rounded-full"
                                style="width: {{ ($quantite / $produit->stock_global) * 100 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                              {{ number_format(($quantite / $produit->stock_global) * 100, 1) }}% du stock total
                            </p>
                          @endif
                        </div>
                      @endforeach
                    </div>
                  @else
                    <div class="text-center py-8">
                      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                      </svg>
                      <p class="mt-2 text-sm text-gray-500">Aucun stock dans les points de vente</p>
                    </div>
                  @endif
                </div>
              </div>
            @else
              {{-- VENDEUR : Seulement stock de son point --}}
              <div class="space-y-6">
                <!-- Stock du point de vente -->
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                  <p class="text-sm text-gray-600 mb-2">Stock Disponible</p>
                  @if($produit->stock_point_vente > 10)
                    <p class="text-4xl font-bold text-green-600">{{ $produit->stock_point_vente }}</p>
                    <p class="text-sm text-green-600 mt-1">En stock</p>
                  @elseif($produit->stock_point_vente > 0)
                    <p class="text-4xl font-bold text-orange-600">{{ $produit->stock_point_vente }}</p>
                    <p class="text-sm text-orange-600 mt-1">Stock faible</p>
                  @else
                    <p class="text-4xl font-bold text-red-600">{{ $produit->stock_point_vente }}</p>
                    <p class="text-sm text-red-600 mt-1">Stock épuisé</p>
                  @endif
                  <p class="text-xs text-gray-500 mt-2">dans votre point de vente</p>
                </div>

                <!-- Message si stock épuisé -->
                @if($produit->stock_point_vente == 0)
                  <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                      <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                        </svg>
                      </div>
                      <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">Stock épuisé</p>
                        <p class="text-xs text-yellow-600 mt-1">
                          Demandez un transfert de stock au responsable pour pouvoir vendre ce produit.
                        </p>
                      </div>
                    </div>
                  </div>
                @endif

                <!-- Informations utiles -->
                <div class="border-t border-gray-200 pt-4 space-y-3">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Prix de vente:</span>
                    <span class="font-semibold text-gray-900">
                      {{ number_format($produit->prix_vente_unite, 0, ',', ' ') }} FCFA
                    </span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Stock minimum:</span>
                    <span class="font-medium text-gray-900">{{ $produit->stock_minimum }} unités</span>
                  </div>
                </div>

                <!-- Bouton action -->
                @if($produit->stock_point_vente > 0)
                  <a href="{{ route('ventes.create') }}" class="btn-primary w-full">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                    Vendre ce produit
                  </a>
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection