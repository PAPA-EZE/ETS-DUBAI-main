<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Rules\RecaptchaRule;
use Illuminate\Support\Facades\Validator;

class VerifyRecaptcha
{
  // /**
  //  * Handle an incoming request.
  //  *
  //  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
  //  */
  // public function handle(Request $request, Closure $next): Response
  // {

  //   dd('la vie djonie ');
  //   // Si reCAPTCHA est désactivé, passer
  //   if (!config('services.recaptcha.enabled', true)) {
  //     return $next($request);
  //   }

  //   // Ne vérifier que pour les requêtes POST, PUT, PATCH
  //   if (!in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
  //     return $next($request);
  //   }

  //   // Valider le token reCAPTCHA
  //   $validator = Validator::make($request->all(), [
  //     'g-recaptcha-response' => ['required', new RecaptchaRule()],
  //   ], [
  //     'g-recaptcha-response.required' => 'La vérification reCAPTCHA est requise.',
  //   ]);

  //   if ($validator->fails()) {
  //     return back()
  //       ->withErrors($validator)
  //       ->withInput()
  //       ->with('error', 'Échec de la vérification reCAPTCHA.');
  //   }

  //   return $next($request);
  // }
}
