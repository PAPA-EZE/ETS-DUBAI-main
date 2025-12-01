@extends('layouts.app')

@section('title', 'Nouveau Fournisseur')
@section('page-title', 'Nouveau Fournisseur')

@section('content')
    <div class="fade-in">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('fournisseurs.index') }}"
                        class="text-gray-700 hover:text-primary-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m0 0a1.125 1.125 0 011.125-1.125h1.5c.621 0 1.125.504 1.125 1.125M6.75 14.25a1.125 1.125 0 011.125-1.125h4.125c.621 0 1.125.504 1.125 1.125M6.75 14.25V9.375a1.125 1.125 0 011.125-1.125h4.125M6.75 14.25V12a9 9 0 019-9" />
                        </svg>
                        Fournisseurs
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-1 text-gray-500">Nouveau fournisseur</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="page-header">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nouveau Fournisseur</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Ajoutez un nouveau fournisseur à votre système
                </p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="max-w-4xl">
            <form method="POST" action="{{ route('fournisseurs.store') }}" class="space-y-6">
                @csrf

                <!-- Informations de base -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Informations générales</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nom -->
                        <div class="sm:col-span-2">
                            <label for="nom" class="block text-sm font-medium text-gray-700">
                                Nom du fournisseur <span class="text-danger-500">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                                class="mt-1 input-field @error('nom') border-danger-300 @enderror"
                                placeholder="Ex: Brasseries du Cameroun">
                            @error('nom')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700">
                                Téléphone
                            </label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}"
                                class="mt-1 input-field @error('telephone') border-danger-300 @enderror"
                                placeholder="Ex: +237 6XX XX XX XX">
                            @error('telephone')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 input-field @error('email') border-danger-300 @enderror"
                                placeholder="contact@fournisseur.com">
                            @error('email')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="sm:col-span-2">
                            <label for="adresse" class="block text-sm font-medium text-gray-700">
                                Adresse complète
                            </label>
                            <textarea name="adresse" id="adresse" rows="3"
                                class="mt-1 input-field @error('adresse') border-danger-300 @enderror"
                                placeholder="Adresse complète du fournisseur...">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Conditions commerciales -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Conditions commerciales</h3>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Conditions de paiement -->
                        <div>
                            <label for="conditions_paiement" class="block text-sm font-medium text-gray-700">
                                Conditions de paiement <span class="text-danger-500">*</span>
                            </label>
                            <select name="conditions_paiement" id="conditions_paiement"
                                class="mt-1 input-field @error('conditions_paiement') border-danger-300 @enderror">
                                <option value="">Sélectionnez...</option>
                                <option value="comptant" {{ old('conditions_paiement') === 'comptant' ? 'selected' : '' }}>
                                    Comptant
                                </option>
                                <option value="30_jours" {{ old('conditions_paiement') === '30_jours' ? 'selected' : '' }}>
                                    30 jours
                                </option>
                                <option value="60_jours" {{ old('conditions_paiement') === '60_jours' ? 'selected' : '' }}>
                                    60 jours
                                </option>
                                <option value="90_jours" {{ old('conditions_paiement') === '90_jours' ? 'selected' : '' }}>
                                    90 jours
                                </option>
                            </select>
                            @error('conditions_paiement')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Taux de ristourne -->
                        <div>
                            <label for="taux_ristourne_defaut" class="block text-sm font-medium text-gray-700">
                                Taux de ristourne par défaut (%) <span class="text-danger-500">*</span>
                            </label>
                            <div class="mt-1 relative">
                                <input type="number" name="taux_ristourne_defaut" id="taux_ristourne_defaut"
                                    value="{{ old('taux_ristourne_defaut', '0') }}" min="0" max="100" step="0.01"
                                    class="input-field pr-8 @error('taux_ristourne_defaut') border-danger-300 @enderror">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Pourcentage de ristourne appliqué sur les achats
                            </p>
                            @error('taux_ristourne_defaut')
                                <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="card p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Statut</h3>

                    <div class="flex items-center">
                        <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="actif" class="ml-2 block text-sm text-gray-900">
                            Fournisseur actif
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Les fournisseurs inactifs n'apparaîtront pas dans les listes de sélection
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('fournisseurs.index') }}" class="btn-secondary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Créer le fournisseur
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection