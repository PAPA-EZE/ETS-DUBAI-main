@extends('layouts.app')

@section('title', 'Rapports & Analytics')
@section('page-title', 'Rapports & Analytics')

@section('content')
  <div class="fade-in">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Rapports & Analytics</h2>
      <p class="text-gray-600 mt-1">Analysez vos performances et générez des rapports détaillés</p>
    </div>

    <!-- Grille des rapports disponibles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Rapport Ventes -->
      <a href="{{ route('rapports.ventes', ['date_debut' => now()->startOfMonth()->format('Y-m-d'), 'date_fin' => now()->format('Y-m-d')]) }}"
        class="card p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="p-3 bg-success-100 rounded-lg">
              <svg class="h-8 w-8 text-success-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4 flex-1">
            <h3 class="text-lg font-semibold text-gray-900">Rapport de Ventes</h3>
            <p class="text-sm text-gray-600 mt-1">
              Analysez vos ventes par période, produit et client
            </p>
            <div class="mt-3 flex items-center text-primary-600 text-sm font-medium">
              <span>Voir le rapport</span>
              <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
        </div>
      </a>

      <!-- Rapport Stocks -->
      <a href="{{ route('rapports.stocks') }}" class="card p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="p-3 bg-warning-100 rounded-lg">
              <svg class="h-8 w-8 text-warning-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
              </svg>
            </div>
          </div>
          <div class="ml-4 flex-1">
            <h3 class="text-lg font-semibold text-gray-900">État des Stocks</h3>
            <p class="text-sm text-gray-600 mt-1">
              Situation actuelle et valorisation des stocks
            </p>
            <div class="mt-3 flex items-center text-primary-600 text-sm font-medium">
              <span>Voir le rapport</span>
              <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
        </div>
      </a>

      <!-- Rapport Achats -->
      <a href="{{ route('rapports.achats', ['date_debut' => now()->startOfMonth()->format('Y-m-d'), 'date_fin' => now()->format('Y-m-d')]) }}"
        class="card p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="p-3 bg-info-100 rounded-lg">
              <svg class="h-8 w-8 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
              </svg>
            </div>
          </div>
          <div class="ml-4 flex-1">
            <h3 class="text-lg font-semibold text-gray-900">Rapport d'Achats</h3>
            <p class="text-sm text-gray-600 mt-1">
              Suivi des commandes fournisseurs et achats
            </p>
            <div class="mt-3 flex items-center text-primary-600 text-sm font-medium">
              <span>Voir le rapport</span>
              <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
        </div>
      </a>

      <!-- Tableau de bord financier -->
      <a href="{{ route('rapports.financier') }}" class="card p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="p-3 bg-primary-100 rounded-lg">
              <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
              </svg>
            </div>
          </div>
          <div class="ml-4 flex-1">
            <h3 class="text-lg font-semibold text-gray-900">Tableau de Bord Financier</h3>
            <p class="text-sm text-gray-600 mt-1">
              Vue d'ensemble de la santé financière
            </p>
            <div class="mt-3 flex items-center text-primary-600 text-sm font-medium">
              <span>Voir le rapport</span>
              <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Informations -->
    <div class="mt-8 p-6 bg-info-50 rounded-lg border border-info-200">
      <h3 class="text-sm font-semibold text-info-900 mb-2">À propos des rapports</h3>
      <ul class="text-sm text-info-800 space-y-1">
        <li>• Les rapports sont générés en temps réel à partir de vos données</li>
        <li>• Vous pouvez exporter les rapports en PDF pour les archiver</li>
        <li>• Les données sont filtrables par période pour des analyses précises</li>
      </ul>
    </div>
  </div>
@endsection