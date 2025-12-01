<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * Define the application's command schedule.
   */
  protected function schedule(Schedule $schedule): void
  {
    // Sauvegarde automatique de la base de données toutes les 2 heures
    $frequencyHours = config('backup.frequency_hours', 2);

    $schedule->command('backup:database --push')
      ->cron("0 */{$frequencyHours} * * *") // Toutes les X heures
      ->onFailure(function () {
        // Notification en cas d'échec
        Log::error('Backup automatique échoué à ' . now());

        // Envoyer email si configuré
        $email = config('backup.notification_email');
        if ($email) {
          Mail::raw(
            'La sauvegarde automatique de la base de données a échoué le ' . now()->format('d/m/Y à H:i:s'),
            function ($message) use ($email) {
              $message->to($email)
                ->subject('⚠️ Échec sauvegarde BDD - ETS Dubai');
            }
          );
        }
      })
      ->onSuccess(function () {
        Log::info('Backup automatique réussi à ' . now());
      });

    // Autres tâches planifiées...
    // $schedule->command('inspire')->hourly();
  }

  /**
   * Register the commands for the application.
   */
  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
