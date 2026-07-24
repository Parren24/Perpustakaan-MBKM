<?php

use App\Http\Controllers\Frontend\Academic\JurusanController;
use App\Http\Controllers\Frontend\Academic\LecturerController;
use App\Http\Controllers\Frontend\Academic\ProdiController;
use App\Http\Controllers\Frontend\DEV;
use App\Http\Controllers\Frontend\Academic\MainController as AcademicController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\CampusLifeController;
use App\Http\Controllers\Frontend\InformationController;
use App\Http\Controllers\Frontend\MainController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\PCRSquadController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ResearchController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\BiblioController;
use App\Http\Controllers\Frontend\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartLoanController;
use App\Http\Controllers\Frontend\LoanController;

Route::get(
    '/read/{numeric}/{slug}',
    function ($numeric, $slug) {
        return redirect()->route('frontend.articles.show', ['articlesSlug' => $slug], 301);
    }
)->where('numeric', '[0-9]+');
// routes/web-frontend.php

// Frontend Routes
Route::name('frontend.')->group(function () {

    Route::get('/biblio/kios/unlock', [BiblioController::class, 'showUnlockPin'])->name('biblio.kios.unlock');
    Route::post('/biblio/kios/unlock', [BiblioController::class, 'verifyDevicePin'])->name('biblio.kios.unlock.submit');
    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        });
    Route::middleware(['kios.pin'])->group(function () {
        Route::prefix('/biblio')->name('biblio.')->controller(BiblioController::class)->group(function () {
            Route::get('/peminjaman', 'index')->name('index');
            Route::get('/pengembalian', 'returnLoanPage')->name('return-loan');
            Route::get('/modul/modul-loan', 'modulLoanPage')->name('modul.modul-loan');
            Route::get('/modul/modul-return', 'modulReturnPage')->name('modul.modul-return');
            Route::get('/item/{item_code}', 'getItemInformation')->name('item-info');
            Route::post('/authorize-session', 'authorizeSession')->name('authorize-session');

            // Mobile pages routes (no auth required for confirmation)
            Route::get('/konfirmasi/{token}', 'showConfirmation')->name('confirmation');

            //#####################################################
            Route::get('/kios/generate-qr-ajax', 'getKiosQrAjax')->name('kios.generate-qr-ajax');
            Route::get('/kios/check-status/{sessionId}', 'checkKiosStatus')->name('kios.check-status');
            Route::post('/kios/claim-session', 'claimKiosSession')->name('kios.claim-session');
            //#####################################################

            // Routes that require biblio session (semua butuh member_id aktif & belum expired)
            Route::middleware(['kios.session'])->group(function () {
                
                Route::get('/scan-buku', 'showScanBook')->name('scan-book');
                Route::get('/keranjang', 'showCart')->name('cart');
                Route::post('/add-to-cart', 'addBookToCartLoan')->name('add-to-cart');
                Route::get('/cart-items', 'getCartItems')->name('cart-items');
                Route::delete('/cart-item', 'removeFromCart')->name('remove-from-cart');
                Route::delete('/cart-clear', 'clearCart')->name('clear-cart');
                Route::post('/complete-loan', 'completeLoan')->name('complete-loan');
                Route::post('/kios/check-session', 'checkKiosSessionStatus')->name('kios.check-session');
                Route::post('/kios/close-session', 'closeKiosSession')->name('kios.close-session');
            });

        });

        Route::get('/print/struk', function () {
            return view('contents.frontend.pages.print.struk');
        });

        Route::prefix('/cart-loan')->name('cart-loan.')->controller(CartLoanController::class)->group(function () {
            Route::post('/add-to-cart', 'addBookToCartLoan')->name('add-to-cart');
            Route::get('/cart-items', 'getCartItems')->name('cart-items');
            Route::get('/cart-modul-items', 'getCartModulItems')->name('cart-modul-items');
            Route::post('/add-modul-to-cart', 'addModulToCartLoan')->name('add-modul-to-cart');
            Route::delete('/cart-item', 'removeFromCart')->name('remove-from-cart');
            Route::delete('/cart-clear', 'clearCart')->name('clear-cart');
        });

        Route::prefix('/loan')->name('loan.')->controller(LoanController::class)->group(function () {
            Route::post('/complete-loan', 'completeLoan')->name('complete-loan');
            Route::get('/active-loans', 'getLoan')->name('active-loans');
            Route::post('/return-item', 'returnLoanItem')->name('return-item');
            
        });

    });

    Route::prefix('/item')->name('item.')->controller(ItemController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/borrow-item', 'borrowItem')->name('borrow-item');
        Route::post('/initiateUserToken', 'initiateUserToken')->name('initiate-user-token');
    });

    
    // Development Routes
    Route::prefix('/dev')->name('dev.')->controller(DEV\MainController::class)->group(function () {
        Route::get('/changelog', 'changelog')->name('changelog');
    });
});
