<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'conditions_paiement',
        'taux_ristourne_defaut',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'taux_ristourne_defaut' => 'decimal:2',
    ];

    // Relations
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    // Accesseurs
    public function getConditionsPaiementTextAttribute()
    {
        return match ($this->conditions_paiement) {
            'comptant' => 'Comptant',
            '30_jours' => '30 jours',
            '60_jours' => '60 jours',
            '90_jours' => '90 jours',
            default => 'Non défini'
        };
    }
    public function ristournes(): HasMany
    {
        return $this->hasMany(Ristourne::class);
    }

    public function baremes(): HasMany
    {
        return $this->hasMany(BaremeRistourne::class);
    }
}
