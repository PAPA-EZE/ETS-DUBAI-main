<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RistourneController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\AnalyseVendeurController;
use App\Http\Controllers\TransfertStockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // dd('la vie de djonie');
    return redirect()->route('password.request');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // ==================== DASHBOARD ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== PRODUITS ====================
    // Accessibles à tous, mais permissions gérées dans le controller
    Route::resource('produits', ProduitController::class);

    // ==================== VENTES ====================
    // Tous peuvent voir les ventes (filtrées par rôle dans le controller)
    Route::prefix('ventes')->name('ventes.')->group(function () {
        Route::get('/', [VenteController::class, 'index'])->name('index');
        Route::get('/create', [VenteController::class, 'create'])->name('create');
        Route::post('/', [VenteController::class, 'store'])->name('store');
        Route::get('/{vente}', [VenteController::class, 'show'])->name('show');

        // ✅ AJOUTÉ : Actions sur les ventes
        Route::post('/{vente}/valider', [VenteController::class, 'valider'])->name('valider');
        Route::post('/{vente}/annuler', [VenteController::class, 'annuler'])->name('annuler');
        Route::get('/{vente}/imprimer', [VenteController::class, 'imprimer'])->name('imprimer');
    });

    // ==================== CLIENTS ====================
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/credit', [ClientController::class, 'ajusterCredit'])->name('clients.ajuster-credit');

    // ==================== CAISSES ====================
    Route::prefix('caisses')->name('caisses.')->group(function () {
        Route::get('/', [CaisseController::class, 'index'])->name('index');
        Route::get('/create', [CaisseController::class, 'create'])->name('create');
        Route::post('/', [CaisseController::class, 'store'])->name('store');
        Route::get('/{caisse}', [CaisseController::class, 'show'])->name('show');
        Route::get('/{caisse}/fermer', [CaisseController::class, 'fermer'])->name('fermer');
        Route::post('/{caisse}/fermer', [CaisseController::class, 'fermerStore'])->name('fermer.store');
        Route::delete('/{caisse}', [CaisseController::class, 'destroy'])->name('destroy');
    });

    // ==================== TRANSFERTS DE STOCK ====================
    Route::prefix('transferts')->name('transferts.')->group(function () {
        Route::get('/', [TransfertStockController::class, 'index'])->name('index');
        Route::get('/create', [TransfertStockController::class, 'create'])->name('create');
        Route::post('/', [TransfertStockController::class, 'store'])->name('store');
        Route::get('/{transfert}', [TransfertStockController::class, 'show'])->name('show');

        // Actions sur les transferts
        Route::post('/{transfert}/valider', [TransfertStockController::class, 'valider'])->name('valider');
        Route::post('/{transfert}/expedier', [TransfertStockController::class, 'expedier'])->name('expedier');
        Route::post('/{transfert}/receptionner', [TransfertStockController::class, 'receptionner'])->name('receptionner');
        Route::post('/{transfert}/refuser', [TransfertStockController::class, 'refuser'])->name('refuser');
        Route::post('/{transfert}/annuler', [TransfertStockController::class, 'annuler'])->name('annuler');

        // AJAX
        Route::get('/stock', [TransfertStockController::class, 'getStock'])->name('stock');
    });

    // ==================== RAPPORTS ====================
    // Accessibles à tous (contenu filtré par rôle dans le controller)
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/ventes', [RapportController::class, 'ventes'])->name('ventes');
        Route::get('/stocks', [RapportController::class, 'stocks'])->name('stocks');
        Route::get('/achats', [RapportController::class, 'achats'])->name('achats');
        Route::get('/financier', [RapportController::class, 'financier'])->name('financier');
    });

    // ==================== PROFILE ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ==================== ROUTES ADMIN & RESPONSABLE ====================

    // Fournisseurs
    Route::middleware(['responsable'])->group(function () {
        Route::resource('fournisseurs', FournisseurController::class);
    });

    // Commandes
    Route::middleware(['responsable'])->prefix('commandes')->name('commandes.')->group(function () {
        Route::get('/', [CommandeController::class, 'index'])->name('index');
        Route::get('/create', [CommandeController::class, 'create'])->name('create');
        Route::post('/', [CommandeController::class, 'store'])->name('store');
        Route::get('/{commande}', [CommandeController::class, 'show'])->name('show');
        Route::get('/{commande}/edit', [CommandeController::class, 'edit'])->name('edit');
        Route::put('/{commande}', [CommandeController::class, 'update'])->name('update');
        Route::delete('/{commande}', [CommandeController::class, 'destroy'])->name('destroy');

        // Actions spécifiques
        Route::post('/{commande}/statut', [CommandeController::class, 'changerStatut'])->name('statut');
        Route::get('/{commande}/livraison', [CommandeController::class, 'livraison'])->name('livraison');
        Route::post('/{commande}/livraison', [CommandeController::class, 'enregistrerLivraison'])->name('enregistrer-livraison');
    });

    // Ristournes
    Route::middleware(['responsable'])->prefix('ristournes')->name('ristournes.')->group(function () {
        Route::get('/', [RistourneController::class, 'index'])->name('index');
        Route::get('/create', [RistourneController::class, 'create'])->name('create');
        Route::post('/', [RistourneController::class, 'store'])->name('store');
        Route::get('/{ristourne}', [RistourneController::class, 'show'])->name('show');

        // Actions
        Route::post('/{ristourne}/valider', [RistourneController::class, 'valider'])->name('valider');
        Route::post('/{ristourne}/payer', [RistourneController::class, 'payer'])->name('payer');
        Route::post('/{ristourne}/annuler', [RistourneController::class, 'annuler'])->name('annuler');

        // Simulateur
        Route::get('/simulateur', [RistourneController::class, 'simulateur'])->name('simulateur');
        Route::post('/simulation', [RistourneController::class, 'simulation'])->name('simulation');
    });

    // ==================== ROUTES ADMIN UNIQUEMENT ====================

    // Gestion des utilisateurs
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Analyses vendeurs
    Route::middleware(['admin'])->prefix('analyses')->name('analyses.')->group(function () {
        Route::get('/', [AnalyseVendeurController::class, 'index'])->name('index');
        Route::get('/logs', [AnalyseVendeurController::class, 'logs'])->name('logs');
        Route::get('/vendeur/{user}', [AnalyseVendeurController::class, 'vendeur'])->name('vendeur');
    });

    // Paramètres système
    Route::middleware(['admin'])->prefix('parametres')->name('parametres.')->group(function () {
        Route::get('/', [ParametreController::class, 'index'])->name('index');
        Route::post('/', [ParametreController::class, 'update'])->name('update');
    });
});

require __DIR__ . '/auth.php';
