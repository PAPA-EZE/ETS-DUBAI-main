@extends('layouts.app')

@section('title', 'Rapport de Ventes')
@section('page-title', 'Rapport de Ventes')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Rapports', 'url' => route('rapports.index')],
      ['label' => 'Ventes', 'url' => null],
    ]" />

    <!-- En-tête avec filtres -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Rapport de Ventes</h2>
          <p class="text-gray-600 mt-1">
            Du {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <form action="{{ route('rapports.ventes') }}" method="GET">
            <input type="hidden" name="date_debut" value="{{ $dateDebut->format('Y-m-d') }}">
            <input type="hidden" name="date_fin" value="{{ $dateFin->format('Y-m-d') }}">
            <input type="hidden" name="format" value="pdf">
            <button type="submit" class="btn-secondary">
              <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
              Exporter PDF
            </button>
          </form>
        </div>
      </div>

      <!-- Formulaire de filtrage -->
      <div class="card p-6">
        <form method="GET" action="{{ route('rapports.ventes') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
            <input type="date" name="date_debut" value="{{ $dateDebut->format('Y-m-d') }}" required class="input-field">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
            <input type="date" name="date_fin" value="{{ $dateFin->format('Y-m-d') }}" required class="input-field">
          </div>
          <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">Générer le Rapport</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <div class="card p-6 bg-success-50 border-l-4 border-success-500">
        <p class="text-sm font-medium text-success-900">Chiffre d'Affaires</p>
        <p class="text-3xl font-bold text-success-600 mt-2">{{ number_format($stats['ca_total'], 0, ',', ' ') }}</p>
        <p class="text-sm text-success-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-primary-50 border-l-4 border-primary-500">
        <p class="text-sm font-medium text-primary-900">Nombre de Ventes</p>
        <p class="text-3xl font-bold text-primary-600 mt-2">{{ $stats['nombre_ventes'] }}</p>
        <p class="text-sm text-primary-700 mt-1">transactions</p>
      </div>

      <div class="card p-6 bg-info-50 border-l-4 border-info-500">
        <p class="text-sm font-medium text-info-900">Panier Moyen</p>
        <p class="text-3xl font-bold text-info-600 mt-2">{{ number_format($stats['ca_moyen'], 0, ',', ' ') }}</p>
        <p class="text-sm text-info-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-warning-50 border-l-4 border-warning-500">
        <p class="text-sm font-medium text-warning-900">Articles Vendus</p>
        <p class="text-3xl font-bold text-warning-600 mt-2">{{ number_format($stats['articles_vendus'], 0, ',', ' ') }}
        </p>
        <p class="text-sm text-warning-700 mt-1">unités</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Répartition par type de paiement -->
      <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Répartition par Type de Paiement</h3>
        </div>
        <div class="p-6">
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-success-50 rounded-lg">
              <span class="font-medium text-gray-900">💵 Espèces</span>
              <span class="text-lg font-bold text-success-600">
                {{ number_format($stats['especes'], 0, ',', ' ') }} FCFA
              </span>
            </div>
            <div class="flex items-center justify-between p-3 bg-info-50 rounded-lg">
              <span class="font-medium text-gray-900">📱 Mobile Money</span>
              <span class="text-lg font-bold text-info-600">
                {{ number_format($stats['mobile_money'], 0, ',', ' ') }} FCFA
              </span>
            </div>
            <div class="flex items-center justify-between p-3 bg-warning-50 rounded-lg">
              <span class="font-medium text-gray-900">💳 Crédit</span>
              <span class="text-lg font-bold text-warning-600">
                {{ number_format($stats['credit'], 0, ',', ' ') }} FCFA
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Performance par vendeur -->
      <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Performance par Vendeur</h3>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            @foreach($stats['par_vendeur'] as $vendeurStats)
              <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                  <p class="font-medium text-gray-900">{{ $vendeurStats['vendeur'] }}</p>
                  <p class="text-sm text-gray-500">{{ $vendeurStats['nombre'] }} vente(s)</p>
                </div>
                <span class="text-lg font-bold text-primary-600">
                  {{ number_format($vendeurStats['montant'], 0, ',', ' ') }}
                </span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- Top 10 Produits -->
    <div class="card mb-6">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Top 10 Produits</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantité</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">CA Total</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($topProduits as $index => $produit)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 text-primary-700 font-bold">
                    {{ $index + 1 }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-medium text-gray-900">{{ $produit->nom }}</span>
                </td>
                <td class="px-6 py-4 text-right text-sm text-gray-900">
                  {{ number_format($produit->total_quantite, 0, ',', ' ') }} unités
                </td>
                <td class="px-6 py-4 text-right text-sm font-bold text-success-600">
                  {{ number_format($produit->total_ca, 0, ',', ' ') }} FCFA
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Top 10 Clients -->
    @if($topClients->count() > 0)
      <div class="card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Top 10 Clients</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nb Achats</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">CA Total</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($topClients as $index => $client)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-info-100 text-info-700 font-bold">
                      {{ $index + 1 }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm font-medium text-gray-900">{{ $client->nom }}</span>
                  </td>
                  <td class="px-6 py-4 text-right text-sm text-gray-900">
                    {{ $client->nombre_achats }}
                  </td>
                  <td class="px-6 py-4 text-right text-sm font-bold text-success-600">
                    {{ number_format($client->total_ca, 0, ',', ' ') }} FCFA
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif

    <!-- Évolution journalière -->
    <div class="card">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Évolution Journalière</h3>
      </div>
      <div class="p-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">CA</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($evolutionJournaliere as $jour)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($jour->date)->format('d/m/Y') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                    {{ $jour->nombre }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                    {{ number_format($jour->ca, 0, ',', ' ') }} FCFA
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection