@extends('layouts.app')

@section('title', 'Liste des Produits')
@section('page-title', 'Produits')

@section('content')
  <div class="fade-in">
    <!-- En-tete avec bouton -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Catalogue des Produits</h2>
        <p class="text-gray-600 mt-1">
          @if($isAdminOrResponsable)
            Gestion complete du catalogue - Vue globale des stocks
          @else
            Produits disponibles dans votre point de vente
          @endif
        </p>
      </div>
      @if($isAdminOrResponsable)
        <a href="{{ route('produits.create') }}" class="btn-primary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Nouveau Produit
        </a>
      @endif
    </div>

    <!-- Filtres -->
    <div class="card mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('produits.index') }}" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Recherche -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, reference, code-barre..."
                class="input-field">
            </div>

            <!-- Categorie -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Categorie</label>
              <select name="categorie" class="input-field">
                <option value="">Toutes les categories</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nom }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Fournisseur -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur</label>
              <select name="fournisseur" class="input-field">
                <option value="">Tous les fournisseurs</option>
                @foreach($fournisseurs as $fourn)
                  <option value="{{ $fourn->id }}" {{ request('fournisseur') == $fourn->id ? 'selected' : '' }}>
                    {{ $fourn->nom }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Statut stock -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
              <select name="stock_statut" class="input-field">
                <option value="">Tous</option>
                <option value="normal" {{ request('stock_statut') == 'normal' ? 'selected' : '' }}>En stock</option>
                <option value="faible" {{ request('stock_statut') == 'faible' ? 'selected' : '' }}>Stock faible</option>
                <option value="rupture" {{ request('stock_statut') == 'rupture' ? 'selected' : '' }}>Rupture</option>
              </select>
            </div>

            <!-- Boutons -->
            <div class="flex items-end gap-2">
              <button type="submit" class="btn-primary flex-1">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                Filtrer
              </button>
              <a href="{{ route('produits.index') }}" class="btn-secondary">
                Reinitialiser
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Table des produits -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorie</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Vente</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                @if($isAdminOrResponsable)
                  Stock Global
                @else
                  Mon Stock
                @endif
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($produits as $produit)
              <tr class="hover:bg-gray-50 transition-colors">
                <!-- Produit -->
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                      <span class="text-xs font-medium text-blue-700">
                        {{ strtoupper(substr($produit->nom, 0, 2)) }}
                      </span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        <a href="{{ route('produits.show', $produit) }}" class="hover:text-primary-600">
                          {{ $produit->nom }}
                        </a>
                      </div>
                      <div class="text-sm text-gray-500">{{ $produit->reference }}</div>
                    </div>
                  </div>
                </td>

                <!-- Categorie -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                    {{ $produit->categorie->nom }}
                  </span>
                </td>

                <!-- Prix -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    {{ number_format($produit->prix_vente_unite, 0, ',', ' ') }} FCFA
                  </div>
                  <div class="text-xs text-gray-500">/ unite</div>
                </td>

                <!-- Stock -->
                <td class="px-6 py-4">
                  @if($isAdminOrResponsable)
                    {{-- ADMIN/RESPONSABLE : Stock global + detail par point --}}
                    <div class="space-y-2">
                      <!-- Stock global -->
                      <div class="flex items-center">
                        @if($produit->stock_global > 10)
                          <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $produit->stock_global }} unites
                          </span>
                        @elseif($produit->stock_global > 0)
                          <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                            {{ $produit->stock_global }} unites
                          </span>
                        @else
                          <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Rupture
                          </span>
                        @endif
                      </div>

                      <!-- Detail par point de vente -->
                      @if($produit->stocks_par_point->count() > 0)
                        <div class="text-xs text-gray-500">
                          @foreach($produit->stocks_par_point as $point => $quantite)
                            <div class="flex justify-between py-1">
                              <span class="font-medium">{{ $point }}:</span>
                              <span class="ml-2">
                                @if($quantite > 0)
                                  <span class="text-green-600">{{ $quantite }}</span>
                                @else
                                  <span class="text-red-600">0</span>
                                @endif
                              </span>
                            </div>
                          @endforeach
                        </div>
                      @else
                        <div class="text-xs text-gray-400">Aucun stock</div>
                      @endif
                    </div>
                  @else
                    {{-- VENDEUR : Seulement stock de son point --}}
                    <div>
                      @if($produit->stock_point_vente > 10)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                          {{ $produit->stock_point_vente }} en stock
                        </span>
                      @elseif($produit->stock_point_vente > 0)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                          {{ $produit->stock_point_vente }} en stock
                        </span>
                      @else
                        <div class="space-y-1">
                          <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Stock epuise
                          </span>
                          <div class="text-xs text-gray-500">Demandez un transfert</div>
                        </div>
                      @endif
                    </div>
                  @endif
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="{{ route('produits.show', $produit) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                    Voir
                  </a>
                  @if($isAdminOrResponsable)
                    <a href="{{ route('produits.edit', $produit) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                      Modifier
                    </a>
                    <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="inline"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-900">
                        Supprimer
                      </button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucun produit trouve</p>
                  <p class="mt-2 text-sm text-gray-500">
                    @if($isAdminOrResponsable)
                      Commencez par creer votre premier produit
                    @else
                      Aucun produit disponible dans votre point de vente
                    @endif
                  </p>
                  @if($isAdminOrResponsable)
                    <div class="mt-6">
                      <a href="{{ route('produits.create') }}" class="btn-primary">
                        Creer un produit
                      </a>
                    </div>
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($produits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $produits->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection