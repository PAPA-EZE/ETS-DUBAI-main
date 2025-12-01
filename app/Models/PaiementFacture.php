<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementFacture extends Model
{
    use HasFactory;

    protected $fillable = [
        'facture_id',
        'user_id',
        'date_paiement',
        'montant',
        'mode_paiement',
        'reference',
        'notes',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
    ];

    // ==================== Relations ====================

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==================== Accesseurs ====================

    public function getModePaiementLibelleAttribute(): string
    {
        return match ($this->mode_paiement) {
            'especes' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'virement' => 'Virement',
            'cheque' => 'Chèque',
            default => 'Inconnu'
        };
    }

    public function getModePaiementBadgeAttribute(): string
    {
        return match ($this->mode_paiement) {
            'especes' => 'success',
            'mobile_money' => 'info',
            'virement' => 'primary',
            'cheque' => 'warning',
            default => 'secondary'
        };
    }
}
