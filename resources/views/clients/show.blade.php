@extends('layouts.app')

@section('title', $client->nom)
@section('page-title', 'Détails du Client')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Clients', 'url' => route('clients.index')],
      ['label' => $client->nom, 'url' => null],
    ]" />

    <!-- En-tête avec actions -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <div class="flex items-center gap-3">
          <h2 class="text-2xl font-bold text-gray-900">{{ $client->nom }}</h2>
          <x-status-badge :status="$client->type_libelle" :type="$client->type_badge" />
          <x-status-badge :status="$client->actif ? 'Actif' : 'Inactif'" :type="$client->actif ? 'success' : 'secondary'" />
        </div>
        <p class="text-gray-600 mt-1">Client depuis le {{ $client->created_at->format('d/m/Y') }}</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('clients.edit', $client) }}" class="btn-secondary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
          </svg>
          Modifier
        </a>
        <a href="{{ route('clients.index') }}" class="btn-secondary">
          Retour à la liste
        </a>
      </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-primary-100 rounded-lg">
              <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Achats</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['nombre_achats'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-success-100 rounded-lg">
              <svg class="h-6 w-6 text-success-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">CA Total</p>
            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['montant_total_achats'], 0, ',', ' ') }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-info-100 rounded-lg">
              <svg class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Panier Moyen</p>
            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['montant_moyen_achat'], 0, ',', ' ') }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-accent-100 rounded-lg">
              <svg class="h-6 w-6 text-accent-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Dernier Achat</p>
            <p class="text-sm font-bold text-gray-900">
              {{ $stats['dernier_achat'] ? $stats['dernier_achat']->format('d/m/Y') : 'Jamais' }}
            </p>
          </div>
        </div>
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
                <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->nom }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Type de client</dt>
                <dd class="mt-1">
                  <x-status-badge :status="$client->type_libelle" :type="$client->type_badge" />
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->telephone }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->email ?? '-' }}</dd>
              </div>
              @if($client->adresse)
                <div class="md:col-span-2">
                  <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ $client->adresse }}</dd>
                </div>
              @endif
            </dl>
          </div>
        </div>

        <!-- Top produits achetés -->
        @if($topProduits->isNotEmpty())
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Produits les Plus Achetés</h3>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                @foreach($topProduits as $produit)
                  <div class="flex items-center justify-between">
                    <div class="flex-1">
                      <p class="text-sm font-medium text-gray-900">{{ $produit->nom }}</p>
                      <p class="text-xs text-gray-500">{{ $produit->total_quantite }} unités</p>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-bold text-gray-900">{{ number_format($produit->total_montant, 0, ',', ' ') }}
                        FCFA</p>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Historique des achats -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Derniers Achats</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Vente</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Articles</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Paiement</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @forelse($client->ventes as $vente)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <a href="{{ route('ventes.show', $vente) }}"
                        class="text-sm font-medium text-primary-600 hover:text-primary-900">
                        {{ $vente->numero_vente }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ $vente->date_vente->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                      {{ $vente->items->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                      {{ number_format($vente->montant_final, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <x-status-badge :status="$vente->type_paiement_libelle" :type="$vente->type_paiement_badge" />
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                      Aucun achat enregistré
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Colonne latérale : Crédit -->
      <div class="lg:col-span-1">
        <div class="card sticky top-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Gestion du Crédit</h3>
          </div>
          <div class="p-6">
            @if($client->credit_limite > 0)
              <!-- Limite de crédit -->
              <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm text-gray-600">Limite autorisée</span>
                  <span class="text-lg font-bold text-gray-900">{{ number_format($client->credit_limite, 0, ',', ' ') }}
                    FCFA</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm text-gray-600">Crédit utilisé</span>
                  <span
                    class="text-lg font-bold text-{{ $client->statut_credit == 'depassement' ? 'danger' : 'primary' }}-600">
                    {{ number_format($client->solde_actuel, 0, ',', ' ') }} FCFA
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600">Crédit disponible</span>
                  <span
                    class="text-lg font-bold text-success-600">{{ number_format($client->credit_disponible, 0, ',', ' ') }}
                    FCFA</span>
                </div>
              </div>

              <!-- Barre de progression -->
              <div class="mb-6">
                <div class="flex justify-between text-xs text-gray-600 mb-1">
                  <span>Utilisation</span>
                  <span>{{ number_format($client->taux_utilisation_credit, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div
                    class="bg-{{ $client->statut_credit == 'depassement' ? 'danger' : ($client->statut_credit == 'alerte' ? 'warning' : 'primary') }}-600 h-2 rounded-full transition-all"
                    style="width: {{ min($client->taux_utilisation_credit, 100) }}%"></div>
                </div>
              </div>

              <!-- Statut crédit -->
              <div
                class="mb-6 p-3 rounded-lg bg-{{ $client->statut_credit == 'depassement' ? 'danger' : ($client->statut_credit == 'alerte' ? 'warning' : 'success') }}-50">
                <p
                  class="text-sm font-medium text-{{ $client->statut_credit == 'depassement' ? 'danger' : ($client->statut_credit == 'alerte' ? 'warning' : 'success') }}-900">
                  @if($client->statut_credit == 'depassement')
                    Crédit en dépassement
                  @elseif($client->statut_credit == 'alerte')
                    Crédit presque épuisé
                  @elseif($client->statut_credit == 'modere')
                    Utilisation modérée
                  @else
                    Crédit disponible
                  @endif
                </p>
              </div>

              <!-- Actions crédit -->
              <div class="space-y-3">
                <button type="button" onclick="document.getElementById('modal-paiement').classList.remove('hidden')"
                  class="w-full btn-success">
                  Enregistrer un Paiement
                </button>
                <button type="button" onclick="document.getElementById('modal-ajustement').classList.remove('hidden')"
                  class="w-full btn-secondary">
                  Ajuster le Crédit
                </button>
              </div>
            @else
              <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <p class="mt-4 text-sm text-gray-600">Ce client n'a pas de crédit autorisé</p>
                <a href="{{ route('clients.edit', $client) }}" class="mt-4 inline-block btn-primary">
                  Activer le Crédit
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Paiement -->
  <div id="modal-paiement" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
          class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <form action="{{ route('clients.ajuster-credit', $client) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="paiement">

            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-success-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-success-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                  <h3 class="text-lg font-semibold leading-6 text-gray-900">Enregistrer un Paiement</h3>
                  <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Montant du paiement
                    </label>
                    <div class="relative">
                      <input type="number" name="montant" required min="0" step="100" class="input-field pr-20">
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 text-sm">FCFA</span>
                      </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                      Solde actuel: {{ number_format($client->solde_actuel, 0, ',', ' ') }} FCFA
                    </p>

                    <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">
                      Motif (optionnel)
                    </label>
                    <textarea name="motif" rows="2" class="input-field"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="submit" class="btn-success w-full sm:ml-3 sm:w-auto">
                Confirmer le Paiement
              </button>
              <button type="button" onclick="document.getElementById('modal-paiement').classList.add('hidden')"
                class="btn-secondary w-full mt-3 sm:mt-0 sm:w-auto">
                Annuler
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Ajustement -->
  <div id="modal-ajustement" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
          class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <form action="{{ route('clients.ajuster-credit', $client) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="ajustement">

            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div
                  class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-warning-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-warning-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                  <h3 class="text-lg font-semibold leading-6 text-gray-900">Ajuster le Crédit</h3>
                  <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Montant à ajouter au solde
                    </label>
                    <div class="relative">
                      <input type="number" name="montant" required min="0" step="100" class="input-field pr-20">
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 text-sm">FCFA</span>
                      </div>
                    </div>
                    <p class="mt-2 text-xs text-warning-600">
                      <strong>Attention:</strong> Cette action augmentera le solde dû
                    </p>

                    <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">
                      Motif
                    </label>
                    <textarea name="motif" rows="2" required class="input-field"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="submit" class="btn-warning w-full sm:ml-3 sm:w-auto">
                Confirmer l'Ajustement
              </button>
              <button type="button" onclick="document.getElementById('modal-ajustement').classList.add('hidden')"
                class="btn-secondary w-full mt-3 sm:mt-0 sm:w-auto">
                Annuler
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection