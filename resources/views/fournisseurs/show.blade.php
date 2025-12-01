@extends('layouts.app')

@section('title', $fournisseur->nom)
@section('page-title', $fournisseur->nom)

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
          <a href="{{ route('fournisseurs.index') }}" class="text-gray-700 hover:text-primary-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0 0a1.125 1.125 0 011.125-1.125h1.5c.621 0 1.125.504 1.125 1.125M6.75 14.25a1.125 1.125 0 011.125-1.125h4.125c.621 0 1.125.504 1.125 1.125M6.75 14.25V9.375a1.125 1.125 0 011.125-1.125h4.125M6.75 14.25V12a9 9 0 019-9" />
            </svg>
            Fournisseurs
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
            </svg>
            <span class="ml-1 text-gray-500">{{ $fournisseur->nom }}</span>
          </div>
        </li>
      </ol>
    </nav>

    <!-- Header avec actions -->
    <div class="page-header">
      <div class="flex items-center">
        <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center mr-4">
          <span class="text-lg font-medium text-primary-600">
            {{ strtoupper(substr($fournisseur->nom, 0, 2)) }}
          </span>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 flex items-center">
            {{ $fournisseur->nom }}
            @if($fournisseur->actif)
              <span
                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                  <circle cx="4" cy="4" r="3" />
                </svg>
                Actif
              </span>
            @else
              <span
                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                  <circle cx="4" cy="4" r="3" />
                </svg>
                Inactif
              </span>
            @endif
          </h1>
          <p class="mt-1 text-sm text-gray-600">
            Ristourne par défaut: {{ $fournisseur->taux_ristourne_defaut }}% •
            Conditions: {{ $fournisseur->conditions_paiement_text }}
          </p>
        </div>
      </div>
      <div class="mt-4 sm:mt-0 flex space-x-3">
        <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn-primary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
          </svg>
          Modifier
        </a>
        <button type="button" onclick="confirmDelete()" class="btn-danger">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
          </svg>
          Supprimer
        </button>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Informations principales -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Informations de contact -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de contact</h3>
          <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @if($fournisseur->telephone)
              <div>
                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                <dd class="mt-1 text-sm text-gray-900 flex items-center">
                  <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                  </svg>
                  <a href="tel:{{ $fournisseur->telephone }}" class="hover:text-primary-600 transition-colors">
                    {{ $fournisseur->telephone }}
                  </a>
                </dd>
              </div>
            @endif

            @if($fournisseur->email)
              <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900 flex items-center">
                  <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                  </svg>
                  <a href="mailto:{{ $fournisseur->email }}" class="hover:text-primary-600 transition-colors">
                    {{ $fournisseur->email }}
                  </a>
                </dd>
              </div>
            @endif

            @if($fournisseur->adresse)
              <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                <dd class="mt-1 text-sm text-gray-900 flex items-start">
                  <svg class="h-4 w-4 text-gray-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                  </svg>
                  {{ $fournisseur->adresse }}
                </dd>
              </div>
            @endif
          </dl>
        </div>

        <!-- Produits du fournisseur -->
        <div class="card">
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-gray-900">
                Produits ({{ $fournisseur->produits->count() }})
              </h3>
              <a href="{{ route('produits.create', ['fournisseur' => $fournisseur->id]) }}" class="btn-secondary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter produit
              </a>
            </div>
          </div>

          @if($fournisseur->produits->count() > 0)
            <div class="divide-y divide-gray-200">
              @foreach($fournisseur->produits->take(5) as $produit)
                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                  <div class="flex items-center">
                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                      <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                      </svg>
                    </div>
                    <div class="ml-4">
                      <p class="text-sm font-medium text-gray-900">{{ $produit->nom }}</p>
                      <p class="text-sm text-gray-500">
                        {{ number_format($produit->prix_achat, 0, ',', ' ') }} FCFA •
                        Stock: {{ $produit->stock_actuel }}
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2">
                    @if($produit->statut_stock === 'rupture')
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-800">
                        Rupture
                      </span>
                    @elseif($produit->statut_stock === 'faible')
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-100 text-accent-800">
                        Stock faible
                      </span>
                    @else
                      <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                        En stock
                      </span>
                    @endif
                    <a href="{{ route('produits.show', $produit) }}" class="text-primary-600 hover:text-primary-900">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                    </a>
                  </div>
                </div>
              @endforeach
            </div>

            @if($fournisseur->produits->count() > 5)
              <div class="px-6 py-4 bg-gray-50 text-center">
                <a href="{{ route('produits.index', ['fournisseur' => $fournisseur->id]) }}"
                  class="text-sm text-primary-600 hover:text-primary-900 font-medium">
                  Voir tous les produits ({{ $fournisseur->produits->count() }})
                </a>
              </div>
            @endif

          @else
            <div class="p-6 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit</h3>
              <p class="mt-1 text-sm text-gray-500">Ce fournisseur n'a pas encore de produits.</p>
              <div class="mt-6">
                <a href="{{ route('produits.create', ['fournisseur' => $fournisseur->id]) }}" class="btn-primary">
                  <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                  </svg>
                  Ajouter un produit
                </a>
              </div>
            </div>
          @endif
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Statistiques -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
          <dl class="space-y-4">
            <div>
              <dt class="text-sm font-medium text-gray-500">Produits</dt>
              <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $fournisseur->produits->count() }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Commandes</dt>
              <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $fournisseur->commandes->count() }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Membre depuis</dt>
              <dd class="mt-1 text-sm text-gray-900">{{ $fournisseur->created_at->format('d/m/Y') }}</dd>
            </div>
          </dl>
        </div>

        <!-- Actions rapides -->
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
          <div class="space-y-3">
            <a href="#" class="btn-secondary w-full">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Nouvelle commande
            </a>
            <a href="{{ route('produits.create', ['fournisseur' => $fournisseur->id]) }}" class="btn-secondary w-full">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
              </svg>
              Ajouter produit
            </a>
            <a href="#" class="btn-secondary w-full">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
              Voir historique
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulaire de suppression caché -->
    <form id="deleteForm" method="POST" action="{{ route('fournisseurs.destroy', $fournisseur) }}" class="hidden">
      @csrf
      @method('DELETE')
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    function confirmDelete() {
      if (confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Cette action est irréversible.')) {
        document.getElementById('deleteForm').submit();
      }
    }
  </script>
@endpush