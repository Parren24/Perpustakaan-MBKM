<?php

namespace App\Http\Controllers\Frontend\Academic;

use App\Models\Dimension\Prodi;
use App\Models\Konten\KontenJurusan;
use App\Models\Konten\KontenProdi;
use App\Models\Dimension\Jurusan;
use App\Services\Academic\JurusanService;
use App\Static\Data\LecturerData;
use App\Http\Controllers\Controller;
use App\Models\Dimension\DmPegawai;
use App\Services\Frontend\SafeDataService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class JurusanController extends Controller
{
    public function jurusan()
    {
        $fallbacks = SafeDataService::getJurusanFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => JurusanService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => JurusanService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.academic.jurusan.index', compact(
            'content',
            'pageConfig'
        ));
    }

    public function jurusanDetail($jurusanAlias)
    {

        $jurusan = JurusanService::getJurusan($jurusanAlias);
        $kontenJurusan = JurusanService::getShowContent($jurusanAlias);

        if (!$kontenJurusan) {
            return view('contents.frontend.pages.academic.jurusan.show', [
                'jurusanTrimmed' => JurusanService::trimJurusanName($jurusan->nama_jurusan),
                'breadcrumbs' => []
            ]);
        }

        $header = []; //JurusanService::getJurusanHeader($jurusanAlias);
        $prodiListGroup = JurusanService::getProdiListGroup($jurusanAlias);
        $countProdi = Prodi::where('alias_jurusan', $jurusanAlias)->count();
        $dosenListGroup = DmPegawai::where('homebase_pegawai', Str::lower($jurusanAlias))->orderBy('nama_pegawai', 'ASC')->get();

        $kontenJurusan->jurusan->nama_jurusan = $jurusan->nama_jurusan;
        $kontenJurusan->jurusan->alias_jurusan = $jurusan->alias_jurusan;

        return view('contents.frontend.pages.academic.jurusan.show', [
            'kontenJurusan' => $kontenJurusan,
            'countProdi' => $countProdi,
            'prodiListGroup' => $prodiListGroup,
            'dosenListGroup' => $dosenListGroup,
            'breadcrumbs' => JurusanService::getJurusanBreadcrumbs(
                jurusan: $jurusan
            ),
            'header' => $header,
            'jurusanTrimmed' => JurusanService::trimJurusanName($jurusan->nama_jurusan)
        ]);
    }
}
