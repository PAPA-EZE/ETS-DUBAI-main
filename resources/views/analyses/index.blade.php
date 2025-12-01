@extends('layouts.app')

@section('title', 'Analyse Vendeurs')
@section('page-title', 'Analyse & Surveillance')

@section('content')
  <div class="fade-in">
    <!-- En-tête -->
    <div class="page-header">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Analyse des Vendeurs</h1>
        <p class="mt-1 text-sm text-gray-600">Surveillez les activités et détectez les anomalies</p>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-blue-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Actions</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_actions']) }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-yellow-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Anomalies</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_anomalies'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-red-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Critiques</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['anomalies_critiques'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-green-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Vendeurs Actifs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['vendeurs_actifs'] }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card p-6 mb-6">
      <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
          <select name="periode" class="input-field">
            <option value="7" {{ $periode == 7 ? 'selected' : '' }}>7 derniers jours</option>
            <option value="15" {{ $periode == 15 ? 'selected' : '' }}>15 derniers jours</option>
            <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 derniers jours</option>
            <option value="90" {{ $periode == 90 ? 'selected' : '' }}>3 derniers mois</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Vendeur</label>
          <select name="user_id" class="input-field">
            <option value="">Tous les vendeurs</option>
            @foreach($vendeurs as $vendeur)
              <option value="{{ $vendeur->id }}" {{ $userId == $vendeur->id ? 'selected' : '' }}>
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
              <option value="{{ $point->id }}" {{ $pointId == $point->id ? 'selected' : '' }}>
                {{ $point->nom }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="flex items-end">
          <button type="submit" class="btn-primary w-full">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            Filtrer
          </button>
        </div>
      </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Anomalies récentes -->
      <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900">Anomalies Récentes</h3>
          <a href="{{ route('analyses.logs') }}?is_anomaly=1" class="text-sm text-blue-600 hover:text-blue-800">
            Voir tout →
          </a>
        </div>
        <div class="p-6">
          @if($anomaliesRecentes->count() > 0)
            <div class="space-y-4">
              @foreach($anomaliesRecentes as $anomalie)
                <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                  <div class="flex-shrink-0 mr-3">
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $anomalie->severity_badge }}">
                      {{ $anomalie->severity_label }}
                    </span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $anomalie->user->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $anomalie->anomaly_reason }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                      {{ $anomalie->created_at->format('d/m/Y à H:i') }} • {{ $anomalie->pointVente->nom }}
                    </p>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Aucune anomalie détectée</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Top vendeurs -->
      <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Top Vendeurs (Activité)</h3>
        </div>
        <div class="p-6">
          @if($topVendeurs->count() > 0)
            <div class="space-y-3">
              @foreach($topVendeurs as $index => $item)
                <a href="{{ route('analyses.vendeur', $item->user) }}"
                  class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                  <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                    <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $item->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $item->user->pointVente->nom }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">{{ $item->total }}</p>
                    <p class="text-xs text-gray-500">actions</p>
                  </div>
                </a>
              @endforeach
            </div>
          @else
            <p class="text-center text-sm text-gray-500 py-8">Aucune donnée</p>
          @endif
        </div>
      </div>
    </div>

    <!-- Types d'actions -->
    <div class="card mt-6">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Répartition par Type d'Action</h3>
      </div>
      <div class="p-6">
        <div class="h-64">
          <canvas id="chartActions"></canvas>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
      const ctx = document.getElementById('chartActions').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($actionsByType->pluck('action_type')->map(fn($type) => ucfirst(str_replace('_', ' ', $type)))) !!},
          datasets: [{
            label: 'Nombre d\'actions',
            data: {!! json_encode($actionsByType->pluck('total')) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 2,
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    </script>
  @endpush
@endsection