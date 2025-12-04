<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Rules\RecaptchaRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // // ✅ VALIDER LE RECAPTCHA
        // $request->validate([
        //     'g-recaptcha-response' => ['required', new RecaptchaRule()],
        // ], [
        //     'g-recaptcha-response.required' => 'Veuillez valider le reCAPTCHA.',
        // ]);
        // dd($request);
        $request->authenticate();

        // ✅ VÉRIFIER SI L'UTILISATEUR EST ACTIF
        if (!Auth::user()->actif) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez un administrateur.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
