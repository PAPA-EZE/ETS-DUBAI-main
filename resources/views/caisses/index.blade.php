@extends('layouts.app')

@section('title', 'Gestion des Caisses')
@section('page-title', 'Gestion des Caisses')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Gestion des Caisses</h2>
        <p class="text-gray-600 mt-1">Suivez les sessions de caisse et leurs mouvements</p>
      </div>
      @if(!$caisseOuverte)
        <a href="{{ route('caisses.create') }}" class="btn-primary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Ouvrir une Caisse
        </a>
      @else
        <a href="{{ route('caisses.show', $caisseOuverte) }}" class="btn-success">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Caisse Ouverte
        </a>
      @endif
    </div>

    <!-- Caisse ouverte actuelle -->
    @if($caisseOuverte)
      <div class="card mb-6 bg-success-50 border-l-4 border-success-500">
        <div class="p-6">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-success-900">{{ $caisseOuverte->nom_caisse }}</h3>
              <p class="text-sm text-success-700 mt-1">
                Ouverte le {{ $caisseOuverte->date_ouverture->format('d/m/Y à H:i') }}
                - Durée: {{ $caisseOuverte->duree }}
              </p>
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                  <p class="text-xs text-success-600 font-medium">Fond de caisse</p>
                  <p class="text-lg font-bold text-success-900">
                    {{ number_format($caisseOuverte->fond_ouverture, 0, ',', ' ') }} FCFA</p>
                </div>
                <div>
                  <p class="text-xs text-success-600 font-medium">Ventes du jour</p>
                  <p class="text-lg font-bold text-success-900">
                    {{ number_format($caisseOuverte->total_ventes, 0, ',', ' ') }} FCFA</p>
                </div>
                <div>
                  <p class="text-xs text-success-600 font-medium">Transactions</p>
                  <p class="text-lg font-bold text-success-900">{{ $caisseOuverte->nombre_transactions }}</p>
                </div>
                <div>
                  <p class="text-xs text-success-600 font-medium">Espèces</p>
                  <p class="text-lg font-bold text-success-900">
                    {{ number_format($caisseOuverte->total_especes, 0, ',', ' ') }} FCFA</p>
                </div>
              </div>
            </div>
            <div class="ml-6 flex flex-col gap-2">
              <a href="{{ route('caisses.show', $caisseOuverte) }}" class="btn-secondary">
                Voir Détails
              </a>
              <a href="{{ route('caisses.fermer', $caisseOuverte) }}" class="btn-danger">
                Fermer la Caisse
              </a>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('caisses.index') }}" class="flex gap-4">
          <select name="statut" class="input-field">
            <option value="">Tous les statuts</option>
            <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>Ouvertes</option>
            <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>Fermées</option>
          </select>

          <input type="date" name="date" value="{{ request('date') }}" class="input-field">

          <button type="submit" class="btn-primary">Filtrer</button>
          <a href="{{ route('caisses.index') }}" class="btn-secondary">Réinitialiser</a>
        </form>
      </div>
    </div>

    <!-- Liste des caisses -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caisse</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ventes</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions
              </th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($caisses as $caisse)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ $caisse->nom_caisse }}</div>
                  <div class="text-xs text-gray-500">Fond: {{ number_format($caisse->fond_ouverture, 0, ',', ' ') }} FCFA
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ $caisse->responsable->name }}
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ $caisse->date_ouverture->format('d/m/Y H:i') }}</div>
                  @if($caisse->date_fermeture)
                    <div class="text-xs text-gray-500">→ {{ $caisse->date_fermeture->format('d/m/Y H:i') }}</div>
                  @else
                    <div class="text-xs text-success-600 font-medium">En cours...</div>
                  @endif
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="text-sm font-medium text-gray-900">{{ number_format($caisse->total_ventes, 0, ',', ' ') }}
                  </div>
                  <div class="text-xs text-gray-500">FCFA</div>
                </td>
                <td class="px-6 py-4 text-right text-sm text-gray-900">
                  {{ $caisse->nombre_transactions }}
                </td>
                <td class="px-6 py-4 text-center">
                  <x-status-badge :status="$caisse->statut_libelle" :type="$caisse->statut_badge" />
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <a href="{{ route('caisses.show', $caisse) }}" class="text-primary-600 hover:text-primary-900">
                    Voir
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucune caisse trouvée</p>
                  <p class="mt-2 text-sm text-gray-500">Ouvrez une caisse pour commencer</p>
                  @if(!$caisseOuverte)
                    <div class="mt-6">
                      <a href="{{ route('caisses.create') }}" class="btn-primary">
                        Ouvrir une Caisse
                      </a>
                    </div>
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($caisses->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $caisses->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection