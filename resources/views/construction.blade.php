@extends('layouts.app')

@section('title', $moduleTitle . ' - En construction')
@section('page-title', $moduleTitle)

@section('content')
  <div class="fade-in">
    <div class="text-center py-12">
      <!-- Icône animée -->
      <div
        class="mx-auto h-24 w-24 rounded-full bg-gradient-to-br from-accent-100 to-accent-200 flex items-center justify-center mb-6 relative">
        <svg class="h-12 w-12 text-accent-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
        </svg>
        <!-- Badge # -->
        <span
          class="absolute -top-2 -right-2 h-6 w-6 bg-accent-500 text-white rounded-full flex items-center justify-center text-xs font-bold">
          #
        </span>
      </div>

      <!-- Titre principal -->
      <h1 class="text-3xl font-bold text-gray-900 mb-4">
        {{ $moduleTitle }}
        <span class="text-accent-600">en construction</span>
      </h1>

      <!-- Message spécifique au module -->
      <div class="max-w-md mx-auto mb-8">
        @if($module === 'point-de-vente')
          <p class="text-gray-600 mb-4">
            Interface de caisse moderne avec scanner de codes-barres, calcul automatique des remises et gestion des
            paiements.
          </p>
          <div class="bg-blue-50 p-4 rounded-lg">
            <h4 class="font-medium text-blue-900 mb-2">Fonctionnalités prévues :</h4>
            <ul class="text-sm text-blue-700 space-y-1">
              <li>• Interface tactile optimisée</li>
              <li>• Scanner codes-barres</li>
              <li>• Gestion multi-paiements</li>
              <li>• Impression tickets</li>
            </ul>
          </div>
        @elseif($module === 'commandes')
          <p class="text-gray-600 mb-4">
            Système complet de gestion des commandes fournisseurs avec suivi des livraisons et optimisation des stocks.
          </p>
          <div class="bg-green-50 p-4 rounded-lg">
            <h4 class="font-medium text-green-900 mb-2">Fonctionnalités prévues :</h4>
            <ul class="text-sm text-green-700 space-y-1">
              <li>• Bon de commande automatisé</li>
              <li>• Suivi des livraisons</li>
              <li>• Réception marchandises</li>
              <li>• Calcul optimal des quantités</li>
            </ul>
          </div>
        @elseif($module === 'ristournes')
          <p class="text-gray-600 mb-4">
            Calcul intelligent et optimisation des ristournes fournisseurs pour maximiser votre rentabilité.
          </p>
          <div class="bg-purple-50 p-4 rounded-lg">
            <h4 class="font-medium text-purple-900 mb-2">Fonctionnalités prévues :</h4>
            <ul class="text-sm text-purple-700 space-y-1">
              <li>• Calcul automatique des ristournes</li>
              <li>• Simulation de scénarios</li>
              <li>• Recommandations d'optimisation</li>
              <li>• Suivi des échéances</li>
            </ul>
          </div>
        @elseif($module === 'rapports')
          <p class="text-gray-600 mb-4">
            Analytics avancés avec tableaux de bord interactifs et exports personnalisables.
          </p>
          <div class="bg-indigo-50 p-4 rounded-lg">
            <h4 class="font-medium text-indigo-900 mb-2">Fonctionnalités prévues :</h4>
            <ul class="text-sm text-indigo-700 space-y-1">
              <li>• Dashboards interactifs</li>
              <li>• Exports Excel/PDF</li>
              <li>• Analyses prédictives</li>
              <li>• Rapports automatisés</li>
            </ul>
          </div>
        @elseif($module === 'stock')
          <p class="text-gray-600 mb-4">
            Gestion avancée des stocks avec mouvements en temps réel et alertes intelligentes.
          </p>
          <div class="bg-orange-50 p-4 rounded-lg">
            <h4 class="font-medium text-orange-900 mb-2">Fonctionnalités prévues :</h4>
            <ul class="text-sm text-orange-700 space-y-1">
              <li>• Mouvements temps réel</li>
              <li>• Inventaires périodiques</li>
              <li>• Alertes stock intelligent</li>
              <li>• Traçabilité complète</li>
            </ul>
          </div>
        @else
          <p class="text-gray-600">
            Ce module sera bientôt disponible. En attendant, vous pouvez utiliser les modules
            <strong>Fournisseurs</strong> et <strong>Produits</strong> qui sont déjà fonctionnels.
          </p>
        @endif
      </div>

      <!-- Timeline estimée -->
      <div class="bg-gray-50 rounded-lg p-6 mb-8 max-w-lg mx-auto">
        <h3 class="font-medium text-gray-900 mb-4">🗓️ Planning de développement</h3>
        <div class="space-y-3 text-sm">
          @if(in_array($module, ['point-de-vente', 'commandes']))
            <div class="flex items-center justify-between p-2 bg-white rounded border-l-4 border-blue-400">
              <span>{{ $moduleTitle }}</span>
              <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Phase 2</span>
            </div>
          @elseif(in_array($module, ['ristournes', 'stock', 'clients']))
            <div class="flex items-center justify-between p-2 bg-white rounded border-l-4 border-green-400">
              <span>{{ $moduleTitle }}</span>
              <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Phase 2-3</span>
            </div>
          @else
            <div class="flex items-center justify-between p-2 bg-white rounded border-l-4 border-purple-400">
              <span>{{ $moduleTitle }}</span>
              <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs">Phase 3</span>
            </div>
          @endif
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
        <a href="{{ route('dashboard') }}" class="btn-primary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
          </svg>
          Retour au Dashboard
        </a>

        <div class="flex space-x-3">
          <a href="{{ route('fournisseurs.index') }}" class="btn-secondary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0 0a1.125 1.125 0 011.125-1.125h1.5c.621 0 1.125.504 1.125 1.125M6.75 14.25a1.125 1.125 0 011.125-1.125h4.125c.621 0 1.125.504 1.125 1.125M6.75 14.25V9.375a1.125 1.125 0 011.125-1.125h4.125M6.75 14.25V12a9 9 0 019-9" />
            </svg>
            Fournisseurs
          </a>

          <a href="{{ route('produits.index') }}" class="btn-secondary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            Produits
          </a>
        </div>
      </div>
    </div>

    <!-- Section d'information supplémentaire -->
    <div class="max-w-4xl mx-auto mt-12">
      <div class="bg-white rounded-xl shadow-soft border border-gray-100 p-8">
        <div class="text-center mb-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-2">🚀 Modules déjà disponibles</h2>
          <p class="text-gray-600">Profitez dès maintenant de ces fonctionnalités complètes</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <!-- Dashboard -->
          <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
              </svg>
            </div>
            <h3 class="font-medium text-green-900 mb-1">Dashboard</h3>
            <p class="text-sm text-green-700">KPIs et graphiques en temps réel</p>
            <span class="inline-block mt-2 bg-green-600 text-white text-xs px-2 py-1 rounded">✓ Complet</span>
          </div>

          <!-- Fournisseurs -->
          <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0 0a1.125 1.125 0 011.125-1.125h1.5c.621 0 1.125.504 1.125 1.125M6.75 14.25a1.125 1.125 0 011.125-1.125h4.125c.621 0 1.125.504 1.125 1.125M6.75 14.25V9.375a1.125 1.125 0 011.125-1.125h4.125M6.75 14.25V12a9 9 0 019-9" />
              </svg>
            </div>
            <h3 class="font-medium text-green-900 mb-1">Fournisseurs</h3>
            <p class="text-sm text-green-700">Gestion complète avec ristournes</p>
            <span class="inline-block mt-2 bg-green-600 text-white text-xs px-2 py-1 rounded">✓ Complet</span>
          </div>

          <!-- Produits -->
          <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
              </svg>
            </div>
            <h3 class="font-medium text-green-900 mb-1">Produits</h3>
            <p class="text-sm text-green-700">Catalogue avec gestion stocks</p>
            <span class="inline-block mt-2 bg-green-600 text-white text-xs px-2 py-1 rounded">✓ Complet</span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    @keyframes float {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    .animate-float {
      animation: float 3s ease-in-out infinite;
    }
  </style>
@endpush