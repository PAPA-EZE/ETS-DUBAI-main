@extends('layouts.app')

@section('title', $caisse->nom_caisse)
@section('page-title', 'Détails de la Caisse')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Caisses', 'url' => route('caisses.index')],
      ['label' => $caisse->nom_caisse, 'url' => null],
    ]" />

    <!-- En-tête avec actions -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <div class="flex items-center gap-3">
          <h2 class="text-2xl font-bold text-gray-900">{{ $caisse->nom_caisse }}</h2>
          <x-status-badge :status="$caisse->statut_libelle" :type="$caisse->statut_badge" />
        </div>
        <p class="text-gray-600 mt-1">
          Ouvert le {{ $caisse->date_ouverture->format('d/m/Y à H:i') }} par {{ $caisse->responsable->name }}
        </p>
      </div>
      <div class="flex items-center gap-3">
        @if($caisse->estOuverte())
          <a href="{{ route('ventes.create') }}" class="btn-secondary">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            Nouvelle Vente
          </a>
          <a href="{{ route('caisses.fermer', $caisse) }}" class="btn-danger">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Fermer la Caisse
          </a>
        @endif
        <a href="{{ route('caisses.index') }}" class="btn-secondary">
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
                  d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Fond initial</p>
            <p class="text-xl font-bold text-gray-900">{{ number_format($caisse->fond_ouverture, 0, ',', ' ') }}</p>
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
                  d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total ventes</p>
            <p class="text-xl font-bold text-gray-900">{{ number_format($caisse->total_ventes, 0, ',', ' ') }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-info-100 rounded-lg">
              <svg class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Transactions</p>
            <p class="text-xl font-bold text-gray-900">{{ $caisse->nombre_transactions }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-accent-100 rounded-lg">
              <svg class="h-6 w-6 text-accent-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Durée</p>
            <p class="text-xl font-bold text-gray-900">{{ $caisse->duree }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Colonne principale -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Détails de la caisse -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Détails de la Session</h3>
          </div>
          <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <dt class="text-sm font-medium text-gray-500">Date d'ouverture</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $caisse->date_ouverture->format('d/m/Y à H:i') }}</dd>
              </div>
              @if($caisse->date_fermeture)
                <div>
                  <dt class="text-sm font-medium text-gray-500">Date de fermeture</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ $caisse->date_fermeture->format('d/m/Y à H:i') }}</dd>
                </div>
              @endif
              <div>
                <dt class="text-sm font-medium text-gray-500">Responsable</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $caisse->responsable->name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                <dd class="mt-1">
                  <x-status-badge :status="$caisse->statut_libelle" :type="$caisse->statut_badge" />
                </dd>
              </div>
              @if($caisse->notes_ouverture)
                <div class="md:col-span-2">
                  <dt class="text-sm font-medium text-gray-500">Notes d'ouverture</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ $caisse->notes_ouverture }}</dd>
                </div>
              @endif
              @if($caisse->notes_fermeture)
                <div class="md:col-span-2">
                  <dt class="text-sm font-medium text-gray-500">Notes de fermeture</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ $caisse->notes_fermeture }}</dd>
                </div>
              @endif
            </dl>
          </div>
        </div>

        <!-- Ventes récentes -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ventes de la Session</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Vente</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Paiement</th>
                </tr>
              </thead>

              {{-- @dd($ventesRecentes) --}}
              <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ventesRecentes as $vente)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <a href="{{ route('ventes.show', $vente) }}"
                        class="text-sm font-medium text-primary-600 hover:text-primary-900">
                        {{ $vente->numero_vente }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ $vente->client?->nom ?? 'Anonyme' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ $vente->date_vente->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                      {{ number_format($vente->montant_paye, 0, ',', ' ') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <x-status-badge :status="$vente->statut" :type="$vente->type_paiement" />
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                      Aucune vente enregistrée
                    </td>
                  </tr>
                @endforelse
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
            <!-- Fonds -->
            <div>
              <h4 class="text-sm font-medium text-gray-700 mb-2">Fonds de caisse</h4>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Ouverture:</span>
                  <span class="font-medium">{{ number_format($caisse->fond_ouverture, 0, ',', ' ') }}</span>
                </div>
                @if($caisse->fond_fermeture > 0)
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Fermeture:</span>
                    <span class="font-medium">{{ number_format($caisse->fond_fermeture, 0, ',', ' ') }}</span>
                  </div>
                @endif
              </div>
            </div>

            <!-- Ventes par type de paiement -->
            <div class="pt-4 border-t border-gray-200">
              <h4 class="text-sm font-medium text-gray-700 mb-2">Paiements</h4>
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Espèces:</span>
                  <span class="font-medium text-success-600">{{ number_format($stats['especes'], 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Mobile Money:</span>
                  <span class="font-medium text-info-600">{{ number_format($stats['mobile_money'], 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Crédit:</span>
                  <span class="font-medium text-warning-600">{{ number_format($stats['credit'], 0, ',', ' ') }}</span>
                </div>
              </div>
            </div>

            <!-- Total -->
            <div class="pt-4 border-t-2 border-gray-300">
              <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total ventes:</span>
                <span
                  class="text-2xl font-bold text-primary-600">{{ number_format($caisse->total_ventes, 0, ',', ' ') }}</span>
              </div>
            </div>

            @if(!$caisse->estOuverte() && $caisse->ecart != 0)
              <div class="pt-4 border-t border-gray-200">
                <div class="p-3 rounded-lg {{ $caisse->ecart > 0 ? 'bg-success-50' : 'bg-danger-50' }}">
                  <div class="flex justify-between items-center">
                    <span class="text-sm font-medium {{ $caisse->ecart > 0 ? 'text-success-900' : 'text-danger-900' }}">
                      Écart:
                    </span>
                    <span class="text-lg font-bold {{ $caisse->ecart > 0 ? 'text-success-600' : 'text-danger-600' }}">
                      {{ $caisse->ecart > 0 ? '+' : '' }}{{ number_format($caisse->ecart, 0, ',', ' ') }}
                    </span>
                  </div>
                </div>
              </div>
            @endif

            <!-- Statistiques -->
            <div class="pt-4 border-t border-gray-200 space-y-2">
              <div class="flex justify-between text-xs text-gray-500">
                <span>Transactions:</span>
                <span>{{ $stats['nombre_ventes'] }}</span>
              </div>
              <div class="flex justify-between text-xs text-gray-500">
                <span>Annulations:</span>
                <span>{{ $stats['nombre_annulations'] }}</span>
              </div>
              <div class="flex justify-between text-xs text-gray-500">
                <span>Panier moyen:</span>
                <span>{{ number_format($stats['montant_moyen'], 0, ',', ' ') }} FCFA</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection