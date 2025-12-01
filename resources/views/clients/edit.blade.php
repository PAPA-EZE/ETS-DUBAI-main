@extends('layouts.app')

@section('title', 'Modifier ' . $client->nom)
@section('page-title', 'Modifier le Client')

@section('content')
  <div class="fade-in">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
      ['label' => 'Clients', 'url' => route('clients.index')],
      ['label' => $client->nom, 'url' => route('clients.show', $client)],
      ['label' => 'Modifier', 'url' => null],
    ]" />

    <div class="max-w-3xl mx-auto">
      <!-- En-tête -->
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Modifier le Client</h2>
        <p class="text-gray-600 mt-1">{{ $client->nom }}</p>
      </div>

      <div class="card">
        <div class="p-6">
          <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations de base -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de Base</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom complet <span class="text-danger-600">*</span>
                  </label>
                  <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" required
                    class="input-field @error('nom') border-danger-500 @enderror">
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
                    <option value="particulier" {{ old('type', $client->type) == 'particulier' ? 'selected' : '' }}>
                      Particulier</option>
                    <option value="entreprise" {{ old('type', $client->type) == 'entreprise' ? 'selected' : '' }}>Entreprise
                    </option>
                    <option value="detaillant" {{ old('type', $client->type) == 'detaillant' ? 'selected' : '' }}>Détaillant
                    </option>
                  </select>
                  @error('type')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Statut -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                  <div class="flex items-center h-10">
                    <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $client->actif) ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
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
                  <input type="tel" name="telephone" value="{{ old('telephone', $client->telephone) }}" required
                    class="input-field @error('telephone') border-danger-500 @enderror">
                  @error('telephone')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email (optionnel)
                  </label>
                  <input type="email" name="email" value="{{ old('email', $client->email) }}"
                    class="input-field @error('email') border-danger-500 @enderror">
                  @error('email')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse (optionnel)
                  </label>
                  <textarea name="adresse" rows="2"
                    class="input-field @error('adresse') border-danger-500 @enderror">{{ old('adresse', $client->adresse) }}</textarea>
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
                    Montant maximum
                  </label>
                  <div class="relative">
                    <input type="number" name="credit_limite" value="{{ old('credit_limite', $client->credit_limite) }}"
                      min="0" step="1000" class="input-field pr-20 @error('credit_limite') border-danger-500 @enderror">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                      <span class="text-gray-500 text-sm">FCFA</span>
                    </div>
                  </div>
                  @error('credit_limite')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <div class="flex items-end">
                  <div class="p-4 bg-warning-50 rounded-lg border border-warning-200 w-full">
                    <p class="text-sm text-warning-800">
                      <strong>Attention :</strong> Le solde actuel est de
                      {{ number_format($client->solde_actuel, 0, ',', ' ') }} FCFA
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
              <div>
                @if(!$client->ventes()->exists())
                  <button type="button"
                    onclick="if(confirm('Voulez-vous vraiment supprimer ce client ?')) { document.getElementById('delete-form').submit(); }"
                    class="btn-danger">
                    Supprimer
                  </button>
                @endif
              </div>
              <div class="flex items-center gap-3">
                <a href="{{ route('clients.show', $client) }}" class="btn-secondary">
                  Annuler
                </a>
                <button type="submit" class="btn-primary">
                  Enregistrer les Modifications
                </button>
              </div>
            </div>
          </form>

          <!-- Formulaire de suppression caché -->
          @if(!$client->ventes()->exists())
            <form id="delete-form" action="{{ route('clients.destroy', $client) }}" method="POST" class="hidden">
              @csrf
              @method('DELETE')
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection