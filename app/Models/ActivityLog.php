<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'point_vente_id',
    'action_type',
    'description',
    'entity_type',
    'entity_id',
    'metadata',
    'is_anomaly',
    'anomaly_reason',
    'severity',
    'ip_address',
    'user_agent',
  ];

  protected $casts = [
    'metadata' => 'array',
    'is_anomaly' => 'boolean',
    'created_at' => 'datetime',
  ];

  // ==================== Relations ====================

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function pointVente(): BelongsTo
  {
    return $this->belongsTo(PointVente::class);
  }

  // ==================== Accesseurs ====================

  public function getActionLabelAttribute(): string
  {
    return match ($this->action_type) {
      'vente_create' => 'Vente créée',
      'vente_update' => 'Vente modifiée',
      'vente_delete' => 'Vente supprimée',
      'vente_annule' => 'Vente annulée',
      'caisse_ouverture' => 'Ouverture de caisse',
      'caisse_fermeture' => 'Fermeture de caisse',
      'stock_ajout' => 'Ajout de stock',
      'stock_retrait' => 'Retrait de stock',
      'transfert_demande' => 'Demande de transfert',
      'transfert_validation' => 'Validation de transfert',
      'prix_modification' => 'Modification de prix',
      'remise_application' => 'Application de remise',
      'autre' => 'Autre action',
      default => 'Action inconnue'
    };
  }

  public function getSeverityBadgeAttribute(): string
  {
    return match ($this->severity) {
      'low' => 'bg-gray-100 text-gray-800',
      'medium' => 'bg-yellow-100 text-yellow-800',
      'high' => 'bg-orange-100 text-orange-800',
      'critical' => 'bg-red-100 text-red-800',
      default => 'bg-gray-100 text-gray-800'
    };
  }

  public function getSeverityLabelAttribute(): string
  {
    return match ($this->severity) {
      'low' => 'Faible',
      'medium' => 'Moyenne',
      'high' => 'Élevée',
      'critical' => 'Critique',
      default => '-'
    };
  }

  // ==================== Scopes ====================

  public function scopeAnomalies($query)
  {
    return $query->where('is_anomaly', true);
  }

  public function scopeByUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeByPoint($query, $pointVenteId)
  {
    return $query->where('point_vente_id', $pointVenteId);
  }

  public function scopeByAction($query, $actionType)
  {
    return $query->where('action_type', $actionType);
  }

  public function scopeRecent($query, $days = 7)
  {
    return $query->where('created_at', '>=', now()->subDays($days));
  }

  // ==================== Méthodes statiques ====================

  /**
   * Logger une action
   */
  public static function logActivity(
    string $actionType,
    string $description,
    ?string $entityType = null,
    ?int $entityId = null,
    ?array $metadata = null,
    bool $checkAnomaly = true
  ): self {
    $user = auth()->user();

    $log = self::create([
      'user_id' => $user->id,
      'point_vente_id' => $user->point_vente_id,
      'action_type' => $actionType,
      'description' => $description,
      'entity_type' => $entityType,
      'entity_id' => $entityId,
      'metadata' => $metadata,
      'ip_address' => request()->ip(),
      'user_agent' => request()->userAgent(),
    ]);

    // Détecter les anomalies
    if ($checkAnomaly) {
      $log->detectAnomaly();
    }

    return $log;
  }

  /**
   * Détecter les anomalies
   */
  public function detectAnomaly(): void
  {
    $isAnomaly = false;
    $reason = null;
    $severity = 'low';

    // Règle 1 : Ventes hors horaires normaux (22h - 6h)
    $hour = $this->created_at->hour;
    if (in_array($this->action_type, ['vente_create']) && ($hour >= 22 || $hour < 6)) {
      $isAnomaly = true;
      $reason = 'Vente effectuée hors horaires normaux';
      $severity = 'medium';
    }

    // Règle 2 : Trop de ventes annulées (plus de 3 par jour)
    if ($this->action_type === 'vente_annule') {
      $count = self::where('user_id', $this->user_id)
        ->where('action_type', 'vente_annule')
        ->whereDate('created_at', $this->created_at->toDateString())
        ->count();

      if ($count > 3) {
        $isAnomaly = true;
        $reason = 'Trop d\'annulations de ventes dans la journée';
        $severity = 'high';
      }
    }

    // Règle 3 : Ventes très élevées (montant > 500 000 FCFA)
    if ($this->action_type === 'vente_create' && isset($this->metadata['montant'])) {
      if ($this->metadata['montant'] > 500000) {
        $isAnomaly = true;
        $reason = 'Montant de vente inhabituellement élevé';
        $severity = 'high';
      }
    }

    // Règle 4 : Remises trop importantes (> 50%)
    if ($this->action_type === 'remise_application' && isset($this->metadata['pourcentage'])) {
      if ($this->metadata['pourcentage'] > 50) {
        $isAnomaly = true;
        $reason = 'Remise excessive appliquée';
        $severity = 'critical';
      }
    }

    // Mettre à jour si anomalie détectée
    if ($isAnomaly) {
      $this->update([
        'is_anomaly' => true,
        'anomaly_reason' => $reason,
        'severity' => $severity,
      ]);
    }
  }
}