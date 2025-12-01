@extends('layouts.app')

@section('title', 'Modifier Commande')
@section('page-title', 'Modifier Commande')

@section('content')
  <div class="fade-in" x-data="commandeEditForm()">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Commandes', 'url' => route('commandes.index'), 'icon' => '<svg class=\'w-4 h-4 mr-2\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.119-1.243l1.263-12C4.468 8.79 4.776 8.5 5.154 8.5h13.692a.75.75 0 01.119 1.007z\' /></svg>'],
      ['label' => $commande->numero_commande, 'url' => route('commandes.show', $commande)],
      ['label' => 'Modifier'],
    ]" />

    <!-- Header -->
    <div class="page-header">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Modifier {{ $commande->numero_commande }}</h1>
        <p class="mt-1 text-sm text-gray-600">
          Modifiez les détails de cette commande (possible uniquement en statut brouillon)
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <x-status-badge status="Brouillon" type="default" />
      </div>
    </div>

    <!-- Alerte -->
    <x-alert type="warning" class="mb-6">
      Cette commande est en mode <strong>brouillon</strong>. Une fois envoyée au fournisseur, elle ne pourra plus être
      modifiée.
    </x-alert>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('commandes.update', $commande) }}" class="space-y-6">
      @csrf
      @method('PUT')

      <!-- Informations générales -->
      <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Informations générales</h3>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <!-- Fournisseur -->
          <div class="sm:col-span-2">
            <label for="fournisseur_id" class="block text-sm font-medium text-gray-700">
              Fournisseur <span class="text-danger-500">*</span>
            </label>
            <select name="fournisseur_id" id="fournisseur_id" x-model="fournisseurId"
              @change="chargerProduitsFournisseur()"
              class="mt-1 input-field @error('fournisseur_id') border-danger-300 @enderror">
              <option value="">Sélectionnez un fournisseur</option>
              @foreach($fournisseurs as $fournisseur)
                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $commande->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                  {{ $fournisseur->nom }} (Ristourne: {{ $fournisseur->taux_ristourne_defaut }}%)
                </option>
              @endforeach
            </select>
            @error('fournisseur_id')
              <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Date de commande -->
          <div>
            <label for="date_commande" class="block text-sm font-medium text-gray-700">
              Date de commande <span class="text-danger-500">*</span>
            </label>
            <input type="date" name="date_commande" id="date_commande"
              value="{{ old('date_commande', $commande->date_commande->format('Y-m-d')) }}"
              class="mt-1 input-field @error('date_commande') border-danger-300 @enderror">
            @error('date_commande')
              <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Date de livraison prévue -->
          <div>
            <label for="date_livraison_prevue" class="block text-sm font-medium text-gray-700">
              Livraison prévue
            </label>
            <input type="date" name="date_livraison_prevue" id="date_livraison_prevue"
              value="{{ old('date_livraison_prevue', $commande->date_livraison_prevue?->format('Y-m-d')) }}"
              class="mt-1 input-field @error('date_livraison_prevue') border-danger-300 @enderror">
            @error('date_livraison_prevue')
              <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Notes -->
          <div class="sm:col-span-3">
            <label for="notes" class="block text-sm font-medium text-gray-700">
              Notes
            </label>
            <textarea name="notes" id="notes" rows="3"
              class="mt-1 input-field @error('notes') border-danger-300 @enderror"
              placeholder="Notes et instructions pour cette commande...">{{ old('notes', $commande->notes) }}</textarea>
            @error('notes')
              <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- Articles de la commande -->
      <div class="card p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-medium text-gray-900">Articles de la commande</h3>
          <button type="button" @click="ajouterLigne()" class="btn-secondary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Ajouter une ligne
          </button>
        </div>

        <!-- Table des articles -->
        <div class="space-y-4">
          <template x-for="(item, index) in items" :key="index">
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-12 items-end">
                <!-- Produit -->
                <div class="sm:col-span-5">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Produit <span class="text-danger-500">*</span>
                  </label>
                  <select :name="`items[${index}][produit_id]`" x-model="item.produit_id" @change="mettreAJourPrix(index)"
                    class="input-field">
                    <option value="">Sélectionnez un produit</option>
                    <template x-for="produit in produitsDisponibles" :key="produit.id">
                      <option :value="produit.id"
                        x-text="`${produit.nom} - ${produit.reference} (Stock: ${produit.stock_actuel})`"></option>
                    </template>
                  </select>
                </div>

                <!-- Quantité -->
                <div class="sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Quantité <span class="text-danger-500">*</span>
                  </label>
                  <input type="number" :name="`items[${index}][quantite]`" x-model="item.quantite"
                    @input="calculerMontantLigne(index)" min="1" class="input-field" placeholder="0">
                </div>

                <!-- Prix unitaire -->
                <div class="sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Prix unitaire (FCFA) <span class="text-danger-500">*</span>
                  </label>
                  <input type="number" :name="`items[${index}][prix_unitaire]`" x-model="item.prix_unitaire"
                    @input="calculerMontantLigne(index)" min="0" step="0.01" class="input-field" placeholder="0">
                </div>

                <!-- Montant ligne -->
                <div class="sm:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Montant ligne
                  </label>
                  <div class="input-field bg-gray-100" x-text="formatCurrency(item.montant_ligne)"></div>
                </div>

                <!-- Actions -->
                <div class="sm:col-span-1">
                  <button type="button" @click="supprimerLigne(index)"
                    class="w-full h-10 flex items-center justify-center text-danger-600 hover:text-danger-800 hover:bg-danger-50 rounded-lg transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </template>

          <!-- Message si aucun article -->
          <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
            <p>Aucun article dans la commande</p>
            <button type="button" @click="ajouterLigne()" class="mt-2 btn-primary">
              Ajouter le premier article
            </button>
          </div>
        </div>
      </div>

      <!-- Récapitulatif -->
      <div x-show="items.length > 0" class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Récapitulatif</h3>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
          <div class="text-center p-4 bg-gray-50 rounded-lg">
            <div class="text-sm font-medium text-gray-500">Total HT</div>
            <div class="text-2xl font-bold text-gray-900" x-text="formatCurrency(totalHT)"></div>
          </div>

          <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-sm font-medium text-blue-600">TVA (19.25%)</div>
            <div class="text-2xl font-bold text-blue-900" x-text="formatCurrency(totalTVA)"></div>
          </div>

          <div class="text-center p-4 bg-primary-50 rounded-lg">
            <div class="text-sm font-medium text-primary-600">Total TTC</div>
            <div class="text-2xl font-bold text-primary-900" x-text="formatCurrency(totalTTC)"></div>
          </div>
        </div>

        <div x-show="ristournePrevue > 0" class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <p class="text-sm font-medium text-green-800">
                Ristourne prévue: <span x-text="formatCurrency(ristournePrevue)"></span>
              </p>
              <p class="text-xs text-green-600">
                Économie réalisée grâce aux conditions négociées
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex justify-end space-x-3">
        <a href="{{ route('commandes.show', $commande) }}" class="btn-secondary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Annuler
        </a>

        <button type="submit" name="action" value="brouillon" class="btn-secondary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75a.75.75 0 01.75-.75h7.5a.75.75 0 01.75.75z" />
          </svg>
          Sauvegarder les modifications
        </button>

        <button type="submit" name="envoyer" value="1" class="btn-primary">
          <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.768 59.768 0 013.27 20.876L5.999 12zm0 0h7.5" />
          </svg>
          Sauvegarder et envoyer
        </button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    function commandeEditForm() {
      return {
        fournisseurId: '{{ $commande->fournisseur_id }}',
        produitsDisponibles: [],
        items: @json($commande->items->map(function ($item) {
          return [
            'produit_id' => $item->produit_id,
            'quantite' => $item->quantite_commandee,
            'prix_unitaire' => $item->prix_unitaire_ht,
            'montant_ligne' => $item->montant_ligne_ht,
          ];
        })),

        get totalHT() {
          return this.items.reduce((total, item) => total + (item.montant_ligne || 0), 0);
        },

        get totalTVA() {
          return this.totalHT * 0.1925;
        },

        get totalTTC() {
          return this.totalHT + this.totalTVA;
        },

        get ristournePrevue() {
          if (!this.fournisseurId) return 0;
          const fournisseur = @json($fournisseurs).find(f => f.id == this.fournisseurId);
          return fournisseur ? (this.totalHT * fournisseur.taux_ristourne_defaut / 100) : 0;
        },

        async chargerProduitsFournisseur() {
          if (!this.fournisseurId) {
            this.produitsDisponibles = [];
            return;
          }

          try {
            const response = await fetch(`/api/fournisseurs/${this.fournisseurId}/produits`);
            this.produitsDisponibles = await response.json();
          } catch (error) {
            console.error('Erreur lors du chargement des produits:', error);
            this.produitsDisponibles = [];
          }
        },

        ajouterLigne() {
          this.items.push({
            produit_id: '',
            quantite: 1,
            prix_unitaire: 0,
            montant_ligne: 0
          });
        },

        supprimerLigne(index) {
          this.items.splice(index, 1);
        },

        mettreAJourPrix(index) {
          const item = this.items[index];
          const produit = this.produitsDisponibles.find(p => p.id == item.produit_id);

          if (produit) {
            item.prix_unitaire = produit.prix_achat;
            this.calculerMontantLigne(index);
          }
        },

        calculerMontantLigne(index) {
          const item = this.items[index];
          item.montant_ligne = (item.quantite || 0) * (item.prix_unitaire || 0);
        },

        formatCurrency(amount) {
          return new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          }).format(amount || 0) + ' FCFA';
        },

        init() {
          this.chargerProduitsFournisseur();
        }
      }
    }
  </script>
@endpush