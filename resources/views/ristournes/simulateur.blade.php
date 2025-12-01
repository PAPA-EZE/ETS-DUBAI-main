@extends('layouts.app')

@section('title', 'Simulateur de Ristournes')
@section('page-title', 'Simulateur de Ristournes')

@section('content')
  <div class="fade-in" x-data="simulateur()">
    <x-breadcrumb :items="[
      ['label' => 'Ristournes', 'url' => route('ristournes.index')],
      ['label' => 'Simulateur', 'url' => null],
    ]" />

    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Simulateur de Ristournes</h2>
      <p class="text-gray-600 mt-1">Calculez vos ristournes potentielles et optimisez vos achats</p>
    </div>

    @if($fournisseurs->count() == 0)
      <x-alert type="warning">
        Aucun fournisseur n'a de barème de ristourne configuré.
      </x-alert>
    @else
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire de simulation -->
        <div class="lg:col-span-1">
          <div class="card sticky top-6">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Paramètres</h3>
            </div>
            <div class="p-6">
              <form @submit.prevent="calculer">
                <!-- Fournisseur -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fournisseur
                  </label>
                  <select x-model="fournisseurId" required class="input-field">
                    <option value="">Sélectionnez...</option>
                    @foreach($fournisseurs as $fournisseur)
                      <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Montant -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Montant d'achats prévu (HT)
                  </label>
                  <div class="relative">
                    <input type="number" x-model.number="montantAchats" min="0" step="10000" required
                      class="input-field pr-20" placeholder="0">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500 text-sm">FCFA</span>
                    </div>
                  </div>
                </div>

                <!-- Montants rapides -->
                <div class="mb-6">
                  <p class="text-sm font-medium text-gray-700 mb-2">Montants rapides</p>
                  <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="montantAchats = 500000" class="btn-secondary btn-sm">500K</button>
                    <button type="button" @click="montantAchats = 1000000" class="btn-secondary btn-sm">1M</button>
                    <button type="button" @click="montantAchats = 2500000" class="btn-secondary btn-sm">2.5M</button>
                    <button type="button" @click="montantAchats = 5000000" class="btn-secondary btn-sm">5M</button>
                  </div>
                </div>

                <!-- Bouton -->
                <button type="submit" class="w-full btn-primary" :disabled="!fournisseurId || !montantAchats">
                  <span x-show="!loading">Calculer</span>
                  <span x-show="loading">Calcul en cours...</span>
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Résultats -->
        <div class="lg:col-span-2">
          <!-- Message initial -->
          <div x-show="!resultats && !loading" class="card p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <p class="mt-4 text-lg font-medium text-gray-900">Simulez vos Ristournes</p>
            <p class="mt-2 text-sm text-gray-500">Sélectionnez un fournisseur et entrez un montant pour voir les résultats
            </p>
          </div>

          <!-- Résultats de la simulation -->
          <div x-show="resultats" class="space-y-6">
            <!-- Résultat principal -->
            <div class="card">
              <div class="p-6">
                <div class="text-center">
                  <p class="text-sm text-gray-600 mb-2">Ristourne estimée</p>
                  <p class="text-5xl font-bold text-success-600" x-text="formatMontant(resultats?.montant_ristourne || 0)">
                  </p>
                  <p class="text-lg text-gray-500 mt-2">
                    Taux appliqué : <span class="font-semibold text-primary-600"
                      x-text="(resultats?.taux_applique || 0) + '%'"></span>
                  </p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                  <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                      <p class="text-sm text-gray-600">Montant HT</p>
                      <p class="text-xl font-bold text-gray-900" x-text="formatMontant(resultats?.montant_achats || 0)"></p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-600">Net à payer</p>
                      <p class="text-xl font-bold text-primary-600"
                        x-text="formatMontant((resultats?.montant_achats || 0) - (resultats?.montant_ristourne || 0))"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Barème applicable -->
            <div x-show="resultats?.bareme_applicable" class="card">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Barème Applicable</h3>
              </div>
              <div class="p-6">
                <div class="bg-primary-50 border-l-4 border-primary-500 p-4 rounded">
                  <p class="font-semibold text-primary-900" x-text="resultats?.bareme_applicable?.nom"></p>
                  <div class="mt-2 text-sm text-primary-800">
                    <p>
                      Seuil minimum : <span class="font-medium"
                        x-text="formatMontant(resultats?.bareme_applicable?.seuil_min)"></span>
                    </p>
                    <p x-show="resultats?.bareme_applicable?.seuil_max">
                      Seuil maximum : <span class="font-medium"
                        x-text="formatMontant(resultats?.bareme_applicable?.seuil_max)"></span>
                    </p>
                    <p class="mt-1">
                      Taux : <span class="font-bold text-lg" x-text="resultats?.bareme_applicable?.taux + '%'"></span>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Aucun barème -->
            <div x-show="!resultats?.bareme_applicable" class="card">
              <div class="p-6">
                <div class="bg-warning-50 border-l-4 border-warning-500 p-4 rounded">
                  <p class="font-semibold text-warning-900">Aucune ristourne applicable</p>
                  <p class="text-sm text-warning-800 mt-1">
                    Le montant ne correspond à aucun barème configuré pour ce fournisseur.
                  </p>
                </div>
              </div>
            </div>

            <!-- Prochains paliers -->
            <div x-show="resultats?.prochains_paliers && resultats.prochains_paliers.length > 0" class="card">
              <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Optimisez vos Achats</h3>
                <p class="text-sm text-gray-600 mt-1">Augmentez vos achats pour atteindre un meilleur taux</p>
              </div>
              <div class="p-6">
                <div class="space-y-4">
                  <template x-for="palier in resultats?.prochains_paliers" :key="palier.seuil">
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 transition-colors">
                      <div class="flex items-start justify-between">
                        <div class="flex-1">
                          <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold text-primary-600" x-text="palier.taux + '%'"></span>
                            <span class="text-sm text-gray-500">de ristourne</span>
                          </div>
                          <p class="text-sm text-gray-600 mt-2">
                            Seuil à atteindre : <span class="font-medium text-gray-900"
                              x-text="formatMontant(palier.seuil)"></span>
                          </p>
                          <p class="text-sm text-primary-600 font-medium mt-1">
                            Ajoutez <span x-text="formatMontant(palier.a_ajouter)"></span> d'achats
                          </p>
                        </div>
                        <div class="text-right ml-4">
                          <p class="text-sm text-gray-600">Gain potentiel</p>
                          <p class="text-lg font-bold text-success-600" x-text="formatMontant(palier.gain_supplementaire)">
                          </p>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  @push('scripts')
    <script>
      function simulateur() {
        return {
          fournisseurId: '',
          montantAchats: 0,
          resultats: null,
          loading: false,

          async calculer() {
            if (!this.fournisseurId || !this.montantAchats) {
              return;
            }

            this.loading = true;

            try {
              const response = await fetch('{{ route("ristournes.simulation") }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                  'Accept': 'application/json'
                },
                body: JSON.stringify({
                  fournisseur_id: this.fournisseurId,
                  montant_achats: this.montantAchats
                })
              });

              if (response.ok) {
                this.resultats = await response.json();
              } else {
                alert('Erreur lors du calcul de la simulation');
              }
            } catch (error) {
              console.error('Erreur:', error);
              alert('Erreur lors du calcul de la simulation');
            } finally {
              this.loading = false;
            }
          },

          formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR').format(Math.round(montant)) + ' FCFA';
          }
        }
      }
    </script>
  @endpush
@endsection