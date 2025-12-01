@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')
    <div class="fade-in">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Informations du profil -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du Profil</h3>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input-field">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="input-field">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror

                        @if($user->email_verified_at === null)
                            <div class="mt-2 p-3 bg-orange-50 rounded-md">
                                <p class="text-sm text-orange-800">
                                    ⚠️ Votre email n'est pas vérifié.
                                <form method="POST" action="{{ route('verification.send') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="underline text-orange-800 hover:text-orange-900">
                                        Renvoyer l'email de vérification
                                    </button>
                                </form>
                                </p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <input type="text" value="{{ $user->role_libelle }}" disabled class="input-field bg-gray-100">
                    </div>

                    @if($user->pointVente)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Point de Vente</label>
                            <input type="text" value="{{ $user->pointVente->nom }}" disabled class="input-field bg-gray-100">
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Membre depuis</label>
                        <input type="text" value="{{ $user->created_at->format('d/m/Y') }}" disabled
                            class="input-field bg-gray-100">
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="btn-primary">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Enregistrer les modifications
                        </button>

                        @if(session('status') === 'profile-updated')
                            <p class="text-sm text-green-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Profil mis à jour !
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <svg class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Changer le Mot de Passe
                </h3>

                <p class="text-sm text-gray-600 mb-4">
                    Assurez-vous d'utiliser un mot de passe long et sécurisé pour protéger votre compte.
                </p>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                        <input type="password" name="current_password" required class="input-field"
                            autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                        <input type="password" name="password" required class="input-field" autocomplete="new-password">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                        @error('password', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de
                            passe</label>
                        <input type="password" name="password_confirmation" required class="input-field"
                            autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="btn-primary">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Mettre à jour le mot de passe
                        </button>

                        @if(session('status') === 'password-updated')
                            <p class="text-sm text-green-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mot de passe mis à jour !
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Supprimer le compte -->
            <div class="card p-6 border-2 border-red-200 bg-red-50">
                <h3 class="text-lg font-semibold text-red-900 mb-2 flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    Zone Dangereuse
                </h3>

                <p class="text-sm text-red-800 mb-4">
                    ⚠️ <strong>Attention :</strong> La suppression de votre compte est <strong>définitive et
                        irréversible</strong>.
                    Toutes vos données (ventes, caisses, historique) seront <strong>supprimées définitivement</strong>.
                </p>

                <div x-data="{ showConfirm: false, password: '' }">
                    <button type="button" @click="showConfirm = true"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <svg class="h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Supprimer mon compte
                    </button>

                    <!-- Modal de confirmation -->
                    <div x-show="showConfirm" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
                        @click.away="showConfirm = false">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="fixed inset-0 bg-gray-900 opacity-75"></div>

                            <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                                <h3 class="text-lg font-semibold text-red-900 mb-4">
                                    Confirmer la suppression du compte
                                </h3>

                                <p class="text-sm text-gray-700 mb-4">
                                    Cette action est <strong>irréversible</strong>. Entrez votre mot de passe pour confirmer
                                    la suppression de votre compte.
                                </p>

                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('DELETE')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                        <input type="password" name="password" x-model="password" required
                                            class="input-field" placeholder="Votre mot de passe">
                                        @error('password', 'userDeletion')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="button" @click="showConfirm = false; password = ''"
                                            class="flex-1 btn-secondary">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            Confirmer la suppression
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection