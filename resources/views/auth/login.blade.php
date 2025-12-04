<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETABLISSEMENT DUBAI - Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-100: #dbeafe;
            --success-600: #16a34a;
            --success-100: #dcfce7;
            --info-600: #0891b2;
            --info-100: #cffafe;
            --danger-600: #dc2626;
            --danger-100: #fee2e2;
            --accent-600: #9333ea;
            --accent-100: #f3e8ff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--info-600) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-brand {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-600) 50%, var(--info-600) 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1) rotate(0deg);
            }

            50% {
                transform: scale(1.1) rotate(180deg);
            }
        }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .brand-icon svg {
            width: 36px;
            height: 36px;
            fill: var(--primary-600);
        }

        .brand-title {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 18px;
            opacity: 0.9;
            font-weight: 300;
        }

        .brand-description {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.85;
            margin-top: 20px;
        }

        .features-list {
            margin-top: 40px;
            list-style: none;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            font-size: 15px;
            opacity: 0.9;
        }

        .features-list li svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .login-form-section {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 15px;
            color: var(--gray-600);
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-success {
            background: var(--success-100);
            color: var(--success-600);
            border: 1px solid var(--success-600);
        }

        .alert-danger {
            background: var(--danger-100);
            color: var(--danger-600);
            border: 1px solid var(--danger-600);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--gray-400);
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            font-size: 15px;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-600);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        .error-message {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            color: var(--danger-600);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: var(--primary-600);
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--gray-700);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 14px;
            color: var(--primary-600);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--primary-700);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--info-600) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit svg {
            width: 20px;
            height: 20px;
            margin-left: 8px;
        }

        .form-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
            text-align: center;
            font-size: 13px;
            color: var(--gray-500);
        }

        @media (max-width: 968px) {
            .login-brand {
                display: none;
            }

            .login-form-section {
                padding: 40px 30px;
            }
        }

        @media (max-width: 480px) {
            .login-form-section {
                padding: 30px 20px;
            }

            .form-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Section Branding -->
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-logo">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <div>
                        <div class="brand-title">DUBAI</div>
                        <div class="brand-subtitle">Établissement</div>
                    </div>
                </div>

                <p class="brand-description">
                    Système de gestion moderne pour dépôts de boissons. Optimisez vos ventes, gérez votre inventaire et
                    suivez vos performances en temps réel.
                </p>

                <ul class="features-list">
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Gestion multi-points de vente
                    </li>
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Suivi en temps réel des stocks
                    </li>
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Tableaux de bord analytiques
                    </li>
                    <li>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Rapports détaillés et exports
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section Formulaire -->
        <div class="login-form-section">
            <div class="form-header">
                <h1 class="form-title">Connexion</h1>
                <p class="form-subtitle">Accédez à votre espace de gestion</p>
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" >
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Adresse email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="form-input" placeholder="votre.email@exemple.cm">
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <input id="password" type="password" name="password" required class="form-input"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Options -->
                <div class="form-options">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" class="checkbox-label">Se souvenir de moi</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <!-- LE RECAPTCHA -->
                {{-- <x-recaptcha /> --}}

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Se connecter
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <div class="form-footer">
                © 2025 Établissement Dubai. Tous droits réservés.
            </div>
        </div>
    </div>
</body>

</html>