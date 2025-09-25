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
use Illuminate\Support\Facades\Route;

Route::get(
    '/read/{numeric}/{slug}',
    function ($numeric, $slug) {
        return redirect()->route('frontend.articles.show', ['articlesSlug' => $slug], 301);
    }
)->where('numeric', '[0-9]+');

// Frontend Routes
Route::name('frontend.')->group(function () {
    Route::controller(MainController::class)->group(function () {
        Route::get('/', 'index')->name('home');
    });

    Route::prefix('/profil')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/sejarah', 'history')->name('history');
        Route::get('/visi-misi', 'visiMisi')->name('visi-misi');
        Route::get('/sambutan-ypcr', 'welcomeYpcr')->name('welcome-ypcr');
        Route::get('/sambutan-direktur', 'welcomeDirector')->name('welcome-director');
        Route::get('/diversitas', 'diversity')->name('diversity');
        Route::get('/panduan-identitas', 'identity')->name('identity');
        Route::get('/organisasi', 'organization')->name('organization');
        Route::get('/lokasi', 'location')->name('location');
        Route::get('/akreditasi', 'accreditation')->name('accreditation');
        Route::get('/prestasi-dan-penghargaan', 'achievements')->name('achievements');
        Route::get('/laporan-tahunan', 'yearlyReport')->name('yearly-report');
    });

    // Route::prefix('/akademik')->name('academic.')->group(function () {
    Route::name('academic.')->group(function () {
        // Route::prefix('/jurusan')->name('jurusan.')->controller(JurusanController::class)->group(function () {
        Route::name('jurusan.')->prefix('/jurusan')->controller(JurusanController::class)->group(function () {
            Route::get('/', 'jurusan')->name('index');
            Route::get('/{jurusanAlias}', 'jurusanDetail')->name('show');
        });

        Route::controller(LecturerController::class)->group(function () {
            Route::get('/profil-dosen/{slugLecturer}', 'lecturerProfile')->name('lecturer-profile');
        });

        Route::name('prodi.')->prefix('/program-studi')->controller(ProdiController::class)->group(function () {
            Route::get('/{prodiAlias}', 'prodiDetail')->name('show');
            Route::get('/{prodiAlias}/beranda', 'home')->name('home');
        });

        Route::controller(AcademicController::class)->group(function () {
            Route::get('/beasiswa', 'scholarship')->name('scholarship');
        });
    });

    Route::prefix('/layanan')->name('service.')->controller(ServiceController::class)->group(function () {
        Route::get('/informasi-dan-pengaduan', 'informationAndComplaints')->name('information-and-complaints');
    });

    Route::prefix('/riset-terapan')->name('research.')->controller(ResearchController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::prefix('/pcr-squad')->name('pcr-squad.')->controller(PCRSquadController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::prefix('/berita')->name('news.')->controller(NewsController::class)->group(function () {
        Route::get("/", 'index')->name('index');
        Route::get('/{newsId}', 'show')->name('show');
    });

    Route::prefix('/artikel')->name('articles.')->controller(ArticleController::class)->group(function () {
        Route::get("/", 'index')->name('index');
        Route::get('/read/{articlesSlug}', 'show')->name('show');
        Route::get('/label/{labelCode}/{page?}', 'byLabel')
            ->where([
                'labelCode' => '^(?!read$).+',
                'page'      => '[0-9]+',
            ])
            ->name('labels');
        Route::get('/{categoriesCode}/{page?}', 'byKategori')
            ->where([
                'categoriesCode' => '^(?!read$).+',
                'page'           => '[0-9]+',
            ])
            ->name('categories');
    });

    Route::prefix('/kehidupan-kampus')->name('campus-life.')->controller(CampusLifeController::class)->group(function () {
        Route::prefix('/fasilitas')->name('facilities.')->group(function () {
            Route::get('/', 'facilities')->name('index');
            Route::get('/{facilityId}', 'facilityDetail')->name('detail');
        });

        Route::prefix('/ukm')->name('ukm.')->group(function () {
            Route::get('/', 'ukm')->name('index');
            Route::get('/{ukmId}', 'ukmDetail')->name('detail');
        });

        Route::prefix('/organisasi-mahasiswa')->name('student-organization.')->group(function () {
            Route::get('/', 'studentOrganization')->name('index');
            Route::get('/{organizationId}', 'studentOrganizationDetail')->name('detail');
        });

        Route::prefix('/himpunan-mahasiswa')->name('himpunan.')->group(function () {
            Route::get('/', 'himpunan')->name('index');
            Route::get('/{himpunanId}', 'himpunanDetail')->name('detail');
        });

        Route::prefix('/kos-dan-sewa-rumah')->name('rental.')->group(function () {
            Route::get('/', 'rental')->name('index');
        });

        Route::get('/jelajahi-pekanbaru', 'explorePekanbaru')->name('explore-pekanbaru');
        Route::get('/virtual-tour', 'virtualTour')->name('virtual-tour');
    });

    Route::prefix('/informasi')->name('information.')->controller(InformationController::class)->group(function () {
        Route::get('/kontak', 'contact')->name('contact');
        Route::post('/kontak/send', 'submitContactForm')->name('contact.submit');
        Route::get('/faq', 'faq')->name('faq');

        Route::prefix('/toko')->name('shop.')->controller(InformationController::class)->group(function () {
            Route::get('/', 'shop')->name('index');
            Route::get('/{id}', 'shopDetail')->name('show');
        });
    });

    //temporary for sertikom
    Route::view('/sertikom', 'contents.frontend.pages.service.sertikom')->name('sertikom');


    // Development Routes
    Route::prefix('/dev')->name('dev.')->controller(DEV\MainController::class)->group(function () {
        Route::get('/changelog', 'changelog')->name('changelog');
    });
});
