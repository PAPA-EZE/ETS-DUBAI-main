<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Configuration GitHub pour Backups
    |--------------------------------------------------------------------------
    */

  'github_repo' => env('BACKUP_GITHUB_REPO'),
  'github_branch' => env('BACKUP_GITHUB_BRANCH', 'main'),
  'github_token' => env('BACKUP_GITHUB_TOKEN'),
  'frequency_hours' => env('BACKUP_FREQUENCY_HOURS', 2),
  'keep_backups' => env('BACKUP_KEEP_COUNT', 30),
  'notification_email' => env('BACKUP_NOTIFICATION_EMAIL'),

];
