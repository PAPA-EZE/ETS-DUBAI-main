<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-center mb-6">Connexion</h2>

    <!-- Messages d'erreur -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-md">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold mb-1">Adresse Email</label>
            <input type="email" name="email" id="email"
                   class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                   required>
        </div>

        <!-- Mot de passe -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold mb-1">Mot de passe</label>
            <input type="password" name="password" id="password"
                   class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                   required>
        </div>

        <!-- Remember -->
        <div class="flex items-center mb-4">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm">Se souvenir de moi</label>
        </div>

        <!-- Bouton -->
        <button type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-lg">
            Se connecter
        </button>
    </form>

    <!-- Lien mot de passe oublié -->
    <div class="text-center mt-4">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-yellow-600 hover:underline">
                Mot de passe oublié ?
            </a>
        @endif
    </div>

</div>

</body>
</html>
