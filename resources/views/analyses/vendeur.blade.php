@extends('layouts.app')

@section('title', 'Analyse - ' . $user->name)
@section('page-title', 'Analyse Vendeur')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li>
          <a href="{{ route('analyses.index') }}" class="text-gray-700 hover:text-blue-600">
            Analyses
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
            </svg>
            <span class="ml-1 text-gray-500">{{ $user->name }}</span>
          </div>
        </li>
      </ol>
    </nav>

    <!-- En-tête -->
    <div class="page-header">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
        <p class="mt-1 text-sm text-gray-600">
          {{ $user->email }} • {{ $user->pointVente->nom }}
        </p>
      </div>
      <div>
        <form method="GET" class="flex items-center space-x-2">
          <select name="periode" onchange="this.form.submit()" class="input-field">
            <option value="7" {{ $periode == 7 ? 'selected' : '' }}>7 jours</option>
            <option value="30" {{ $periode == 30 ? 'selected' : '' }}>30 jours</option>
            <option value="90" {{ $periode == 90 ? 'selected' : '' }}>90 jours</option>
          </select>
        </form>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-blue-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Ventes</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ventes'] }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-green-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">CA Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ca_total'], 0, ',', ' ') }}</p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="flex items-center">
          <div class="p-3 bg-purple-100 rounded-lg mr-4">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-600">Panier Moyen</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['panier_moyen'], 0, ',', ' ') }}</p>
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
            <p class="text-sm text-gray-600">Anomalies</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_anomalies'] }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Graphique performance -->
    <div class="card mb-6">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Performance Journalière</h3>
      </div>
      <div class="p-6">
        <div class="h-64">
          <canvas id="chartPerformance"></canvas>
        </div>
      </div>
    </div>

    <!-- Anomalies -->
    @if($anomalies->count() > 0)
      <div class="card mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Anomalies Détectées ({{ $anomalies->count() }})</h3>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            @foreach($anomalies as $anomalie)
              <div class="flex items-start p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex-shrink-0 mr-3">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $anomalie->severity_badge }}">
                    {{ $anomalie->severity_label }}
                  </span>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900">{{ $anomalie->anomaly_reason }}</p>
                  <p class="text-sm text-gray-600 mt-1">{{ $anomalie->description }}</p>
                  <p class="text-xs text-gray-500 mt-2">{{ $anomalie->created_at->format('d/m/Y à H:i:s') }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    <!-- Activités récentes -->
    <div class="card">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Activités Récentes</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Point</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($activites as $activite)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $activite->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $activite->action_label }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $activite->description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ $activite->pointVente->nom }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($activites->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $activites->links() }}
        </div>
      @endif
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
      const ctx = document.getElementById('chartPerformance').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: {!! json_encode($performanceJour->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
          datasets: [{
            label: 'CA Journalier',
            data: {!! json_encode($performanceJour->pluck('montant')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
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
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return value.toLocaleString() + ' FCFA';
                }
              }
            }
          }
        }
      });
    </script>
  @endpush
@endsection