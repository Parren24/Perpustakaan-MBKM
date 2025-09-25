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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LecturerController extends Controller
{

    public function lecturerProfile($slugLecturer)
    {
        $lecturer = DmPegawai::where('slug_pegawai', $slugLecturer)->first();

        $limitData = 5;
        $researchesLecturer = [];
        $servicesLecturer = [];
        $publicationsLecturer = [];

        $response = Http::get('https://bp2m.pcr.ac.id/main/searchKinerjaDosen/?inisial=' . $lecturer->inisial . '&start=' . date('Y', strtotime('-1 year')) . '&end=' . date('Y'));
        if ($response->successful()) {
            // Ambil hasil JSON
            $data = json_decode($response->body());

            foreach ($data->data->penelitian as $index => $research) {
                if ($index >= $limitData)
                    break;

                $researchesLecturer[] = [
                    'title' => $research->judul,
                    'authors' => strtoupper(collect($research->researchers)->pluck('nama')->implode(', '))
                ];
            }
            foreach ($data->data->pengabdian as $index => $service) {
                if ($index >= $limitData)
                    break;

                $servicesLecturer[] = [
                    'title' => $service->judul,
                    'authors' => strtoupper(collect($service->pelaksana)->pluck('nama')->implode(', '))
                ];
            }
            foreach ($data->data->publikasi as $index => $publication) {
                if ($index >= $limitData)
                    break;

                $publicationsLecturer[] = [
                    'title' => $publication->judul,
                    'authors' => strtoupper(collect($publication->authors)->pluck('nama')->implode(', '))
                ];
            }
        }
        // dd([$researchesLecturer, $servicesLecturer, $publicationsLecturer]);

        return view('contents.frontend.pages.academic.lecturer-profile', [
            'lecturer' => $lecturer,
            'researchesLecturer' => $researchesLecturer,
            'servicesLecturer' => $servicesLecturer,
            'publicationsLecturer' => $publicationsLecturer,
            'breadcrumbs' => JurusanService::getJurusanBreadcrumbs(
                lecturer: $lecturer
            ),
        ]);
    }
}
