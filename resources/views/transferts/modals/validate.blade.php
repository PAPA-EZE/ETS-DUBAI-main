<!-- Modal Validation -->
<div id="validateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
    <div class="mt-3">
      <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mx-auto">
        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Valider le transfert</h3>
      <p class="text-sm text-gray-500 text-center mt-2">
        Êtes-vous sûr de vouloir valider ce transfert ?<br>
        Le transfert sera approuvé et pourra être expédié.
      </p>
      <div class="mt-6 flex space-x-3">
        <button onclick="closeValidateModal()" class="flex-1 btn-secondary">Annuler</button>
        <form action="{{ route('transferts.valider', $transfert) }}" method="POST" class="flex-1">
          @csrf
          <button type="submit" class="w-full btn-success">Valider</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    function openValidateModal() {
      document.getElementById('validateModal').classList.remove('hidden');
    }

    function closeValidateModal() {
      document.getElementById('validateModal').classList.add('hidden');
    }
  </script>
@endpush