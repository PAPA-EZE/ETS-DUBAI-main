<!-- Modal Refus -->
<div id="refuseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
    <form action="{{ route('transferts.refuser', $transfert) }}" method="POST">
      @csrf
      <div class="mt-3">
        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mx-auto">
          <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Refuser le transfert</h3>
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Motif du refus <span
              class="text-red-500">*</span></label>
          <textarea name="motif" rows="3" required class="input-field"
            placeholder="Expliquez pourquoi ce transfert est refusé..."></textarea>
        </div>
        <div class="mt-6 flex space-x-3">
          <button type="button" onclick="closeRefuseModal()" class="flex-1 btn-secondary">Annuler</button>
          <button type="submit" class="flex-1 btn-danger">Refuser</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
  <script>
    function openRefuseModal() {
      document.getElementById('refuseModal').classList.remove('hidden');
    }

    function closeRefuseModal() {
      document.getElementById('refuseModal').classList.add('hidden');
    }
  </script>
@endpush