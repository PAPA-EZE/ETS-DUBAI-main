@extends('layouts.app')

@section('title', 'Gestion des Ristournes')
@section('page-title', 'Gestion des Ristournes')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Ristournes Fournisseurs</h2>
        <p class="text-gray-600 mt-1">Suivez et optimisez vos ristournes</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('ristournes.simulateur') }}" class="btn-secondary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
          </svg>
          Simulateur
        </a>
        <a href="{{ route('ristournes.create') }}" class="btn-primary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Calculer Ristourne
        </a>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-4 bg-info-50 border-l-4 border-info-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-info-900">Calculées</p>
            <p class="text-2xl font-bold text-info-600">{{ number_format($stats['total_calculees'], 0, ',', ' ') }}</p>
            <p class="text-xs text-info-700 mt-1">FCFA</p>
          </div>
          <div class="text-4xl">🔢</div>
        </div>
      </div>

      <div class="card p-4 bg-warning-50 border-l-4 border-warning-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-warning-900">Validées</p>
            <p class="text-2xl font-bold text-warning-600">{{ number_format($stats['total_validees'], 0, ',', ' ') }}</p>
            <p class="text-xs text-warning-700 mt-1">FCFA</p>
          </div>
          <div class="text-4xl">✓</div>
        </div>
      </div>

      <div class="card p-4 bg-success-50 border-l-4 border-success-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-success-900">Payées</p>
            <p class="text-2xl font-bold text-success-600">{{ number_format($stats['total_payees'], 0, ',', ' ') }}</p>
            <p class="text-xs text-success-700 mt-1">FCFA</p>
          </div>
          <div class="text-4xl">💰</div>
        </div>
      </div>

      <div class="card p-4 bg-primary-50 border-l-4 border-primary-500">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-primary-900">En attente</p>
            <p class="text-2xl font-bold text-primary-600">{{ $stats['nombre_en_attente'] }}</p>
            <p class="text-xs text-primary-700 mt-1">{{ number_format($stats['total_en_attente'], 0, ',', ' ') }} FCFA</p>
          </div>
          <div class="text-4xl">⏳</div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('ristournes.index') }}" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur</label>
              <select name="fournisseur_id" class="input-field">
                <option value="">Tous les fournisseurs</option>
                @foreach($fournisseurs as $fournisseur)
                  <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                    {{ $fournisseur->nom }}
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
              <select name="statut" class="input-field">
                <option value="">Tous les statuts</option>
                <option value="calculee" {{ request('statut') == 'calculee' ? 'selected' : '' }}>Calculée</option>
                <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
              <select name="annee" class="input-field">
                <option value="">Toutes les années</option>
                @for($year = now()->year; $year >= now()->year - 3; $year--)
                  <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
              </select>
            </div>

            <div class="flex items-end gap-2">
              <button type="submit" class="btn-primary flex-1">Filtrer</button>
              <a href="{{ route('ristournes.index') }}" class="btn-secondary">Réinitialiser</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Table des ristournes -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Achats HT</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Taux</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ristourne</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($ristournes as $ristourne)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ $ristourne->fournisseur->nom }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ $ristourne->periode_libelle }}</div>
                  <div class="text-xs text-gray-500">
                    {{ $ristourne->date_debut->format('d/m/Y') }} - {{ $ristourne->date_fin->format('d/m/Y') }}
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="text-sm font-medium text-gray-900">
                    {{ number_format($ristourne->montant_achats_ht, 0, ',', ' ') }}</div>
                  <div class="text-xs text-gray-500">FCFA</div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    {{ number_format($ristourne->taux_ristourne, 2) }}%
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="text-sm font-bold text-success-600">
                    {{ number_format($ristourne->montant_ristourne, 0, ',', ' ') }}</div>
                  <div class="text-xs text-gray-500">FCFA</div>
                </td>
                <td class="px-6 py-4 text-center">
                  <x-status-badge :status="$ristourne->statut_libelle" :type="$ristourne->statut_badge" />
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <a href="{{ route('ristournes.show', $ristourne) }}" class="text-primary-600 hover:text-primary-900">
                    Voir
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucune ristourne trouvée</p>
                  <p class="mt-2 text-sm text-gray-500">Calculez vos premières ristournes</p>
                  <div class="mt-6">
                    <a href="{{ route('ristournes.create') }}" class="btn-primary">
                      Calculer une Ristourne
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($ristournes->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $ristournes->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection