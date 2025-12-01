@extends('layouts.app')

@section('title', 'Rapport des Stocks')
@section('page-title', 'Rapport des Stocks')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-500">
      <ol class="flex items-center space-x-2">
        <li>
          <a href="{{ route('rapports.index') }}" class="hover:text-primary-600">Rapports</a>
        </li>
        <li>
          <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd" />
          </svg>
        </li>
        <li class="font-medium text-gray-900">Stocks</li>
      </ol>
    </nav>

    <!-- En-tete -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">État des Stocks</h2>
      <p class="text-gray-600 mt-1">
        @if($isAdminOrResponsable)
          Vue d'ensemble et valorisation des stocks actuels
        @else
          Stocks disponibles dans votre point de vente
        @endif
      </p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <!-- Total Produits -->
      <div class="card border-l-4 border-blue-500">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Produits</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_produits'] }}</p>
              <p class="text-xs text-gray-500 mt-1">produits actifs</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- En Rupture -->
      <div class="card border-l-4 border-red-500">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">En Rupture</p>
              <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['en_rupture'] }}</p>
              <p class="text-xs text-gray-500 mt-1">produits</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Stock Faible -->
      <div class="card border-l-4 border-orange-500">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Stock Faible</p>
              <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['stock_faible'] }}</p>
              <p class="text-xs text-gray-500 mt-1">alertes</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Valeur du Stock -->
      <div class="card border-l-4 border-green-500">
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Valeur du Stock</p>
              <p class="text-3xl font-bold text-green-600 mt-2">
                {{ number_format($stats['valeur_stock'], 0, ',', ' ') }}
              </p>
              <p class="text-xs text-gray-500 mt-1">FCFA (prix d'achat)</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stock par Categorie -->
    @if($parCategorie->count() > 0)
      <div class="card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Stock par Catégorie</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nb Produits
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité
                  Totale</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($parCategorie as $nomCategorie => $stats)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                      {{ $nomCategorie }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                    {{ $stats['nombre'] }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                    {{ number_format($stats['quantite_totale'], 0, ',', ' ') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-green-600">
                    {{ number_format($stats['valeur'], 0, ',', ' ') }} FCFA
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif

    <!-- Detail par Produit -->
    <div class="card">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Détail par Produit</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                @if($isAdminOrResponsable)
                  Stock Global
                @else
                  Mon Stock
                @endif
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Achat
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($produits as $produit)
              <tr class="hover:bg-gray-50">
                <!-- Produit -->
                <td class="px-6 py-4">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $produit->nom }}</div>
                    <div class="text-xs text-gray-500">{{ $produit->categorie->nom }}</div>
                  </div>
                </td>

                <!-- Fournisseur -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ $produit->fournisseur->nom }}</div>
                </td>

                <!-- Stock -->
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  @if($isAdminOrResponsable)
                    {{-- ADMIN : Stock global --}}
                    <div class="text-sm font-semibold text-gray-900">
                      {{ number_format($produit->stock_actuel, 0, ',', ' ') }}
                    </div>
                    {{-- Detail par point si admin --}}
                    @if(isset($produit->stocks_par_point) && $produit->stocks_par_point->count() > 0)
                      <div class="text-xs text-gray-500 mt-1">
                        @foreach($produit->stocks_par_point as $point => $data)
                          <div>{{ $point }}: {{ $data['quantite'] }}</div>
                        @endforeach
                      </div>
                    @endif
                  @else
                    {{-- VENDEUR : Stock de son point --}}
                    <div class="text-sm font-semibold text-gray-900">
                      {{ number_format($produit->stock_point_vente, 0, ',', ' ') }}
                    </div>
                  @endif
                </td>

                <!-- Prix Achat - CORRECTION ICI -->
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="text-sm text-gray-900">
                    {{ number_format($produit->prix_achat_unite, 0, ',', ' ') }}
                  </div>
                </td>

                <!-- Valeur - CORRECTION ICI -->
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  @if($isAdminOrResponsable)
                    {{-- ADMIN : Valeur globale --}}
                    <div class="text-sm font-semibold text-green-600">
                      {{ number_format($produit->stock_actuel * $produit->prix_achat_unite, 0, ',', ' ') }} FCFA
                    </div>
                  @else
                    {{-- VENDEUR : Valeur de son point --}}
                    <div class="text-sm font-semibold text-green-600">
                      {{ number_format($produit->valeur_stock_point, 0, ',', ' ') }} FCFA
                    </div>
                  @endif
                </td>

                <!-- Statut -->
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @php
                    $stock = $isAdminOrResponsable ? $produit->stock_actuel : $produit->stock_point_vente;
                  @endphp

                  @if($stock <= 0)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                      Rupture
                    </span>
                  @elseif($stock <= $produit->stock_minimum)
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                      Stock faible
                    </span>
                  @else
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                      OK
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucun produit trouvé</p>
                  <p class="mt-2 text-sm text-gray-500">Il n'y a actuellement aucun produit en stock.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection