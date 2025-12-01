<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneTransfert extends Model
{
    use HasFactory;

    protected $table = 'lignes_transferts'; 

    protected $fillable = [
        'transfert_stock_id',
        'produit_id',
        'quantite_demandee',
        'quantite_expedie',
        'quantite_recue',
        'remarque',
    ];

    protected $casts = [
        'quantite_demandee' => 'integer',
        'quantite_expedie' => 'integer',
        'quantite_recue' => 'integer',
    ];

    // ==================== Relations ====================

    public function transfert(): BelongsTo
    {
        return $this->belongsTo(TransfertStock::class, 'transfert_stock_id');
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    // ==================== Accesseurs ====================

    public function getEcartAttribute(): int
    {
        if ($this->quantite_recue === null) {
            return 0;
        }
        return $this->quantite_recue - $this->quantite_expedie;
    }

    public function hasEcart(): bool
    {
        return $this->quantite_recue !== null && $this->quantite_recue !== $this->quantite_expedie;
    }
}
