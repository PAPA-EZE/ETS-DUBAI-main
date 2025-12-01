@extends('layouts.app')

@section('title', 'Tableau de Bord Financier')
@section('page-title', 'Tableau de Bord Financier')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Rapports', 'url' => route('rapports.index')],
      ['label' => 'Financier', 'url' => null],
    ]" />

    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Tableau de Bord Financier</h2>
      <p class="text-gray-600 mt-1">Vue d'ensemble de la santé financière de l'entreprise</p>
    </div>

    <!-- Filtres de période -->
    <div class="card p-6 mb-6">
      <form method="GET" action="{{ route('rapports.financier') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mois</label>
          <select name="mois" class="input-field">
            @for($m = 1; $m <= 12; $m++)
              <option value="{{ $m }}" {{ request('mois', now()->month) == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
              </option>
            @endfor
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
          <select name="annee" class="input-field">
            @for($y = now()->year; $y >= now()->year - 3; $y--)
              <option value="{{ $y }}" {{ request('annee', now()->year) == $y ? 'selected' : '' }}>
                {{ $y }}
              </option>
            @endfor
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit" class="btn-primary w-full">Afficher</button>
        </div>
      </form>
    </div>

    <!-- KPIs Financiers -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
      <div class="card p-6 bg-success-50 border-l-4 border-success-500">
        <p class="text-sm font-medium text-success-900">Chiffre d'Affaires</p>
        <p class="text-3xl font-bold text-success-600 mt-2">{{ number_format($stats['caVentes'], 0, ',', ' ') }}</p>
        <p class="text-sm text-success-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-danger-50 border-l-4 border-danger-500">
        <p class="text-sm font-medium text-danger-900">Achats</p>
        <p class="text-3xl font-bold text-danger-600 mt-2">{{ number_format($stats['montantAchats'], 0, ',', ' ') }}</p>
        <p class="text-sm text-danger-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-primary-50 border-l-4 border-primary-500">
        <p class="text-sm font-medium text-primary-900">Marge Brute</p>
        <p class="text-3xl font-bold text-primary-600 mt-2">{{ number_format($stats['margeBrute'], 0, ',', ' ') }}</p>
        <p class="text-sm text-primary-700 mt-1">{{ number_format($stats['tauxMarge'], 2) }}% du CA</p>
      </div>

      <div class="card p-6 bg-info-50 border-l-4 border-info-500">
        <p class="text-sm font-medium text-info-900">Crédit Utilisé</p>
        <p class="text-3xl font-bold text-info-600 mt-2">{{ number_format($stats['creditUtilise'], 0, ',', ' ') }}</p>
        <p class="text-sm text-info-700 mt-1">FCFA</p>
      </div>

      <div class="card p-6 bg-warning-50 border-l-4 border-warning-500">
        <p class="text-sm font-medium text-warning-900">Crédit Disponible</p>
        <p class="text-3xl font-bold text-warning-600 mt-2">{{ number_format($stats['creditDisponible'], 0, ',', ' ') }}
        </p>
        <p class="text-sm text-warning-700 mt-1">FCFA</p>
      </div>

      <div
        class="card p-6 {{ $stats['margeBrute'] >= 0 ? 'bg-success-50 border-success-500' : 'bg-danger-50 border-danger-500' }} border-l-4">
        <p class="text-sm font-medium {{ $stats['margeBrute'] >= 0 ? 'text-success-900' : 'text-danger-900' }}">Résultat
        </p>
        <p class="text-3xl font-bold {{ $stats['margeBrute'] >= 0 ? 'text-success-600' : 'text-danger-600' }} mt-2">
          {{ $stats['margeBrute'] >= 0 ? '+' : '' }}{{ number_format($stats['margeBrute'], 0, ',', ' ') }}
        </p>
        <p class="text-sm {{ $stats['margeBrute'] >= 0 ? 'text-success-700' : 'text-danger-700' }} mt-1">
          {{ $stats['margeBrute'] >= 0 ? 'Bénéfice' : 'Perte' }}
        </p>
      </div>
    </div>

    <!-- Graphique d'évolution -->
    <div class="card mb-6">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Évolution sur 12 Mois</h3>
      </div>
      <div class="p-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mois</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ventes</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Achats</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Marge</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">%</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($evolutionMensuelle as $mois)
                @php
                  $marge = $mois['ventes'] - $mois['achats'];
                  $tauxMarge = $mois['ventes'] > 0 ? ($marge / $mois['ventes']) * 100 : 0;
                @endphp
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $mois['mois'] }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-success-600">
                    {{ number_format($mois['ventes'], 0, ',', ' ') }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-danger-600">
                    {{ number_format($mois['achats'], 0, ',', ' ') }}
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium {{ $marge >= 0 ? 'text-primary-600' : 'text-danger-600' }}">
                    {{ $marge >= 0 ? '+' : '' }}{{ number_format($marge, 0, ',', ' ') }}
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap text-right text-sm {{ $tauxMarge >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                    {{ number_format($tauxMarge, 1) }}%
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Indicateurs de performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Indicateurs Clés</h3>
        </div>
        <div class="p-6 space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-600">Taux de Marge Brute</span>
            <span
              class="text-lg font-bold {{ $stats['tauxMarge'] >= 20 ? 'text-success-600' : ($stats['tauxMarge'] >= 10 ? 'text-warning-600' : 'text-danger-600') }}">
              {{ number_format($stats['tauxMarge'], 2) }}%
            </span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-600">CA Moyen Mensuel (12 mois)</span>
            <span class="text-lg font-bold text-primary-600">
              {{ number_format(collect($evolutionMensuelle)->avg('ventes'), 0, ',', ' ') }} FCFA
            </span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-600">Achats Moyens Mensuels (12 mois)</span>
            <span class="text-lg font-bold text-info-600">
              {{ number_format(collect($evolutionMensuelle)->avg('achats'), 0, ',', ' ') }} FCFA
            </span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Analyse</h3>
        </div>
        <div class="p-6">
          @if($stats['tauxMarge'] >= 20)
            <div class="p-4 bg-success-50 rounded-lg border border-success-200 mb-4">
              <p class="text-sm font-medium text-success-900">✅ Excellente rentabilité</p>
              <p class="text-xs text-success-700 mt-1">Votre taux de marge est sain et vous permet une bonne croissance.</p>
            </div>
          @elseif($stats['tauxMarge'] >= 10)
            <div class="p-4 bg-warning-50 rounded-lg border border-warning-200 mb-4">
              <p class="text-sm font-medium text-warning-900">⚠️ Marge correcte</p>
              <p class="text-xs text-warning-700 mt-1">Votre marge est acceptable mais peut être optimisée.</p>
            </div>
          @else
            <div class="p-4 bg-danger-50 rounded-lg border border-danger-200 mb-4">
              <p class="text-sm font-medium text-danger-900">❌ Marge faible</p>
              <p class="text-xs text-danger-700 mt-1">Attention, votre marge est trop faible. Revoyez vos prix de vente.</p>
            </div>
          @endif

          @if($stats['creditUtilise'] > 0)
            @php
              $totalCredit = $stats['creditUtilise'] + $stats['creditDisponible'];
              $tauxUtilisation = $totalCredit > 0 ? ($stats['creditUtilise'] / $totalCredit) * 100 : 0;
            @endphp
            <div
              class="p-4 {{ $tauxUtilisation >= 80 ? 'bg-warning-50 border-warning-200' : 'bg-info-50 border-info-200' }} rounded-lg border">
              <p class="text-sm font-medium {{ $tauxUtilisation >= 80 ? 'text-warning-900' : 'text-info-900' }}">
                {{ $tauxUtilisation >= 80 ? '⚠️' : 'ℹ️' }} Utilisation du crédit client
              </p>
              <p class="text-xs {{ $tauxUtilisation >= 80 ? 'text-warning-700' : 'text-info-700' }} mt-1">
                {{ number_format($tauxUtilisation, 1) }}% du crédit disponible est utilisé.
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection