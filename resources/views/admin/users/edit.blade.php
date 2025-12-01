@extends('layouts.app')

@section('title', 'Modifier ' . $user->name)
@section('page-title', 'Modifier l\'Utilisateur')

@section('content')
  <div class="fade-in">
    <x-breadcrumb :items="[
      ['label' => 'Utilisateurs', 'url' => route('admin.users.index')],
      ['label' => $user->name, 'url' => route('admin.users.show', $user)],
      ['label' => 'Modifier', 'url' => null],
    ]" />

    <div class="max-w-3xl mx-auto">
      <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Modifier l'Utilisateur</h2>
        <p class="text-gray-600 mt-1">{{ $user->name }}</p>
      </div>

      <div class="card">
        <div class="p-6">
          <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations de base -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de Base</h3>

              <div class="space-y-6">
                <!-- Nom -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nom complet <span class="text-danger-600">*</span>
                  </label>
                  <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="input-field @error('name') border-danger-500 @enderror">
                  @error('name')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse email <span class="text-danger-600">*</span>
                  </label>
                  <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="input-field @error('email') border-danger-500 @enderror">
                  @error('email')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Rôle -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rôle <span class="text-danger-600">*</span>
                  </label>
                  <select name="role" id="role" required class="input-field @error('role') border-danger-500 @enderror" {{ $user->id === auth()->id() ? 'disabled' : '' }} onchange="togglePointVente()">
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur
                    </option>
                    <option value="responsable" {{ old('role', $user->role) == 'responsable' ? 'selected' : '' }}>
                      Responsable</option>
                    <option value="vendeur" {{ old('role', $user->role) == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
                  </select>
                  @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p class="mt-1 text-xs text-warning-600">Vous ne pouvez pas modifier votre propre rôle</p>
                  @else
                    @error('role')
                      <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                  @endif
                </div>

                <!-- Point de Vente (visible uniquement si rôle = vendeur) -->
                <div id="pointVenteField" style="display: none;">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Point de Vente <span class="text-danger-600">*</span>
                  </label>
                  <select name="point_vente_id" id="point_vente_id"
                    class="input-field @error('point_vente_id') border-danger-500 @enderror">
                    <option value="">Sélectionnez un point de vente...</option>
                    @foreach($pointsVente as $point)
                      <option value="{{ $point->id }}" {{ old('point_vente_id', $user->point_vente_id) == $point->id ? 'selected' : '' }}>
                        {{ $point->nom }} ({{ $point->code }})
                      </option>
                    @endforeach
                  </select>
                  @error('point_vente_id')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                  @enderror
                  <p class="mt-1 text-xs text-gray-500">
                    Chaque point de vente ne peut être assigné qu'à un seul vendeur
                  </p>
                </div>
              </div>
            </div>

            <!-- Statut -->
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Statut du Compte</h3>

              <div class="flex items-center">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $user->actif) ? 'checked' : '' }}
                  {{ $user->id === auth()->id() ? 'disabled' : '' }}
                  class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="actif" class="ml-2 text-sm text-gray-700">
                  Compte actif (l'utilisateur pourra se connecter)
                </label>
                @if($user->id === auth()->id())
                  <input type="hidden" name="actif" value="1">
                @endif
              </div>
              @if($user->id === auth()->id())
                <p class="mt-2 text-xs text-warning-600">Vous ne pouvez pas désactiver votre propre compte</p>
              @endif
            </div>

            <!-- Info modification mot de passe -->
            <div class="mb-8 p-4 bg-info-50 rounded-lg border border-info-200">
              <p class="text-sm text-info-900">
                <strong>Note :</strong> Pour modifier le mot de passe, utilisez la fonctionnalité
                "Réinitialiser le mot de passe" sur la page de détails de l'utilisateur.
              </p>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
              <div>
                @if($user->id !== auth()->id() && !$user->ventes()->exists())
                  <button type="button"
                    onclick="if(confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) { document.getElementById('delete-form').submit(); }"
                    class="btn-danger">
                    Supprimer
                  </button>
                @endif
              </div>
              <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
                  Annuler
                </a>
                <button type="submit" class="btn-primary">
                  Enregistrer les Modifications
                </button>
              </div>
            </div>
          </form>

          <!-- Formulaire de suppression caché -->
          @if($user->id !== auth()->id() && !$user->ventes()->exists())
            <form id="delete-form" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
              @csrf
              @method('DELETE')
            </form>
          @endif
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

    // Appeler la fonction au chargement de la page
    document.addEventListener('DOMContentLoaded', function () {
      togglePointVente();
    });
  </script>
@endsection