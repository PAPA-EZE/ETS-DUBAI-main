@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Mon Tableau de bord')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ auth()->user()->name }} 👋</h1>
      <p class="mt-1 text-sm text-gray-600">
        Point de vente : <span class="font-semibold text-blue-600">{{ $pointVente->nom }}</span>
      </p>
    </div>

    <!-- Mes statistiques personnelles -->
    <div class="rounded-xl p-6 mb-6 text-white shadow-lg"
      style="background: linear-gradient(to right, #3b82f6, #2563eb);">
      <h3 class="text-lg font-semibold mb-4" style="color: white;">Mes Performances</h3>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <p class="text-sm" style="color: rgba(255, 255, 255, 0.8);">Mes ventes aujourd'hui</p>
          <p class="text-3xl font-bold mt-1" style="color: white;">{{ number_format($mesVentes['jour'], 0, ',', ' ') }}
            FCFA</p>
          <p class="text-xs mt-1" style="color: rgba(255, 255, 255, 0.8);">{{ $mesVentes['nombre_jour'] }} transactions
          </p>
        </div>
        <div>
          <p class="text-sm" style="color: rgba(255, 255, 255, 0.8);">Mes ventes ce mois</p>
          <p class="text-3xl font-bold mt-1" style="color: white;">{{ number_format($mesVentes['mois'], 0, ',', ' ') }}
            FCFA</p>
          <p class="text-xs mt-1" style="color: rgba(255, 255, 255, 0.8);">Total mensuel</p>
        </div>
        <div>
          <p class="text-sm" style="color: rgba(255, 255, 255, 0.8);">Objectif mensuel</p>
          <div class="mt-2">
            <div class="flex items-end space-x-2">
              <p class="text-2xl font-bold" style="color: white;">67%</p>
              <p class="text-sm pb-1" style="color: white;">atteint</p>
            </div>
            <div class="w-full rounded-full h-2 mt-2" style="background-color: rgba(255, 255, 255, 0.3);">
              <div class="h-2 rounded-full" style="width: 67%; background-color: white;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistiques du point -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <!-- CA Point Jour -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">CA Point (Aujourd'hui)</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
              {{ number_format($stats['ventes_jour'], 0, ',', ' ') }}
            </p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['nombre_ventes_jour'] }} ventes</p>
          </div>
          <div class="p-3 bg-blue-100 rounded-lg">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- CA Point Mois -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">CA Point (Ce mois)</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
              {{ number_format($stats['ventes_mois'], 0, ',', ' ') }}
            </p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['nombre_ventes_mois'] }} ventes</p>
          </div>
          <div class="p-3 bg-green-100 rounded-lg">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Produits disponibles -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Produits Disponibles</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ $stats['produits_disponibles'] }}</p>
            <p class="mt-1 text-xs text-red-600">{{ $stats['produits_rupture'] }} en rupture</p>
          </div>
          <div class="p-3 bg-cyan-100 rounded-lg">
            <svg class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Action rapide -->
      <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
        <div class="text-center">
          <svg class="h-12 w-12 mx-auto text-amber-600 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
          </svg>
          <a href="{{ route('ventes.create') }}" class="btn-primary w-full">
            Nouvelle Vente
          </a>
        </div>
      </div>
    </div>

    <!-- Graphique + Top produits -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Évolution de mes ventes -->
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mes Ventes (7 derniers jours)</h3>
        <div class="h-64">
          <canvas id="chartVentes"></canvas>
        </div>
      </div>

      <!-- Top produits du point -->
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Produits les Plus Vendus (Ce mois)</h3>
        <div class="space-y-4">
          @forelse($topProduits as $index => $produit)
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $produit->nom }}</p>
                <p class="text-xs text-gray-500">{{ $produit->total_quantite }} unités vendues</p>
              </div>
              <p class="text-sm font-semibold text-gray-900">
                {{ number_format($produit->total_ca, 0, ',', ' ') }}
              </p>
            </div>
          @empty
            <div class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Aucune vente ce mois</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Stock du point + Dernières ventes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Mon stock -->
      <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Stock de Mon Point</h3>
          <a href="{{ route('produits.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            Voir tout →
          </a>
        </div>
        <div class="space-y-3">
          @forelse($stockPoint as $stock)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
              <div class="flex items-center flex-1 min-w-0">
                <div class="h-10 w-10 rounded-lg flex items-center justify-center mr-3"
                  style="background-color: {{ $stock->produit->categorie->couleur }}20;">
                  <svg class="h-5 w-5" style="color: {{ $stock->produit->categorie->couleur }};" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ $stock->produit->nom }}</p>
                  <p class="text-xs text-gray-500">{{ $stock->produit->categorie->nom }}</p>
                </div>
              </div>
              <div class="text-right ml-4">
                <p
                  class="text-sm font-semibold {{ $stock->quantite <= 0 ? 'text-red-600' : ($stock->quantite <= $stock->produit->stock_minimum ? 'text-amber-600' : 'text-gray-900') }}">
                  {{ $stock->quantite }}
                </p>
                <p class="text-xs text-gray-500">en stock</p>
              </div>
            </div>
          @empty
            <p class="text-sm text-gray-500 text-center py-8">Aucun produit en stock</p>
          @endforelse
        </div>
      </div>

      <!-- Dernières ventes -->
      <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Dernières Ventes</h3>
          <a href="{{ route('ventes.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            Voir tout →
          </a>
        </div>
        <div class="space-y-3">
          @forelse($dernieresVentes as $vente)
            <a href="{{ route('ventes.show', $vente) }}"
              class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">
                    @if($vente->client)
                      {{ $vente->client->nom }}
                    @else
                      Client passager
                    @endif
                  </p>
                  <div class="flex items-center mt-1">
                    <p class="text-xs text-gray-500">{{ $vente->created_at->format('H:i') }}</p>
                    <span class="mx-1 text-gray-400">•</span>
                    <p class="text-xs text-gray-500">Par {{ $vente->user->name }}</p>
                  </div>
                </div>
                <div class="text-right ml-4">
                  <p class="text-sm font-semibold text-gray-900">
                    {{ number_format($vente->montant_total, 0, ',', ' ') }}
                  </p>
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $vente->statut === 'validee' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($vente->statut) }}
                  </span>
                </div>
              </div>
            </a>
          @empty
            <div class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Aucune vente récente</p>
              <a href="{{ route('ventes.create') }}"
                class="mt-4 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                Créer une vente →
              </a>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    const ctx = document.getElementById('chartVentes').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($evolutionVentes->pluck('date')) !!},
        datasets: [{
          label: 'Mes Ventes',
          data: {!! json_encode($evolutionVentes->pluck('montant')) !!},
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderColor: 'rgb(59, 130, 246)',
          borderWidth: 2,
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function (value) {
                return value.toLocaleString() + ' FCFA';
              }
            }
          }
        }
      }
    });
  </script>
@endpush