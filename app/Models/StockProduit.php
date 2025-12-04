<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class StockProduit extends Model
{
    use HasFactory;

    protected $table = 'stocks_produits';

    protected $fillable = [
        'produit_id',
        'point_vente_id',
        'quantite',
    ];

    protected $casts = [
        'quantite' => 'integer',
    ];

    // ==================== Relations ====================

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function pointVente(): BelongsTo
    {
        return $this->belongsTo(PointVente::class);
    }

    // ==================== Méthodes Statiques ====================

    /**
     * Obtenir le stock d'un produit dans un point de vente
     */
    public static function getStock(int $produitId, int $pointVenteId): int
    {
        $stock = static::where('produit_id', $produitId)
            ->where('point_vente_id', $pointVenteId)
            ->first();

        return $stock ? $stock->quantite : 0;
    }

    /**
     * Ajouter du stock
     */
    public static function ajouterStock(int $produitId, int $pointVenteId, int $quantite): void
    {
        $stock = static::firstOrCreate(
            [
                'produit_id' => $produitId,
                'point_vente_id' => $pointVenteId,
            ],
            [
                'quantite' => 0,
            ]
        );

        $stock->increment('quantite', $quantite);
    }

    /**
     * Retirer du stock
     */
    public static function retirerStock(int $produitId, int $pointVenteId, int $quantite): bool
    {
        $stock = static::where('produit_id', $produitId)
        ->where('point_vente_id', $pointVenteId)
        ->first();
        // dd($stock);

        if (!$stock || $stock->quantite < $quantite) {
            return false; // Stock insuffisant
        }

        $stock->decrement('quantite', $quantite);
        return true;
    }

    /**
     * Obtenir le stock total d'un produit (tous points confondus)
     */
    public static function getStockTotal(int $produitId): int
    {
        return static::where('produit_id', $produitId)->sum('quantite');
    }
}
