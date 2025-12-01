<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'produit_id',
        'quantite_commandee',
        'quantite_livree',
        'prix_unitaire_ht',
        'prix_unitaire_ttc',
        'montant_ligne_ht',
        'montant_ligne_ttc',
        'taux_tva',
        'notes_item',
    ];

    protected $casts = [
        'prix_unitaire_ht' => 'decimal:2',
        'prix_unitaire_ttc' => 'decimal:2',
        'montant_ligne_ht' => 'decimal:2',
        'montant_ligne_ttc' => 'decimal:2',
        'taux_tva' => 'decimal:2',
    ];

    // Relations
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // Accesseurs
    public function getQuantiteRestanteAttribute()
    {
        return $this->quantite_commandee - $this->quantite_livree;
    }

    public function getEstLivreeCompleteAttribute()
    {
        return $this->quantite_livree >= $this->quantite_commandee;
    }

    public function getProgressionLivraisonAttribute()
    {
        return $this->quantite_commandee > 0
            ? round(($this->quantite_livree / $this->quantite_commandee) * 100, 1)
            : 0;
    }

    // Méthodes métier
    public function calculerMontants()
    {
        $this->montant_ligne_ht = $this->quantite_commandee * $this->prix_unitaire_ht;
        $this->montant_ligne_ttc = $this->quantite_commandee * $this->prix_unitaire_ttc;

        $this->save();

        return $this;
    }

    public function calculerPrixTTC()
    {
        $this->prix_unitaire_ttc = $this->prix_unitaire_ht * (1 + $this->taux_tva / 100);
        $this->save();

        return $this;
    }

    // Event listeners
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Calcul automatique du prix TTC si manquant
            if ($item->prix_unitaire_ttc == 0 && $item->prix_unitaire_ht > 0) {
                $item->prix_unitaire_ttc = $item->prix_unitaire_ht * (1 + $item->taux_tva / 100);
            }

            // Calcul automatique des montants
            $item->montant_ligne_ht = $item->quantite_commandee * $item->prix_unitaire_ht;
            $item->montant_ligne_ttc = $item->quantite_commandee * $item->prix_unitaire_ttc;
        });

        static::saved(function ($item) {
            // Recalculer les totaux de la commande
            $item->commande->calculerTotaux();
            $item->commande->calculerRistourne();
        });

        static::deleted(function ($item) {
            // Recalculer les totaux de la commande
            $item->commande->calculerTotaux();
            $item->commande->calculerRistourne();
        });
    }
}
