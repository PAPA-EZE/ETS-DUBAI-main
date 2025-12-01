@extends('layouts.app')

@section('title', 'Vente ' . $vente->numero_vente)
@section('page-title', 'Détails de la Vente')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Ventes', 'url' => route('ventes.index')],
      ['label' => $vente->numero_vente, 'url' => null],
    ]" />

    <!-- En-tête avec actions -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Vente {{ $vente->numero_vente }}</h2>
        <p class="text-gray-600 mt-1">{{ $vente->date_vente->format('d/m/Y à H:i') }}</p>
      </div>
      <div class="flex items-center gap-3">
        @if($vente->peutEtreValidee())
          <form action="{{ route('ventes.valider', $vente) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit"
              onclick="return confirm('Voulez-vous vraiment valider cette vente ? Les stocks seront mis à jour.')"
              class="btn-primary">
              <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Valider la vente
            </button>
          </form>
        @endif
        @if($vente->peutEtreAnnulee())
          <button type="button" onclick="document.getElementById('modal-annulation').classList.remove('hidden')"
            class="btn-danger">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Annuler la vente
          </button>
        @endif
        <a href="{{ route('ventes.imprimer', $vente) }}" target="_blank" class="btn-secondary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
          </svg>
          Imprimer
        </a>
        <a href="{{ route('ventes.index') }}" class="btn-secondary">
          Retour à la liste
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Colonne principale -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Informations générales -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informations Générales</h3>
          </div>
          <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <dt class="text-sm font-medium text-gray-500">Numéro de vente</dt>
                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $vente->numero_vente }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Date et heure</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $vente->date_vente->format('d/m/Y à H:i') }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Client</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $vente->client?->nom ?? 'Client passager' }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Vendeur</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $vente->user->name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Point de vente</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $vente->pointVente->nom }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                <dd class="mt-1">
                  @if($vente->statut === 'completee')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      Complétée
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
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Mode de paiement</dt>
                <dd class="mt-1">
                  @if($vente->type_paiement === 'especes')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                      Espèces
                    </span>
                  @elseif($vente->type_paiement === 'mobile_money')
                    <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                      Mobile Money
                    </span>
                  @elseif($vente->type_paiement === 'credit')
                    <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                      Crédit
                    </span>
                  @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                      {{ ucfirst($vente->type_paiement) }}
                    </span>
                  @endif
                </dd>
              </div>
              @if($vente->caisse)
                <div>
                  <dt class="text-sm font-medium text-gray-500">Caisse</dt>
                  <dd class="mt-1">
                    <a href="{{ route('caisses.show', $vente->caisse) }}" class="text-primary-600 hover:text-primary-900">
                      {{ $vente->caisse->nom_caisse }}
                    </a>
                  </dd>
                </div>
              @endif
              @if($vente->notes)
                <div class="md:col-span-2">
                  <dt class="text-sm font-medium text-gray-500">Notes</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ $vente->notes }}</dd>
                </div>
              @endif
            </dl>
          </div>
        </div>

        <!-- Articles vendus -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Articles Vendus</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">PU HT</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total TTC
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($vente->lignes as $ligne)
                  <tr>
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                          <span class="text-xs font-medium text-blue-700">
                            {{ strtoupper(substr($ligne->produit->nom, 0, 2)) }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ $ligne->produit->nom }}</div>
                          <div class="text-sm text-gray-500">{{ $ligne->produit->reference }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                      {{ $ligne->quantite }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-900">
                      {{ number_format($ligne->prix_unitaire_ht, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ number_format($ligne->montant_total, 0, ',', ' ') }} FCFA
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Colonne latérale : Résumé financier -->
      <div class="lg:col-span-1">
        <div class="card sticky top-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Résumé Financier</h3>
          </div>
          <div class="p-6 space-y-4">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Montant HT:</span>
              <span class="font-medium">{{ number_format($vente->montant_ht, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">TVA (19.25%):</span>
              <span class="font-medium">{{ number_format($vente->montant_tva, 0, ',', ' ') }} FCFA</span>
            </div>

            @if($vente->remise_globale > 0)
              <div class="flex justify-between text-sm text-red-600">
                <span>Remise globale ({{ $vente->remise_globale }}%):</span>
                <span
                  class="font-medium">-{{ number_format($vente->montant_ht * ($vente->remise_globale / 100), 0, ',', ' ') }}
                  FCFA</span>
              </div>
            @endif

            <div class="pt-4 border-t-2 border-gray-300">
              <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total:</span>
                <span class="text-2xl font-bold text-primary-600">
                  {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                </span>
              </div>
            </div>

            <div class="pt-4 border-t border-gray-200 space-y-3">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Montant payé:</span>
                <span class="font-medium text-green-600">{{ number_format($vente->montant_paye, 0, ',', ' ') }}
                  FCFA</span>
              </div>
              @if($vente->montant_rendu > 0)
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Rendu:</span>
                  <span class="font-medium">{{ number_format($vente->montant_rendu, 0, ',', ' ') }} FCFA</span>
                </div>
              @endif
            </div>

            <!-- Statistiques -->
            <div class="pt-4 border-t border-gray-200 space-y-2">
              <div class="flex justify-between text-xs text-gray-500">
                <span>Nombre d'articles:</span>
                <span>{{ $vente->lignes->sum('quantite') }}</span>
              </div>
              <div class="flex justify-between text-xs text-gray-500">
                <span>Lignes de vente:</span>
                <span>{{ $vente->lignes->count() }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal d'annulation -->
  <div id="modal-annulation" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
          class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <form action="{{ route('ventes.annuler', $vente) }}" method="POST">
            @csrf
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                  <h3 class="text-lg font-semibold leading-6 text-gray-900">Annuler la vente</h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 mb-4">
                      Êtes-vous sûr de vouloir annuler cette vente ? Les stocks seront automatiquement restaurés.
                    </p>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'annulation *</label>
                    <textarea name="motif" rows="3" required
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                      placeholder="Expliquez la raison de l'annulation..."></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="submit"
                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                Confirmer l'annulation
              </button>
              <button type="button" onclick="document.getElementById('modal-annulation').classList.add('hidden')"
                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                Annuler
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection