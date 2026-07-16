<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BiblioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinesController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PenaltiesController;

include_once __DIR__ . "/web-frontend.php";

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::prefix('app')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        generalRoute(DashboardController::class, 'dashboard', 'app');

        // generalRoute(PenaltiesController::class, 'penalties', 'app');
        generalRoute(FinesController::class, 'fines', 'app');



        generalRoute(BiblioController::class, 'biblio', 'app');
        generalRoute(LoanController::class, 'loan', 'app');

        // Route khusus untuk generate token
        Route::post('/generate-token', [UserController::class, 'initiateUserToken'])
            ->name('generate-token');

        Route::get('/user/loan-history', [UserController::class, 'LoanHistory'])
            ->name('user.loan-history');

        Route::post('/sync-mahasiswa', [UserController::class, 'syncAPIMahasiswa'])
            ->name('user.sync.mahasiswa');

        Route::post('/sync-pegawai', [UserController::class, 'syncAPIPegawai'])
            ->name('user.sync.pegawai');

        generalRoute(UserController::class, 'user', 'app');
        generalRoute(RoleController::class, 'roles', 'app', false);

        // User Management Routes
        // generalRoute(App\Http\Controllers\Admin\RoleController::class, 'roles', 'app');


        // ###############################################
        // ###############################################
        Route::get('/kios/scan/{sessionId}', [App\Http\Controllers\Admin\UserController::class, 'mahasiswaScanQr'])
            ->name('kios.scan');
        // ###############################################
        
        // ###############################################
    });

// //temporary
// Route::get('/media/{id}', function ($id) {
//     return serveMedia(decid($id));
// })->name('media.show');
// Route::get('/media/thumb/{id}', function ($id) {
//     return serveMedia(decid($id), true);
// })->name('media.thumb');

// Test route tanpa middleware untuk debug
Route::get('/test-token-debug', [App\Http\Controllers\Admin\UserController::class, 'testToken']);
Route::get('/test-generate-get', [App\Http\Controllers\Admin\UserController::class, 'initiateUserToken']);


