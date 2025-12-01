@extends('layouts.app')

@section('title', 'Historique des Ventes')
@section('page-title', 'Historique des Ventes')

@section('content')
  <div class="fade-in">
    <!-- En-tête avec bouton -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Toutes les Ventes</h2>
        <p class="text-gray-600 mt-1">Gérez et consultez l'historique de vos ventes</p>
      </div>
      <a href="{{ route('ventes.create') }}" class="btn-primary">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Nouvelle Vente
      </a>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">CA Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ca_total'], 0, ',', ' ') }} FCFA</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Ventes</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ventes'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-purple-100 rounded-lg">
              <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Panier Moyen</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ca_moyen'], 0, ',', ' ') }} FCFA</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-indigo-100 rounded-lg">
              <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Espèces</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['especes'], 0, ',', ' ') }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('ventes.index') }}" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Recherche -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="N° vente, client..."
                class="input-field">
            </div>

            <!-- Statut -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
              <select name="statut" class="input-field">
                <option value="">Tous les statuts</option>
                <option value="completee" {{ request('statut') == 'completee' ? 'selected' : '' }}>Complétée</option>
                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
              </select>
            </div>

            <!-- Type paiement -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Paiement</label>
              <select name="type_paiement" class="input-field">
                <option value="">Tous les types</option>
                <option value="especes" {{ request('type_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                <option value="mobile_money" {{ request('type_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money
                </option>
                <option value="credit" {{ request('type_paiement') == 'credit' ? 'selected' : '' }}>Crédit</option>
                <option value="mixte" {{ request('type_paiement') == 'mixte' ? 'selected' : '' }}>Mixte</option>
              </select>
            </div>

            <!-- Période -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
              <select name="periode" class="input-field">
                <option value="">Toutes les périodes</option>
                <option value="aujourdhui" {{ request('periode') == 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette semaine
                </option>
                <option value="ce_mois" {{ request('periode') == 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
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
              <a href="{{ route('ventes.index') }}" class="btn-secondary">
                Réinitialiser
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Table des ventes -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vente</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($ventes as $vente)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                      <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        <a href="{{ route('ventes.show', $vente) }}" class="hover:text-primary-600">
                          {{ $vente->numero_vente }}
                        </a>
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ $vente->lignes->count() }} article(s)
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ $vente->client?->nom ?? 'Client passager' }}
                  </div>
                  <div class="text-sm text-gray-500">
                    Par {{ $vente->user->name }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $vente->date_vente->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if($vente->type_paiement === 'especes')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                      Espèces
                    </span>
                  @elseif($vente->type_paiement === 'mobile_money')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                      Mobile Money
                    </span>
                  @elseif($vente->type_paiement === 'credit')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                      Crédit
                    </span>
                  @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                      {{ ucfirst($vente->type_paiement) }}
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if($vente->statut === 'completee')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      Complétée
                    </span>
                  @elseif($vente->statut === 'brouillon')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                      Brouillon
                    </span>
                  @elseif($vente->statut === 'annulee')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                      Annulée
                    </span>
                  @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                      En cours
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="{{ route('ventes.show', $vente) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                    Voir
                  </a>
                  @if($vente->peutEtreAnnulee())
                    <button type="button"
                      onclick="if(confirm('Voulez-vous vraiment annuler cette vente ?')) { document.getElementById('annuler-form-{{ $vente->id }}').submit(); }"
                      class="text-red-600 hover:text-red-900">
                      Annuler
                    </button>
                    <form id="annuler-form-{{ $vente->id }}" action="{{ route('ventes.annuler', $vente) }}" method="POST"
                      class="hidden">
                      @csrf
                      <input type="hidden" name="motif" value="Annulation par {{ Auth::user()->name }}">
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucune vente trouvée</p>
                  <p class="mt-2 text-sm text-gray-500">Commencez par créer votre première vente</p>
                  <div class="mt-6">
                    <a href="{{ route('ventes.create') }}" class="btn-primary">
                      Nouvelle Vente
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($ventes->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $ventes->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection