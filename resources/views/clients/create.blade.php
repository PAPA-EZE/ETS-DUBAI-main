@extends('layouts.app')

@section('title', 'Nouveau Client')
@section('page-title', 'Nouveau Client')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Clients', 'url' => route('clients.index')],
      ['label' => 'Nouveau', 'url' => null],
    ]" />

    <div class="max-w-3xl mx-auto">
      <!-- En-tête -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Ajouter un Nouveau Client</h2>
        <p class="text-gray-600 mt-1">Renseignez les informations du client</p>
      </div>

      <div class="card">
        <div class="p-6">
          <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <!-- Informations de base -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de Base</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom complet <span class="text-danger-600">*</span>
                  </label>
                  <input type="text" name="nom" value="{{ old('nom') }}" required
                    class="input-field @error('nom') border-danger-500 @enderror"
                    placeholder="Ex: Jean Dupont, Entreprise SARL...">
                  @error('nom')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Type -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type de client <span class="text-danger-600">*</span>
                  </label>
                  <select name="type" required class="input-field @error('type') border-danger-500 @enderror">
                    <option value="">Sélectionnez...</option>
                    <option value="particulier" {{ old('type') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="entreprise" {{ old('type') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                    <option value="detaillant" {{ old('type') == 'detaillant' ? 'selected' : '' }}>Détaillant</option>
                  </select>
                  @error('type')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Statut -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                  <div class="flex items-center h-10">
                    <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                      class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="actif" class="ml-2 text-sm text-gray-700">Client actif</label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Contact -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Coordonnées</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Téléphone -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Téléphone <span class="text-danger-600">*</span>
                  </label>
                  <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                    class="input-field @error('telephone') border-danger-500 @enderror" placeholder="+237 6XX XXX XXX">
                  @error('telephone')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email (optionnel)
                  </label>
                  <input type="email" name="email" value="{{ old('email') }}"
                    class="input-field @error('email') border-danger-500 @enderror" placeholder="email@exemple.com">
                  @error('email')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse (optionnel)
                  </label>
                  <textarea name="adresse" rows="2" class="input-field @error('adresse') border-danger-500 @enderror"
                    placeholder="Adresse complète...">{{ old('adresse') }}</textarea>
                  @error('adresse')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Crédit -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Limite de Crédit</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Montant maximum (optionnel)
                  </label>
                  <div class="relative">
                    <input type="number" name="credit_limite" value="{{ old('credit_limite', 0) }}" min="0" step="1000"
                      class="input-field pr-20 @error('credit_limite') border-danger-500 @enderror">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500 text-sm">FCFA</span>
                    </div>
                  </div>
                  @error('credit_limite')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                  <p class="mt-1 text-xs text-gray-500">Laisser à 0 pour désactiver le crédit</p>
                </div>

                <div class="flex items-end">
                  <div class="p-4 bg-info-50 rounded-lg border border-info-200 w-full">
                    <p class="text-sm text-info-800">
                      <strong>Info :</strong> Le crédit permet au client d'acheter maintenant et de payer plus tard.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
              <a href="{{ route('clients.index') }}" class="btn-secondary">
                Annuler
              </a>
              <button type="submit" class="btn-primary">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Créer le Client
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection