@extends('layouts.app')

@section('title', 'Nouveau Produit')
@section('page-title', 'Nouveau Produit')

@section('content')
  <div class="fade-in">
    <div class="max-w-4xl mx-auto">
      <form action="{{ route('produits.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card">
          <!-- En-tête -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Informations du produit</h3>
          </div>

          <div class="p-6 space-y-6">
            <!-- Informations de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Nom -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nom du produit <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nom" value="{{ old('nom') }}" required class="input-field"
                  placeholder="Ex: Heineken 33cl">
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
                    <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
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
                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
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
                <input type="text" name="reference" value="{{ old('reference') }}" required class="input-field"
                  placeholder="Ex: BEER-HEI-33">
                @error('reference')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Code-barre -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Code-barre</label>
                <input type="text" name="code_barre" value="{{ old('code_barre') }}" class="input-field"
                  placeholder="Ex: 8710103883012">
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
                    <option value="casier" {{ old('type_conditionnement', 'casier') == 'casier' ? 'selected' : '' }}>Casier
                    </option>
                    <option value="palette" {{ old('type_conditionnement') == 'palette' ? 'selected' : '' }}>Palette
                    </option>
                    <option value="unite" {{ old('type_conditionnement') == 'unite' ? 'selected' : '' }}>Unité</option>
                  </select>
                </div>

                <!-- Unités par conditionnement -->
                <div id="unites_conditionnement_field">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span id="label_unites">Bouteilles par casier</span> <span class="text-red-500">*</span>
                  </label>
                  <input type="number" name="unites_par_conditionnement" id="unites_par_conditionnement"
                    value="{{ old('unites_par_conditionnement', 12) }}" min="1" required class="input-field">
                  <p class="mt-1 text-xs text-gray-500">Ex: 6, 12, 24, 48...</p>
                </div>

                <!-- Unités par pack (MASQUÉ) -->
                <input type="hidden" name="unites_par_pack" value="{{ old('unites_par_pack', 6) }}">
              </div>
            </div>

            <!-- Prix -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">Prix et Marges</h4>

              <!-- Prix par conditionnement -->
              <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h5 class="font-medium text-blue-900 mb-3">
                  Prix par <span id="label_conditionnement">Casier</span>
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Prix d'achat -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Prix d'achat HT <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="prix_achat_conditionnement" id="prix_achat_conditionnement"
                      value="{{ old('prix_achat_conditionnement', 0) }}" step="0.01" min="0" required class="input-field"
                      placeholder="0.00">
                  </div>

                  <!-- Prix de vente -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Prix de vente <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="prix_vente_conditionnement" id="prix_vente_conditionnement"
                      value="{{ old('prix_vente_conditionnement', 0) }}" step="0.01" min="0" required class="input-field"
                      placeholder="0.00">
                  </div>

                  <!-- Marge (calculée) -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marge (%)</label>
                    <input type="number" name="marge_conditionnement" id="marge_conditionnement"
                      value="{{ old('marge_conditionnement', 0) }}" step="0.01" min="0" class="input-field bg-gray-100"
                      readonly>
                  </div>
                </div>
              </div>

              <!-- Prix par unité -->
              <div class="bg-green-50 p-4 rounded-lg">
                <h5 class="font-medium text-green-900 mb-3">
                  Prix par <span id="label_unite">Bouteille</span>
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Prix d'achat (calculé) -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prix d'achat HT</label>
                    <input type="number" name="prix_achat_unite" id="prix_achat_unite"
                      value="{{ old('prix_achat_unite', 0) }}" step="0.01" min="0" class="input-field bg-gray-100"
                      readonly>
                    <p class="mt-1 text-xs text-gray-500">Calculé automatiquement</p>
                  </div>

                  <!-- Prix de vente (auto puis modifiable) -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Prix de vente <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="prix_vente_unite" id="prix_vente_unite"
                      value="{{ old('prix_vente_unite', 0) }}" step="0.01" min="0" required class="input-field">
                    <p class="mt-1 text-xs text-gray-500">Modifiable manuellement</p>
                  </div>

                  <!-- Marge (calculée) -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marge (%)</label>
                    <input type="number" name="marge_unite" id="marge_unite" value="{{ old('marge_unite', 0) }}"
                      step="0.01" min="0" class="input-field bg-gray-100" readonly>
                  </div>
                </div>
              </div>

              <!-- Info supplémentaire -->
              <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                  💡 <strong>Astuce :</strong> Le prix de vente bouteille est calculé automatiquement à partir du prix
                  casier,
                  mais vous pouvez le modifier manuellement si besoin.
                </p>
              </div>
            </div>

            <!-- TVA -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">TVA</h4>

              <div class="flex items-start space-x-4">
                <div class="flex items-center h-10">
                  <input type="checkbox" name="tva_applicable" id="tva_applicable" value="1" {{ old('tva_applicable', true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                  <label for="tva_applicable" class="ml-2 text-sm font-medium text-gray-700">
                    Appliquer la TVA
                  </label>
                </div>

                <div id="taux_tva_field" class="flex-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Taux de TVA (%)</label>
                  <input type="number" name="taux_tva" id="taux_tva" value="{{ old('taux_tva', 19.25) }}" step="0.01"
                    min="0" class="input-field">
                </div>
              </div>
            </div>

            <!-- Stock -->
            <div class="border-t pt-6">
              <h4 class="text-md font-semibold text-gray-900 mb-4">Gestion du Stock</h4>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Stock initial -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stock initial (en <span id="label_stock_conditionnement">casiers</span>)
                  </label>
                  <input type="number" name="stock_actuel_input" id="stock_actuel_input"
                    value="{{ old('stock_actuel_input', 0) }}" min="0" step="1" class="input-field">
                  <input type="hidden" name="stock_actuel" id="stock_actuel" value="{{ old('stock_actuel', 0) }}">
                  <p class="mt-1 text-xs text-gray-500">
                    Soit : <span id="stock_equivalent" class="font-medium">0 bouteilles</span>
                  </p>
                </div>

                <!-- Stock minimum -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stock minimum (en <span id="label_stock_min_conditionnement">casiers</span>)
                  </label>
                  <input type="number" name="stock_minimum_input" id="stock_minimum_input"
                    value="{{ old('stock_minimum_input', 1) }}" min="0" step="1" class="input-field">
                  <input type="hidden" name="stock_minimum" id="stock_minimum" value="{{ old('stock_minimum', 12) }}">
                  <p class="mt-1 text-xs text-gray-500">
                    Soit : <span id="stock_min_equivalent" class="font-medium">12 bouteilles</span>
                  </p>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div class="border-t pt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea name="description" rows="3" class="input-field"
                placeholder="Informations complémentaires sur le produit...">{{ old('description') }}</textarea>
            </div>

            <!-- Actif -->
            <div class="border-t pt-6">
              <div class="flex items-center">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="actif" class="ml-2 text-sm font-medium text-gray-700">
                  Produit actif
                </label>
              </div>
            </div>
          </div>

          <!-- Boutons -->
          <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('produits.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">
              <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
              Créer le produit
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
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

        // Stock
        const stockActuelInput = document.getElementById('stock_actuel_input');
        const stockActuel = document.getElementById('stock_actuel');
        const stockEquivalent = document.getElementById('stock_equivalent');
        const stockMinimumInput = document.getElementById('stock_minimum_input');
        const stockMinimum = document.getElementById('stock_minimum');
        const stockMinEquivalent = document.getElementById('stock_min_equivalent');
        const labelStockConditionnement = document.getElementById('label_stock_conditionnement');
        const labelStockMinConditionnement = document.getElementById('label_stock_min_conditionnement');

        let prixVenteUniteModifieManuellement = false;

        // Mettre à jour les labels selon le type
        function updateLabels() {
          const type = typeConditionnement.value;
          const labelConditionnement = document.getElementById('label_conditionnement');
          const labelUnite = document.getElementById('label_unite');
          const labelUnites = document.getElementById('label_unites');
          const unitesField = document.getElementById('unites_conditionnement_field');

          if (type === 'casier') {
            labelConditionnement.textContent = 'Casier';
            labelUnite.textContent = 'Bouteille';
            labelUnites.textContent = 'Bouteilles par casier';
            labelStockConditionnement.textContent = 'casiers';
            labelStockMinConditionnement.textContent = 'casiers';
            unitesField.style.display = 'block';
          } else if (type === 'palette') {
            labelConditionnement.textContent = 'Palette';
            labelUnite.textContent = 'Bouteille';
            labelUnites.textContent = 'Bouteilles par palette';
            labelStockConditionnement.textContent = 'palettes';
            labelStockMinConditionnement.textContent = 'palettes';
            unitesField.style.display = 'block';
          } else {
            labelConditionnement.textContent = 'Lot';
            labelUnite.textContent = 'Unité';
            labelUnites.textContent = 'Unités par lot';
            labelStockConditionnement.textContent = 'unités';
            labelStockMinConditionnement.textContent = 'unités';
            unitesField.style.display = 'none';
            unitesParConditionnement.value = 1;
          }

          updateStock();
        }

        // Calculer et convertir le stock
        function updateStock() {
          const unites = parseFloat(unitesParConditionnement.value) || 1;

          // Stock initial
          const stockValue = parseFloat(stockActuelInput.value) || 0;
          const totalBouteilles = stockValue * unites;
          stockActuel.value = totalBouteilles;
          stockEquivalent.textContent = totalBouteilles + ' bouteilles';

          // Stock minimum
          const stockMinValue = parseFloat(stockMinimumInput.value) || 0;
          const totalMin = stockMinValue * unites;
          stockMinimum.value = totalMin;
          stockMinEquivalent.textContent = totalMin + ' bouteilles';
        }

        // Calculer tous les prix et marges
        function calculerPrix() {
          const unites = parseFloat(unitesParConditionnement.value) || 1;
          const prixAchatCond = parseFloat(prixAchatConditionnement.value) || 0;
          const prixVenteCond = parseFloat(prixVenteConditionnement.value) || 0;

          // 1. Calculer prix d'achat unité (toujours automatique)
          const prixAchatUni = prixAchatCond / unites;
          prixAchatUnite.value = prixAchatUni.toFixed(2);

          // 2. Calculer marge conditionnement (%)
          if (prixAchatCond > 0) {
            const margeCond = ((prixVenteCond - prixAchatCond) / prixAchatCond) * 100;
            margeConditionnement.value = margeCond.toFixed(2);
          } else {
            margeConditionnement.value = '0.00';
          }

          // 3. Calculer prix de vente unité AUTOMATIQUEMENT si pas modifié manuellement
          if (!prixVenteUniteModifieManuellement) {
            const prixVenteUni = prixVenteCond / unites;
            prixVenteUnite.value = prixVenteUni.toFixed(2);
          }

          // 4. Calculer marge unité (%)
          const prixVenteUni = parseFloat(prixVenteUnite.value) || 0;
          if (prixAchatUni > 0) {
            const margeUni = ((prixVenteUni - prixAchatUni) / prixAchatUni) * 100;
            margeUnite.value = margeUni.toFixed(2);
          } else {
            margeUnite.value = '0.00';
          }
        }

        // Event listeners
        typeConditionnement.addEventListener('change', function () {
          updateLabels();
          prixVenteUniteModifieManuellement = false;
          calculerPrix();
        });

        unitesParConditionnement.addEventListener('input', function () {
          prixVenteUniteModifieManuellement = false;
          calculerPrix();
          updateStock();
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

        // Stock events
        stockActuelInput.addEventListener('input', updateStock);
        stockMinimumInput.addEventListener('input', updateStock);

        // TVA toggle
        tvaApplicable.addEventListener('change', function () {
          tauxTva.disabled = !this.checked;
          if (!this.checked) {
            tauxTva.value = 0;
          } else {
            tauxTva.value = 19.25;
          }
        });

        // Initialiser
        updateLabels();
        calculerPrix();
        updateStock();
      });
    </script>
  @endpush
@endsection