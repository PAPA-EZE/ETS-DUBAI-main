<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictPriceModification
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si c'est une requête de modification de produit
        if ($request->isMethod('put') || $request->isMethod('patch')) {
            $user = auth()->user();

            // Si l'utilisateur n'est pas admin et essaie de modifier les prix
            if ($user->role !== 'admin') {
                $champsProtege = [
                    'prix_achat_conditionnement',
                    'prix_vente_conditionnement',
                    'prix_achat_unite',
                    'prix_vente_unite',
                    'marge_conditionnement',
                    'marge_unite',
                ];

                // Retirer les champs prix de la requête
                foreach ($champsProtege as $champ) {
                    $request->request->remove($champ);
                }
            }
        }

        return $next($request);
    }
}
