<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    /**
     * Vérifier l'accès admin
     */
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }
    }

    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        $this->checkAdmin();

        // Récupérer tous les paramètres groupés par catégorie
        $parametres = Parametre::all()->groupBy('categorie');

        return view('parametres.index', compact('parametres'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function update(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'parametres' => 'required|array',
            'parametres.*' => 'nullable',
        ]);

        foreach ($validated['parametres'] as $cle => $valeur) {
            Parametre::set($cle, $valeur);
        }

        // Vider le cache
        Parametre::clearCache();

        return back()->with('success', 'Paramètres mis à jour avec succès !');
    }
}
