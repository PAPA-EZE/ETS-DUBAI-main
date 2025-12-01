@extends('layouts.app')

@section('title', 'Transferts de Stock')
@section('page-title', 'Transferts de Stock')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="page-header">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Transferts de Stock</h1>
        <p class="mt-1 text-sm text-gray-600">Gérez les mouvements de stock entre points de vente</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('transferts.create') }}" class="btn-primary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
          </svg>
          Nouveau Transfert
        </a>
      </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-yellow-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">En attente</p>
            <p class="text-2xl font-bold text-gray-900">{{ $transferts->where('statut', 'en_attente')->count() }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-green-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Validés</p>
            <p class="text-2xl font-bold text-gray-900">{{ $transferts->where('statut', 'valide')->count() }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-purple-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">En transit</p>
            <p class="text-2xl font-bold text-gray-900">{{ $transferts->where('statut', 'expedie')->count() }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-blue-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $transferts->total() }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card p-6 mb-6">
      <form method="GET" action="{{ route('transferts.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <!-- Recherche -->
          <div class="lg:col-span-2">
            <label for="search" class="sr-only">Rechercher</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
              </div>
              <input type="text" name="search" id="search" value="{{ request('search') }}" class="input-field pl-10"
                placeholder="Numéro, produit...">
            </div>
          </div>

          <!-- Statut -->
          <div>
            <select name="statut" class="input-field">
              <option value="">Tous les statuts</option>
              <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
              <option value="valide" {{ request('statut') === 'valide' ? 'selected' : '' }}>Validé</option>
              <option value="expedie" {{ request('statut') === 'expedie' ? 'selected' : '' }}>Expédié</option>
              <option value="recu" {{ request('statut') === 'recu' ? 'selected' : '' }}>Reçu</option>
              <option value="refuse" {{ request('statut') === 'refuse' ? 'selected' : '' }}>Refusé</option>
              <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>
          </div>

          <!-- Point source -->
          <div>
            <select name="point_source" class="input-field">
              <option value="">Point source</option>
              @foreach($points as $point)
                <option value="{{ $point->id }}" {{ request('point_source') == $point->id ? 'selected' : '' }}>
                  {{ $point->nom }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-between">
          <div class="flex space-x-2">
            <button type="submit" class="btn-primary">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
              </svg>
              Filtrer
            </button>

            @if(request()->hasAny(['search', 'statut', 'point_source']))
              <a href="{{ route('transferts.index') }}" class="btn-secondary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Réinitialiser
              </a>
            @endif
          </div>
        </div>
      </form>
    </div>

    <!-- Liste des transferts -->
    @if($transferts->count() > 0)
      <div class="card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produits</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trajet</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandeur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($transferts as $transfert)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $transfert->numero_transfert }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $transfert->date_demande->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-500">{{ $transfert->date_demande->format('H:i') }}</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ $transfert->lignes->count() }} produit(s)</div>
                    <div class="text-xs text-gray-500">
                      {{ $transfert->lignes->first()->produit->nom ?? '-' }}
                      @if($transfert->lignes->count() > 1)
                        +{{ $transfert->lignes->count() - 1 }} autre(s)
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-900">{{ $transfert->pointSource->nom }}</span>
                      <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                      </svg>
                      <span class="text-sm text-gray-900">{{ $transfert->pointDestination->nom }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $transfert->demandeur->name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transfert->statut_badge }}">
                      {{ $transfert->statut_libelle }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('transferts.show', $transfert) }}" class="text-blue-600 hover:text-blue-900">
                      Voir
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      @if($transferts->hasPages())
        <div class="mt-6">
          {{ $transferts->appends(request()->query())->links() }}
        </div>
      @endif

    @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun transfert trouvé</h3>
        <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau transfert de stock.</p>
        <div class="mt-6">
          <a href="{{ route('transferts.create') }}" class="btn-primary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nouveau Transfert
          </a>
        </div>
      </div>
    @endif
  </div>
@endsection