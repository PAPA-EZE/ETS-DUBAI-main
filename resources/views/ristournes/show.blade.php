@extends('layouts.app')

@section('title', 'Ristourne ' . $ristourne->periode_libelle)
@section('page-title', 'Détails de la Ristourne')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Ristournes', 'url' => route('ristournes.index')],
      ['label' => $ristourne->periode_libelle, 'url' => null],
    ]" />

    <!-- En-tête -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <div class="flex items-center gap-3">
          <h2 class="text-2xl font-bold text-gray-900">{{ $ristourne->periode_libelle }}</h2>
          <x-status-badge :status="$ristourne->statut_libelle" :type="$ristourne->statut_badge" />
        </div>
        <p class="text-gray-600 mt-1">{{ $ristourne->fournisseur->nom }}</p>
      </div>
      <div class="flex items-center gap-3">
        @if($ristourne->peutEtreValidee())
          <form action="{{ route('ristournes.valider', $ristourne) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-success">
              Valider la Ristourne
            </button>
          </form>
        @endif

        @if($ristourne->peutEtrePayee())
          <button type="button" onclick="document.getElementById('modal-paiement').classList.remove('hidden')"
            class="btn-primary">
            Marquer comme Payée
          </button>
        @endif

        @if($ristourne->statut !== 'payee')
          <button type="button" onclick="document.getElementById('modal-annulation').classList.remove('hidden')"
            class="btn-danger">
            Annuler
          </button>
        @endif

        <a href="{{ route('ristournes.index') }}" class="btn-secondary">
          Retour
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Colonne principale -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Résumé du calcul -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Résumé du Calcul</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                <span class="text-gray-700">Période</span>
                <span class="font-medium text-gray-900">
                  {{ $ristourne->date_debut->format('d/m/Y') }} → {{ $ristourne->date_fin->format('d/m/Y') }}
                </span>
              </div>

              <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                <span class="text-gray-700">Total achats HT</span>
                <span class="text-xl font-bold text-primary-600">
                  {{ number_format($ristourne->montant_achats_ht, 0, ',', ' ') }} FCFA
                </span>
              </div>

              <div class="flex justify-between items-center p-4 bg-primary-50 rounded-lg">
                <span class="text-primary-900 font-medium">Taux de ristourne appliqué</span>
                <span class="text-2xl font-bold text-primary-600">
                  {{ number_format($ristourne->taux_ristourne, 2) }}%
                </span>
              </div>

              <div class="flex justify-between items-center p-4 bg-success-50 rounded-lg border-2 border-success-200">
                <span class="text-success-900 font-semibold text-lg">Montant de la ristourne</span>
                <span class="text-3xl font-bold text-success-600">
                  {{ number_format($ristourne->montant_ristourne, 0, ',', ' ') }} FCFA
                </span>
              </div>
            </div>

            @if($ristourne->details && isset($ristourne->details['bareme_nom']))
              <div class="mt-6 p-4 bg-info-50 rounded-lg border border-info-200">
                <p class="text-sm text-info-900">
                  <strong>Barème appliqué:</strong> {{ $ristourne->details['bareme_nom'] }}
                </p>
                <p class="text-xs text-info-700 mt-1">
                  Calculé le {{ \Carbon\Carbon::parse($ristourne->details['date_calcul'])->format('d/m/Y à H:i') }}
                </p>
              </div>
            @endif

            @if($ristourne->notes)
              <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Notes</h4>
                <p class="text-sm text-gray-600 p-3 bg-gray-50 rounded">{{ $ristourne->notes }}</p>
              </div>
            @endif
          </div>
        </div>

        <!-- Commandes de la période -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Commandes de la Période</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Commande</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date livraison</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant HT</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @forelse($commandes as $commande)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <a href="{{ route('commandes.show', $commande->id) }}"
                        class="text-sm font-medium text-primary-600 hover:text-primary-900">
                        {{ $commande->numero_commande }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ \Carbon\Carbon::parse($commande->date_livraison_reelle)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                      {{ number_format($commande->montant_ht, 0, ',', ' ') }} FCFA
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                      Aucune commande trouvée pour cette période
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Colonne latérale -->
      <div class="lg:col-span-1">
        <div class="card sticky top-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Suivi</h3>
          </div>
          <div class="p-6 space-y-4">
            <!-- Statut actuel -->
            <div class="text-center p-4 bg-{{ $ristourne->statut_badge }}-50 rounded-lg">
              <div class="text-4xl mb-2">{{ $ristourne->statut_icone }}</div>
              <p class="font-semibold text-{{ $ristourne->statut_badge }}-900">{{ $ristourne->statut_libelle }}</p>
            </div>

            <!-- Timeline -->
            <div class="space-y-3">
              <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-info-100 rounded-full flex items-center justify-center">
                  <svg class="w-4 h-4 text-info-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3 flex-1">
                  <p class="text-sm font-medium text-gray-900">Calculée</p>
                  <p class="text-xs text-gray-500">{{ $ristourne->created_at->format('d/m/Y H:i') }}</p>
                </div>
              </div>

              @if($ristourne->date_validation)
                <div class="flex items-center">
                  <div class="flex-shrink-0 w-8 h-8 bg-warning-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Validée</p>
                    <p class="text-xs text-gray-500">{{ $ristourne->date_validation->format('d/m/Y') }}</p>
                  </div>
                </div>
              @endif

              @if($ristourne->date_paiement)
                <div class="flex items-center">
                  <div class="flex-shrink-0 w-8 h-8 bg-success-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Payée</p>
                    <p class="text-xs text-gray-500">{{ $ristourne->date_paiement->format('d/m/Y') }}</p>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Paiement -->
  <div id="modal-paiement" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 z-50">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
          <form action="{{ route('ristournes.payer', $ristourne) }}" method="POST">
            @csrf
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Marquer comme Payée</h3>

              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date de paiement</label>
                <input type="date" name="date_paiement" value="{{ now()->format('Y-m-d') }}" required class="input-field">
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                <textarea name="notes" rows="2" class="input-field"
                  placeholder="Référence de paiement, remarques..."></textarea>
              </div>

              <div class="p-3 bg-success-50 rounded-lg">
                <p class="text-sm text-success-900">
                  Montant à payer : <strong>{{ number_format($ristourne->montant_ristourne, 0, ',', ' ') }} FCFA</strong>
                </p>
              </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3">
              <button type="button" onclick="document.getElementById('modal-paiement').classList.add('hidden')"
                class="btn-secondary">
                Annuler
              </button>
              <button type="submit" class="btn-primary">
                Confirmer le Paiement
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Annulation -->
  <div id="modal-annulation" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 z-50">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
          <form action="{{ route('ristournes.annuler', $ristourne) }}" method="POST">
            @csrf
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Annuler la Ristourne</h3>

              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'annulation *</label>
                <textarea name="motif" rows="3" required class="input-field"
                  placeholder="Expliquez la raison..."></textarea>
              </div>

              <div class="p-3 bg-warning-50 rounded-lg border border-warning-200">
                <p class="text-sm text-warning-900">
                  Cette action ne peut pas être annulée.
                </p>
              </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3">
              <button type="button" onclick="document.getElementById('modal-annulation').classList.add('hidden')"
                class="btn-secondary">
                Retour
              </button>
              <button type="submit" class="btn-danger">
                Confirmer l'Annulation
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection