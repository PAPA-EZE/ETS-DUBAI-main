<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsResponsable
{
  /**
   * Vérifier que l'utilisateur est responsable ou admin
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!auth()->check()) {
      return redirect()->route('login');
    }

    if (!in_array(auth()->user()->role, ['admin', 'responsable'])) {
      abort(403, 'Accès refusé. Cette fonctionnalité est réservée aux responsables et administrateurs.');
    }

    return $next($request);
  }
}
