@extends('layouts.app')

@section('title', 'Gestion des Clients')
@section('page-title', 'Gestion des Clients')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Tous les Clients</h2>
        <p class="text-gray-600 mt-1">Gérez votre base clients et leurs crédits</p>
      </div>
      <a href="{{ route('clients.create') }}" class="btn-primary">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Nouveau Client
      </a>
    </div>

    <!-- Statistiques rapides -->
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
            <p class="text-sm font-medium text-gray-600">Total Clients</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_clients'] }}</p>
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
            <p class="text-sm font-medium text-gray-600">Clients Actifs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['clients_actifs'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-info-100 rounded-lg">
              <svg class="h-6 w-6 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Avec Crédit</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['clients_avec_credit'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-4">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="p-3 bg-warning-100 rounded-lg">
              <svg class="h-6 w-6 text-warning-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">En Dépassement</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['clients_en_depassement'] }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-6">
      <div class="p-6">
        <form method="GET" action="{{ route('clients.index') }}" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Recherche -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, téléphone, email..."
                class="input-field">
            </div>

            <!-- Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
              <select name="type" class="input-field">
                <option value="">Tous les types</option>
                <option value="particulier" {{ request('type') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                <option value="entreprise" {{ request('type') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                <option value="detaillant" {{ request('type') == 'detaillant' ? 'selected' : '' }}>Détaillant</option>
              </select>
            </div>

            <!-- Statut -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
              <select name="actif" class="input-field">
                <option value="">Tous</option>
                <option value="1" {{ request('actif') == '1' ? 'selected' : '' }}>Actifs</option>
                <option value="0" {{ request('actif') == '0' ? 'selected' : '' }}>Inactifs</option>
              </select>
            </div>

            <!-- Crédit -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Crédit</label>
              <select name="credit" class="input-field">
                <option value="">Tous</option>
                <option value="avec" {{ request('credit') == 'avec' ? 'selected' : '' }}>Avec crédit</option>
                <option value="sans" {{ request('credit') == 'sans' ? 'selected' : '' }}>Sans crédit</option>
                <option value="depassement" {{ request('credit') == 'depassement' ? 'selected' : '' }}>En dépassement
                </option>
              </select>
            </div>

            <!-- Boutons -->
            <div class="flex items-end gap-2">
              <button type="submit" class="btn-primary flex-1">
                Filtrer
              </button>
              <a href="{{ route('clients.index') }}" class="btn-secondary">
                Réinitialiser
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Table des clients -->
    <div class="card">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Client
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Contact
              </th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Type
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Crédit
              </th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($clients as $client)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div
                      class="flex-shrink-0 h-10 w-10 bg-{{ $client->type_badge }}-100 rounded-full flex items-center justify-center">
                      <span class="text-sm font-medium text-{{ $client->type_badge }}-700">
                        {{ strtoupper(substr($client->nom, 0, 2)) }}
                      </span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        <a href="{{ route('clients.show', $client) }}" class="hover:text-primary-600">
                          {{ $client->nom }}
                        </a>
                      </div>
                      @if($client->adresse)
                        <div class="text-sm text-gray-500">{{ Str::limit($client->adresse, 30) }}</div>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ $client->telephone }}</div>
                  @if($client->email)
                    <div class="text-sm text-gray-500">{{ $client->email }}</div>
                  @endif
                </td>
                <td class="px-6 py-4 text-center">
                  <x-status-badge :status="$client->type_libelle" :type="$client->type_badge" />
                </td>
                <td class="px-6 py-4 text-right">
                  @if($client->credit_limite > 0)
                    <div class="text-sm font-medium text-gray-900">
                      {{ number_format($client->solde_actuel, 0, ',', ' ') }} /
                      {{ number_format($client->credit_limite, 0, ',', ' ') }}
                    </div>
                    <div class="text-xs text-gray-500">
                      Utilisé: {{ number_format($client->taux_utilisation_credit, 1) }}%
                    </div>
                  @else
                    <span class="text-sm text-gray-400">-</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-center">
                  <x-status-badge :status="$client->actif ? 'Actif' : 'Inactif'" :type="$client->actif ? 'success' : 'secondary'" />
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <a href="{{ route('clients.show', $client) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                    Voir
                  </a>
                  <a href="{{ route('clients.edit', $client) }}" class="text-info-600 hover:text-info-900">
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
                  <p class="mt-4 text-lg font-medium text-gray-900">Aucun client trouvé</p>
                  <p class="mt-2 text-sm text-gray-500">Commencez par créer votre premier client</p>
                  <div class="mt-6">
                    <a href="{{ route('clients.create') }}" class="btn-primary">
                      Nouveau Client
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($clients->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $clients->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection