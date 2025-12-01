<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ristourne extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'date_debut',
        'date_fin',
        'periode_libelle',
        'montant_achats_ht',
        'taux_ristourne',
        'montant_ristourne',
        'statut',
        'date_validation',
        'date_paiement',
        'details',
        'notes',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_validation' => 'date',
        'date_paiement' => 'date',
        'montant_achats_ht' => 'decimal:2',
        'taux_ristourne' => 'decimal:2',
        'montant_ristourne' => 'decimal:2',
        'details' => 'array',
    ];

    // ==================== Relations ====================

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    // ==================== Scopes ====================

    public function scopeCalculees($query)
    {
        return $query->where('statut', 'calculee');
    }

    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopePayees($query)
    {
        return $query->where('statut', 'payee');
    }

    public function scopeEnAttente($query)
    {
        return $query->whereIn('statut', ['calculee', 'validee']);
    }

    public function scopeParPeriode($query, $debut, $fin)
    {
        return $query->where('date_debut', '>=', $debut)
            ->where('date_fin', '<=', $fin);
    }

    public function scopeParFournisseur($query, $fournisseurId)
    {
        return $query->where('fournisseur_id', $fournisseurId);
    }

    // ==================== Accesseurs ====================

    public function getStatutLibelleAttribute(): string
    {
        return match ($this->statut) {
            'calculee' => 'Calculée',
            'validee' => 'Validée',
            'payee' => 'Payée',
            'annulee' => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        return match ($this->statut) {
            'calculee' => 'info',
            'validee' => 'warning',
            'payee' => 'success',
            'annulee' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatutIconeAttribute(): string
    {
        return match ($this->statut) {
            'calculee' => '🔢',
            'validee' => '✓',
            'payee' => '💰',
            'annulee' => '✗',
            default => '?'
        };
    }

    // ==================== Méthodes Helper ====================

    public function estCalculee(): bool
    {
        return $this->statut === 'calculee';
    }

    public function estValidee(): bool
    {
        return $this->statut === 'validee';
    }

    public function estPayee(): bool
    {
        return $this->statut === 'payee';
    }

    public function peutEtreValidee(): bool
    {
        return $this->statut === 'calculee';
    }

    public function peutEtrePayee(): bool
    {
        return $this->statut === 'validee';
    }

    public function valider(): bool
    {
        if (!$this->peutEtreValidee()) {
            return false;
        }

        $this->statut = 'validee';
        $this->date_validation = now();
        return $this->save();
    }

    public function marquerPayee($datePaiement = null): bool
    {
        if (!$this->peutEtrePayee()) {
            return false;
        }

        $this->statut = 'payee';
        $this->date_paiement = $datePaiement ?? now();
        return $this->save();
    }

    public function annuler(string $motif = null): bool
    {
        if ($this->statut === 'payee') {
            return false; // Ne peut pas annuler une ristourne déjà payée
        }

        $this->statut = 'annulee';
        if ($motif) {
            $this->notes = ($this->notes ? $this->notes . "\n" : '') . "Annulation: " . $motif;
        }

        return $this->save();
    }

    // ==================== Méthodes Statiques ====================

    public static function calculerPourFournisseur($fournisseurId, $dateDebut, $dateFin, $periodeLibelle)
    {
        // Récupérer le total des achats HT pour la période
        $totalAchatsHT = \DB::table('commandes')
            ->where('fournisseur_id', $fournisseurId)
            ->where('statut', 'livree')
            ->whereBetween('date_livraison_reelle', [$dateDebut, $dateFin])
            ->sum('montant_ht');

        // Trouver le barème applicable
        $bareme = BaremeRistourne::where('fournisseur_id', $fournisseurId)
            ->actif()
            ->where('seuil_min', '<=', $totalAchatsHT)
            ->where(function ($q) use ($totalAchatsHT) {
                $q->whereNull('seuil_max')
                    ->orWhere('seuil_max', '>=', $totalAchatsHT);
            })
            ->orderBy('taux', 'desc')
            ->first();

        $tauxRistourne = $bareme ? $bareme->taux : 0;
        $montantRistourne = $totalAchatsHT * ($tauxRistourne / 100);

        // Créer ou mettre à jour la ristourne
        return static::updateOrCreate(
            [
                'fournisseur_id' => $fournisseurId,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
            ],
            [
                'periode_libelle' => $periodeLibelle,
                'montant_achats_ht' => $totalAchatsHT,
                'taux_ristourne' => $tauxRistourne,
                'montant_ristourne' => $montantRistourne,
                'statut' => 'calculee',
                'details' => [
                    'bareme_id' => $bareme?->id,
                    'bareme_nom' => $bareme?->nom,
                    'date_calcul' => now()->toDateTimeString(),
                ],
            ]
        );
    }

    public static function statistiquesGlobales(): array
    {
        return [
            'total_calculees' => static::calculees()->sum('montant_ristourne'),
            'total_validees' => static::validees()->sum('montant_ristourne'),
            'total_payees' => static::payees()->sum('montant_ristourne'),
            'nombre_en_attente' => static::enAttente()->count(),
            'total_en_attente' => static::enAttente()->sum('montant_ristourne'),
        ];
    }
}
