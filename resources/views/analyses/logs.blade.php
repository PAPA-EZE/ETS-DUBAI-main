@extends('layouts.app')

@section('title', 'Journal des Activités')
@section('page-title', 'Journal des Activités')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="page-header">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Journal des Activités</h1>
        <p class="mt-1 text-sm text-gray-600">Historique complet de toutes les actions</p>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card p-6 mb-6">
      <form method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Vendeur</label>
            <select name="user_id" class="input-field">
              <option value="">Tous les vendeurs</option>
              @foreach($vendeurs as $vendeur)
                <option value="{{ $vendeur->id }}" {{ request('user_id') == $vendeur->id ? 'selected' : '' }}>
                  {{ $vendeur->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Point de vente</label>
            <select name="point_id" class="input-field">
              <option value="">Tous les points</option>
              @foreach($points as $point)
                <option value="{{ $point->id }}" {{ request('point_id') == $point->id ? 'selected' : '' }}>
                  {{ $point->nom }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Type d'action</label>
            <select name="action_type" class="input-field">
              <option value="">Tous les types</option>
              <option value="vente_create" {{ request('action_type') == 'vente_create' ? 'selected' : '' }}>Vente créée
              </option>
              <option value="vente_annule" {{ request('action_type') == 'vente_annule' ? 'selected' : '' }}>Vente annulée
              </option>
              <option value="caisse_ouverture" {{ request('action_type') == 'caisse_ouverture' ? 'selected' : '' }}>
                Ouverture caisse</option>
              <option value="caisse_fermeture" {{ request('action_type') == 'caisse_fermeture' ? 'selected' : '' }}>
                Fermeture caisse</option>
              <option value="transfert_demande" {{ request('action_type') == 'transfert_demande' ? 'selected' : '' }}>
                Transfert demandé</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="input-field">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="input-field">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Anomalies</label>
            <select name="is_anomaly" class="input-field">
              <option value="">Toutes</option>
              <option value="1" {{ request('is_anomaly') == '1' ? 'selected' : '' }}>Anomalies uniquement</option>
              <option value="0" {{ request('is_anomaly') == '0' ? 'selected' : '' }}>Normales uniquement</option>
            </select>
          </div>
        </div>

        <div class="flex space-x-3">
          <button type="submit" class="btn-primary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            Filtrer
          </button>

          @if(request()->hasAny(['user_id', 'point_id', 'action_type', 'date_debut', 'date_fin', 'is_anomaly']))
            <a href="{{ route('analyses.logs') }}" class="btn-secondary">
              Réinitialiser
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- Tableau -->
    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Point</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($logs as $log)
              <tr class="hover:bg-gray-50 {{ $log->is_anomaly ? 'bg-red-50' : '' }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div>{{ $log->created_at->format('d/m/Y') }}</div>
                  <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <a href="{{ route('analyses.vendeur', $log->user) }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800">
                    {{ $log->user->name }}
                  </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $log->action_label }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  {{ $log->description }}
                  @if($log->is_anomaly)
                    <div class="text-xs text-red-600 mt-1">⚠️ {{ $log->anomaly_reason }}</div>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $log->pointVente->nom }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if($log->is_anomaly)
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->severity_badge }}">
                      {{ $log->severity_label }}
                    </span>
                  @else
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      Normal
                    </span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                  </svg>
                  <p class="mt-2 text-sm text-gray-500">Aucune activité trouvée</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $logs->appends(request()->query())->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection