<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Vente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_vente',
        'point_vente_id',
        'user_id',
        'client_id',
        'caisse_id',
        'date_vente',
        'type_paiement',
        'montant_ht',
        'montant_tva',
        'montant_total',
        'remise_globale',
        'montant_paye',
        'montant_rendu',
        'statut',
        'notes',
    ];

    protected $casts = [
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'remise_globale' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_rendu' => 'decimal:2',
        'date_vente' => 'datetime',
    ];

    // ==================== Boot ====================

    protected static function boot()
    {
        parent::boot();

        // Générer numero_vente automatiquement
        static::creating(function ($vente) {
            if (empty($vente->numero_vente)) {
                $vente->numero_vente = self::generateNumeroVente();
            }

            // Remplir point_vente_id automatiquement si manquant
            if (empty($vente->point_vente_id) && auth()->check()) {
                $vente->point_vente_id = auth()->user()->point_vente_id;
            }
        });
    }

    // ==================== Méthodes Helper ====================

    /**
     * Générer un numéro de vente unique
     */
    public static function generateNumeroVente(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'VTE-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Vérifier si la vente est complétée
     */
    public function estCompletee(): bool
    {
        return $this->statut === 'completee';
    }


    /**
     * Annuler la vente
     */
    public function annuler(string $motif): bool
    {
        if (!$this->peutEtreAnnulee()) {
            return false;
        }

        $this->statut = 'annulee';
        $this->notes = ($this->notes ? $this->notes . "\n" : '') . "Annulée: " . $motif;
        return $this->save();
    }

    /**
     * Calculer les totaux de la vente
     */
    public function calculerTotaux(): void
    {
        $montantHT = 0;
        $montantTVA = 0;

        foreach ($this->lignes as $ligne) {
            $montantHT += $ligne->montant_ht;
            $montantTVA += $ligne->montant_tva;
        }

        // Appliquer la remise globale
        if ($this->remise_globale > 0) {
            $tauxRemise = $this->remise_globale / 100;
            $montantHT -= ($montantHT * $tauxRemise);
            $montantTVA -= ($montantTVA * $tauxRemise);
        }

        $this->montant_ht = round($montantHT, 2);
        $this->montant_tva = round($montantTVA, 2);
        $this->montant_total = round($montantHT + $montantTVA, 2);
        $this->save();
    }

    // ==================== Relations ====================

    public function pointVente(): BelongsTo
    {
        return $this->belongsTo(PointVente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneVente::class);
    }

    // ==================== Scopes ====================

    public function scopeCompletees($query)
    {
        return $query->where('statut', 'completee');
    }

    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annulee');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeParPointVente($query, $pointVenteId)
    {
        return $query->where('point_vente_id', $pointVenteId);
    }

    public function scopeParVendeur($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }

    /**
     * Vérifier si la vente est un brouillon
     */
    public function estBrouillon(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Vérifier si la vente peut être validée
     */
    public function peutEtreValidee(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Vérifier si la vente peut être annulée
     */
    public function peutEtreAnnulee(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Valider la vente et mettre à jour les stocks
     */
    public function valider(int $userId): bool
    {
        if (!$this->peutEtreValidee()) {
            return false;
        }

        DB::beginTransaction();
        try {

            // dd($this->lignes);
            // Mettre à jour les stocks
            foreach ($this->lignes as $ligne) {
                if (!StockProduit::retirerStock(
                    $ligne->produit_id,
                    $this->point_vente_id,
                    $ligne->quantite
                )) {
                    throw new \Exception("Stock insuffisant pour {$ligne->produit->nom}");
                }

                // Mettre à jour stock_actuel du produit
                $produit = $ligne->produit;
                $produit->stock_actuel = StockProduit::getStockTotal($produit->id);
                $produit->save();
            }

            // Mettre à jour le statut
            $this->statut = 'completee';
            $this->date_validation = now();
            $this->valide_par = $userId;
            $this->save();

            // Mettre à jour crédit client si nécessaire
            if ($this->type_paiement === 'credit' && $this->client_id) {
                $this->client->ajouterCredit($this->montant_total);
            }

            // Mettre à jour la caisse
            if ($this->caisse) {
                $this->caisse->mettreAJourTotaux();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur validation vente', [
                'vente_id' => $this->id,
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Relation avec le validateur
     */
    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
