@extends('layouts.app')

@section('title', 'Modifier le Produit')
@section('page-title', 'Modifier le Produit')

@section('content')
  <div class="fade-in">
    <div class="max-w-4xl mx-auto">
      <form action="{{ route('produits.update', $produit) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
          <!-- En-tête -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Modifier le produit</h3>
                <p class="text-sm text-gray-500 mt-1">Référence : {{ $produit->reference }}</p>
              </div>
              <span
                class="px-3 py-1 text-sm rounded-full {{ $produit->actif ? 'bg-success-100 text-success-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $produit->actif ? 'Actif' : 'Inactif' }}
              </span>
            </div>
          </div>

          <div class="p-6 space-y-6">
            <!-- Informations de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Nom -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nom du produit <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nom" value="{{ old('nom', $produit->nom) }}" required class="input-field">
                @error('nom')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Catégorie -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Catégorie <span class="text-red-500">*</span>
                </label>
                <select name="categorie_id" required class="input-field">
                  <option value="">Sélectionnez une catégorie</option>
                  @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                      {{ $categorie->nom }}
                    </option>
                  @endforeach
                </select>
                @error('categorie_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Fournisseur -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Fournisseur <span class="text-red-500">*</span>
                </label>
                <select name="fournisseur_id" required class="input-field">
                  <option value="">Sélectionnez un fournisseur</option>
                  @foreach($fournisseurs as $fournisseur)
                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $produit->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                      {{ $fournisseur->nom }}
                    </option>
                  @endforeach
                </select>
                @error('fournisseur_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Référence -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Référence <span class="text-red-500">*</span>
                </label>
                <input type="text" name="reference" value="{{ old('reference', $produit->reference) }}" required
                  class="input-field bg-gray-50" readonly>
                <p class="mt-1 text-xs text-gray-500">Générée automatiquement</p>
              </div>

              <!-- Code-barre -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Code-barre</label>
                <input type="text" name="code_barre" value="{{ old('code_barre', $produit->code_barre) }}"
                  class="input-field">
              </div>
            </div>

            <!-- Conditionnement -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">Conditionnement</h4>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type de conditionnement -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type de conditionnement <span class="text-red-500">*</span>
                  </label>
                  <select name="type_conditionnement" id="type_conditionnement" required class="input-field">
                    <option value="casier" {{ old('type_conditionnement', $produit->type_conditionnement) == 'casier' ? 'selected' : '' }}>Casier</option>
                    <option value="palette" {{ old('type_conditionnement', $produit->type_conditionnement) == 'palette' ? 'selected' : '' }}>Palette</option>
                    <option value="unite" {{ old('type_conditionnement', $produit->type_conditionnement) == 'unite' ? 'selected' : '' }}>Unité</option>
                  </select>
                </div>

                <!-- Unités par conditionnement -->
                <div id="unites_conditionnement_field">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span id="label_unites">Bouteilles par casier</span> <span class="text-red-500">*</span>
                  </label>
                  <input type="number" name="unites_par_conditionnement" id="unites_par_conditionnement"
                    value="{{ old('unites_par_conditionnement', $produit->unites_par_conditionnement) }}" min="1" required
                    class="input-field">
                  <p class="mt-1 text-xs text-gray-500">Ex: 6, 12, 24, 48...</p>
                </div>
              </div>
            </div>

            <!-- Prix (Réservé aux admins uniquement) -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">Prix et Marges</h4>

              @if(auth()->user()->canEditPrices())
                <!-- Prix par conditionnement -->
                <div class="bg-blue-50 p-4 rounded-lg mb-4">
                  <h5 class="font-medium text-blue-900 mb-3">
                    Prix par <span id="label_conditionnement">{{ $produit->libelle_conditionnement }}</span>
                  </h5>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Prix d'achat HT <span
                          class="text-red-500">*</span></label>
                      <input type="number" name="prix_achat_conditionnement" id="prix_achat_conditionnement"
                        value="{{ old('prix_achat_conditionnement', $produit->prix_achat_conditionnement) }}" step="0.01"
                        min="0" required class="input-field">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Prix de vente <span
                          class="text-red-500">*</span></label>
                      <input type="number" name="prix_vente_conditionnement" id="prix_vente_conditionnement"
                        value="{{ old('prix_vente_conditionnement', $produit->prix_vente_conditionnement) }}" step="0.01"
                        min="0" required class="input-field">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Marge (%)</label>
                      <input type="number" name="marge_conditionnement" id="marge_conditionnement"
                        value="{{ old('marge_conditionnement', $produit->marge_conditionnement) }}" step="0.01" min="0"
                        class="input-field bg-gray-100" readonly>
                    </div>
                  </div>
                </div>

                <!-- Prix par unité -->
                <div class="bg-green-50 p-4 rounded-lg">
                  <h5 class="font-medium text-green-900 mb-3">
                    Prix par <span id="label_unite">{{ $produit->libelle_unite }}</span>
                  </h5>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Prix d'achat HT</label>
                      <input type="number" name="prix_achat_unite" id="prix_achat_unite"
                        value="{{ old('prix_achat_unite', $produit->prix_achat_unite) }}" step="0.01" min="0"
                        class="input-field bg-gray-100" readonly>
                      <p class="mt-1 text-xs text-gray-500">Calculé automatiquement</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Prix de vente <span
                          class="text-red-500">*</span></label>
                      <input type="number" name="prix_vente_unite" id="prix_vente_unite"
                        value="{{ old('prix_vente_unite', $produit->prix_vente_unite) }}" step="0.01" min="0" required
                        class="input-field">
                      <p class="mt-1 text-xs text-gray-500">Modifiable manuellement</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Marge (%)</label>
                      <input type="number" name="marge_unite" id="marge_unite"
                        value="{{ old('marge_unite', $produit->marge_unite) }}" step="0.01" min="0"
                        class="input-field bg-gray-100" readonly>
                    </div>
                  </div>
                </div>
              @else
                <!-- Affichage en lecture seule pour les vendeurs -->
                <div class="bg-gray-50 p-4 rounded-lg">
                  <p class="text-sm text-gray-600 mb-4">
                    <svg class="inline h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Seuls les administrateurs peuvent modifier les prix
                  </p>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-sm font-medium text-gray-700">Prix {{ $produit->libelle_conditionnement }}</p>
                      <p class="text-lg font-semibold text-gray-900">
                        {{ number_format($produit->prix_vente_conditionnement, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-700">Prix {{ $produit->libelle_unite }}</p>
                      <p class="text-lg font-semibold text-gray-900">
                        {{ number_format($produit->prix_vente_unite, 0, ',', ' ') }} FCFA</p>
                    </div>
                  </div>
                  <!-- Champs cachés pour maintenir les valeurs -->
                  <input type="hidden" name="prix_achat_conditionnement" value="{{ $produit->prix_achat_conditionnement }}">
                  <input type="hidden" name="prix_vente_conditionnement" value="{{ $produit->prix_vente_conditionnement }}">
                  <input type="hidden" name="prix_achat_unite" value="{{ $produit->prix_achat_unite }}">
                  <input type="hidden" name="prix_vente_unite" value="{{ $produit->prix_vente_unite }}">
                  <input type="hidden" name="marge_conditionnement" value="{{ $produit->marge_conditionnement }}">
                  <input type="hidden" name="marge_unite" value="{{ $produit->marge_unite }}">
                </div>
              @endif
            </div>

            <!-- TVA -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">TVA</h4>

              <div class="flex items-start space-x-4">
                <div class="flex items-center h-10">
                  <input type="checkbox" name="tva_applicable" id="tva_applicable" value="1" {{ old('tva_applicable', $produit->tva_applicable) ? 'checked' : '' }}
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                  <label for="tva_applicable" class="ml-2 text-sm font-medium text-gray-700">
                    Appliquer la TVA
                  </label>
                </div>

                <div id="taux_tva_field" class="flex-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Taux de TVA (%)</label>
                  <input type="number" name="taux_tva" id="taux_tva" value="{{ old('taux_tva', $produit->taux_tva) }}"
                    step="0.01" min="0" class="input-field">
                </div>
              </div>
            </div>

            <!-- Stock (Lecture seule - géré via transferts) -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">Gestion du Stock</h4>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stock actuel (en <span id="label_stock_unite">{{ strtolower($produit->libelle_unite) }}s</span>)
                  </label>
                  <input type="number" name="stock_actuel" value="{{ old('stock_actuel', $produit->stock_actuel) }}"
                    min="0" class="input-field bg-gray-50" readonly>
                  <p class="mt-1 text-xs text-gray-500">
                    {{ $produit->afficherStock($produit->stock_actuel) }}
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stock minimum (alerte)
                  </label>
                  <input type="number" name="stock_minimum" value="{{ old('stock_minimum', $produit->stock_minimum) }}"
                    min="0" class="input-field">
                </div>
              </div>

              <p class="mt-2 text-sm text-info-600">
                💡 Le stock est géré automatiquement via les transferts et les ventes
              </p>
            </div>

            <!-- Description -->
            <div class="border-t pt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea name="description" rows="3"
                class="input-field">{{ old('description', $produit->description) }}</textarea>
            </div>

            <!-- Actif -->
            <div class="border-t pt-6">
              <div class="flex items-center">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $produit->actif) ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="actif" class="ml-2 text-sm font-medium text-gray-700">
                  Produit actif
                </label>
              </div>
            </div>
          </div>

          <!-- Boutons -->
          <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
            <a href="{{ route('produits.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">
              <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              Enregistrer les modifications
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        @if(auth()->user()->canEditPrices())
          const typeConditionnement = document.getElementById('type_conditionnement');
          const unitesParConditionnement = document.getElementById('unites_par_conditionnement');
          const prixAchatConditionnement = document.getElementById('prix_achat_conditionnement');
          const margeConditionnement = document.getElementById('marge_conditionnement');
          const prixVenteConditionnement = document.getElementById('prix_vente_conditionnement');
          const prixAchatUnite = document.getElementById('prix_achat_unite');
          const margeUnite = document.getElementById('marge_unite');
          const prixVenteUnite = document.getElementById('prix_vente_unite');
          const tvaApplicable = document.getElementById('tva_applicable');
          const tauxTva = document.getElementById('taux_tva');

          let prixVenteUniteModifieManuellement = false;

          function updateLabels() {
            const type = typeConditionnement.value;
            const labelConditionnement = document.getElementById('label_conditionnement');
            const labelUnite = document.getElementById('label_unite');
            const labelUnites = document.getElementById('label_unites');
            const labelStockUnite = document.getElementById('label_stock_unite');
            const unitesField = document.getElementById('unites_conditionnement_field');

            if (type === 'casier') {
              labelConditionnement.textContent = 'Casier';
              labelUnite.textContent = 'Bouteille';
              labelUnites.textContent = 'Bouteilles par casier';
              labelStockUnite.textContent = 'bouteilles';
              unitesField.style.display = 'block';
            } else if (type === 'palette') {
              labelConditionnement.textContent = 'Palette';
              labelUnite.textContent = 'Bouteille';
              labelUnites.textContent = 'Bouteilles par palette';
              labelStockUnite.textContent = 'bouteilles';
              unitesField.style.display = 'block';
            } else {
              labelConditionnement.textContent = 'Lot';
              labelUnite.textContent = 'Unité';
              labelUnites.textContent = 'Unités par lot';
              labelStockUnite.textContent = 'unités';
              unitesField.style.display = 'none';
              unitesParConditionnement.value = 1;
            }
          }

          function calculerPrix() {
            const unites = parseFloat(unitesParConditionnement.value) || 1;
            const prixAchatCond = parseFloat(prixAchatConditionnement.value) || 0;
            const prixVenteCond = parseFloat(prixVenteConditionnement.value) || 0;

            const prixAchatUni = prixAchatCond / unites;
            prixAchatUnite.value = prixAchatUni.toFixed(2);

            if (prixAchatCond > 0) {
              const margeCond = ((prixVenteCond - prixAchatCond) / prixAchatCond) * 100;
              margeConditionnement.value = margeCond.toFixed(2);
            } else {
              margeConditionnement.value = '0.00';
            }

            if (!prixVenteUniteModifieManuellement) {
              const prixVenteUni = prixVenteCond / unites;
              prixVenteUnite.value = prixVenteUni.toFixed(2);
            }

            const prixVenteUni = parseFloat(prixVenteUnite.value) || 0;
            if (prixAchatUni > 0) {
              const margeUni = ((prixVenteUni - prixAchatUni) / prixAchatUni) * 100;
              margeUnite.value = margeUni.toFixed(2);
            } else {
              margeUnite.value = '0.00';
            }
          }

          typeConditionnement.addEventListener('change', function () {
            updateLabels();
            prixVenteUniteModifieManuellement = false;
            calculerPrix();
          });

          unitesParConditionnement.addEventListener('input', function () {
            prixVenteUniteModifieManuellement = false;
            calculerPrix();
          });

          prixAchatConditionnement.addEventListener('input', calculerPrix);

          prixVenteConditionnement.addEventListener('input', function () {
            prixVenteUniteModifieManuellement = false;
            calculerPrix();
          });

          prixVenteUnite.addEventListener('input', function () {
            prixVenteUniteModifieManuellement = true;
            calculerPrix();
          });

          prixVenteUnite.addEventListener('focus', function () {
            prixVenteUniteModifieManuellement = true;
          });

          tvaApplicable.addEventListener('change', function () {
            tauxTva.disabled = !this.checked;
            if (!this.checked) {
              tauxTva.value = 0;
            } else {
              tauxTva.value = 19.25;
            }
          });

          updateLabels();
          calculerPrix();
        @endif
    });
    </script>
  @endpush
@endsection