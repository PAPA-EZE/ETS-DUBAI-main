@extends('layouts.app')

@section('title', 'Réception Commande ' . $commande->numero_commande)
@section('page-title', 'Réception Livraison')

@section('content')
	<div class="fade-in">
		<!-- Breadcrumb -->
		<x-breadcrumb :items="[
			['label' => 'Commandes', 'url' => route('commandes.index'), 'icon' => '<svg class=\'w-4 h-4 mr-2\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z\' /></svg>'],
			['label' => $commande->numero_commande, 'url' => route('commandes.show', $commande)],
			['label' => 'Réception livraison'],
		]" />

		<!-- Header -->
		<div class="page-header">
			<div>
				<h1 class="text-2xl font-bold text-gray-900">Réception Livraison</h1>
				<p class="mt-1 text-sm text-gray-600">
					Commande {{ $commande->numero_commande }} - {{ $commande->fournisseur->nom }}
				</p>
			</div>
			<div class="mt-4 sm:mt-0">
				<x-status-badge :status="$commande->statut_text" :type="match ($commande->statut_color) {
			'green' => 'success',
			'red' => 'danger',
			'yellow', 'orange' => 'warning',
			'blue', 'purple' => 'info',
			default => 'default'
		}" />
			</div>
		</div>

		<!-- Alerte informative -->
		<x-alert type="info" class="mb-6">
			<strong>Instructions:</strong> Saisissez la quantité réellement livrée pour chaque article.
			Le stock sera automatiquement mis à jour lors de la validation.
		</x-alert>

		<!-- Formulaire de réception -->
		<form method="POST" action="{{ route('commandes.enregistrer-livraison', $commande) }}" x-data="livraisonForm()">
			@csrf

			<div class="space-y-6">
				<!-- Articles à réceptionner -->
				<div class="card">
					<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
						<h3 class="text-lg font-medium text-gray-900">Articles à réceptionner</h3>
					</div>

					<div class="p-6">
						<div class="space-y-4">
							@foreach($commande->items as $item)
								<div
									class="border border-gray-200 rounded-lg p-4 {{ $item->est_livree_complete ? 'bg-green-50 border-green-200' : 'bg-white' }}">
									<div class="grid grid-cols-1 gap-4 sm:grid-cols-12 items-center">
										<!-- Produit -->
										<div class="sm:col-span-5">
											<div class="flex items-center">
												<div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
													<svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
													</svg>
												</div>
												<div class="ml-4">
													<div class="text-sm font-medium text-gray-900">{{ $item->produit->nom }}</div>
													<div class="text-sm text-gray-500">{{ $item->produit->reference }}</div>
												</div>
											</div>
										</div>

										<!-- Quantité commandée -->
										<div class="sm:col-span-2 text-center">
											<label class="block text-xs font-medium text-gray-500 mb-1">Commandé</label>
											<div class="text-lg font-bold text-gray-900">{{ $item->quantite_commandee }}</div>
											<div class="text-xs text-gray-500">{{ $item->produit->unite }}</div>
										</div>

										<!-- Déjà livré -->
										<div class="sm:col-span-2 text-center">
											<label class="block text-xs font-medium text-gray-500 mb-1">Déjà livré</label>
											<div class="text-lg font-bold {{ $item->quantite_livree > 0 ? 'text-green-600' : 'text-gray-400' }}">
												{{ $item->quantite_livree }}
											</div>
											<div class="text-xs text-gray-500">{{ $item->produit->unite }}</div>
										</div>

										<!-- Quantité à livrer -->
										<div class="sm:col-span-2">
											<label class="block text-xs font-medium text-gray-700 mb-1">
												Livrer maintenant
												@if($item->quantite_restante <= 0)
													<span class="text-green-600">(Complet)</span>
												@endif
											</label>
											<input type="number" name="items[{{ $item->id }}][quantite_livree]"
												value="{{ old('items.' . $item->id . '.quantite_livree', $item->quantite_restante > 0 ? $item->quantite_restante : 0) }}"
												min="0" max="{{ $item->quantite_restante }}" @input="calculerTotaux()"
												class="input-field text-center {{ $item->quantite_restante <= 0 ? 'bg-gray-100' : '' }}" {{ $item->quantite_restante <= 0 ? 'readonly' : '' }}>
											@if($item->quantite_restante > 0)
												<div class="text-xs text-gray-500 mt-1 text-center">
													Max: {{ $item->quantite_restante }}
												</div>
											@endif
										</div>

										<!-- Statut -->
										<div class="sm:col-span-1 text-center">
											@if($item->est_livree_complete)
												<span
													class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
													✓ Complet
												</span>
											@elseif($item->quantite_livree > 0)
												<span
													class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
													Partiel
												</span>
											@else
												<span
													class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
													En attente
												</span>
											@endif
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>

				<!-- Résumé de la livraison -->
				<div class="card p-6">
					<h3 class="text-lg font-medium text-gray-900 mb-4">Résumé de la livraison</h3>

					<div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
						<div class="text-center p-4 bg-gray-50 rounded-lg">
							<div class="text-sm font-medium text-gray-500">Articles commandés</div>
							<div class="text-2xl font-bold text-gray-900">{{ $commande->total_articles }}</div>
						</div>

						<div class="text-center p-4 bg-blue-50 rounded-lg">
							<div class="text-sm font-medium text-blue-600">Déjà livrés</div>
							<div class="text-2xl font-bold text-blue-900">{{ $commande->total_articles_livres }}</div>
						</div>

						<div class="text-center p-4 bg-green-50 rounded-lg">
							<div class="text-sm font-medium text-green-600">Livraison actuelle</div>
							<div class="text-2xl font-bold text-green-900" x-text="totalLivraison">0</div>
						</div>
					</div>

					<!-- Progression -->
					<div class="mt-6">
						<div class="flex justify-between text-sm text-gray-600 mb-2">
							<span>Progression globale</span>
							<span x-text="progressionTexte">{{ $commande->progression_livraison }}%</span>
						</div>
						<div class="w-full bg-gray-200 rounded-full h-3">
							<div class="bg-green-500 h-3 rounded-full transition-all duration-300"
								x-bind:style="`width: ${progression}%`"></div>
						</div>
					</div>
				</div>

				<!-- Notes de livraison -->
				<div class="card p-6">
					<h3 class="text-lg font-medium text-gray-900 mb-4">Notes de livraison</h3>
					<textarea name="notes_livraison" rows="4" class="input-field"
						placeholder="Notes sur cette livraison (état des marchandises, problèmes constatés, etc.)">{{ old('notes_livraison') }}</textarea>
				</div>

				<!-- Actions -->
				<div class="flex justify-end space-x-3">
					<a href="{{ route('commandes.show', $commande) }}" class="btn-secondary">
						<svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
						</svg>
						Annuler
					</a>

					<button type="submit" class="btn-success">
						<svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round"
								d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						Valider la réception
					</button>
				</div>
			</div>
		</form>
	</div>
@endsection

@push('scripts')
	<script>
		function livraisonForm() {
			return {
				totalLivraison: 0,
				totalCommande: {{ $commande->total_articles }},
				totalDejaLivre: {{ $commande->total_articles_livres }},

				get progression() {
					const total = this.totalDejaLivre + this.totalLivraison;
					return this.totalCommande > 0 ? Math.round((total / this.totalCommande) * 100) : 0;
				},

				get progressionTexte() {
					return this.progression + '%';
				},

				calculerTotaux() {
					const inputs = document.querySelectorAll('input[name*="quantite_livree"]');
					this.totalLivraison = 0;

					inputs.forEach(input => {
						this.totalLivraison += parseInt(input.value) || 0;
					});
				},

				init() {
					this.calculerTotaux();
				}
			}
		}
	</script>
@endpush