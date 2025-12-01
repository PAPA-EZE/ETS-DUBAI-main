@extends('layouts.app')

@section('title', 'Point de Vente')
@section('page-title', 'Point de Vente')

@section('content')
  <div class="fade-in" x-data="pointDeVente()">
    <!-- Alerte caisse -->
    <div class="mb-6">
      <div class="card p-4 bg-green-50 border-l-4 border-green-500">
        <div class="flex items-center">
          <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex-1">
            <p class="font-medium text-green-900">Caisse ouverte : {{ $caisseOuverte->nom_caisse }}</p>
            <p class="text-sm text-green-700">Ouvert depuis {{ $caisseOuverte->date_ouverture->format('H:i') }} - Fond de
              caisse : {{ number_format($caisseOuverte->fond_ouverture, 0, ',', ' ') }} FCFA</p>
          </div>
          <a href="{{ route('caisses.show', $caisseOuverte) }}" class="btn-secondary">
            Voir la caisse
          </a>
        </div>
      </div>
    </div>

    <form @submit.prevent="submitVente" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Colonne gauche : Sélection produits -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Recherche produit -->
        <div class="card p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Sélection des Produits</h3>

          <!-- Barre de recherche -->
          <div class="relative mb-4">
            <input type="text" x-model="searchQuery" @input="filtrerProduits()"
              placeholder="Rechercher un produit (nom, référence, code-barre)..." class="input-field pl-10">
            <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
          </div>

          <!-- Liste des produits filtrés -->
          <div class="max-h-96 overflow-y-auto space-y-2" x-show="produitsFiltres.length > 0">
            <template x-for="produit in produitsFiltres" :key="produit.id">
              <div class="border rounded-lg p-3 transition-all" :class="{
                        'border-gray-200 hover:bg-gray-50 cursor-pointer': produit.stock_disponible > 0,
                        'border-gray-300 bg-gray-100 opacity-60 cursor-not-allowed': produit.stock_disponible <= 0
                      }" @click="produit.stock_disponible > 0 ? ajouterAuPanier(produit) : null">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                      <h4 class="font-medium text-gray-900" x-text="produit.nom"></h4>
                      <!-- Badge stock -->
                      <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="{
                                'bg-green-100 text-green-800': produit.stock_disponible > 10,
                                'bg-orange-100 text-orange-800': produit.stock_disponible > 0 && produit.stock_disponible <= 10,
                                'bg-red-100 text-red-800': produit.stock_disponible <= 0
                              }">
                        <span x-show="produit.stock_disponible > 0">
                          Stock: <span x-text="produit.stock_disponible"></span>
                        </span>
                        <span x-show="produit.stock_disponible <= 0">Épuisé</span>
                      </span>
                    </div>
                    <p class="text-sm text-gray-500">
                      <span x-text="produit.reference"></span>
                      <template x-if="produit.categorie">
                        - <span x-text="produit.categorie.nom"></span>
                      </template>
                    </p>
                  </div>
                  <div class="text-right ml-4">
                    <p class="text-lg font-bold text-blue-600" x-text="formatMontant(produit.prix_vente_unite)"></p>
                    <p class="text-xs text-gray-500">FCFA / unité</p>
                  </div>
                </div>
              </div>
            </template>
          </div>

          <div x-show="searchQuery && produitsFiltres.length === 0" class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">Aucun produit trouvé</p>
          </div>
        </div>

        <!-- Panier -->
        <div class="card p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Panier</h3>

          <div x-show="panier.length === 0" class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="mt-4 text-gray-500">Le panier est vide</p>
            <p class="text-sm text-gray-400">Ajoutez des produits pour commencer</p>
          </div>

          <div x-show="panier.length > 0" class="space-y-4">
            <template x-for="(item, index) in panier" :key="index">
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                  <div class="flex-1">
                    <h4 class="font-medium text-gray-900" x-text="item.produit.nom"></h4>
                    <p class="text-sm text-gray-500" x-text="item.produit.reference"></p>
                  </div>
                  <button type="button" @click="retirerDuPanier(index)" class="text-red-600 hover:text-red-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                  </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <!-- Quantité -->
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Quantité</label>
                    <div class="flex items-center space-x-2">
                      <button type="button" @click="item.quantite > 1 ? item.quantite-- : null; calculerTotaux()"
                        class="p-1 bg-gray-100 rounded hover:bg-gray-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <input type="number" x-model.number="item.quantite" @input="calculerTotaux()" min="1"
                        :max="item.produit.stock_disponible" class="w-16 text-center input-field">
                      <button type="button"
                        @click="item.quantite < item.produit.stock_disponible ? item.quantite++ : null; calculerTotaux()"
                        class="p-1 bg-gray-100 rounded hover:bg-gray-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <!-- Prix unitaire -->
                  <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Prix unitaire HT</label>
                    <input type="number" x-model.number="item.prix_unitaire" @input="calculerTotaux()" class="input-field"
                      step="0.01">
                  </div>
                </div>

                <!-- Total ligne -->
                <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                  <span class="text-sm text-gray-600">Total ligne:</span>
                  <span class="text-lg font-bold text-gray-900" x-text="formatMontant(calculerTotalLigne(item))"></span>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- Colonne droite : Résumé et paiement -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Client -->
        <div class="card p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Client</h3>
          <select x-model="clientId" class="input-field">
            <option value="">Client passager</option>
            @foreach($clients as $client)
              <option value="{{ $client->id }}">{{ $client->nom }}</option>
            @endforeach
          </select>
        </div>

        <!-- Résumé -->
        <div class="card p-6 sticky top-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Résumé</h3>

          <div class="space-y-3 mb-4">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Sous-total HT:</span>
              <span class="font-medium" x-text="formatMontant(totaux.montant_ht)"></span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">TVA (19.25%):</span>
              <span class="font-medium" x-text="formatMontant(totaux.montant_tva)"></span>
            </div>

            <!-- Remise globale -->
            <div class="pt-3 border-t border-gray-200">
              <label class="block text-sm font-medium text-gray-700 mb-2">Remise globale (%)</label>
              <input type="number" x-model.number="remiseGlobale" @input="calculerTotaux()" min="0" max="100"
                class="input-field" placeholder="0">
            </div>
          </div>

          <!-- Total final -->
          <div class="pt-4 border-t-2 border-gray-300">
            <div class="flex justify-between items-center">
              <span class="text-lg font-semibold text-gray-900">Total à payer:</span>
              <span class="text-2xl font-bold text-blue-600" x-text="formatMontant(totaux.montant_final)"></span>
            </div>
          </div>

          <!-- Type de paiement -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Mode de paiement</label>
            <select x-model="typePaiement" class="input-field">
              <option value="especes">Espèces</option>
              <option value="mobile_money">Mobile Money</option>
              <option value="credit">Crédit</option>
              <option value="mixte">Mixte</option>
            </select>
          </div>

          <!-- Montant payé -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Montant payé</label>
            <input type="number" x-model.number="montantPaye" @input="calculerRendu()" class="input-field" placeholder="0"
              step="100">
          </div>

          <!-- Rendu -->
          <div x-show="rendu > 0" class="mt-4 p-3 bg-green-50 rounded-lg">
            <div class="flex justify-between items-center">
              <span class="text-sm font-medium text-green-900">Rendu:</span>
              <span class="text-xl font-bold text-green-600" x-text="formatMontant(rendu)"></span>
            </div>
          </div>

          <!-- Notes -->
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
            <textarea x-model="notes" rows="2" class="input-field" placeholder="Notes..."></textarea>
          </div>

          <!-- Bouton valider -->
          <button type="submit" :disabled="panier.length === 0 || isSubmitting"
            :class="panier.length === 0 || isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
            class="w-full mt-6 btn-primary btn-lg">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-show="!isSubmitting">Valider la vente</span>
            <span x-show="isSubmitting">Enregistrement...</span>
          </button>

          <button type="button" @click="viderPanier()" :disabled="panier.length === 0" class="w-full mt-3 btn-secondary">
            Vider le panier
          </button>
        </div>
      </div>
    </form>
  </div>

  @push('scripts')
    <script>
      function pointDeVente() {
        return {
          produits: @json($produits),
          searchQuery: '',
          produitsFiltres: [],
          panier: [],
          clientId: '',
          typePaiement: 'especes',
          montantPaye: 0,
          remiseGlobale: 0,
          notes: '',
          rendu: 0,
          isSubmitting: false,
          totaux: {
            montant_ht: 0,
            montant_tva: 0,
            montant_ttc: 0,
            montant_final: 0
          },

          init() {
            this.produitsFiltres = this.produits.slice(0, 10);
          },

          filtrerProduits() {
            if (!this.searchQuery.trim()) {
              this.produitsFiltres = this.produits.slice(0, 10);
              return;
            }

            const query = this.searchQuery.toLowerCase();
            this.produitsFiltres = this.produits.filter(p =>
              p.nom.toLowerCase().includes(query) ||
              p.reference.toLowerCase().includes(query) ||
              (p.code_barre && p.code_barre.toLowerCase().includes(query))
            ).slice(0, 20);
          },

          ajouterAuPanier(produit) {
            if (produit.stock_disponible <= 0) {
              alert('Ce produit est épuisé');
              return;
            }

            const existe = this.panier.find(item => item.produit.id === produit.id);

            if (existe) {
              if (existe.quantite < produit.stock_disponible) {
                existe.quantite++;
              } else {
                alert('Stock insuffisant');
                return;
              }
            } else {
              this.panier.push({
                produit: produit,
                quantite: 1,
                prix_unitaire: produit.prix_vente_unite || 0
              });
            }

            this.calculerTotaux();
            this.searchQuery = '';
            this.filtrerProduits();
          },

          retirerDuPanier(index) {
            this.panier.splice(index, 1);
            this.calculerTotaux();
          },

          viderPanier() {
            if (confirm('Voulez-vous vraiment vider le panier ?')) {
              this.panier = [];
              this.calculerTotaux();
              this.montantPaye = 0;
              this.remiseGlobale = 0;
              this.notes = '';
            }
          },

          calculerTotalLigne(item) {
            const sousTotal = item.prix_unitaire * item.quantite;
            const tva = sousTotal * 0.1925;
            return sousTotal + tva;
          },

          calculerTotaux() {
            let montantHT = 0;

            this.panier.forEach(item => {
              montantHT += item.prix_unitaire * item.quantite;
            });

            // Appliquer remise globale
            if (this.remiseGlobale > 0) {
              montantHT -= montantHT * (this.remiseGlobale / 100);
            }

            this.totaux.montant_ht = montantHT;
            this.totaux.montant_tva = montantHT * 0.1925;
            this.totaux.montant_ttc = montantHT + this.totaux.montant_tva;
            this.totaux.montant_final = this.totaux.montant_ttc;

            this.calculerRendu();
          },

          calculerRendu() {
            this.rendu = Math.max(0, this.montantPaye - this.totaux.montant_final);
          },

          formatMontant(montant) {
            if (isNaN(montant) || montant === null || montant === undefined) {
              return '0 FCFA';
            }
            return new Intl.NumberFormat('fr-FR').format(Math.round(montant)) + ' FCFA';
          },

          async submitVente() {
            if (this.panier.length === 0) {
              alert('Le panier est vide');
              return;
            }

            if (this.montantPaye < this.totaux.montant_final && this.typePaiement !== 'credit') {
              alert('Le montant payé est insuffisant');
              return;
            }

            this.isSubmitting = true;

            const formData = {
              client_id: this.clientId || null,
              items: this.panier.map(item => ({
                produit_id: item.produit.id,
                quantite: item.quantite,
                prix_unitaire: item.prix_unitaire
              })),
              remise_globale: this.remiseGlobale || 0,
              type_paiement: this.typePaiement,
              montant_paye: this.montantPaye,
              notes: this.notes
            };

            try {
              const response = await fetch('{{ route("ventes.store") }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                  'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
              });

              const contentType = response.headers.get('content-type');
              if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Le serveur a retourné une réponse non-JSON');
              }

              const data = await response.json();

              if (response.ok && data.success) {
                window.location.href = data.redirect || '{{ route("ventes.index") }}';
              } else {
                alert(data.message || 'Erreur lors de l\'enregistrement');
                this.isSubmitting = false;
              }
            } catch (error) {
              console.error('Erreur:', error);
              alert('Erreur: ' + error.message);
              this.isSubmitting = false;
            }
          }
        }
      }
    </script>
  @endpush
@endsection