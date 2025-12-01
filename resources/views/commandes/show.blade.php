@extends('layouts.app')

@section('title', 'Commande ' . $commande->numero_commande)
@section('page-title', 'Commande ' . $commande->numero_commande)

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Commandes', 'url' => route('commandes.index'), 'icon' => '<svg class=\'w-4 h-4 mr-2\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z\' /></svg>'],
      ['label' => $commande->numero_commande],
    ]" />

    <!-- Header avec actions -->
    <div class="page-header">
      <div class="flex items-center">
        <div class="h-16 w-16 rounded-xl bg-primary-100 flex items-center justify-center mr-4">
          <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 flex items-center">
            {{ $commande->numero_commande }}

            <!-- Badge statut -->
            <span class="ml-3">
              <x-status-badge :status="$commande->statut_text" :type="match ($commande->statut_color) {
      'green' => 'success',
      'red' => 'danger',
      'yellow', 'orange' => 'warning',
      'blue', 'purple' => 'info',
      default => 'default'
    }" />
            </span>

            @if($commande->est_en_retard)
              <span
                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                ⚠️ En retard
              </span>
            @endif
          </h1>
          <p class="mt-1 text-sm text-gray-600">
            Fournisseur: {{ $commande->fournisseur->nom }} •
            Créée le {{ $commande->created_at->format('d/m/Y à H:i') }} par {{ $commande->user->name }}
          </p>
        </div>
      </div>
      <div class="mt-4 sm:mt-0 flex space-x-3">
        @if($commande->statut === 'brouillon')
          <a href="{{ route('commandes.edit', $commande) }}" class="btn-primary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
            </svg>
            Modifier
          </a>
        @endif

        @if(in_array($commande->statut, ['confirmee', 'en_preparation', 'expediee', 'partiellement_livree']))
          <a href="{{ route('commandes.livraison', $commande) }}" class="btn-success">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Réceptionner
          </a>
        @endif

        @if(!in_array($commande->statut, ['livree', 'annulee']))
          <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="btn-secondary">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
              </svg>
              Changer statut
              <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
              </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
              x-transition:enter-start="transform opacity-0 scale-95"
              x-transition:enter-end="transform opacity-100 scale-100"
              class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
              <div class="py-1">
                @if($commande->statut === 'brouillon')
                  <form method="POST" action="{{ route('commandes.statut', $commande) }}" class="block">
                    @csrf
                    <input type="hidden" name="statut" value="en_attente">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      Envoyer au fournisseur
                    </button>
                  </form>
                @endif

                @if($commande->statut === 'en_attente')
                  <form method="POST" action="{{ route('commandes.statut', $commande) }}" class="block">
                    @csrf
                    <input type="hidden" name="statut" value="confirmee">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      Marquer comme confirmée
                    </button>
                  </form>
                @endif

                @if(in_array($commande->statut, ['en_attente', 'confirmee']))
                  <form method="POST" action="{{ route('commandes.statut', $commande) }}" class="block">
                    @csrf
                    <input type="hidden" name="statut" value="en_preparation">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      En préparation
                    </button>
                  </form>
                @endif

                @if(in_array($commande->statut, ['confirmee', 'en_preparation']))
                  <form method="POST" action="{{ route('commandes.statut', $commande) }}" class="block">
                    @csrf
                    <input type="hidden" name="statut" value="expediee">
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                      Marquer comme expédiée
                    </button>
                  </form>
                @endif

                <div class="border-t border-gray-100"></div>

                @if(!in_array($commande->statut, ['annulee', 'livree']))
                  <form method="POST" action="{{ route('commandes.statut', $commande) }}" class="block">
                    @csrf
                    <input type="hidden" name="statut" value="annulee">
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')"
                      class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                      Annuler la commande
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        @endif

        <button type="button" class="btn-secondary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
          </svg>
          Imprimer bon
        </button>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Informations principales -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Détails de la commande -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
          <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <dt class="text-sm font-medium text-gray-500">Numéro de commande</dt>
              <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $commande->numero_commande }}</dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500">Fournisseur</dt>
              <dd class="mt-1 text-sm">
                <a href="{{ route('fournisseurs.show', $commande->fournisseur) }}"
                  class="text-primary-600 hover:text-primary-800 transition-colors">
                  {{ $commande->fournisseur->nom }}
                </a>
              </dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500">Date de commande</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $commande->date_commande->format('d/m/Y') }}</dd>
            </div>

            @if($commande->date_livraison_prevue)
              <div>
                <dt class="text-sm font-medium text-gray-500">Livraison prévue</dt>
                <dd class="mt-1 text-sm {{ $commande->est_en_retard ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                  {{ $commande->date_livraison_prevue->format('d/m/Y') }}
                  @if($commande->est_en_retard)
                    <span class="text-red-600">⚠️ En retard</span>
                  @endif
                </dd>
              </div>
            @endif

            @if($commande->date_livraison_reelle)
              <div>
                <dt class="text-sm font-medium text-gray-500">Date de livraison réelle</dt>
                <dd class="mt-1 text-sm text-green-600 font-medium">
                  ✓ {{ $commande->date_livraison_reelle->format('d/m/Y') }}
                </dd>
              </div>
            @endif

            <div>
              <dt class="text-sm font-medium text-gray-500">Créée par</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $commande->user->name }}</dd>
            </div>

            <div>
              <dt class="text-sm font-medium text-gray-500">Conditions de paiement</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $commande->fournisseur->conditions_paiement_text }}</dd>
            </div>

            @if($commande->notes)
              <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $commande->notes }}</dd>
              </div>
            @endif
          </dl>
        </div>

        <!-- Articles de la commande -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
              Articles commandés ({{ $commande->items->count() }})
            </h3>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Produit
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Quantité
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Prix unitaire
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total ligne
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Livraison
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($commande->items as $item)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                          <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                          </svg>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            <a href="{{ route('produits.show', $item->produit) }}" class="hover:text-primary-600">
                              {{ $item->produit->nom }}
                            </a>
                          </div>
                          <div class="text-sm text-gray-500">{{ $item->produit->reference }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ $item->quantite_commandee }} {{ $item->produit->unite }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ number_format($item->prix_unitaire_ht, 0, ',', ' ') }} FCFA HT
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ number_format($item->montant_ligne_ht, 0, ',', ' ') }} FCFA HT
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      @if($item->quantite_livree > 0)
                        <div class="text-sm">
                          <div class="font-medium text-green-600">
                            {{ $item->quantite_livree }} / {{ $item->quantite_commandee }}
                          </div>
                          @if($item->quantite_livree < $item->quantite_commandee)
                            <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                              <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $item->progression_livraison }}%">
                              </div>
                            </div>
                          @else
                            <span class="text-xs text-green-600">✓ Complet</span>
                          @endif
                        </div>
                      @else
                        <span class="text-xs text-gray-400">En attente</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        @if($commande->notes_livraison)
          <!-- Notes de livraison -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes de livraison</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
              <pre class="whitespace-pre-wrap text-sm text-gray-900">{{ $commande->notes_livraison }}</pre>
            </div>
          </div>
        @endif
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Récapitulatif financier -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Récapitulatif financier</h3>
          <dl class="space-y-3">
            <div class="flex justify-between">
              <dt class="text-sm text-gray-600">Total HT</dt>
              <dd class="text-sm font-medium text-gray-900">
                {{ number_format($commande->montant_ht, 0, ',', ' ') }} FCFA
              </dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-sm text-gray-600">TVA (19.25%)</dt>
              <dd class="text-sm font-medium text-gray-900">
                {{ number_format($commande->montant_tva, 0, ',', ' ') }} FCFA
              </dd>
            </div>
            <div class="border-t border-gray-200 pt-3">
              <div class="flex justify-between">
                <dt class="text-base font-medium text-gray-900">Total TTC</dt>
                <dd class="text-base font-bold text-primary-600">
                  {{ number_format($commande->montant_ttc, 0, ',', ' ') }} FCFA
                </dd>
              </div>
            </div>

            @if($commande->ristourne_prevue > 0)
              <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-4">
                <div class="flex justify-between items-center">
                  <dt class="text-sm font-medium text-green-800">Ristourne prévue</dt>
                  <dd class="text-sm font-bold text-green-600">
                    -{{ number_format($commande->ristourne_prevue, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
                <div class="text-xs text-green-600 mt-1">
                  Taux: {{ $commande->fournisseur->taux_ristourne_defaut }}%
                </div>
              </div>
            @endif

            @if($commande->montant_paye > 0)
              <div class="border-t border-gray-200 pt-3">
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Montant payé</dt>
                  <dd class="text-sm font-medium text-green-600">
                    {{ number_format($commande->montant_paye, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
                <div class="flex justify-between mt-2">
                  <dt class="text-sm font-medium text-gray-900">Solde restant</dt>
                  <dd class="text-sm font-bold {{ $commande->solde_restant > 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format($commande->solde_restant, 0, ',', ' ') }} FCFA
                  </dd>
                </div>
              </div>
            @endif
          </dl>
        </div>

        <!-- Progression de livraison -->
        @if(!in_array($commande->statut, ['brouillon', 'annulee']))
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Progression livraison</h3>
            <div class="space-y-3">
              <div class="flex justify-between text-sm">
                <span>Articles livrés</span>
                <span class="font-medium">{{ $commande->total_articles_livres }} / {{ $commande->total_articles }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-500 h-3 rounded-full transition-all duration-300"
                  style="width: {{ $commande->progression_livraison }}%"></div>
              </div>
              <div class="text-center text-sm font-medium text-gray-600">
                {{ $commande->progression_livraison }}% complété
              </div>
            </div>
          </div>
        @endif

        <!-- Actions rapides -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
          <div class="space-y-3">
            @if($commande->statut === 'brouillon')
              <a href="{{ route('commandes.edit', $commande) }}" class="btn-primary w-full">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
                Modifier commande
              </a>
            @endif

            @if(in_array($commande->statut, ['confirmee', 'en_preparation', 'expediee', 'partiellement_livree']))
              <a href="{{ route('commandes.livraison', $commande) }}" class="btn-success w-full">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Réceptionner livraison
              </a>
            @endif

            <button type="button" class="btn-secondary w-full">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
              </svg>
              Télécharger PDF
            </button>

            <a href="{{ route('fournisseurs.show', $commande->fournisseur) }}" class="btn-secondary w-full">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0 0a1.125 1.125 0 011.125-1.125h1.5c.621 0 1.125.504 1.125 1.125M6.75 14.25a1.125 1.125 0 011.125-1.125h4.125c.621 0 1.125.504 1.125 1.125M6.75 14.25V9.375a1.125 1.125 0 011.125-1.125h4.125M6.75 14.25V12a9 9 0 019-9" />
              </svg>
              Voir fournisseur
            </a>
          </div>
        </div>

        <!-- Informations système -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>
          <dl class="space-y-3 text-sm">
            <div>
              <dt class="text-gray-500">Créée le</dt>
              <dd class="font-medium">{{ $commande->created_at->format('d/m/Y à H:i') }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">Modifiée le</dt>
              <dd class="font-medium">{{ $commande->updated_at->format('d/m/Y à H:i') }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">ID Commande</dt>
              <dd class="font-mono text-xs">{{ $commande->id }}</dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </div>
@endsection