@extends('layouts.app')

@section('title', 'Transfert ' . $transfert->numero_transfert)
@section('page-title', 'Détails du Transfert')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
          <a href="{{ route('transferts.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            Transferts
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
            </svg>
            <span class="ml-1 text-gray-500">{{ $transfert->numero_transfert }}</span>
          </div>
        </li>
      </ol>
    </nav>

    <!-- Header -->
    <div class="page-header">
      <div>
        <div class="flex items-center space-x-3">
          <h1 class="text-2xl font-bold text-gray-900">{{ $transfert->numero_transfert }}</h1>
          <span
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transfert->statut_badge }}">
            {{ $transfert->statut_libelle }}
          </span>
        </div>
        <p class="mt-1 text-sm text-gray-600">
          Demandé le {{ $transfert->date_demande->format('d/m/Y à H:i') }}
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex space-x-3">
        @if($transfert->statut === 'en_attente')
          @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <button type="button" onclick="openValidateModal()" class="btn-success">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Valider
            </button>
            <button type="button" onclick="openRefuseModal()" class="btn-danger">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Refuser
            </button>
          @elseif($transfert->demande_par === auth()->id())
            <form action="{{ route('transferts.annuler', $transfert) }}" method="POST"
              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')">
              @csrf
              <button type="submit" class="btn-secondary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Annuler la demande
              </button>
            </form>
          @endif
        @elseif($transfert->statut === 'valide' && (auth()->user()->isAdmin() || auth()->user()->isResponsable()))
          <form action="{{ route('transferts.expedier', $transfert) }}" method="POST"
            onsubmit="return confirm('Confirmer l\'expédition ? Les stocks seront mis à jour.')">
            @csrf
            <button type="submit" class="btn-primary">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
              </svg>
              Expédier
            </button>
          </form>
        @elseif($transfert->statut === 'expedie')
          <button type="button" onclick="openReceptionModal()" class="btn-success">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Réceptionner
          </button>
        @endif
      </div>
    </div>

    <!-- Contenu -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Détails -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Trajet -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Trajet du transfert</h3>
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mx-auto">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
              </div>
              <p class="text-center mt-2 font-medium text-gray-900">{{ $transfert->pointSource->nom }}</p>
              <p class="text-center text-sm text-gray-500">{{ $transfert->pointSource->code }}</p>
            </div>

            <div class="flex-shrink-0 px-4">
              <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
              </svg>
            </div>

            <div class="flex-1">
              <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mx-auto">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
              </div>
              <p class="text-center mt-2 font-medium text-gray-900">{{ $transfert->pointDestination->nom }}</p>
              <p class="text-center text-sm text-gray-500">{{ $transfert->pointDestination->code }}</p>
            </div>
          </div>
        </div>

        <!-- Produits -->
        <div class="card overflow-hidden">
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Produits transférés</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité demandée</th>
                  @if($transfert->statut === 'expedie' || $transfert->statut === 'recu')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expédiée</th>
                  @endif
                  @if($transfert->statut === 'recu')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reçue</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Écart</th>
                  @endif
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($transfert->lignes as $ligne)
                  <tr>
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <div class="h-10 w-10 rounded-lg flex items-center justify-center mr-3"
                          style="background-color: {{ $ligne->produit->categorie->couleur }}20;">
                          <svg class="h-6 w-6" style="color: {{ $ligne->produit->categorie->couleur }};" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                          </svg>
                        </div>
                        <div>
                          <div class="text-sm font-medium text-gray-900">{{ $ligne->produit->nom }}</div>
                          <div class="text-xs text-gray-500">{{ $ligne->produit->reference }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ $ligne->quantite_affichee }}
                      </div>
                      <div class="text-xs text-gray-500">{{ $ligne->quantite_demandee }} unités</div>
                    </td>
                    @if($transfert->statut === 'expedie' || $transfert->statut === 'recu')
                      <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $ligne->quantite_expedie }}</div>
                      </td>
                    @endif
                    @if($transfert->statut === 'recu')
                      <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $ligne->quantite_recue }}</div>
                      </td>
                      <td class="px-6 py-4">
                        @if($ligne->hasEcart())
                          <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ligne->ecart < 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $ligne->ecart > 0 ? '+' : '' }}{{ $ligne->ecart }}
                          </span>
                        @else
                          <span class="text-sm text-gray-500">-</span>
                        @endif
                      </td>
                    @endif
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Motif -->
        @if($transfert->motif)
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Motif du transfert</h3>
            <p class="text-gray-600">{{ $transfert->motif }}</p>
          </div>
        @endif

        <!-- Notes (refus) -->
        @if($transfert->notes && $transfert->statut === 'refuse')
          <div class="card p-6 border-red-200 bg-red-50">
            <h3 class="text-lg font-medium text-red-900 mb-2">Motif du refus</h3>
            <p class="text-red-700">{{ $transfert->notes }}</p>
          </div>
        @endif
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informations</h3>
          <dl class="space-y-3 text-sm">
            <div>
              <dt class="text-gray-500">Demandé par</dt>
              <dd class="font-medium text-gray-900">{{ $transfert->demandeur->name }}</dd>
            </div>
            <div>
              <dt class="text-gray-500">Date de demande</dt>
              <dd class="font-medium text-gray-900">{{ $transfert->date_demande->format('d/m/Y à H:i') }}</dd>
            </div>
            @if($transfert->valide_par)
              <div>
                <dt class="text-gray-500">Validé par</dt>
                <dd class="font-medium text-gray-900">{{ $transfert->valideur->name }}</dd>
              </div>
              <div>
                <dt class="text-gray-500">Date de validation</dt>
                <dd class="font-medium text-gray-900">{{ $transfert->date_validation->format('d/m/Y à H:i') }}</dd>
              </div>
            @endif
            @if($transfert->date_expedition)
              <div>
                <dt class="text-gray-500">Date d'expédition</dt>
                <dd class="font-medium text-gray-900">{{ $transfert->date_expedition->format('d/m/Y à H:i') }}</dd>
              </div>
            @endif
            @if($transfert->date_reception)
              <div>
                <dt class="text-gray-500">Date de réception</dt>
                <dd class="font-medium text-gray-900">{{ $transfert->date_reception->format('d/m/Y à H:i') }}</dd>
              </div>
            @endif
            <div>
              <dt class="text-gray-500">Statut</dt>
              <dd>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transfert->statut_badge }}">
                  {{ $transfert->statut_libelle }}
                </span>
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  @include('transferts.modals.validate')
  @include('transferts.modals.refuse')
  @include('transferts.modals.reception')
@endsection