@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="fade-in">
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="card p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-semibold text-gray-900">Bienvenue, {{ Auth::user()->name }}!</h2>
                        <p class="text-gray-600">Voici un aperçu de votre dépôt de boissons aujourd'hui.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes importantes -->
        @if($stats['commandes_en_retard'] > 0)
            <x-alert type="warning" class="mb-6" dismissible>
                <strong>{{ $stats['commandes_en_retard'] }}</strong> commande(s) en retard nécessitent votre attention.
                <a href="{{ route('commandes.index', ['periode' => 'en_retard']) }}" class="underline font-medium ml-2">
                    Voir les commandes en retard
                </a>
            </x-alert>
        @endif

        @if($stats['stock_bas'] > 0)
            <x-alert type="warning" class="mb-6" dismissible>
                <strong>{{ $stats['stock_bas'] }}</strong> produit(s) ont un stock faible ou sont en rupture.
                <a href="{{ route('produits.index', ['stock_statut' => 'faible']) }}" class="underline font-medium ml-2">
                    Voir les produits concernés
                </a>
            </x-alert>
        @endif

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Chiffre d'affaires du jour -->
            <x-stat-card title="CA Aujourd'hui" :value="number_format($stats['ca_jour'], 0, ',', ' ') . ' FCFA'"
                :subtitle="($stats['ca_jour_evolution'] >= 0 ? '+' : '') . $stats['ca_jour_evolution'] . '% vs hier'"
                icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                color="success" />

            <!-- Ventes du jour -->
            <x-stat-card title="Ventes Aujourd'hui" :value="$stats['ventes_jour']" :subtitle="$stats['articles_vendus'] . ' articles'"
                icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.001 3.001 0 013.75-.614A2.993 2.993 0 003.75 8.25c-.896 0-1.7.393-2.25 1.016A2.993 2.993 0 003.75 9.75c-.896 0-1.7.393-2.25 1.016A3.001 3.001 0 000 9.349m16.5 0a3.001 3.001 0 00-3.75-.614A2.993 2.993 0 0012 8.25c-.896 0-1.7.393-2.25 1.016A2.993 2.993 0 007.5 8.25c-.896 0-1.7.393-2.25 1.016A3.001 3.001 0 000 9.349" /></svg>'
                color="primary" />

            <!-- Commandes en cours -->
            <x-stat-card title="Commandes en Cours" :value="$stats['commandes_en_cours']"
                :subtitle="$stats['commandes_en_retard'] > 0 ? $stats['commandes_en_retard'] . ' en retard' : 'Aucun retard'"
                icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" /></svg>'
                color="{{ $stats['commandes_en_retard'] > 0 ? 'warning' : 'info' }}" />

            <!-- Stock bas -->
            <x-stat-card title="Alertes Stock" :value="$stats['stock_bas']" subtitle="Produits en rupture/faible"
                icon='<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>'
                color="{{ $stats['stock_bas'] > 0 ? 'warning' : 'success' }}" />
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Évolution du CA -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution du Chiffre d'Affaires</h3>
                <div class="relative h-64">
                    <canvas id="caChart"></canvas>
                </div>
            </div>

            <!-- Répartition des ventes -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ventes par Catégorie</h3>
                <div class="relative h-64">
                    <canvas id="categoriesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Dernières ventes -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Dernières Ventes</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($dernieres_ventes ?? [] as $vente)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                            <span class="text-xs font-medium text-primary-600">#{{ $vente->id }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $vente->client_nom ?? 'Client anonyme' }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $vente->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $vente->items_count }} articles</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-gray-500 text-sm mt-2">Aucune vente aujourd'hui</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières commandes -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Commandes Récentes</h3>
                            <a href="{{ route('commandes.index') }}"
                                class="text-sm text-primary-600 hover:text-primary-800">Voir tout</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($dernieres_commandes ?? [] as $commande)
                                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center">
                                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                <a href="{{ route('commandes.show', $commande) }}"
                                                                    class="hover:text-primary-600">
                                                                    {{ $commande->numero_commande }}
                                                                </a>
                                                            </p>
                                                            <p class="text-xs text-gray-500">{{ $commande->fournisseur->nom }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <x-status-badge :status="$commande->statut_text" :type="match ($commande->statut_color) {
                                    'green' => 'success',
                                    'red' => 'danger',
                                    'yellow', 'orange' => 'warning',
                                    'blue', 'purple' => 'info',
                                    default => 'default'
                                }" />
                                                        <p class="text-xs text-gray-500 mt-1">{{ $commande->created_at->format('d/m') }}</p>
                                                    </div>
                                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-gray-500 text-sm mt-2">Aucune commande récente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <!-- Actions rapides -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Actions Rapides</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('ventes.create') }}" class="btn-primary w-full">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouvelle Vente
                        </a>

                        <a href="{{ route('commandes.create') }}" class="btn-secondary w-full">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z" />
                            </svg>
                            Nouvelle Commande
                        </a>

                        <a href="{{ route('fournisseurs.create') }}" class="btn-secondary w-full">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                            Ajouter Fournisseur
                        </a>

                        <a href="{{ route('produits.create') }}" class="btn-secondary w-full">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau Produit
                        </a>

                        <a href="{{ route('rapports.stocks') }}"
                            class="sidebar-item {{ request()->routeIs('rapports.stocks') ? 'active' : '' }}">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            Rapport du Jour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configuration Chart.js
            Chart.defaults.font.family = 'Inter';
            Chart.defaults.color = '#6B7280';

            // Données par défaut
            const defaultCaLabels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
            const defaultCaValues = [150000, 180000, 220000, 195000, 175000, 240000, 210000];
            const defaultCategoriesLabels = ['Bières', 'Sodas', 'Eaux', 'Autres'];
            const defaultCategoriesValues = [45, 30, 15, 10];

            // Graphique CA
            const caCtx = document.getElementById('caChart').getContext('2d');
            new Chart(caCtx, {
                type: 'line',
                data: {
                    labels: @json($chart_data['ca_labels'] ?? null) || defaultCaLabels,
                    datasets: [{
                        label: 'Chiffre d\'Affaires (FCFA)',
                        data: @json($chart_data['ca_values'] ?? null) || defaultCaValues,
                        borderColor: '#3B82F6',
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
                                    return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                                }
                            }
                        }
                    }
                }
            });

            // Graphique Catégories
            const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
            new Chart(categoriesCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($chart_data['categories_labels'] ?? null) || defaultCategoriesLabels,
                    datasets: [{
                        data: @json($chart_data['categories_values'] ?? null) || defaultCategoriesValues,
                        backgroundColor: [
                            '#3B82F6',
                            '#22C55E',
                            '#F59E0B',
                            '#EF4444'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush