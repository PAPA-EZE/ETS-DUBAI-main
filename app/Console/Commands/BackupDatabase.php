<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'backup:database {--push : Push to GitHub immediately}';

    /**
     * The console command description.
     */
    protected $description = 'Backup database to file and optionally push to GitHub';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Démarrage de la sauvegarde...');

        try {
            // Détecter le driver de base de données
            $driver = config('database.default');
            $connection = config("database.connections.{$driver}");

            $this->info("📊 Type de base de données: {$driver}");

            // Créer le nom du fichier avec date et heure
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $backupPath = storage_path("app/backups/{$filename}");

            // Créer le dossier backups s'il n'existe pas
            if (!is_dir(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Effectuer la sauvegarde selon le driver
            if ($driver === 'sqlite') {
                $this->backupSqlite($connection, $backupPath);
            } elseif (in_array($driver, ['mysql', 'mariadb'])) {
                $this->backupMysql($connection, $backupPath);
            } else {
                $this->error("❌ Driver non supporté: {$driver}");
                return Command::FAILURE;
            }

            $this->info("✅ Sauvegarde créée: {$filename}");

            // Créer une copie 'latest.sql' pour faciliter la restauration
            copy($backupPath, storage_path('app/backups/latest.sql'));

            // Nettoyer les anciennes sauvegardes (garder les 30 dernières)
            $this->cleanOldBackups();

            // Push sur GitHub si demandé ou en mode automatique
            if ($this->option('push') || app()->environment('production')) {
                $this->pushToGitHub($filename, $timestamp);
            }

            $this->info('🎉 Sauvegarde terminée avec succès !');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la sauvegarde: {$e->getMessage()}");
            Log::error('Backup failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Sauvegarde SQLite (simple copie du fichier)
     */
    protected function backupSqlite($connection, $backupPath)
    {
        $databaseFile = $connection['database'];

        // Vérifier si le chemin est absolu ou relatif
        if ($this->isAbsolutePath($databaseFile)) {
            // Chemin absolu : utiliser tel quel
            $databasePath = $databaseFile;
        } else {
            // Chemin relatif : ajouter le chemin de base database
            $databasePath = database_path($databaseFile);
        }

        if (!file_exists($databasePath)) {
            throw new \Exception("Fichier SQLite introuvable: {$databasePath}");
        }

        copy($databasePath, $backupPath);
        $this->info("📦 SQLite copié depuis: {$databasePath}");
    }

    /**
     * Vérifier si un chemin est absolu
     */
    protected function isAbsolutePath($path)
    {
        // Windows: vérifie C:\ ou D:\ etc
        if (preg_match('/^[a-zA-Z]:\\\\/', $path)) {
            return true;
        }

        // Unix/Linux: vérifie /
        if (substr($path, 0, 1) === '/') {
            return true;
        }

        return false;
    }

    /**
     * Sauvegarde MySQL avec mysqldump
     */
    protected function backupMysql($connection, $backupPath)
    {
        $host = $connection['host'];
        $port = $connection['port'] ?? 3306;
        $database = $connection['database'];
        $username = $connection['username'];
        $password = $connection['password'];

        // Construire la commande mysqldump
        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );

        // Exécuter la commande
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception("Échec de mysqldump: " . implode("\n", $output));
        }

        $this->info("📦 MySQL dumpé depuis: {$database}@{$host}");
    }

    /**
     * Nettoyer les anciennes sauvegardes (garder les 30 dernières)
     */
    protected function cleanOldBackups()
    {
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . '/backup_*.sql');

        // Trier par date de modification (plus récent en premier)
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        // Supprimer les fichiers au-delà de 30
        $filesToDelete = array_slice($files, 30);

        foreach ($filesToDelete as $file) {
            unlink($file);
            $this->info("🗑️ Supprimé: " . basename($file));
        }

        if (count($filesToDelete) > 0) {
            $this->info("🧹 " . count($filesToDelete) . " anciennes sauvegardes supprimées");
        }
    }

    /**
     * Pusher la sauvegarde sur GitHub
     */
    protected function pushToGitHub($filename, $timestamp)
    {
        $this->info('📤 Push vers GitHub...');

        // Vérifier la configuration GitHub
        $repoUrl = config('backup.github_repo');
        $branch = config('backup.github_branch', 'main');
        $token = config('backup.github_token');

        if (empty($repoUrl) || empty($token)) {
            $this->warn('⚠️ Configuration GitHub manquante. Push ignoré.');
            $this->warn('   Configurez BACKUP_GITHUB_REPO et BACKUP_GITHUB_TOKEN dans .env');
            return;
        }

        $backupDir = storage_path('app/backups');
        $isFirstCommit = false;

        // Vérifier si c'est un dépôt Git
        if (!is_dir($backupDir . '/.git')) {
            $this->info('🔧 Initialisation du dépôt Git...');

            // Initialiser le dépôt
            chdir($backupDir);
            exec('git init 2>&1');

            // Configuration de base
            exec('git config user.email "backup@ets-dubai.com" 2>&1');
            exec('git config user.name "ETS Dubai Backup Bot" 2>&1');

            // Configurer le remote avec le token
            $authenticatedUrl = $this->addTokenToUrl($repoUrl, $token);
            exec("git remote add origin {$authenticatedUrl} 2>&1");

            // Créer un fichier README pour le premier commit
            $readmeContent = "# ETS Dubai - Sauvegardes Base de Données\n\n";
            $readmeContent .= "Ce dépôt contient les sauvegardes automatiques de la base de données.\n\n";
            $readmeContent .= "**Dernière sauvegarde:** " . now()->format('d/m/Y H:i:s') . "\n";
            file_put_contents($backupDir . '/README.md', $readmeContent);

            exec('git add README.md 2>&1');
            exec('git commit -m "Initial commit - Configuration du système de backup" 2>&1');

            // IMPORTANT: Renommer la branche APRÈS le commit
            exec("git branch -M {$branch} 2>&1");

            $isFirstCommit = true;
        } else {
            chdir($backupDir);

            // Vérifier si la branche locale existe
            exec("git rev-parse --verify {$branch} 2>&1", $branchCheck, $branchCheckCode);

            if ($branchCheckCode !== 0) {
                // La branche locale n'existe pas
                $this->info("🔧 Création de la branche locale {$branch}...");

                // Vérifier s'il y a déjà des commits
                exec("git rev-parse HEAD 2>&1", $headCheck, $headCheckCode);

                if ($headCheckCode === 0) {
                    // Il y a des commits, créer/renommer la branche
                    exec("git branch -M {$branch} 2>&1");
                } else {
                    // Aucun commit, on doit en faire un premier
                    $readmeContent = "# ETS Dubai - Sauvegardes Base de Données\n\n";
                    $readmeContent .= "Ce dépôt contient les sauvegardes automatiques de la base de données.\n\n";
                    $readmeContent .= "**Dernière sauvegarde:** " . now()->format('d/m/Y H:i:s') . "\n";
                    file_put_contents($backupDir . '/README.md', $readmeContent);

                    exec('git add README.md 2>&1');
                    exec('git commit -m "Initial commit - Configuration du système de backup" 2>&1');
                    exec("git branch -M {$branch} 2>&1");

                    $isFirstCommit = true;
                }
            } else {
                // La branche existe, s'assurer qu'on est dessus
                exec("git checkout {$branch} 2>&1");
            }
        }

        // Créer .gitignore si nécessaire
        if (!file_exists($backupDir . '/.gitignore')) {
            file_put_contents($backupDir . '/.gitignore', "# Ignorer les fichiers temporaires\n*.tmp\n*.log\n");
            exec('git add .gitignore 2>&1');
            exec('git commit -m "Ajout du .gitignore" 2>&1');
        }

        // Mettre à jour le README avec la dernière sauvegarde
        $readmeContent = "# ETS Dubai - Sauvegardes Base de Données\n\n";
        $readmeContent .= "Ce dépôt contient les sauvegardes automatiques de la base de données.\n\n";
        $readmeContent .= "**Dernière sauvegarde:** " . now()->format('d/m/Y à H:i:s') . "\n";
        $readmeContent .= "**Fichier:** `{$filename}`\n\n";
        $readmeContent .= "## Fichiers disponibles\n\n";
        $readmeContent .= "- `latest.sql` : Dernière sauvegarde (toujours à jour)\n";
        $readmeContent .= "- `backup_YYYY-MM-DD_HH-MM-SS.sql` : Sauvegardes horodatées\n";
        file_put_contents($backupDir . '/README.md', $readmeContent);

        // Ajouter les fichiers
        exec("git add {$filename} latest.sql README.md 2>&1", $addOutput);

        // Vérifier s'il y a des changements à committer
        exec("git diff --cached --quiet", $diffOutput, $diffReturnCode);

        if ($diffReturnCode === 0 && !$isFirstCommit) {
            // Pas de changements à committer
            $this->info('ℹ️  Aucun changement détecté, pas de commit nécessaire');

            // Vérifier si on doit quand même pousser
            exec("git rev-list --count origin/{$branch}..HEAD 2>&1", $commitCount, $countReturnCode);
            if ($countReturnCode === 0 && intval($commitCount[0] ?? 0) > 0) {
                $this->info('📤 Push des commits précédents...');
                exec("git push origin {$branch} 2>&1", $output, $returnCode);

                if ($returnCode === 0) {
                    $this->info('✅ Commits précédents pushés sur GitHub !');
                }
            } else {
                $this->info('✅ Tout est déjà synchronisé avec GitHub');
            }
            return;
        }

        // Commit avec message incluant date et heure
        // Convertir le timestamp au format lisible (2025-10-30_12-33-02 -> 2025-10-30 12:33:02)
        $formattedTimestamp = str_replace('_', ' ', $timestamp);
        $formattedTimestamp = preg_replace('/(\d{2})-(\d{2})-(\d{2})/', '$1:$2:$3', $formattedTimestamp);
        $commitMessage = "Backup {$formattedTimestamp}";

        exec("git commit -m " . escapeshellarg($commitMessage) . " 2>&1", $commitOutput, $commitReturnCode);

        // Vérifier si le commit a réussi
        if ($commitReturnCode !== 0) {
            $this->warn('⚠️  Échec du commit: ' . implode("\n", $commitOutput));
            return;
        }

        $this->info('✅ Commit créé: ' . $commitMessage);

        // Push vers GitHub
        if ($isFirstCommit) {
            // Premier push : créer la branche sur le remote
            $this->info("🚀 Premier push vers GitHub...");
            exec("git push -u origin {$branch} 2>&1", $output, $returnCode);
        } else {
            // Vérifier si la branche remote existe
            exec("git ls-remote --heads origin {$branch} 2>&1", $remoteCheck, $remoteCheckCode);

            if ($remoteCheckCode === 0 && !empty($remoteCheck)) {
                // La branche existe sur le remote, push normal
                exec("git push origin {$branch} 2>&1", $output, $returnCode);
            } else {
                // La branche n'existe pas sur le remote, créer la branche
                $this->info("🚀 Création de la branche sur GitHub...");
                exec("git push -u origin {$branch} 2>&1", $output, $returnCode);
            }
        }

        if ($returnCode === 0) {
            $this->info('✅ Backup pushé sur GitHub avec succès !');
            $this->info("🔗 Voir sur: {$repoUrl}");
        } else {
            $this->error('❌ Échec du push GitHub');

            // Filtrer les messages d'erreur sensibles (token)
            $filteredOutput = array_map(function ($line) use ($token) {
                return str_replace($token, '***TOKEN***', $line);
            }, $output);

            $this->error(implode("\n", $filteredOutput));

            // Afficher des conseils de dépannage
            $this->warn('');
            $this->warn('💡 Conseils de dépannage:');
            $this->warn('1. Vérifiez que le token GitHub est valide');
            $this->warn('2. Vérifiez que le repository existe sur GitHub');
            $this->warn('3. Vérifiez les permissions du token (doit avoir "repo")');
            $this->warn('4. Vérifiez que la branche par défaut correspond (main/master)');
        }
    }

    /**
     * Ajouter le token à l'URL GitHub pour l'authentification
     */
    protected function addTokenToUrl($url, $token)
    {
        // Transformer https://github.com/user/repo.git
        // en https://token@github.com/user/repo.git
        return str_replace('https://', "https://{$token}@", $url);
    }
}
