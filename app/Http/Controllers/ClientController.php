<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Afficher la liste des clients
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par statut
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        // Filtre par crédit
        if ($request->filled('credit')) {
            switch ($request->credit) {
                case 'avec':
                    $query->avecCredit();
                    break;
                case 'sans':
                    $query->where('credit_limite', '<=', 0);
                    break;
                case 'depassement':
                    $query->enDepassement();
                    break;
            }
        }

        // Tri
        $sortField = $request->get('sort', 'nom');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $clients = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = Client::statistiquesGlobales();

        return view('clients.index', compact('clients', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:500',
            'type' => 'required|in:particulier,entreprise,detaillant',
            'credit_limite' => 'nullable|numeric|min:0',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['credit_limite'] = $validated['credit_limite'] ?? 0;
        $validated['solde_actuel'] = 0;

        $client = Client::create($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', "Client {$client->nom} créé avec succès !");
    }

    /**
     * Afficher les détails d'un client
     */
    public function show(Client $client)
    {
        $client->load(['ventes' => function ($query) {
            $query->with('lignes.produit')->latest('date_vente')->limit(10);
        }]);

        $stats = $client->calculerStatistiques();

        // Top produits achetés
        $topProduits = DB::table('lignes_vente')
            ->join('ventes', 'lignes_vente.vente_id', '=', 'ventes.id')
            ->join('produits', 'lignes_vente.produit_id', '=', 'produits.id')
            ->where('ventes.client_id', $client->id)
            ->where('ventes.statut', 'completee')
            ->whereNull('ventes.deleted_at')
            ->select(
                'produits.nom',
                DB::raw('SUM(lignes_vente.quantite) as total_quantite'),
                DB::raw('SUM(lignes_vente.montant_total) as total_montant')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderBy('total_montant', 'desc')
            ->limit(5)
            ->get();

        return view('clients.show', compact('client', 'stats', 'topProduits'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:500',
            'type' => 'required|in:particulier,entreprise,detaillant',
            'credit_limite' => 'nullable|numeric|min:0',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['credit_limite'] = $validated['credit_limite'] ?? 0;

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client mis à jour avec succès !');
    }

    /**
     * Supprimer un client
     */
    public function destroy(Client $client)
    {
        // Vérifier s'il a des ventes
        if ($client->ventes()->exists()) {
            return back()->with('error', 'Impossible de supprimer un client ayant des ventes enregistrées. Désactivez-le plutôt.');
        }

        $nom = $client->nom;
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', "Client {$nom} supprimé avec succès !");
    }

    /**
     * Ajuster le crédit d'un client
     */
    public function ajusterCredit(Request $request, Client $client)
    {
        $validated = $request->validate([
            'type' => 'required|in:paiement,ajustement',
            'montant' => 'required|numeric|min:0',
            'motif' => 'nullable|string|max:500',
        ]);

        if ($validated['type'] === 'paiement') {
            $client->reduireCredit($validated['montant']);
            $message = "Paiement de {$validated['montant']} FCFA enregistré";
        } else {
            $client->ajouterCredit($validated['montant']);
            $message = "Ajustement de +{$validated['montant']} FCFA effectué";
        }

        return redirect()->route('clients.show', $client)
            ->with('success', $message);
    }
}
