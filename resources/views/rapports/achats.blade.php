@extends('layouts.app')

@section('title', 'Rapport des Achats')
@section('page-title', 'Rapport des Achats')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Rapports', 'url' => route('rapports.index')],
      ['label' => 'Achats', 'url' => null],
    ]" />

    <!-- En-tête avec filtres -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Rapport des Achats</h2>
          <p class="text-gray-600 mt-1">
            Du {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
          </p>
        </div>
      </div>

      <!-- Formulaire de filtrage -->
      <div class="card p-6">
        <form method="GET" action="{{ route('rapports.achats') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
      <div class="card p-6 bg-primary-50 border-l-4 border-primary-500">
        <p class="text-sm font-medium text-primary-900">Nombre de Commandes</p>
        <p class="text-3xl font-bold text-primary-600 mt-2">{{ $stats['nombre_commandes'] }}</p>
        <p class="text-sm text-primary-700 mt-1">commandes livrées</p>
      </div>

      <div class="card p-6 bg-info-50 border-l-4 border-info-500">
        <p class="text-sm font-medium text-info-900">Montant Total HT</p>
        <p class="text-3xl font-bold text-info-600 mt-2">{{ number_format($stats['montant_total_ht'], 0, ',', ' ') }}</p>
        <p class="text-sm text-info-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-success-50 border-l-4 border-success-500">
        <p class="text-sm font-medium text-success-900">Montant Total TTC</p>
        <p class="text-3xl font-bold text-success-600 mt-2">{{ number_format($stats['montant_total_ttc'], 0, ',', ' ') }}
        </p>
        <p class="text-sm text-success-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-warning-50 border-l-4 border-warning-500">
        <p class="text-sm font-medium text-warning-900">Ristournes Prévues</p>
        <p class="text-3xl font-bold text-warning-600 mt-2">{{ number_format($stats['ristournes_prevues'], 0, ',', ' ') }}
        </p>
        <p class="text-sm text-warning-700 mt-1">FCFA</p>
      </div>
    </div>

    <!-- Achats par fournisseur -->
    @if($parFournisseur->count() > 0)
      <div class="card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Achats par Fournisseur</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Nb Commandes</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant HT</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ristourne</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($parFournisseur as $fournisseur => $statsF)
                <tr>
                  <td class="px-6 py-4">
                    <span class="text-sm font-medium text-gray-900">{{ $fournisseur }}</span>
                  </td>
                  <td class="px-6 py-4 text-right text-sm text-gray-900">
                    {{ $statsF['nombre'] }}
                  </td>
                  <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                    {{ number_format($statsF['montant_ht'], 0, ',', ' ') }} FCFA
                  </td>
                  <td class="px-6 py-4 text-right text-sm text-success-600">
                    -{{ number_format($statsF['ristourne'], 0, ',', ' ') }} FCFA
                  </td>
                  <td class="px-6 py-4 text-right text-sm font-bold text-primary-600">
                    {{ number_format($statsF['montant_ht'] - $statsF['ristourne'], 0, ',', ' ') }} FCFA
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif

    <!-- Liste détaillée des commandes -->
    <div class="card">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Détail des Commandes</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Commande</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Livraison</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant HT</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">TVA</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">TTC</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($commandes as $commande)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <a href="{{ route('commandes.show', $commande) }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-900">
                    {{ $commande->numero_commande }}
                  </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $commande->fournisseur->nom }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $commande->date_livraison_reelle->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                  {{ number_format($commande->montant_ht, 0, ',', ' ') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                  {{ number_format($commande->montant_tva, 0, ',', ' ') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                  {{ number_format($commande->montant_ttc, 0, ',', ' ') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <x-status-badge :status="$commande->statut_libelle" :type="$commande->statut_badge" />
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                  Aucune commande trouvée pour cette période
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection