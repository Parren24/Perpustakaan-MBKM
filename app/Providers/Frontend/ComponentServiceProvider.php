<?php

namespace App\Providers\Frontend;

use App\Models\Konten\KontenJurusan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Header Data
        // view()->composer('contents.frontend.partials.common.header', function ($view) {
        //     // Format jurusan list
        //     $jurusanList = \App\Models\Dimension\Jurusan::inRandomOrder()->get()->map(function ($item) {
        //         return (object)[
        //             'id' => $item->kontenjurusan_id,
        //             'alias' => Str::lower($item->alias_jurusan),
        //             'name' => $item->nama_jurusan,
        //             'slicedName' => Str::trim(Str::replace('Jurusan', '', $item->nama_jurusan)),
        //         ];
        //     });

        //     $view->with('jurusanList', $jurusanList);
        // });
    }
}
