@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')
@section('page-title', 'Nouvel Utilisateur')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Utilisateurs', 'url' => route('admin.users.index')],
      ['label' => 'Nouveau', 'url' => null],
    ]" />

    <div class="max-w-3xl mx-auto">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Créer un Nouvel Utilisateur</h2>
        <p class="text-gray-600 mt-1">Ajoutez un nouveau membre à votre équipe</p>
      </div>

      <div class="card">
        <div class="p-6">
          <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
            @csrf

            <!-- Informations de base -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de Base</h3>

              <div class="space-y-6">
                <!-- Nom -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom complet <span class="text-danger-600">*</span>
                  </label>
                  <input type="text" name="name" value="{{ old('name') }}" required
                    class="input-field @error('name') border-danger-500 @enderror" placeholder="Ex: Jean Dupont">
                  @error('name')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse email <span class="text-danger-600">*</span>
                  </label>
                  <input type="email" name="email" value="{{ old('email') }}" required
                    class="input-field @error('email') border-danger-500 @enderror" placeholder="email@exemple.com">
                  @error('email')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Rôle -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rôle <span class="text-danger-600">*</span>
                  </label>
                  <select name="role" id="role" required class="input-field @error('role') border-danger-500 @enderror"
                    onchange="togglePointVente()">
                    <option value="">Sélectionnez un rôle...</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="responsable" {{ old('role') == 'responsable' ? 'selected' : '' }}>Responsable</option>
                    <option value="vendeur" {{ old('role') == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
                  </select>
                  @error('role')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                  <p class="mt-1 text-xs text-gray-500">
                    <strong>Admin :</strong> Accès complet au système.<br>
                    <strong>Responsable :</strong> Gestion des stocks, commandes et transferts.<br>
                    <strong>Vendeur :</strong> Accès aux ventes et caisses.
                  </p>
                </div>

                <!-- Point de Vente -->
                <div id="pointVenteField" style="display: none;">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Point de Vente <span class="text-danger-600">*</span>
                  </label>
                  <select name="point_vente_id" id="point_vente_id"
                    class="input-field @error('point_vente_id') border-danger-500 @enderror">
                    <option value="">Sélectionnez un point de vente...</option>
                    @foreach($pointsVente as $point)
                      <option value="{{ $point->id }}" {{ old('point_vente_id') == $point->id ? 'selected' : '' }}>
                        {{ $point->nom }} ({{ $point->code }})
                      </option>
                    @endforeach
                  </select>
                  @error('point_vente_id')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                  @if($pointsVente->isEmpty())
                    <p class="mt-1 text-sm text-warning-600">⚠️ Aucun point de vente disponible</p>
                  @else
                    <p class="mt-1 text-xs text-gray-500">
                      Le point de vente principal de l'utilisateur
                    </p>
                  @endif
                </div>
              </div>
            </div>

            <!-- Mot de passe -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Mot de Passe</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mot de passe <span class="text-danger-600">*</span>
                  </label>
                  <input type="password" name="password" required
                    class="input-field @error('password') border-danger-500 @enderror" placeholder="Minimum 8 caractères">
                  @error('password')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmer le mot de passe <span class="text-danger-600">*</span>
                  </label>
                  <input type="password" name="password_confirmation" required class="input-field"
                    placeholder="Confirmez le mot de passe">
                </div>
              </div>
            </div>

            <!-- Statut -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Statut du Compte</h3>

              <div class="flex items-center">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                  class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="actif" class="ml-2 text-sm text-gray-700">
                  Compte actif (l'utilisateur pourra se connecter)
                </label>
              </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
              <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                Annuler
              </a>
              <button type="submit" class="btn-primary">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Créer l'Utilisateur
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Afficher/masquer le champ point de vente selon le rôle
    function togglePointVente() {
      const role = document.getElementById('role').value;
      const pointVenteField = document.getElementById('pointVenteField');
      const pointVenteSelect = document.getElementById('point_vente_id');

      // Point de vente pour TOUS les rôles (admin, responsable, vendeur)
      if (role === 'admin' || role === 'responsable' || role === 'vendeur') {
        pointVenteField.style.display = 'block';
        pointVenteSelect.setAttribute('required', 'required');
      } else {
        pointVenteField.style.display = 'none';
        pointVenteSelect.removeAttribute('required');
        pointVenteSelect.value = '';
      }
    }

    // Appeler la fonction au chargement de la page si un rôle est déjà sélectionné
    document.addEventListener('DOMContentLoaded', function () {
      togglePointVente();
    });
  </script>
@endsection