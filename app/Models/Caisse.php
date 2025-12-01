<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caisse extends Model
{
    use HasFactory;

    protected $table = 'caisses';

    protected $fillable = [
        'nom_caisse',
        'user_id',
        'date_ouverture',
        'date_fermeture',
        'fond_ouverture',
        'fond_fermeture',
        'total_ventes',
        'total_especes',
        'total_mobile_money',
        'total_credit',
        'nombre_transactions',
        'montant_theorique',
        'montant_reel',
        'ecart',
        'statut',
        'notes_ouverture',
        'notes_fermeture',
    ];

    protected $casts = [
        'date_ouverture' => 'datetime',
        'date_fermeture' => 'datetime',
        'fond_ouverture' => 'decimal:2',
        'fond_fermeture' => 'decimal:2',
        'total_ventes' => 'decimal:2',
        'total_especes' => 'decimal:2',
        'total_mobile_money' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'nombre_transactions' => 'integer',
        'montant_theorique' => 'decimal:2',
        'montant_reel' => 'decimal:2',
        'ecart' => 'decimal:2',
    ];

    // ==================== Relations ====================

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    // ==================== Scopes ====================

    public function scopeOuvertes($query)
    {
        return $query->where('statut', 'ouverte');
    }

    public function scopeFermees($query)
    {
        return $query->where('statut', 'fermee');
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_ouverture', today());
    }

    // ==================== Accesseurs ====================

    public function getStatutBadgeAttribute(): string
    {
        return $this->statut === 'ouverte' ? 'success' : 'secondary';
    }

    public function getStatutLibelleAttribute(): string
    {
        return $this->statut === 'ouverte' ? 'Ouverte' : 'Fermée';
    }

    public function getDureeAttribute(): ?string
    {
        if (!$this->date_ouverture) {
            return null;
        }

        $fin = $this->date_fermeture ?? now();
        $duree = $this->date_ouverture->diff($fin);

        return $duree->format('%H:%I:%S');
    }

    // ==================== Méthodes Helper ====================

    public function estOuverte(): bool
    {
        return $this->statut === 'ouverte';
    }

    public function mettreAJourTotaux(): void
    {
        $ventes = $this->ventes()->completees()->get();

        $this->total_ventes = $ventes->sum('montant_total');
        $this->nombre_transactions = $ventes->count();
        $this->total_especes = $ventes->where('type_paiement', 'especes')->sum('montant_total');
        $this->total_mobile_money = $ventes->where('type_paiement', 'mobile_money')->sum('montant_total');
        $this->total_credit = $ventes->where('type_paiement', 'credit')->sum('montant_total');

        $this->montant_theorique = $this->fond_ouverture + $this->total_especes;

        $this->save();
    }

    public function fermer(float $montantReel, ?string $notes = null): bool
    {
        if (!$this->estOuverte()) {
            return false;
        }

        $this->mettreAJourTotaux();

        $this->date_fermeture = now();
        $this->montant_reel = $montantReel;
        $this->ecart = $montantReel - $this->montant_theorique;
        $this->fond_fermeture = $montantReel;
        $this->statut = 'fermee';
        $this->notes_fermeture = $notes;

        return $this->save();
    }

    // ==================== Boot Method ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($caisse) {
            if (!$caisse->date_ouverture) {
                $caisse->date_ouverture = now();
            }

            if (!$caisse->nom_caisse) {
                $caisse->nom_caisse = 'Caisse ' . (static::count() + 1);
            }
        });
    }

    // ==================== Méthodes Statiques ====================

    public static function caisseOuverteParUtilisateur($userId): ?Caisse
    {
        return static::where('user_id', $userId)
            ->where('statut', 'ouverte')
            ->whereDate('date_ouverture', today())
            ->first();
    }

    public static function ouvrirNouvelleCaisse($userId, float $fondOuverture, ?string $notes = null): Caisse
    {
        return static::create([
            'user_id' => $userId,
            'fond_ouverture' => $fondOuverture,
            'notes_ouverture' => $notes,
            'statut' => 'ouverte',
        ]);
    }
}
