<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Parametre extends Model
{
    use HasFactory;

    protected $fillable = [
        'cle',
        'valeur',
        'type',
        'categorie',
        'description',
    ];

    // ==================== Méthodes Statiques ====================

    /**
     * Récupérer la valeur d'un paramètre (avec cache)
     */
    public static function get(string $cle, $default = null)
    {
        return Cache::remember("parametre.{$cle}", 3600, function () use ($cle, $default) {
            $parametre = static::where('cle', $cle)->first();

            if (!$parametre) {
                return $default;
            }

            return static::castValue($parametre->valeur, $parametre->type);
        });
    }

    /**
     * Définir la valeur d'un paramètre
     */
    public static function set(string $cle, $valeur): void
    {
        $parametre = static::where('cle', $cle)->first();

        if ($parametre) {
            $parametre->update(['valeur' => $valeur]);
        } else {
            static::create([
                'cle' => $cle,
                'valeur' => $valeur,
            ]);
        }

        // Vider le cache
        Cache::forget("parametre.{$cle}");
    }

    /**
     * Récupérer tous les paramètres d'une catégorie
     */
    public static function getByCategorie(string $categorie): array
    {
        return static::where('categorie', $categorie)
            ->get()
            ->mapWithKeys(function ($param) {
                return [$param->cle => static::castValue($param->valeur, $param->type)];
            })
            ->toArray();
    }

    /**
     * Convertir la valeur selon le type
     */
    private static function castValue($valeur, string $type)
    {
        return match ($type) {
            'number' => (float) $valeur,
            'boolean' => (bool) $valeur,
            'json' => json_decode($valeur, true),
            default => $valeur,
        };
    }

    /**
     * Vider tout le cache des paramètres
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
