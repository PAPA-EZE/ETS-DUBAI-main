<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneVente extends Model
{
  use HasFactory;

  protected $table = 'lignes_vente'; // ✅ IMPORTANT : Nom exact de la table

  protected $fillable = [
    'vente_id',
    'produit_id',
    'quantite',
    'unite',
    'prix_unitaire_ht',
    'prix_unitaire_ttc',
    'montant_ht',
    'montant_tva',
    'montant_total',
    'taux_tva',
  ];

  protected $casts = [
    'quantite' => 'integer',
    'prix_unitaire_ht' => 'decimal:2',
    'prix_unitaire_ttc' => 'decimal:2',
    'montant_ht' => 'decimal:2',
    'montant_tva' => 'decimal:2',
    'montant_total' => 'decimal:2',
    'taux_tva' => 'decimal:2',
  ];

  // ==================== Boot ====================

  protected static function boot()
  {
    parent::boot();

    // Mise à jour du stock après création
    static::created(function ($ligne) {
      if ($ligne->vente->estCompletee()) {
        // Le stock est déjà réduit dans le controller
        // Cette méthode est juste un hook pour d'autres actions futures
      }
    });
  }

  // ==================== Relations ====================

  public function vente(): BelongsTo
  {
    return $this->belongsTo(Vente::class);
  }

  public function produit(): BelongsTo
  {
    return $this->belongsTo(Produit::class);
  }
}
