@extends('layouts.app')

@section('title', 'Paramètres Système')
@section('page-title', 'Paramètres Système')

@section('content')
  <div class="fade-in">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-900">Paramètres Système</h2>
      <p class="text-gray-600 mt-1">Configurez les paramètres globaux de l'application</p>
    </div>

    <form action="{{ route('parametres.update') }}" method="POST">
      @csrf

      <div class="space-y-6">
        <!-- Informations de l'entreprise -->
        @if(isset($parametres['entreprise']))
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                </svg>
                Informations de l'Entreprise
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($parametres['entreprise'] as $parametre)
                  <div class="{{ in_array($parametre->cle, ['entreprise_adresse']) ? 'md:col-span-2' : '' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      {{ $parametre->description }}
                    </label>
                    @if($parametre->type === 'text' && $parametre->cle === 'entreprise_adresse')
                      <textarea name="parametres[{{ $parametre->cle }}]" rows="2"
                        class="input-field">{{ old('parametres.' . $parametre->cle, $parametre->valeur) }}</textarea>
                    @else
                      <input type="{{ $parametre->type === 'number' ? 'number' : 'text' }}"
                        name="parametres[{{ $parametre->cle }}]"
                        value="{{ old('parametres.' . $parametre->cle, $parametre->valeur) }}" class="input-field"
                        step="{{ $parametre->type === 'number' ? 'any' : '' }}">
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Configuration TVA -->
        @if(isset($parametres['tva']))
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-success-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                Configuration TVA
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($parametres['tva'] as $parametre)
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      {{ $parametre->description }}
                    </label>
                    <div class="relative">
                      <input type="{{ $parametre->type === 'number' ? 'number' : 'text' }}"
                        name="parametres[{{ $parametre->cle }}]"
                        value="{{ old('parametres.' . $parametre->cle, $parametre->valeur) }}"
                        class="input-field {{ $parametre->type === 'number' ? 'pr-12' : '' }}"
                        step="{{ $parametre->type === 'number' ? '0.01' : '' }}">
                      @if($parametre->type === 'number' && $parametre->cle === 'tva_taux_defaut')
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                          <span class="text-gray-500 text-sm">%</span>
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="mt-4 p-3 bg-info-50 rounded-lg border border-info-200">
                <p class="text-sm text-info-900">
                  <strong>Note :</strong> Le taux de TVA par défaut au Cameroun est de 19.25%
                </p>
              </div>
            </div>
          </div>
        @endif

        <!-- Paramètres de Caisse -->
        @if(isset($parametres['caisse']))
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-warning-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                </svg>
                Paramètres de Caisse
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($parametres['caisse'] as $parametre)
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      {{ $parametre->description }}
                    </label>
                    <div class="relative">
                      <input type="number" name="parametres[{{ $parametre->cle }}]"
                        value="{{ old('parametres.' . $parametre->cle, $parametre->valeur) }}" class="input-field pr-20"
                        step="1000">
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 text-sm">FCFA</span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Paramètres Généraux -->
        @if(isset($parametres['general']))
          <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
              <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-info-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Paramètres Généraux
              </h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($parametres['general'] as $parametre)
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      {{ $parametre->description }}
                    </label>
                    @if($parametre->type === 'boolean')
                      <div class="flex items-center h-10">
                        <input type="checkbox" name="parametres[{{ $parametre->cle }}]" id="{{ $parametre->cle }}" value="1" {{ old('parametres.' . $parametre->cle, $parametre->valeur) ? 'checked' : '' }}
                          class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="{{ $parametre->cle }}" class="ml-2 text-sm text-gray-700">
                          Activer
                        </label>
                      </div>
                    @else
                      <input type="{{ $parametre->type === 'number' ? 'number' : 'text' }}"
                        name="parametres[{{ $parametre->cle }}]"
                        value="{{ old('parametres.' . $parametre->cle, $parametre->valeur) }}" class="input-field"
                        step="{{ $parametre->type === 'number' ? 'any' : '' }}">
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      </div>

      <!-- Boutons -->
      <div
        class="mt-6 flex items-center justify-end gap-3 sticky bottom-0 bg-white p-4 border-t border-gray-200 shadow-lg rounded-lg">
        <a href="{{ route('dashboard') }}" class="btn-secondary">
          Annuler
        </a>
        <button type="submit" class="btn-primary">
          <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Enregistrer les Paramètres
        </button>
      </div>
    </form>
  </div>
@endsection