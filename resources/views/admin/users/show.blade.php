@extends('layouts.app')

@section('title', $user->name)
@section('page-title', 'Détails de l\'Utilisateur')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Utilisateurs', 'url' => route('admin.users.index')],
      ['label' => $user->name, 'url' => null],
    ]" />

    <!-- En-tête avec actions -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <div class="flex items-center gap-3">
          <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
          <x-status-badge :status="$user->role_libelle" :type="$user->role === 'admin' ? 'danger' : 'info'" />
          <x-status-badge :status="$user->actif ? 'Actif' : 'Inactif'" :type="$user->actif ? 'success' : 'secondary'" />
        </div>
        <p class="text-gray-600 mt-1">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
      </div>
      <div class="flex items-center gap-3">
        @if($user->id !== auth()->id())
          <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="btn-{{ $user->actif ? 'warning' : 'success' }}">
              {{ $user->actif ? 'Désactiver' : 'Activer' }}
            </button>
          </form>
        @endif

        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
          </svg>
          Modifier
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">
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
                <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Adresse email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                <dd class="mt-1">
                  <x-status-badge :status="$user->role_libelle" :type="$user->role === 'admin' ? 'danger' : 'info'" />
                </dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                <dd class="mt-1">
                  <x-status-badge :status="$user->actif ? 'Actif' : 'Inactif'" :type="$user->actif ? 'success' : 'secondary'" />
                </dd>
              </div>

              @if($user->role === 'vendeur')
                <div>
                  <dt class="text-sm font-medium text-gray-500">Point de Vente Assigné</dt>
                  <dd class="mt-1">
                    @if($user->pointVente)
                      <div class="flex items-center">
                        <svg class="h-5 w-5 text-primary-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                        <div>
                          <p class="text-sm font-medium text-gray-900">{{ $user->pointVente->nom }}</p>
                          <p class="text-xs text-gray-500">{{ $user->pointVente->code }}</p>
                        </div>
                      </div>
                    @else
                      <span class="text-sm text-warning-600">⚠️ Aucun point de vente assigné</span>
                    @endif
                  </dd>
                </div>
              @endif

              <div>
                <dt class="text-sm font-medium text-gray-500">Membre depuis</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</dd>
              </div>
              <div>
                <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                <dd class="mt-1">
                  @if($user->email_verified_at)
                    <span class="text-sm text-success-600">✓ Vérifié le
                      {{ $user->email_verified_at->format('d/m/Y') }}</span>
                  @else
                    <span class="text-sm text-warning-600">Non vérifié</span>
                  @endif
                </dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Statistiques de performance -->
        @if($user->role === 'vendeur')
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Statistiques de Performance</h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <p class="text-3xl font-bold text-primary-600">{{ $stats['ventes_total'] }}</p>
                  <p class="text-sm text-gray-600 mt-1">Ventes Total</p>
                </div>
                <div class="text-center">
                  <p class="text-3xl font-bold text-info-600">{{ $stats['ventes_mois'] }}</p>
                  <p class="text-sm text-gray-600 mt-1">Ce Mois</p>
                </div>
                <div class="text-center">
                  <p class="text-3xl font-bold text-success-600">{{ number_format($stats['ca_total'], 0, ',', ' ') }}</p>
                  <p class="text-sm text-gray-600 mt-1">CA Total (FCFA)</p>
                </div>
                <div class="text-center">
                  <p class="text-3xl font-bold text-warning-600">{{ number_format($stats['ca_mois'], 0, ',', ' ') }}</p>
                  <p class="text-sm text-gray-600 mt-1">CA Mois (FCFA)</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Dernières ventes -->
          @if($user->ventes->count() > 0)
            <div class="card">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Dernières Ventes</h3>
              </div>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Vente</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                      <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Montant</th>
                      <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($user->ventes as $vente)
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
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                          {{ number_format($vente->montant_final, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                          <x-status-badge :status="$vente->statut_libelle" :type="$vente->statut_badge" />
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        @endif
      </div>

      <!-- Colonne latérale -->
      <div class="lg:col-span-1">
        <div class="card sticky top-6">
          <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Actions Rapides</h3>
          </div>
          <div class="p-6 space-y-3">
            <button type="button" onclick="document.getElementById('modal-reset-password').classList.remove('hidden')"
              class="w-full btn-secondary">
              🔑 Réinitialiser le mot de passe
            </button>

            @if($user->id !== auth()->id())
              <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                @csrf
                <button type="submit" class="w-full btn-{{ $user->actif ? 'warning' : 'success' }}">
                  {{ $user->actif ? '🔒 Désactiver le compte' : '✓ Activer le compte' }}
                </button>
              </form>

              @if(!$user->ventes()->exists())
                <button type="button"
                  onclick="if(confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) { document.getElementById('delete-form').submit(); }"
                  class="w-full btn-danger">
                  🗑️ Supprimer l'utilisateur
                </button>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Réinitialisation Mot de Passe -->
  <div id="modal-reset-password" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 z-50">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
          <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
            @csrf
            <div class="p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Réinitialiser le Mot de Passe</h3>

              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                  <input type="password" name="password" required minlength="8" class="input-field">
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                  <input type="password" name="password_confirmation" required class="input-field">
                </div>

                <div class="p-3 bg-warning-50 rounded-lg">
                  <p class="text-sm text-warning-900">
                    L'utilisateur devra utiliser ce nouveau mot de passe pour se connecter.
                  </p>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3">
              <button type="button" onclick="document.getElementById('modal-reset-password').classList.add('hidden')"
                class="btn-secondary">
                Annuler
              </button>
              <button type="submit" class="btn-primary">
                Réinitialiser
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Formulaire de suppression caché -->
  @if($user->id !== auth()->id() && !$user->ventes()->exists())
    <form id="delete-form" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
      @csrf
      @method('DELETE')
    </form>
  @endif
@endsection