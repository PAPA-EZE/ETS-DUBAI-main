@extends('layouts.app')

@section('title', 'Ouvrir une Caisse')
@section('page-title', 'Ouvrir une Caisse')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Caisses', 'url' => route('caisses.index')],
      ['label' => 'Ouvrir', 'url' => null],
    ]" />

    <div class="max-w-2xl mx-auto">
      <!-- En-tête -->
      <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
          <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Ouverture de Caisse</h2>
        <p class="text-gray-600 mt-2">Renseignez les informations pour ouvrir votre session de caisse</p>
      </div>

      <!-- Formulaire -->
      <div class="card">
        <div class="p-6">
          <form action="{{ route('caisses.store') }}" method="POST">
            @csrf

            <!-- Nom de la caisse -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Nom de la caisse (optionnel)
              </label>
              <input type="text" name="nom_caisse" value="{{ old('nom_caisse') }}"
                placeholder="Ex: Caisse Principale, Caisse 1..."
                class="input-field @error('nom_caisse') border-danger-500 @enderror">
              @error('nom_caisse')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
              @enderror
              <p class="mt-1 text-xs text-gray-500">Si vide, un nom sera généré automatiquement</p>
            </div>

            <!-- Fond de caisse -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Fond de caisse initial <span class="text-danger-600">*</span>
              </label>
              <div class="relative">
                <input type="number" name="fond_ouverture" value="{{ old('fond_ouverture', 0) }}" required min="0"
                  step="100" class="input-field pr-20 @error('fond_ouverture') border-danger-500 @enderror">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                  <span class="text-gray-500 text-sm">FCFA</span>
                </div>
              </div>
              @error('fond_ouverture')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
              @enderror
              <p class="mt-1 text-xs text-gray-500">Montant en espèces disponible au démarrage</p>
            </div>

            <!-- Suggestions rapides -->
            <div class="mb-6">
              <p class="text-sm font-medium text-gray-700 mb-2">Suggestions rapides:</p>
              <div class="flex flex-wrap gap-2">
                <button type="button" onclick="document.querySelector('input[name=fond_ouverture]').value = 10000"
                  class="btn-secondary btn-sm">
                  10 000 FCFA
                </button>
                <button type="button" onclick="document.querySelector('input[name=fond_ouverture]').value = 25000"
                  class="btn-secondary btn-sm">
                  25 000 FCFA
                </button>
                <button type="button" onclick="document.querySelector('input[name=fond_ouverture]').value = 50000"
                  class="btn-secondary btn-sm">
                  50 000 FCFA
                </button>
                <button type="button" onclick="document.querySelector('input[name=fond_ouverture]').value = 100000"
                  class="btn-secondary btn-sm">
                  100 000 FCFA
                </button>
              </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Notes d'ouverture (optionnel)
              </label>
              <textarea name="notes_ouverture" rows="3"
                class="input-field @error('notes_ouverture') border-danger-500 @enderror"
                placeholder="Observations, remarques...">{{ old('notes_ouverture') }}</textarea>
              @error('notes_ouverture')
                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Informations -->
            <div class="mb-6 p-4 bg-info-50 rounded-lg border border-info-200">
              <div class="flex">
                <svg class="h-5 w-5 text-info-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div class="ml-3">
                  <h4 class="text-sm font-medium text-info-900">Informations importantes</h4>
                  <ul class="mt-2 text-sm text-info-700 space-y-1">
                    <li>• Une seule caisse peut être ouverte par utilisateur à la fois</li>
                    <li>• Le fond de caisse servira de référence pour la fermeture</li>
                    <li>• Toutes vos ventes seront liées à cette session de caisse</li>
                    <li>• Pensez à fermer la caisse en fin de journée</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end gap-3">
              <a href="{{ route('caisses.index') }}" class="btn-secondary">
                Annuler
              </a>
              <button type="submit" class="btn-primary">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ouvrir la Caisse
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Aide -->
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
          Besoin d'aide ?
          <a href="#" class="text-primary-600 hover:text-primary-800">Consulter le guide d'utilisation</a>
        </p>
      </div>
    </div>
  </div>
@endsection