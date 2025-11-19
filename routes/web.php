<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BiblioController;
use Illuminate\Support\Facades\Route;

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
        generalRoute(App\Http\Controllers\Admin\DashboardController::class, 'dashboard', 'app');



        generalRoute(App\Http\Controllers\Admin\BiblioController::class, 'biblio', 'app');

        generalRoute(App\Http\Controllers\Admin\LoanController::class, 'loan', 'app');

        // Route khusus untuk generate token
        Route::post('/generate-token', [App\Http\Controllers\Admin\UserController::class, 'initiateUserToken'])
            ->name('generate-token');

        generalRoute(App\Http\Controllers\Admin\UserController::class, 'user', 'app');
        generalRoute(App\Http\Controllers\Admin\RoleController::class, 'roles', 'app', false);

        // User Management Routes
        // generalRoute(App\Http\Controllers\Admin\RoleController::class, 'roles', 'app');

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
