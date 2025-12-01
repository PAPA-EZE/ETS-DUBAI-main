@extends('layouts.app')

@section('title', 'Commandes')
@section('page-title', 'Gestion des Commandes')

@section('content')
  <div class="fade-in">
    <!-- Header avec stats rapides -->
    <div class="page-header">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Commandes Fournisseurs</h1>
        <p class="mt-1 text-sm text-gray-600">
          Gérez vos commandes et suivez les livraisons
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('commandes.create') }}" class="btn-primary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Nouvelle Commande
        </a>
      </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
      <x-stat-card title="Total Commandes" :value="$stats['total']"
        icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" /></svg>'
        color="primary" />

      <x-stat-card title="En Cours" :value="$stats['en_cours']"
        icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        color="warning" />

      <x-stat-card title="En Retard" :value="$stats['en_retard']" :subtitle="$stats['en_retard'] > 0 ? 'Attention requise' : 'Tout va bien'"
        icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>'
        color="danger" />

      <x-stat-card title="CA Ce Mois" :value="number_format($stats['ce_mois'], 0, ',', ' ') . ' FCFA'"
        subtitle="Commandes validées"
        icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        color="success" />
    </div>

    <!-- Alertes pour commandes en retard -->
    @if($stats['en_retard'] > 0)
      <x-alert type="warning" class="mb-6" dismissible>
        <strong>{{ $stats['en_retard'] }}</strong> commande(s) en retard nécessitent votre attention.
        <a href="{{ route('commandes.index', ['periode' => 'en_retard']) }}" class="underline font-medium ml-2">
          Voir les commandes en retard
        </a>
      </x-alert>
    @endif

    <!-- Filtres et recherche -->
    <div class="card p-6 mb-6">
      <form method="GET" action="{{ route('commandes.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
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
                placeholder="N° commande, fournisseur...">
            </div>
          </div>

          <!-- Fournisseur -->
          <div>
            <select name="fournisseur" class="input-field">
              <option value="">Tous fournisseurs</option>
              @foreach($fournisseurs as $fournisseur)
                <option value="{{ $fournisseur->id }}" {{ request('fournisseur') == $fournisseur->id ? 'selected' : '' }}>
                  {{ $fournisseur->nom }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Statut -->
          <div>
            <select name="statut" class="input-field">
              <option value="">Tous statuts</option>
              <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
              <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
              <option value="confirmee" {{ request('statut') === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
              <option value="en_preparation" {{ request('statut') === 'en_preparation' ? 'selected' : '' }}>En préparation
              </option>
              <option value="expediee" {{ request('statut') === 'expediee' ? 'selected' : '' }}>Expédiée</option>
              <option value="livree" {{ request('statut') === 'livree' ? 'selected' : '' }}>Livrée</option>
              <option value="partiellement_livree" {{ request('statut') === 'partiellement_livree' ? 'selected' : '' }}>
                Partiellement livrée</option>
              <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
          </div>

          <!-- Période -->
          <div>
            <select name="periode" class="input-field">
              <option value="">Toutes périodes</option>
              <option value="aujourd_hui" {{ request('periode') === 'aujourd_hui' ? 'selected' : '' }}>Aujourd'hui</option>
              <option value="cette_semaine" {{ request('periode') === 'cette_semaine' ? 'selected' : '' }}>Cette semaine
              </option>
              <option value="ce_mois" {{ request('periode') === 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
              <option value="en_retard" {{ request('periode') === 'en_retard' ? 'selected' : '' }}>En retard</option>
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

            @if(request()->hasAny(['search', 'fournisseur', 'statut', 'periode']))
              <a href="{{ route('commandes.index') }}" class="btn-secondary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Réinitialiser
              </a>
            @endif
          </div>

          <!-- Actions rapides -->
          <div class="flex space-x-2">
            <button type="button" class="btn-secondary">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
              </svg>
              Exporter
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Liste des commandes -->
    <div class="table-container">
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-medium text-gray-900">
          {{ $commandes->total() }} commande(s) trouvée(s)
        </h3>
      </div>

      @if($commandes->count() > 0)
        <div class="table-responsive">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'numero_commande', 'direction' => request('sort') === 'numero_commande' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center space-x-1 hover:text-gray-700">
                    <span>Commande</span>
                    @if(request('sort') === 'numero_commande')
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        @if(request('direction') === 'asc')
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                        @else
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        @endif
                      </svg>
                    @endif
                  </a>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fournisseur
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_commande', 'direction' => request('sort') === 'date_commande' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center space-x-1 hover:text-gray-700">
                    <span>Date</span>
                    @if(request('sort') === 'date_commande')
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        @if(request('direction') === 'asc')
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                        @else
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        @endif
                      </svg>
                    @endif
                  </a>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Statut
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Articles
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Montant
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Livraison
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Actions</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($commandes as $commande)
                    <tr class="hover:bg-gray-50 transition-colors {{ $commande->est_en_retard ? 'bg-red-50' : '' }}">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                              stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
                            </svg>
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                              <a href="{{ route('commandes.show', $commande) }}" class="hover:text-primary-600 transition-colors">
                                {{ $commande->numero_commande }}
                              </a>
                            </div>
                            <div class="text-sm text-gray-500">
                              Par {{ $commande->user->name }}
                            </div>
                          </div>
                        </div>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $commande->fournisseur->nom }}</div>
                        <div class="text-sm text-gray-500">
                          Ristourne: {{ $commande->fournisseur->taux_ristourne_defaut }}%
                        </div>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium">{{ $commande->date_commande->format('d/m/Y') }}</div>
                        @if($commande->date_livraison_prevue)
                          <div class="text-gray-500 {{ $commande->est_en_retard ? 'text-red-600 font-medium' : '' }}">
                            Livraison: {{ $commande->date_livraison_prevue->format('d/m/Y') }}
                            @if($commande->est_en_retard)
                              <span class="text-red-600">⚠️</span>
                            @endif
                          </div>
                        @endif
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        <x-status-badge :status="$commande->statut_text" :type="match ($commande->statut_color) {
                  'green' => 'success',
                  'red' => 'danger',
                  'yellow', 'orange' => 'warning',
                  'blue', 'purple' => 'info',
                  default => 'default'
                }" />
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium">{{ $commande->total_articles }} articles</div>
                        @if($commande->statut !== 'brouillon' && $commande->total_articles_livres > 0)
                          <div class="text-green-600 text-xs">
                            {{ $commande->total_articles_livres }} livrés ({{ $commande->progression_livraison }}%)
                          </div>
                        @endif
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium">{{ number_format($commande->montant_ttc, 0, ',', ' ') }} FCFA</div>
                        @if($commande->ristourne_prevue > 0)
                          <div class="text-green-600 text-xs">
                            Ristourne: {{ number_format($commande->ristourne_prevue, 0, ',', ' ') }} FCFA
                          </div>
                        @endif
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        @if($commande->date_livraison_reelle)
                          <div class="text-sm text-green-600 font-medium">
                            ✓ {{ $commande->date_livraison_reelle->format('d/m/Y') }}
                          </div>
                        @elseif(in_array($commande->statut, ['confirmee', 'en_preparation', 'expediee', 'partiellement_livree']))
                          <div class="text-sm">
                            @if($commande->progression_livraison > 0)
                              <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300"
                                  style="width: {{ $commande->progression_livraison }}%"></div>
                              </div>
                              <span class="text-xs text-gray-600">{{ $commande->progression_livraison }}%</span>
                            @else
                              <span class="text-xs text-gray-500">En attente</span>
                            @endif
                          </div>
                        @else
                          <span class="text-xs text-gray-400">--</span>
                        @endif
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                          <a href="{{ route('commandes.show', $commande) }}"
                            class="text-primary-600 hover:text-primary-900 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                          </a>

                          @if($commande->statut === 'brouillon')
                            <a href="{{ route('commandes.edit', $commande) }}"
                              class="text-gray-600 hover:text-gray-900 transition-colors">
                              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                              </svg>
                            </a>
                          @endif

                          @if(in_array($commande->statut, ['confirmee', 'en_preparation', 'expediee', 'partiellement_livree']))
                            <a href="{{ route('commandes.livraison', $commande) }}"
                              class="text-green-600 hover:text-green-900 transition-colors" title="Réceptionner">
                              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                            </a>
                          @endif
                        </div>
                      </td>
                    </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($commandes->hasPages())
          <div class="px-6 py-4 border-t border-gray-200">
            {{ $commandes->appends(request()->query())->links() }}
          </div>
        @endif

      @else
        <div class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune commande trouvée</h3>
          <p class="mt-1 text-sm text-gray-500">
            @if(request()->hasAny(['search', 'fournisseur', 'statut', 'periode']))
              Aucune commande ne correspond à vos critères de recherche.
            @else
              Commencez par créer votre première commande.
            @endif
          </p>
          <div class="mt-6">
            @if(request()->hasAny(['search', 'fournisseur', 'statut', 'periode']))
              <a href="{{ route('commandes.index') }}" class="btn-secondary mr-3">
                Voir toutes les commandes
              </a>
            @endif
            <a href="{{ route('commandes.create') }}" class="btn-primary">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Nouvelle Commande
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection