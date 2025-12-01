<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointVente extends Model
{
    use HasFactory;

    protected $table = 'points_vente';

    protected $fillable = [
        'nom',
        'code',
        'adresse',
        'telephone',
        'type',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // ==================== Relations ====================

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(StockProduit::class);
    }

    public function caisses(): HasMany
    {
        return $this->hasMany(Caisse::class);
    }

    public function transfertsSource(): HasMany
    {
        return $this->hasMany(TransfertStock::class, 'point_source_id');
    }

    public function transfertsDestination(): HasMany
    {
        return $this->hasMany(TransfertStock::class, 'point_destination_id');
    }

    // ==================== Scopes ====================

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeCentral($query)
    {
        return $query->where('type', 'central');
    }

    public function scopeVente($query)
    {
        return $query->where('type', 'vente');
    }

    // ==================== Méthodes ====================

    public function estCentral(): bool
    {
        return $this->type === 'central';
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->nom} ({$this->code})";
    }
}
