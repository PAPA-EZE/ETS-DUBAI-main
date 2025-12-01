@extends('layouts.app')

@section('title', 'Nouveau Transfert')
@section('page-title', 'Nouveau Transfert de Stock')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
      <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
          <a href="{{ route('transferts.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            Transferts
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
            </svg>
            <span class="ml-1 text-gray-500">Nouveau transfert</span>
          </div>
        </li>
      </ol>
    </nav>

    <!-- Formulaire -->
    <form action="{{ route('transferts.store') }}" method="POST">
      @csrf

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Points de vente -->
          <div class="card p-6">
            <div class="flex items-center mb-4">
              <div class="p-2 bg-blue-100 rounded-lg mr-3">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900">Points de vente</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Point source -->
              <div>
                <label for="point_source_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Point source <span class="text-red-500">*</span>
                </label>
                @if($pointSource)
                  <input type="hidden" name="point_source_id" value="{{ $pointSource->id }}">
                  <div class="input-field bg-gray-50 cursor-not-allowed">
                    {{ $pointSource->nom }}
                  </div>
                @else
                  <select name="point_source_id" id="point_source_id" class="input-field" required>
                    <option value="">Sélectionner...</option>
                    @foreach($points as $point)
                      <option value="{{ $point->id }}" {{ old('point_source_id') == $point->id ? 'selected' : '' }}>
                        {{ $point->nom }} ({{ $point->code }})
                      </option>
                    @endforeach
                  </select>
                @endif
                @error('point_source_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Point destination -->
              <div>
                <label for="point_destination_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Point destination <span class="text-red-500">*</span>
                </label>
                @if($pointDestination)
                  <input type="hidden" name="point_destination_id" value="{{ $pointDestination->id }}">
                  <div class="input-field bg-gray-50 cursor-not-allowed">
                    {{ $pointDestination->nom }}
                  </div>
                @else
                  <select name="point_destination_id" id="point_destination_id" class="input-field" required>
                    <option value="">Sélectionner...</option>
                    @foreach($points as $point)
                      <option value="{{ $point->id }}" {{ old('point_destination_id') == $point->id ? 'selected' : '' }}>
                        {{ $point->nom }} ({{ $point->code }})
                      </option>
                    @endforeach
                  </select>
                @endif
                @error('point_destination_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Produits -->
          <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                  <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                  </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Produits à transférer</h3>
              </div>
              <button type="button" onclick="ajouterLigne()" class="btn-primary btn-sm">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajouter un produit
              </button>
            </div>

            @error('produits')
              <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600">{{ $message }}</p>
              </div>
            @enderror

            <div id="produits-container" class="space-y-4">
              <!-- Les lignes de produits seront ajoutées ici -->
            </div>

            <div id="empty-state" class="text-center py-8 text-gray-500">
              Aucun produit ajouté. Cliquez sur "Ajouter un produit" pour commencer.
            </div>
          </div>

          <!-- Motif -->
          <div class="card p-6">
            <div class="flex items-center mb-4">
              <div class="p-2 bg-green-100 rounded-lg mr-3">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900">Motif du transfert</h3>
            </div>

            <!-- Motifs prédéfinis -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Motifs courants (facultatif)
              </label>
              <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="setMotif('Réapprovisionnement du stock')"
                  class="text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                  📦 Réapprovisionnement du stock
                </button>
                <button type="button" onclick="setMotif('Rupture de stock')"
                  class="text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                  ⚠️ Rupture de stock
                </button>
                <button type="button" onclick="setMotif('Forte demande client')"
                  class="text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                  📈 Forte demande client
                </button>
                <button type="button" onclick="setMotif('Équilibrage des stocks')"
                  class="text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                  ⚖️ Équilibrage des stocks
                </button>
                <button type="button" onclick="setMotif('Ouverture nouveau point de vente')"
                  class="text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                  🏪 Nouveau point de vente
                </button>
                <button type="button" onclick="setMotif('Événement spécial')"
                  class="text-left px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
                  🎉 Événement spécial
                </button>
              </div>
            </div>

            <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
              <div class="flex">
                <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div class="text-sm text-blue-700">
                  <p class="font-medium">💡 Conseil</p>
                  <p>Vous pouvez utiliser un motif prédéfini ci-dessus, mais n'oubliez pas de <strong>préciser les détails
                      exacts</strong> de votre demande dans le champ ci-dessous (quantités, urgence, contexte spécifique,
                    etc.)</p>
                </div>
              </div>
            </div>

            <label for="motif" class="block text-sm font-medium text-gray-700 mb-2">
              Motif détaillé <span class="text-red-500">*</span>
            </label>
            <textarea name="motif" id="motif" rows="4" class="input-field" required
              placeholder="Exemple : Réapprovisionnement urgent suite à une rupture de stock de 33 Export. Forte demande depuis 3 jours. Besoin de 50 casiers pour tenir le weekend.">{{ old('motif') }}</textarea>
            @error('motif')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Minimum 10 caractères. Soyez précis et détaillé.</p>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Actions -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
            <div class="space-y-3">
              <button type="submit" class="w-full btn-primary">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Créer le transfert
              </button>
              <a href="{{ route('transferts.index') }}" class="w-full btn-secondary block text-center">
                Annuler
              </a>
            </div>
          </div>

          <!-- Aide -->
          <div class="card p-6 bg-blue-50 border-blue-200">
            <h4 class="font-medium text-blue-900 mb-2">💡 Conseils</h4>
            <ul class="text-sm text-blue-700 space-y-2">
              <li>• Vérifiez le stock disponible avant de créer le transfert</li>
              <li>• Vous pouvez ajouter plusieurs produits</li>
              <li>• Le motif doit être clair et précis</li>
              @if(auth()->user()->isVendeur())
                <li>• Votre demande sera validée par un administrateur</li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </form>
  </div>

  @push('scripts')
    <script>
      let ligneCounter = 0;
      const produits = @json($produits);
      const pointSourceId = {{ $pointSource ? $pointSource->id : 'null' }};

      function setMotif(motif) {
        const textarea = document.getElementById('motif');
        const currentValue = textarea.value.trim();

        // Si le champ est vide, on met juste le motif
        if (!currentValue) {
          textarea.value = motif + '\n\nDétails : ';
        } else {
          // Sinon on ajoute le motif au début s'il n'y est pas déjà
          if (!currentValue.includes(motif)) {
            textarea.value = motif + '\n\n' + currentValue;
          }
        }
        textarea.focus();
      }

      function ajouterLigne() {
        ligneCounter++;
        const container = document.getElementById('produits-container');
        const emptyState = document.getElementById('empty-state');

        emptyState.style.display = 'none';

        const ligne = document.createElement('div');
        ligne.className = 'border border-gray-200 rounded-lg p-4 bg-white';
        ligne.id = `ligne-${ligneCounter}`;

        ligne.innerHTML = `
              <div class="flex items-start space-x-4">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Produit -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Produit <span class="text-red-500">*</span>
                    </label>
                    <select name="produits[${ligneCounter}][produit_id]" 
                            class="input-field produit-select" 
                            data-ligne="${ligneCounter}"
                            onchange="updateStock(${ligneCounter})"
                            required>
                      <option value="">Sélectionner...</option>
                      ${produits.map(p => `
                        <option value="${p.id}" 
                                data-unites="${p.unites_par_conditionnement}"
                                data-pack="${p.unites_par_pack || 0}">
                          ${p.nom} - ${p.reference}
                        </option>
                      `).join('')}
                    </select>
                  </div>

                  <!-- Quantité -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Quantité <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="produits[${ligneCounter}][quantite]" 
                           class="input-field" 
                           min="1" 
                           value="1"
                           required>
                  </div>

                  <!-- Type -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Type <span class="text-red-500">*</span>
                    </label>
                    <select name="produits[${ligneCounter}][type_conditionnement]" 
                            class="input-field"
                            required>
                      <option value="casier">Casier</option>
                      <option value="pack">Pack</option>
                      <option value="unite">Unité</option>
                    </select>
                  </div>
                </div>

                <!-- Bouton supprimer -->
                <button type="button" 
                        onclick="supprimerLigne(${ligneCounter})" 
                        class="mt-6 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <!-- Stock disponible -->
              <div id="stock-info-${ligneCounter}" class="mt-2 text-sm text-gray-600 hidden">
                Stock disponible : <span class="font-medium" id="stock-${ligneCounter}">-</span>
              </div>
            `;

        container.appendChild(ligne);
      }

      function supprimerLigne(id) {
        const ligne = document.getElementById(`ligne-${id}`);
        if (ligne) {
          ligne.remove();
        }

        // Afficher le message vide si aucune ligne
        const container = document.getElementById('produits-container');
        const emptyState = document.getElementById('empty-state');
        if (container.children.length === 0) {
          emptyState.style.display = 'block';
        }
      }

      async function updateStock(ligneId) {
        const select = document.querySelector(`select[name="produits[${ligneId}][produit_id]"]`);
        const produitId = select.value;
        const sourceId = pointSourceId || document.getElementById('point_source_id')?.value;

        if (!produitId || !sourceId) {
          return;
        }

        try {
          const response = await fetch(`/transferts/stock?produit_id=${produitId}&point_id=${sourceId}`);
          const data = await response.json();

          const stockInfo = document.getElementById(`stock-info-${ligneId}`);
          const stockSpan = document.getElementById(`stock-${ligneId}`);

          if (stockInfo && stockSpan) {
            stockSpan.textContent = data.affichage;
            stockInfo.classList.remove('hidden');

            // Ajouter une alerte si stock faible
            if (data.stock < 10) {
              stockInfo.classList.add('text-red-600');
              stockInfo.classList.remove('text-gray-600');
            } else {
              stockInfo.classList.add('text-gray-600');
              stockInfo.classList.remove('text-red-600');
            }
          }
        } catch (error) {
          console.error('Erreur lors de la récupération du stock:', error);
        }
      }

      // Ajouter une première ligne automatiquement
      document.addEventListener('DOMContentLoaded', function () {
        ajouterLigne();
      });
    </script>
  @endpush
@endsection