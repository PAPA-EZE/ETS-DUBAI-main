<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaremeRistourne extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'nom',
        'seuil_min',
        'seuil_max',
        'taux',
        'type_periode',
        'actif',
        'date_debut',
        'date_fin',
        'description',
    ];

    protected $casts = [
        'seuil_min' => 'decimal:2',
        'seuil_max' => 'decimal:2',
        'taux' => 'decimal:2',
        'actif' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    // ==================== Relations ====================

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    // ==================== Scopes ====================

    public function scopeActif($query)
    {
        return $query->where('actif', true)
            ->where(function ($q) {
                $q->whereNull('date_debut')
                    ->orWhere('date_debut', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('date_fin')
                    ->orWhere('date_fin', '>=', now());
            });
    }

    public function scopeInactif($query)
    {
        return $query->where('actif', false);
    }

    public function scopeParFournisseur($query, $fournisseurId)
    {
        return $query->where('fournisseur_id', $fournisseurId);
    }

    public function scopeParTypePeriode($query, $type)
    {
        return $query->where('type_periode', $type);
    }

    // ==================== Accesseurs ====================

    public function getTypePeriodeLibelleAttribute(): string
    {
        return match ($this->type_periode) {
            'mensuel' => 'Mensuel',
            'trimestriel' => 'Trimestriel',
            'semestriel' => 'Semestriel',
            'annuel' => 'Annuel',
            default => 'Inconnu'
        };
    }

    public function getSeuilMaxFormatAttribute(): string
    {
        return $this->seuil_max ? number_format($this->seuil_max, 0, ',', ' ') . ' FCFA' : 'Illimité';
    }

    // ==================== Méthodes Helper ====================

    public function estActif(): bool
    {
        if (!$this->actif) {
            return false;
        }

        if ($this->date_debut && $this->date_debut->isFuture()) {
            return false;
        }

        if ($this->date_fin && $this->date_fin->isPast()) {
            return false;
        }

        return true;
    }

    public function correspondA(float $montant): bool
    {
        if ($montant < $this->seuil_min) {
            return false;
        }

        if ($this->seuil_max && $montant > $this->seuil_max) {
            return false;
        }

        return true;
    }

    public function calculerRistourne(float $montantAchats): float
    {
        if (!$this->correspondA($montantAchats)) {
            return 0;
        }

        return $montantAchats * ($this->taux / 100);
    }
}
