<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_commande',
        'fournisseur_id',
        'user_id',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_reelle',
        'statut',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'montant_paye',
        'ristourne_prevue',
        'ristourne_reelle',
        'notes',
        'notes_livraison',
        'bon_commande_path',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_livraison_prevue' => 'date',
        'date_livraison_reelle' => 'date',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'ristourne_prevue' => 'decimal:2',
        'ristourne_reelle' => 'decimal:2',
    ];

    // Relations
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CommandeItem::class);
    }

    // Scopes
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeEnCours($query)
    {
        return $query->whereIn('statut', ['en_attente', 'confirmee', 'en_preparation', 'expediee']);
    }

    public function scopeTerminees($query)
    {
        return $query->whereIn('statut', ['livree', 'annulee']);
    }

    public function scopeFournisseur($query, $fournisseurId)
    {
        return $query->where('fournisseur_id', $fournisseurId);
    }

    // Accesseurs
    public function getStatutTextAttribute()
    {
        return match ($this->statut) {
            'brouillon' => 'Brouillon',
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'expediee' => 'Expédiée',
            'livree' => 'Livrée',
            'partiellement_livree' => 'Partiellement livrée',
            'annulee' => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getStatutColorAttribute()
    {
        return match ($this->statut) {
            'brouillon' => 'gray',
            'en_attente' => 'blue',
            'confirmee' => 'green',
            'en_preparation' => 'yellow',
            'expediee' => 'purple',
            'livree' => 'green',
            'partiellement_livree' => 'orange',
            'annulee' => 'red',
            default => 'gray'
        };
    }

    public function getTotalArticlesAttribute()
    {
        return $this->items->sum('quantite_commandee');
    }

    public function getTotalArticlesLivresAttribute()
    {
        return $this->items->sum('quantite_livree');
    }

    public function getProgressionLivraisonAttribute()
    {
        $total = $this->total_articles;
        $livres = $this->total_articles_livres;

        return $total > 0 ? round(($livres / $total) * 100, 1) : 0;
    }

    public function getSoldeRestantAttribute()
    {
        return $this->montant_ttc - $this->montant_paye;
    }

    public function getEstPayeeAttribute()
    {
        return $this->solde_restant <= 0;
    }

    public function getEstLivreeCompleteAttribute()
    {
        return $this->progression_livraison >= 100;
    }

    public function getEstEnRetardAttribute()
    {
        if (!$this->date_livraison_prevue || $this->statut === 'livree') {
            return false;
        }

        return Carbon::now()->gt($this->date_livraison_prevue);
    }

    // Méthodes métier
    public function calculerTotaux()
    {
        $montant_ht = $this->items->sum('montant_ligne_ht');
        $montant_ttc = $this->items->sum('montant_ligne_ttc');
        $montant_tva = $montant_ttc - $montant_ht;

        $this->update([
            'montant_ht' => $montant_ht,
            'montant_tva' => $montant_tva,
            'montant_ttc' => $montant_ttc,
        ]);

        return $this;
    }

    public function calculerRistourne()
    {
        $taux = $this->fournisseur->taux_ristourne_defaut;
        $ristourne = ($this->montant_ht * $taux) / 100;

        $this->update(['ristourne_prevue' => $ristourne]);

        return $ristourne;
    }

    public function changerStatut($nouveauStatut, $notes = null)
    {
        $ancienStatut = $this->statut;

        $this->update([
            'statut' => $nouveauStatut,
            'notes_livraison' => $notes ? $this->notes_livraison . "\n" . $notes : $this->notes_livraison
        ]);

        // Actions automatiques selon le nouveau statut
        switch ($nouveauStatut) {
            case 'livree':
                $this->update(['date_livraison_reelle' => Carbon::now()]);
                $this->mettreAJourStocks();
                break;
            case 'partiellement_livree':
                $this->mettreAJourStocksPartiel();
                break;
        }

        return $this;
    }

    public function mettreAJourStocks()
    {
        foreach ($this->items as $item) {
            $produit = $item->produit;
            $produit->increment('stock_actuel', $item->quantite_livree);
        }
    }

    public function mettreAJourStocksPartiel()
    {
        foreach ($this->items as $item) {
            if ($item->quantite_livree > 0) {
                $produit = $item->produit;
                $produit->increment('stock_actuel', $item->quantite_livree);
            }
        }
    }

    public static function genererNumeroCommande()
    {
        $prefix = 'CMD';
        $date = Carbon::now()->format('Ymd');
        $compteur = static::whereDate('created_at', Carbon::today())->count() + 1;

        return $prefix . '-' . $date . '-' . str_pad($compteur, 3, '0', STR_PAD_LEFT);
    }

    // Boot method pour générer automatiquement le numéro
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($commande) {
            if (empty($commande->numero_commande)) {
                $commande->numero_commande = static::genererNumeroCommande();
            }
        });
    }
}
