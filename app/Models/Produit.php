<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'reference',
        'description',
        'code_barre',
        'categorie_id',
        'fournisseur_id',
        'type_conditionnement',
        'unites_par_conditionnement',
        'unites_par_pack',
        'prix_achat_conditionnement',
        'prix_vente_conditionnement',
        'prix_achat_unite',
        'prix_vente_unite',
        'marge_conditionnement',
        'marge_unite',
        'tva_applicable',
        'taux_tva',
        'stock_actuel',
        'stock_minimum',
        'image',
        'actif',
    ];

    protected $casts = [
        'prix_achat_conditionnement' => 'decimal:2',
        'prix_vente_conditionnement' => 'decimal:2',
        'prix_achat_unite' => 'decimal:2',
        'prix_vente_unite' => 'decimal:2',
        'marge_conditionnement' => 'decimal:2',
        'marge_unite' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'stock_actuel' => 'integer',
        'stock_minimum' => 'integer',
        'unites_par_conditionnement' => 'integer',
        'unites_par_pack' => 'integer',
        'tva_applicable' => 'boolean',
        'actif' => 'boolean',
    ];

    // ==================== Relations ====================

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(StockProduit::class);
    }

    public function lignesVente(): HasMany
    {
        return $this->hasMany(LigneVente::class);
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * Mettre à jour le stock_actuel à partir des stocks points de vente
     */
    public function mettreAJourStockActuel(): void
    {
        $this->stock_actuel = StockProduit::getStockTotal($this->id);
        $this->save();
    }

    // ==================== Accesseurs ====================

    /**
     * Obtenir le libellé du conditionnement
     */
    public function getLibelleConditionnementAttribute(): string
    {
        return match ($this->type_conditionnement) {
            'casier' => 'Casier',
            'palette' => 'Palette',
            'unite' => 'Unité',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir le libellé de l'unité
     */
    public function getLibelleUniteAttribute(): string
    {
        return match ($this->type_conditionnement) {
            'casier' => 'Bouteille',
            'palette' => 'Bouteille',
            'unite' => 'Unité',
            default => 'Unité'
        };
    }

    /**
     * Stock total (tous points confondus) en unités
     */
    public function getStockTotalAttribute(): int
    {
        return StockProduit::getStockTotal($this->id);
    }

    /**
     * Stock d'un point spécifique en unités
     */
    public function getStockPoint(int $pointVenteId): int
    {
        return StockProduit::getStock($this->id, $pointVenteId);
    }

    /**
     * Convertir unités en conditionnements + reste
     * Ex: 50 bouteilles → 2 casiers + 2 bouteilles
     */
    public function convertirEnConditionnements(int $quantiteUnites): array
    {
        $nombreConditionnements = intdiv($quantiteUnites, $this->unites_par_conditionnement);
        $reste = $quantiteUnites % $this->unites_par_conditionnement;

        return [
            'conditionnements' => $nombreConditionnements,
            'unites_restantes' => $reste,
            'total_unites' => $quantiteUnites,
        ];
    }

    /**
     * Affichage formaté du stock
     * Ex: "2 casiers + 5 bouteilles (53 bouteilles)"
     */
    public function afficherStock(int $quantiteUnites): string
    {
        $conversion = $this->convertirEnConditionnements($quantiteUnites);

        if ($conversion['conditionnements'] > 0) {
            $texte = "{$conversion['conditionnements']} {$this->libelle_conditionnement}";

            if ($conversion['unites_restantes'] > 0) {
                $texte .= " + {$conversion['unites_restantes']} {$this->libelle_unite}";
            }

            $texte .= " ({$quantiteUnites} {$this->libelle_unite})";
        } else {
            $texte = "{$quantiteUnites} {$this->libelle_unite}";
        }

        return $texte;
    }

    /**
     * Calculer le prix de vente conditionnement avec marge
     */
    public function calculerPrixVenteConditionnement(): float
    {
        if ($this->marge_conditionnement > 0) {
            return round($this->prix_achat_conditionnement * (1 + $this->marge_conditionnement / 100), 2);
        }
        return $this->prix_vente_conditionnement;
    }

    /**
     * Calculer le prix de vente unité avec marge
     */
    public function calculerPrixVenteUnite(): float
    {
        if ($this->marge_unite > 0) {
            return round($this->prix_achat_unite * (1 + $this->marge_unite / 100), 2);
        }
        return $this->prix_vente_unite;
    }

    /**
     * Calculer la marge réelle conditionnement
     */
    public function getMargeReelleConditionnementAttribute(): float
    {
        if ($this->prix_achat_conditionnement == 0) {
            return 0;
        }

        return round(
            (($this->prix_vente_conditionnement - $this->prix_achat_conditionnement) / $this->prix_achat_conditionnement) * 100,
            2
        );
    }

    /**
     * Calculer la marge réelle unité
     */
    public function getMargeReelleUniteAttribute(): float
    {
        if ($this->prix_achat_unite == 0) {
            return 0;
        }

        return round(
            (($this->prix_vente_unite - $this->prix_achat_unite) / $this->prix_achat_unite) * 100,
            2
        );
    }

    /**
     * Calculer le prix avec TVA
     */
    public function getPrixAvecTva(float $prixHT): float
    {
        if (!$this->tva_applicable) {
            return $prixHT;
        }

        return round($prixHT * (1 + $this->taux_tva / 100), 2);
    }

    /**
     * Prix de vente unité TTC (avec ou sans TVA)
     */
    public function getPrixVenteUniteTtcAttribute(): float
    {
        return $this->getPrixAvecTva($this->prix_vente_unite);
    }

    /**
     * Prix de vente conditionnement TTC
     */
    public function getPrixVenteConditionnementTtcAttribute(): float
    {
        return $this->getPrixAvecTva($this->prix_vente_conditionnement);
    }

    // ==================== Scopes ====================

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeEnRupture($query)
    {
        return $query->where('stock_actuel', '<=', 0);
    }

    public function scopeStockFaible($query)
    {
        return $query->whereColumn('stock_actuel', '<=', 'stock_minimum')
            ->where('stock_actuel', '>', 0);
    }

    public function scopeAvecTva($query)
    {
        return $query->where('tva_applicable', true);
    }

    public function scopeSansTva($query)
    {
        return $query->where('tva_applicable', false);
    }

    // ==================== Méthodes de classe ====================

    /**
     * Synchroniser les prix automatiquement
     * Calcule prix unité à partir du conditionnement
     */
    public function synchroniserPrix(): void
    {
        // Calculer prix unité si pas défini
        if ($this->prix_achat_unite == 0 && $this->prix_achat_conditionnement > 0) {
            $this->prix_achat_unite = round($this->prix_achat_conditionnement / $this->unites_par_conditionnement, 2);
        }

        if ($this->prix_vente_unite == 0 && $this->prix_vente_conditionnement > 0) {
            $this->prix_vente_unite = round($this->prix_vente_conditionnement / $this->unites_par_conditionnement, 2);
        }

        // Calculer les marges si pas définies
        if ($this->marge_conditionnement == 0) {
            $this->marge_conditionnement = $this->marge_reelle_conditionnement;
        }

        if ($this->marge_unite == 0) {
            $this->marge_unite = $this->marge_reelle_unite;
        }
    }
}
