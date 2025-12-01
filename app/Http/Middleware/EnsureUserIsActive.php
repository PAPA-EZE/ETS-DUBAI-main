<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
  /**
   * Vérifier que l'utilisateur connecté est actif
   */
  public function handle(Request $request, Closure $next): Response
  {
    // Vérifier uniquement si l'utilisateur est authentifié
    if (Auth::check()) {
      $user = Auth::user();

      // Si l'utilisateur est inactif
      if (!$user->actif) {
        // Déconnecter l'utilisateur
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
          ->withErrors(['email' => 'Votre compte a été désactivé. Contactez un administrateur.']);
      }
    }

    return $next($request);
  }
}
