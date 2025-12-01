<!-- Modal Réception -->
<div id="receptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
  <div class="relative top-10 mx-auto p-5 border max-w-2xl shadow-lg rounded-lg bg-white">
    <form action="{{ route('transferts.receptionner', $transfert) }}" method="POST">
      @csrf
      <div class="mt-3">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto">
          <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Réceptionner le transfert</h3>
        <p class="text-sm text-gray-500 text-center mt-2">
          Vérifiez et confirmez les quantités reçues pour chaque produit.
        </p>

        <div class="mt-6 space-y-4">
          @foreach($transfert->lignes as $ligne)
            <div class="border border-gray-200 rounded-lg p-4">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3">
                  <div class="h-10 w-10 rounded-lg flex items-center justify-center"
                    style="background-color: {{ $ligne->produit->categorie->couleur }}20;">
                    <svg class="h-6 w-6" style="color: {{ $ligne->produit->categorie->couleur }};" fill="none"
                      viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $ligne->produit->nom }}</div>
                    <div class="text-xs text-gray-500">Expédié : {{ $ligne->quantite_expedie }} unités</div>
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Quantité reçue <span class="text-red-500">*</span>
                </label>
                <input type="number" name="quantites[{{ $ligne->id }}]" class="input-field" min="0"
                  value="{{ $ligne->quantite_expedie }}" required>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
          <div class="flex">
            <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <div class="text-sm text-yellow-700">
              <p class="font-medium">Attention</p>
              <p>Les stocks seront automatiquement mis à jour après validation de la réception.</p>
            </div>
          </div>
        </div>

        <div class="mt-6 flex space-x-3">
          <button type="button" onclick="closeReceptionModal()" class="flex-1 btn-secondary">Annuler</button>
          <button type="submit" class="flex-1 btn-success">Confirmer la réception</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
  <script>
    function openReceptionModal() {
      document.getElementById('receptionModal').classList.remove('hidden');
    }

    function closeReceptionModal() {
      document.getElementById('receptionModal').classList.add('hidden');
    }

    // Fermer en cliquant en dehors
    window.onclick = function (event) {
      const receptionModal = document.getElementById('receptionModal');
      const validateModal = document.getElementById('validateModal');
      const refuseModal = document.getElementById('refuseModal');

      if (event.target === receptionModal) {
        closeReceptionModal();
      }
      if (event.target === validateModal) {
        closeValidateModal();
      }
      if (event.target === refuseModal) {
        closeRefuseModal();
      }
    }
  </script>
@endpush