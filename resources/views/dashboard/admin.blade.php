@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord Administrateur')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ auth()->user()->name }} 👋</h1>
      <p class="mt-1 text-sm text-gray-600">Voici un aperçu de votre activité aujourd'hui</p>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <!-- CA Jour -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">CA Aujourd'hui</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
              {{ number_format($stats['ventes_jour'], 0, ',', ' ') }}
            </p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['nombre_ventes_jour'] }} ventes</p>
          </div>
          <div class="p-3 bg-primary-100 rounded-lg">
            <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- CA Mois -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">CA Ce Mois</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
              {{ number_format($stats['ventes_mois'], 0, ',', ' ') }}
            </p>
            <p class="mt-1 text-xs text-gray-500">{{ $stats['nombre_ventes_mois'] }} ventes</p>
          </div>
          <div class="p-3 bg-success-100 rounded-lg">
            <svg class="h-8 w-8 text-success-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Produits -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Produits Actifs</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['produits_total'] }}</p>
            <p class="mt-1 text-xs text-danger-600">{{ $stats['produits_rupture'] }} en rupture</p>
          </div>
          <div class="p-3 bg-info-100 rounded-lg">
            <svg class="h-8 w-8 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Utilisateurs -->
      <div class="card p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Utilisateurs Actifs</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['utilisateurs_actifs'] }}</p>
            <p class="mt-1 text-xs text-gray-500">Vendeurs & admins</p>
          </div>
          <div class="p-3 bg-accent-100 rounded-lg">
            <svg class="h-8 w-8 text-accent-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Graphique + Performance points -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- Évolution CA -->
      <div class="lg:col-span-2 card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution du CA (7 derniers jours)</h3>
        <div class="h-64">
          <canvas id="chartCA"></canvas>
        </div>
      </div>

      <!-- Performance par point -->
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Points de vente (Aujourd'hui)</h3>
        <div class="space-y-4">
          @forelse($performancePoints as $point)
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center mr-3">
                  <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ $point['nom'] }}</p>
                  <p class="text-xs text-gray-500">{{ $point['ventes'] }} ventes</p>
                </div>
              </div>
              <p class="text-sm font-semibold text-gray-900">
                {{ number_format($point['ca'], 0, ',', ' ') }}
              </p>
            </div>
          @empty
            <p class="text-sm text-gray-500 text-center py-4">Aucune vente aujourd'hui</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Top produits + Alertes stock + Top vendeurs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Top produits -->
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Produits (Ce mois)</h3>
        <div class="space-y-3">
          @forelse($topProduits as $index => $produit)
            <div class="flex items-center">
              <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                <span class="text-sm font-semibold text-primary-600">{{ $index + 1 }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $produit->nom }}</p>
                <p class="text-xs text-gray-500">{{ $produit->total_quantite }} vendus</p>
              </div>
              <p class="text-sm font-semibold text-gray-900">
                {{ number_format($produit->total_ca, 0, ',', ' ') }}
              </p>
            </div>
          @empty
            <p class="text-sm text-gray-500 text-center py-4">Aucune vente ce mois</p>
          @endforelse
        </div>
      </div>

      <!-- Alertes stock -->
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Alertes Stock</h3>
        <div class="space-y-3">
          @forelse($alertesStock as $produit)
            <div class="flex items-center justify-between">
              <div class="flex items-center flex-1 min-w-0">
                @if($produit->stock_actuel <= 0)
                  <span class="flex-shrink-0 h-2 w-2 rounded-full bg-danger-500 mr-2"></span>
                @else
                  <span class="flex-shrink-0 h-2 w-2 rounded-full bg-accent-500 mr-2"></span>
                @endif
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ $produit->nom }}</p>
                  <p class="text-xs text-gray-500">{{ $produit->categorie->nom }}</p>
                </div>
              </div>
              <span
                class="ml-2 text-sm font-semibold {{ $produit->stock_actuel <= 0 ? 'text-danger-600' : 'text-accent-600' }}">
                {{ $produit->stock_actuel }}
              </span>
            </div>
          @empty
            <p class="text-sm text-gray-500 text-center py-4">Aucune alerte</p>
          @endforelse
        </div>
        @if($alertesStock->count() > 0)
          <a href="{{ route('produits.index', ['stock_statut' => 'rupture']) }}"
            class="mt-4 block text-center text-sm text-primary-600 hover:text-primary-800">
            Voir tous les produits →
          </a>
        @endif
      </div>

      <!-- Top vendeurs -->
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Vendeurs (Ce mois)</h3>
        <div class="space-y-3">
          @forelse($topVendeurs as $index => $vendeur)
            <div class="flex items-center">
              <div class="flex-shrink-0 h-8 w-8 rounded-full bg-success-100 flex items-center justify-center mr-3">
                <span class="text-sm font-semibold text-success-600">{{ $index + 1 }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">{{ $vendeur->name }}</p>
                <p class="text-xs text-gray-500">{{ $vendeur->ventes_mois }} ventes</p>
              </div>
              <p class="text-sm font-semibold text-gray-900">
                {{ number_format($vendeur->ca_mois, 0, ',', ' ') }}
              </p>
            </div>
          @empty
            <p class="text-sm text-gray-500 text-center py-4">Aucune vente</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    const ctx = document.getElementById('chartCA').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($evolutionCA->pluck('date')) !!},
        datasets: [{
          label: 'Chiffre d\'affaires',
          data: {!! json_encode($evolutionCA->pluck('montant')) !!},
          borderColor: 'rgb(59, 130, 246)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.4,
          fill: true
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