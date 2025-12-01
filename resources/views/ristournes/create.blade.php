@extends('layouts.app')

@section('title', 'Calculer une Ristourne')
@section('page-title', 'Calculer une Ristourne')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Ristournes', 'url' => route('ristournes.index')],
      ['label' => 'Calculer', 'url' => null],
    ]" />

    <div class="max-w-3xl mx-auto">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Calculer une Ristourne</h2>
        <p class="text-gray-600 mt-1">Sélectionnez le fournisseur et la période de calcul</p>
      </div>

      @if($fournisseurs->count() == 0)
        <x-alert type="warning">
          Aucun fournisseur n'a de barème de ristourne configuré.
          <a href="{{ route('fournisseurs.index') }}" class="underline font-medium ml-2">Configurer les barèmes</a>
        </x-alert>
      @else
        <div class="card">
          <div class="p-6">
            <form action="{{ route('ristournes.store') }}" method="POST">
              @csrf

              <!-- Sélection fournisseur -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Fournisseur <span class="text-danger-600">*</span>
                </label>
                <select name="fournisseur_id" required
                  class="input-field @error('fournisseur_id') border-danger-500 @enderror">
                  <option value="">Sélectionnez un fournisseur...</option>
                  @foreach($fournisseurs as $fournisseur)
                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                      {{ $fournisseur->nom }} ({{ $fournisseur->baremes_count }} barème(s))
                    </option>
                  @endforeach
                </select>
                @error('fournisseur_id')
                  <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Type de période -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Type de période <span class="text-danger-600">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                  <label
                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-500">
                    <input type="radio" name="type_periode" value="mensuel" {{ old('type_periode', 'mensuel') == 'mensuel' ? 'checked' : '' }} class="sr-only">
                    <span class="flex flex-1">
                      <span class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-900">Mensuel</span>
                      </span>
                    </span>
                    <svg class="h-5 w-5 text-primary-600 hidden" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                    </svg>
                  </label>

                  <label
                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-500">
                    <input type="radio" name="type_periode" value="trimestriel" {{ old('type_periode') == 'trimestriel' ? 'checked' : '' }} class="sr-only">
                    <span class="flex flex-1">
                      <span class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-900">Trimestriel</span>
                      </span>
                    </span>
                  </label>

                  <label
                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-500">
                    <input type="radio" name="type_periode" value="semestriel" {{ old('type_periode') == 'semestriel' ? 'checked' : '' }} class="sr-only">
                    <span class="flex flex-1">
                      <span class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-900">Semestriel</span>
                      </span>
                    </span>
                  </label>

                  <label
                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-500">
                    <input type="radio" name="type_periode" value="annuel" {{ old('type_periode') == 'annuel' ? 'checked' : '' }} class="sr-only">
                    <span class="flex flex-1">
                      <span class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-900">Annuel</span>
                      </span>
                    </span>
                  </label>
                </div>
                @error('type_periode')
                  <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
              </div>

              <!-- Date de référence -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Date de référence <span class="text-danger-600">*</span>
                </label>
                <input type="date" name="date_reference" value="{{ old('date_reference', now()->format('Y-m-d')) }}"
                  required class="input-field @error('date_reference') border-danger-500 @enderror">
                @error('date_reference')
                  <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                  La période sera calculée automatiquement selon cette date et le type choisi
                </p>
              </div>

              <!-- Info -->
              <div class="mb-6 p-4 bg-info-50 rounded-lg border border-info-200">
                <div class="flex">
                  <svg class="h-5 w-5 text-info-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                  </svg>
                  <div class="ml-3">
                    <h4 class="text-sm font-medium text-info-900">Comment ça marche ?</h4>
                    <ul class="mt-2 text-sm text-info-700 space-y-1">
                      <li>• Le système récupère toutes les commandes livrées du fournisseur sur la période</li>
                      <li>• Le barème applicable est déterminé selon le montant total d'achats</li>
                      <li>• La ristourne est calculée automatiquement</li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Boutons -->
              <div class="flex items-center justify-end gap-3">
                <a href="{{ route('ristournes.index') }}" class="btn-secondary">
                  Annuler
                </a>
                <button type="submit" class="btn-primary">
                  Calculer la Ristourne
                </button>
              </div>
            </form>
          </div>
        </div>
      @endif
    </div>
  </div>

  @push('scripts')
    <script>
      // Gérer l'apparence visuelle des radio buttons
      document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function () {
          document.querySelectorAll('input[type="radio"][name="' + this.name + '"]').forEach(r => {
            r.closest('label').classList.remove('border-primary-500', 'ring-2', 'ring-primary-500');
            r.nextElementSibling.querySelector('svg').classList.add('hidden');
          });

          if (this.checked) {
            this.closest('label').classList.add('border-primary-500', 'ring-2', 'ring-primary-500');
            this.nextElementSibling.querySelector('svg').classList.remove('hidden');
          }
        });

        // Init au chargement
        if (radio.checked) {
          radio.closest('label').classList.add('border-primary-500', 'ring-2', 'ring-primary-500');
          radio.nextElementSibling.querySelector('svg').classList.remove('hidden');
        }
      });
    </script>
  @endpush
@endsection