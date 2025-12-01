<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'produit_id',
        'point_vente_id',
        'type',
        'quantite',
        'user_id',
        'reference',
        'notes',
    ];

    protected $casts = [
        'quantite' => 'integer',
    ];

    // Relations
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function pointVente(): BelongsTo
    {
        return $this->belongsTo(PointVente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
