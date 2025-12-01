<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PointVente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Vérifier l'accès admin
     */
    private function checkAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs');
        }
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = User::with('pointVente');

        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre par statut
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'vendeurs' => User::where('role', 'vendeur')->count(),
            'actifs' => User::where('actif', true)->count(),
            'inactifs' => User::where('actif', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $this->checkAdmin();

        // ✅ CORRECTION : Récupérer TOUS les points de vente actifs
        $pointsVente = PointVente::actif()
            ->orderBy('nom')
            ->get();

        return view('admin.users.create', compact('pointsVente'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,responsable,vendeur',
            'actif' => 'boolean',
        ];

        // ✅ CORRECTION : Point de vente pour tous les rôles (sauf si admin ne veut pas)
        if (in_array($request->role, ['admin', 'vendeur', 'responsable'])) {
            $rules['point_vente_id'] = 'nullable|exists:points_vente,id';
        }

        $validated = $request->validate($rules);

        $validated['password'] = Hash::make($validated['password']);
        $validated['actif'] = $request->has('actif');
        $validated['email_verified_at'] = now();

        // Si admin sans point de vente spécifié, mettre à null
        if ($validated['role'] === 'admin' && !$request->filled('point_vente_id')) {
            $validated['point_vente_id'] = null;
        }

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Utilisateur {$user->name} créé avec succès !");
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $this->checkAdmin();

        $user->load(['pointVente', 'ventes' => function ($query) {
            $query->where('statut', 'completee')->latest()->limit(10);
        }]);

        // Statistiques de l'utilisateur
        $stats = [
            'ventes_total' => $user->ventes()->count(),
            'ventes_mois' => $user->ventes()
                ->whereMonth('date_vente', now()->month)
                ->whereYear('date_vente', now()->year)
                ->count(),
            'ca_total' => $user->ventes()->where('statut', 'completee')->sum('montant_total'),
            'ca_mois' => $user->ventes()
                ->where('statut', 'completee')
                ->whereMonth('date_vente', now()->month)
                ->whereYear('date_vente', now()->year)
                ->sum('montant_total'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(User $user)
    {
        $this->checkAdmin();

        // ✅ CORRECTION : Récupérer TOUS les points de vente actifs
        $pointsVente = PointVente::actif()
            ->orderBy('nom')
            ->get();

        return view('admin.users.edit', compact('user', 'pointsVente'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,responsable,vendeur',
            'actif' => 'boolean',
        ];

        // Point de vente pour tous les rôles
        if (in_array($request->role, ['admin', 'vendeur', 'responsable'])) {
            $rules['point_vente_id'] = 'nullable|exists:points_vente,id';
        }

        $validated = $request->validate($rules);

        $validated['actif'] = $request->has('actif');

        // Si admin sans point de vente spécifié, mettre à null
        if ($validated['role'] === 'admin' && !$request->filled('point_vente_id')) {
            $validated['point_vente_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès !');
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request, User $user)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Mot de passe réinitialisé avec succès !');
    }

    /**
     * Basculer le statut actif/inactif
     */
    public function toggleStatus(User $user)
    {
        $this->checkAdmin();

        // Empêcher de se désactiver soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte !');
        }

        $user->actif = !$user->actif;
        $user->save();

        $status = $user->actif ? 'activé' : 'désactivé';
        return back()->with('success', "Utilisateur {$status} avec succès.");
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $user)
    {
        $this->checkAdmin();

        // Empêcher de se supprimer soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte !');
        }

        // Vérifier si l'utilisateur a des ventes
        if ($user->ventes()->exists()) {
            return back()->with('error', 'Impossible de supprimer un utilisateur ayant effectué des ventes. Désactivez-le plutôt.');
        }

        $nom = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Utilisateur {$nom} supprimé avec succès !");
    }
}
