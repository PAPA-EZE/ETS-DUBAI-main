<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'type',
        'credit_limite',
        'solde_actuel',
        'actif',
    ];

    protected $casts = [
        'credit_limite' => 'decimal:2',
        'solde_actuel' => 'decimal:2',
        'actif' => 'boolean',
    ];

    // ==================== Relations ====================

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    // ==================== Scopes ====================

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeInactif($query)
    {
        return $query->where('actif', false);
    }

    public function scopeParticuliers($query)
    {
        return $query->where('type', 'particulier');
    }

    public function scopeEntreprises($query)
    {
        return $query->where('type', 'entreprise');
    }

    public function scopeDetaillants($query)
    {
        return $query->where('type', 'detaillant');
    }

    public function scopeAvecCredit($query)
    {
        return $query->where('credit_limite', '>', 0);
    }

    public function scopeEnDepassement($query)
    {
        return $query->whereColumn('solde_actuel', '>', 'credit_limite');
    }

    // ==================== Accesseurs ====================

    public function getTypeLibelleAttribute(): string
    {
        return match ($this->type) {
            'particulier' => 'Particulier',
            'entreprise' => 'Entreprise',
            'detaillant' => 'Détaillant',
            default => 'Inconnu'
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'particulier' => 'info',
            'entreprise' => 'primary',
            'detaillant' => 'success',
            default => 'secondary'
        };
    }

    public function getCreditDisponibleAttribute(): float
    {
        return max(0, $this->credit_limite - $this->solde_actuel);
    }

    public function getTauxUtilisationCreditAttribute(): float
    {
        if ($this->credit_limite <= 0) {
            return 0;
        }
        return ($this->solde_actuel / $this->credit_limite) * 100;
    }

    public function getStatutCreditAttribute(): string
    {
        if ($this->credit_limite <= 0) {
            return 'aucun';
        }

        $taux = $this->taux_utilisation_credit;

        if ($taux >= 100) {
            return 'depassement';
        } elseif ($taux >= 80) {
            return 'alerte';
        } elseif ($taux >= 50) {
            return 'modere';
        } else {
            return 'bon';
        }
    }

    // ==================== Méthodes Helper ====================

    public function estActif(): bool
    {
        return $this->actif === true;
    }

    public function aCredit(): bool
    {
        return $this->credit_limite > 0;
    }

    public function peutAcheterACredit(float $montant): bool
    {
        if (!$this->aCredit()) {
            return false;
        }

        return ($this->solde_actuel + $montant) <= $this->credit_limite;
    }

    public function ajouterCredit(float $montant): void
    {
        $this->solde_actuel += $montant;
        $this->save();
    }

    public function reduireCredit(float $montant): void
    {
        $this->solde_actuel = max(0, $this->solde_actuel - $montant);
        $this->save();
    }

    public function calculerStatistiques(): array
    {
        // Récupérer toutes les ventes complétées
        $ventesCompletees = $this->ventes()->where('statut', 'completee')->get();

        return [
            'nombre_achats' => $ventesCompletees->count(),
            'montant_total_achats' => $ventesCompletees->sum('montant_total'),
            'montant_moyen_achat' => $ventesCompletees->avg('montant_total') ?? 0,
            'dernier_achat' => $ventesCompletees->sortByDesc('date_vente')->first()?->date_vente,
        ];
    }

    // ==================== Méthodes Statiques ====================

    public static function statistiquesGlobales(): array
    {
        return [
            'total_clients' => static::count(),
            'clients_actifs' => static::actif()->count(),
            'clients_avec_credit' => static::avecCredit()->count(),
            'credit_total_accorde' => static::sum('credit_limite'),
            'credit_total_utilise' => static::sum('solde_actuel'),
            'clients_en_depassement' => static::enDepassement()->count(),
        ];
    }
}
