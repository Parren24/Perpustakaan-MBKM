<?php

namespace App\Http\Controllers\Frontend\Academic;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenMain;
use App\Models\Konten\KontenProdi;
use App\Models\View\Prodi;
use App\Services\Academic\ProdiService;
use Illuminate\Http\Request;
use Str;

class ProdiController extends Controller
{

    public function prodiDetail($prodiAlias)
    {
        $kontenProdi = ProdiService::getKonten($prodiAlias);
        $prodi       = ProdiService::getProdi($prodiAlias);

        if (!$kontenProdi) {
            abort(404, 'Program Studi tidak ditemukan.');
        }

        return view('contents.frontend.pages.academic.jurusan.prodi.show', compact('kontenProdi', 'prodi'));
    }

    public function home($prodiAlias)
    {
        $prodi      = Prodi::where('alias', Str::upper($prodiAlias))->firstOrFail();
        $kontenMain = KontenMain::where('level', 'prodi-site')->where('level_id', Str::upper($prodiAlias))->first();

        return view('contents.frontend.pages.academic.jurusan.prodi.home', compact('prodi', 'kontenMain'));
    }
}
