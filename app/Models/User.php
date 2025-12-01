<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'actif',
        'point_vente_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'actif' => 'boolean',
    ];

    // ==================== Constantes ====================

    const ROLES = [
        'admin' => 'Administrateur',
        'responsable' => 'Responsable',
        'vendeur' => 'Vendeur',
    ];

    // ==================== Relations ====================

    public function ventes(): HasMany
    {
        return $this->hasMany(\App\Models\Vente::class);
    }

    public function pointVente(): BelongsTo
    {
        return $this->belongsTo(PointVente::class);
    }

    // ==================== Scopes ====================

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeResponsables($query)
    {
        return $query->where('role', 'responsable');
    }

    public function scopeVendeurs($query)
    {
        return $query->where('role', 'vendeur');
    }

    // ==================== Accesseurs ====================

    public function getRoleLibelleAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrateur',
            'responsable' => 'Responsable',
            'vendeur' => 'Vendeur',
            default => 'Utilisateur'
        };
    }

    // ==================== Méthodes Helper de Rôles ====================

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isResponsable(): bool
    {
        return $this->hasRole('responsable');
    }

    public function isVendeur(): bool
    {
        return $this->hasRole('vendeur');
    }

    public function estAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function estResponsable(): bool
    {
        return $this->role === 'responsable';
    }

    public function estVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    public function estActif(): bool
    {
        return $this->actif === true;
    }

    // ==================== Permissions ====================

    /**
     * Vérifier si l'utilisateur peut créer/modifier des produits
     */
    public function canManageProduits(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut faire des ventes
     */
    public function canMakeVentes(): bool
    {
        return in_array($this->role, ['vendeur', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les caisses
     */
    public function canManageCaisses(): bool
    {
        return in_array($this->role, ['vendeur', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut modifier les prix
     */
    public function canEditPrices(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les commandes
     */
    public function canManageCommandes(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les fournisseurs
     */
    public function canManageFournisseurs(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les ristournes
     */
    public function canManageRistournes(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut voir tous les rapports
     */
    public function canViewAllReports(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur peut voir les analyses vendeurs
     */
    public function canViewVendeurAnalyses(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur peut gérer les paramètres
     */
    public function canManageSettings(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur peut valider les transferts
     */
    public function canValidateTransferts(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les catégories
     */
    public function canManageCategories(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les clients
     */
    public function canManageClients(): bool
    {
        return in_array($this->role, ['admin', 'responsable', 'vendeur']);
    }

    /**
     * Vérifier si l'utilisateur peut annuler des ventes
     */
    public function canCancelVentes(): bool
    {
        return in_array($this->role, ['admin', 'responsable']);
    }
}
