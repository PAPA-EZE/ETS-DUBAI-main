@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Gestion des Utilisateurs</h2>
        <p class="text-gray-600 mt-1">Gérez les comptes et les accès au système</p>
      </div>
      <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Nouvel Utilisateur
      </a>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-primary-100 rounded-lg">
              <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-danger-100 rounded-lg">
              <svg class="h-6 w-6 text-danger-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Administrateurs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['admins'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-info-100 rounded-lg">
              <svg class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Vendeurs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['vendeurs'] }}</p>
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
                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Actifs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['actifs'] }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email..."
                class="input-field">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
              <select name="role" class="input-field">
                <option value="">Tous les rôles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                <option value="vendeur" {{ request('role') == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
              <select name="actif" class="input-field">
                <option value="">Tous</option>
                <option value="1" {{ request('actif') == '1' ? 'selected' : '' }}>Actifs</option>
                <option value="0" {{ request('actif') == '0' ? 'selected' : '' }}>Inactifs</option>
              </select>
            </div>

            <div class="flex items-end gap-2">
              <button type="submit" class="btn-primary flex-1">Filtrer</button>
              <a href="{{ route('admin.users.index') }}" class="btn-secondary">Réinitialiser</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Table des utilisateurs -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rôle</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Créé le</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center">
                      <span class="text-sm font-medium text-primary-700">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                      </span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        <a href="{{ route('admin.users.show', $user) }}" class="hover:text-primary-600">
                          {{ $user->name }}
                        </a>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $user->email }}
                </td>
                <td class="px-6 py-4 text-center">
                  <x-status-badge :status="$user->role_libelle" :type="$user->role === 'admin' ? 'danger' : 'info'" />
                </td>
                <td class="px-6 py-4 text-center">
                  <x-status-badge :status="$user->actif ? 'Actif' : 'Inactif'" :type="$user->actif ? 'success' : 'secondary'" />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                  <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                    Voir
                  </a>
                  <a href="{{ route('admin.users.edit', $user) }}" class="text-info-600 hover:text-info-900">
                    Modifier
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucun utilisateur trouvé</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection