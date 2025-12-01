<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransfertStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transferts_stocks';

    protected $fillable = [
        'numero_transfert',
        'point_source_id',
        'point_destination_id',
        'demande_par',
        'valide_par',
        'statut',
        'date_demande',
        'date_validation',
        'date_expedition',
        'date_reception',
        'motif',
        'notes',
    ];

    protected $casts = [
        'date_demande' => 'datetime',
        'date_validation' => 'datetime',
        'date_expedition' => 'datetime',
        'date_reception' => 'datetime',
    ];

    // ==================== Relations ====================

    public function pointSource(): BelongsTo
    {
        return $this->belongsTo(PointVente::class, 'point_source_id');
    }

    public function pointDestination(): BelongsTo
    {
        return $this->belongsTo(PointVente::class, 'point_destination_id');
    }

    public function demandeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'demande_par');
    }

    public function valideur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(LigneTransfert::class, 'transfert_stock_id');
    }

    // ==================== Accesseurs ====================

    public function getStatutBadgeAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'valide' => 'bg-blue-100 text-blue-800',
            'expedie' => 'bg-purple-100 text-purple-800',
            'recu' => 'bg-green-100 text-green-800',
            'refuse' => 'bg-red-100 text-red-800',
            'annule' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatutLibelleAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'valide' => 'Validé',
            'expedie' => 'Expédié',
            'recu' => 'Reçu',
            'refuse' => 'Refusé',
            'annule' => 'Annulé',
            default => 'Inconnu'
        };
    }

    // ==================== Scopes ====================

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeExpediee($query)
    {
        return $query->where('statut', 'expedie');
    }

    public function scopeRecue($query)
    {
        return $query->where('statut', 'recu');
    }

    // ==================== Méthodes ====================

    /**
     * Générer un numéro de transfert unique
     */
    public static function genererNumero(): string
    {
        $dernier = self::latest('id')->first();
        $numero = $dernier ? $dernier->id + 1 : 1;
        return 'TRANS-' . date('Y') . '-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Valider le transfert (Admin/Responsable uniquement)
     */
    public function valider(User $valideur): bool
    {
        if ($this->statut !== 'en_attente') {
            return false;
        }

        // Vérifier le stock disponible pour chaque ligne
        foreach ($this->lignes as $ligne) {
            $stockSource = StockProduit::getStock($ligne->produit_id, $this->point_source_id);

            if ($stockSource < $ligne->quantite_demandee) {
                return false; // Stock insuffisant
            }
        }

        $this->update([
            'statut' => 'valide',
            'valide_par' => $valideur->id,
            'date_validation' => now(),
        ]);

        return true;
    }

    /**
     * Expédier le transfert (retirer du stock source)
     */
    public function expedier(User $expediteur): bool
    {
        if ($this->statut !== 'valide') {
            return false;
        }

        \DB::beginTransaction();

        try {
            foreach ($this->lignes as $ligne) {
                // Retirer du stock source
                StockProduit::retirerStock(
                    $ligne->produit_id,
                    $this->point_source_id,
                    $ligne->quantite_demandee
                );

                // Mettre à jour la ligne
                $ligne->update(['quantite_expedie' => $ligne->quantite_demandee]);
            }

            $this->update([
                'statut' => 'expedie',
                'date_expedition' => now(),
            ]);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            return false;
        }
    }

    /**
     * Réceptionner le transfert (ajouter au stock destination)
     */
    public function receptionner(array $quantitesRecues): bool
    {
        if ($this->statut !== 'expedie') {
            return false;
        }

        \DB::beginTransaction();

        try {
            foreach ($this->lignes as $ligne) {
                $quantiteRecue = $quantitesRecues[$ligne->id] ?? $ligne->quantite_expedie;

                // Ajouter au stock destination
                StockProduit::ajouterStock(
                    $ligne->produit_id,
                    $this->point_destination_id,
                    $quantiteRecue
                );

                // Mettre à jour la ligne
                $ligne->update(['quantite_recue' => $quantiteRecue]);
            }

            $this->update([
                'statut' => 'recu',
                'date_reception' => now(),
            ]);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            return false;
        }
    }

    /**
     * Refuser le transfert
     */
    public function refuser(User $valideur, string $motif = null): bool
    {
        if ($this->statut !== 'en_attente') {
            return false;
        }

        $this->update([
            'statut' => 'refuse',
            'valide_par' => $valideur->id,
            'date_validation' => now(),
            'notes' => $motif,
        ]);

        return true;
    }

    /**
     * Annuler le transfert
     */
    public function annuler(): bool
    {
        if (!in_array($this->statut, ['en_attente', 'valide'])) {
            return false;
        }

        $this->update(['statut' => 'annule']);
        return true;
    }
}
