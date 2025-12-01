<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaRule implements ValidationRule
{
  /**
   * Exécuter la règle de validation.
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    // Si reCAPTCHA est désactivé, passer la validation
    if (!config('services.recaptcha.enabled', true)) {
      return;
    }

    // Vérifier que le token existe
    if (empty($value)) {
      $fail('Le token reCAPTCHA est manquant.');
      return;
    }

    try {
      // Appel à l'API Google pour vérifier le token
      $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => config('services.recaptcha.secret_key'),
        'response' => $value,
        'remoteip' => request()->ip(),
      ]);

      // Vérifier si la requête a réussi
      if (!$response->successful()) {
        Log::error('reCAPTCHA API error', [
          'status' => $response->status(),
          'body' => $response->body(),
        ]);
        $fail('Erreur lors de la vérification reCAPTCHA. Veuillez réessayer.');
        return;
      }

      $result = $response->json();

      // Vérifier le succès de la validation
      if (!isset($result['success']) || $result['success'] !== true) {
        Log::warning('reCAPTCHA validation failed', [
          'result' => $result,
          'ip' => request()->ip(),
        ]);
        $fail('La vérification reCAPTCHA a échoué. Veuillez réessayer.');
        return;
      }

      // Vérifier le score (pour reCAPTCHA v3)
      if (isset($result['score'])) {
        $score = $result['score'];
        $minScore = 0.5; // Score minimum requis (0.0 à 1.0)

        if ($score < $minScore) {
          Log::warning('reCAPTCHA score too low', [
            'score' => $score,
            'min_required' => $minScore,
            'ip' => request()->ip(),
          ]);
          $fail('Votre activité semble suspecte. Veuillez réessayer plus tard.');
          return;
        }

        // Log du succès avec le score
        Log::info('reCAPTCHA validation successful', [
          'score' => $score,
          'ip' => request()->ip(),
        ]);
      }
    } catch (\Exception $e) {
      Log::error('reCAPTCHA validation exception', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);
      $fail('Erreur lors de la vérification reCAPTCHA. Veuillez réessayer.');
    }
  }
}
