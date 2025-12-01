@extends('layouts.app')

@section('title', 'Fermer la Caisse')
@section('page-title', 'Fermeture de Caisse')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Caisses', 'url' => route('caisses.index')],
      ['label' => $caisse->nom_caisse, 'url' => route('caisses.show', $caisse)],
      ['label' => 'Fermer', 'url' => null],
    ]" />

    <div class="max-w-4xl mx-auto">
      <!-- En-tête -->
      <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-warning-100 rounded-full mb-4">
          <svg class="h-8 w-8 text-warning-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Fermeture de Caisse</h2>
        <p class="text-gray-600 mt-2">{{ $caisse->nom_caisse }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Colonne gauche : Résumé -->
        <div class="space-y-6">
          <!-- Informations session -->
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Résumé de la Session</h3>
            </div>
            <div class="p-6 space-y-4">
              <div>
                <p class="text-sm text-gray-600">Ouverture</p>
                <p class="text-lg font-medium text-gray-900">{{ $caisse->date_ouverture->format('d/m/Y à H:i') }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Durée</p>
                <p class="text-lg font-medium text-gray-900">{{ $caisse->duree }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Responsable</p>
                <p class="text-lg font-medium text-gray-900">{{ $caisse->responsable->name }}</p>
              </div>
            </div>
          </div>

          <!-- Statistiques ventes -->
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Activité de la Journée</h3>
            </div>
            <div class="p-6 space-y-3">
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Nombre de ventes:</span>
                <span class="text-2xl font-bold text-primary-600">{{ $caisse->nombre_transactions }}</span>
              </div>
              <div class="flex justify-between items-center pt-3 border-t">
                <span class="text-gray-600">Total ventes:</span>
                <span class="text-xl font-bold text-success-600">{{ number_format($caisse->total_ventes, 0, ',', ' ') }}
                  FCFA</span>
              </div>
            </div>
          </div>

          <!-- Répartition paiements -->
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Répartition des Paiements</h3>
            </div>
            <div class="p-6 space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">Espèces:</span>
                <span class="font-medium text-success-600">{{ number_format($caisse->total_especes, 0, ',', ' ') }}
                  FCFA</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Mobile Money:</span>
                <span class="font-medium text-info-600">{{ number_format($caisse->total_mobile_money, 0, ',', ' ') }}
                  FCFA</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Crédit:</span>
                <span class="font-medium text-warning-600">{{ number_format($caisse->total_credit, 0, ',', ' ') }}
                  FCFA</span>
              </div>
            </div>
          </div>

          <!-- Calcul théorique -->
          <div class="card bg-info-50 border border-info-200">
            <div class="p-6 space-y-3">
              <h4 class="font-semibold text-info-900">Montant Théorique en Caisse</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-info-700">Fond d'ouverture:</span>
                  <span class="font-medium">{{ number_format($caisse->fond_ouverture, 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-info-700">+ Espèces reçues:</span>
                  <span class="font-medium">{{ number_format($caisse->total_especes, 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-info-300">
                  <span class="text-info-900 font-semibold">= Montant attendu:</span>
                  <span
                    class="text-lg font-bold text-info-900">{{ number_format($caisse->montant_theorique, 0, ',', ' ') }}
                    FCFA</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Colonne droite : Formulaire -->
        <div>
          <div class="card sticky top-6">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Comptage Final</h3>
            </div>
            <div class="p-6">
              <form action="{{ route('caisses.fermer.store', $caisse) }}" method="POST">
                @csrf

                <!-- Montant réel -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Montant réel en caisse <span class="text-danger-600">*</span>
                  </label>
                  <div class="relative">
                    <input type="number" name="montant_reel" value="{{ old('montant_reel', $caisse->montant_theorique) }}"
                      required min="0" step="100"
                      class="input-field pr-20 text-xl font-bold @error('montant_reel') border-danger-500 @enderror"
                      autofocus>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500">FCFA</span>
                    </div>
                  </div>
                  @error('montant_reel')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                  <p class="mt-2 text-xs text-gray-500">Comptez les espèces présentes dans la caisse</p>
                </div>

                <!-- Aide au comptage -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg" x-data="{ 
                                    billets: {
                                        '10000': 0, '5000': 0, '2000': 0, '1000': 0, '500': 0
                                    },
                                    get total() {
                                        return Object.keys(this.billets).reduce((sum, denomination) => {
                                            return sum + (parseInt(denomination) * this.billets[denomination]);
                                        }, 0);
                                    },
                                    updateInput() {
                                        document.querySelector('input[name=montant_reel]').value = this.total;
                                    }
                                }">
                  <h4 class="text-sm font-medium text-gray-700 mb-3">Aide au comptage (optionnel)</h4>
                  <div class="space-y-2">
                    <div class="flex items-center gap-3">
                      <span class="text-sm text-gray-600 w-24">10 000 FCFA:</span>
                      <input type="number" x-model.number="billets['10000']" @input="updateInput()" min="0"
                        class="input-field text-sm flex-1">
                      <span class="text-sm text-gray-500 w-32"
                        x-text="(billets['10000'] * 10000).toLocaleString('fr-FR')"></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="text-sm text-gray-600 w-24">5 000 FCFA:</span>
                      <input type="number" x-model.number="billets['5000']" @input="updateInput()" min="0"
                        class="input-field text-sm flex-1">
                      <span class="text-sm text-gray-500 w-32"
                        x-text="(billets['5000'] * 5000).toLocaleString('fr-FR')"></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="text-sm text-gray-600 w-24">2 000 FCFA:</span>
                      <input type="number" x-model.number="billets['2000']" @input="updateInput()" min="0"
                        class="input-field text-sm flex-1">
                      <span class="text-sm text-gray-500 w-32"
                        x-text="(billets['2000'] * 2000).toLocaleString('fr-FR')"></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="text-sm text-gray-600 w-24">1 000 FCFA:</span>
                      <input type="number" x-model.number="billets['1000']" @input="updateInput()" min="0"
                        class="input-field text-sm flex-1">
                      <span class="text-sm text-gray-500 w-32"
                        x-text="(billets['1000'] * 1000).toLocaleString('fr-FR')"></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="text-sm text-gray-600 w-24">500 FCFA:</span>
                      <input type="number" x-model.number="billets['500']" @input="updateInput()" min="0"
                        class="input-field text-sm flex-1">
                      <span class="text-sm text-gray-500 w-32"
                        x-text="(billets['500'] * 500).toLocaleString('fr-FR')"></span>
                    </div>
                    <div class="pt-3 border-t border-gray-300 flex justify-between items-center">
                      <span class="font-semibold text-gray-900">Total:</span>
                      <span class="text-lg font-bold text-primary-600"
                        x-text="total.toLocaleString('fr-FR') + ' FCFA'"></span>
                    </div>
                  </div>
                </div>

                <!-- Notes de fermeture -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Notes de fermeture (optionnel)
                  </label>
                  <textarea name="notes_fermeture" rows="3"
                    class="input-field @error('notes_fermeture') border-danger-500 @enderror"
                    placeholder="Remarques, incidents, observations...">{{ old('notes_fermeture') }}</textarea>
                  @error('notes_fermeture')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror>
                </div>

                <!-- Avertissement -->
                <div class="mb-6 p-4 bg-warning-50 rounded-lg border border-warning-200">
                  <div class="flex">
                    <svg class="h-5 w-5 text-warning-600 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="text-sm text-warning-800">
                      <strong>Attention :</strong> Une fois la caisse fermée, vous ne pourrez plus enregistrer de ventes
                      sur cette session.
                    </div>
                  </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3">
                  <a href="{{ route('caisses.show', $caisse) }}" class="btn-secondary">
                    Annuler
                  </a>
                  <button type="submit" class="btn-danger">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Fermer la Caisse
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection