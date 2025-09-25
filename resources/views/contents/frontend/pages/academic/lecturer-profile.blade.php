@extends('layouts.frontend.main')

@section('title', "Profil Dosen $lecturer->nama_pegawai")

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="publicMedia('lecturer-profile.webp')">
        Profil
        <span>{{ ($lecturer->gelar_depan_pegawai ? $lecturer->gelar_depan_pegawai . ' ' : '') . $lecturer->nama_pegawai . ($lecturer->gelar_belakang_pegawai ? ' ' . $lecturer->gelar_belakang_pegawai : '') }}</span>
    </x-frontend.page-header>

    <div class="page-team-single lecturer-profile-section">
        <div class="container">
            <section class="lecturer-section">
                <div class="row">
                    <div class="col-lg-4">
                        {{-- Team Single Image Start --}}
                        <div class="team-single-image">
                            <figure class="image-anime reveal lecturer-image">
                                <img src="{{ publicMedia($lecturer->filemedia_pegawai, 'jurusan/dosen') }}" alt="">
                            </figure>
                            <div class="team-info-list wow fadeInUp mt-6 lecturer-card" data-wow-delay="0.75s">
                                <div class="lecturer-attr-section">
                                    <div class="lecturer-attr-badge">
                                        NIP
                                    </div>
                                    <div class="lecturer-attr-info">
                                        {{ $lecturer->nip_pegawai }}
                                    </div>
                                </div>
                                {{-- <div class="lecturer-attr-section">
                                    <div class="lecturer-attr-badge">
                                        NIDN
                                    </div>
                                    <div class="lecturer-attr-info">
                                        {{ $lecturer->nidn_pegawai }}
                                    </div>
                                </div> --}}
                                <div class="lecturer-attr-section">
                                    <div class="lecturer-attr-badge">
                                        Jurusan
                                    </div>
                                    <div class="lecturer-attr-info">
                                        {{ $lecturer->jurusan->nama_jurusan }}
                                    </div>
                                </div>
                                {{-- <div class="lecturer-attr-section">
                                    <div class="lecturer-attr-badge">
                                        Bidang Kompetensi
                                    </div>
                                    <div class="lecturer-attr-info">
                                        -
                                    </div>
                                </div> --}}
                                {{-- <div class="lecturer-attr-section">
                                    <div class="lecturer-attr-badge">
                                        Jabatan
                                    </div>
                                    <div class="lecturer-attr-info">
                                        {{ $lecturer->jabatan_pegawai }}
                                    </div>
                                </div> --}}
                                <div class="lecturer-attr-section">
                                    <div class="lecturer-attr-badge">
                                        Email
                                    </div>
                                    <div class="lecturer-attr-info">
                                        @if ($lecturer->email_pegawai)
                                            <a
                                                href="mailto:{{ $lecturer->email_pegawai }}">{{ $lecturer->email_pegawai }}</a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Team Single Image End --}}
                    </div>

                    <div class="col-lg-8">
                        <div class="team-single-content">
                            @if ($lecturer->profil_pegawai)
                                <div class="team-member-info">
                                    <div class="team-info-body">
                                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                                            {{ $lecturer->profil_pegawai }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="team-member-experience">
                                <div class="section-title">
                                    <h2 class="wow fadeInUp">Publikasi</h2>
                                </div>

                                <div class="member-experience-list lecturer-profile-lists wow fadeInUp"
                                    data-wow-delay="0.25s">
                                    {{-- <h4>Publikasi <span>Nasional</span></h4> --}}
                                    @if (!empty($publicationsLecturer))
                                        <ul>
                                            @foreach ($publicationsLecturer as $data)
                                                <li>
                                                    <h5>{{ $data['title'] }}
                                                    </h5>
                                                    <p class="fs-8 fst-italic"
                                                        style="
font-size: smaller;
display: -webkit-box;
                                                            -webkit-line-clamp: 2;
                                                            -webkit-box-orient: vertical;
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;
                                                            ">
                                                        {{ $data['authors'] }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="font-italic">- Data belum ditentukan -</span>
                                    @endif
                                    {{-- <ul>
                                        <h4>Publikasi <span>Internasional</span></h4>
                                        <li>
                                            <h5>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rem, laudantium?
                                            </h5>
                                            <p>
                                                Lorem Ipsum
                                            </p>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>

                            {{-- Lecturer Research Start --}}
                            <div class="team-member-experience">
                                {{-- Section Title Start --}}
                                <div class="section-title">
                                    <h2 class="wow fadeInUp">Penelitian</h2>
                                </div>
                                {{-- Section Btn Start --}}

                                {{-- Lecturer Research List Start --}}
                                <div class="member-experience-list lecturer-profile-lists wow fadeInUp"
                                    data-wow-delay="0.25s">
                                    @if (!empty($researchesLecturer))
                                        <ul>
                                            @foreach ($researchesLecturer as $data)
                                                <li>
                                                    <h5>{{ $data['title'] }}
                                                    </h5>
                                                    <p class="fs-8 fst-italic"
                                                        style="
  font-size: smaller;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
">
                                                        {{ $data['authors'] }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="font-italic">- Data belum ditentukan -</span>
                                    @endif
                                </div>
                                {{-- Lecturer Research List End --}}
                            </div>
                            {{-- Lecturer Research End --}}

                            {{-- Lecturer Service Start --}}
                            <div class="team-member-experience">
                                {{-- Section Title Start --}}
                                <div class="section-title">
                                    <h2 class="wow fadeInUp">Pengabdian Masyarakat</h2>
                                </div>
                                {{-- Section Btn Start --}}

                                {{-- Lecturer Service List Start --}}
                                <div class="member-experience-list lecturer-profile-lists wow fadeInUp"
                                    data-wow-delay="0.25s">
                                    @if (!empty($servicesLecturer))
                                        <ul>
                                            @foreach ($servicesLecturer as $data)
                                                <li>
                                                    <h5>{{ $data['title'] }}
                                                    </h5>
                                                    <p class="fs-8 fst-italic"
                                                        style="
  font-size: smaller;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
">
                                                        {{ $data['authors'] }}
                                                    </p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="font-italic">- Data belum ditentukan -</span>
                                    @endif
                                </div>
                                {{-- Lecturer Service List End --}}
                            </div>
                            {{-- Lecturer Service End --}}

                        </div>

                        <div class="service-catagery-list wow fadeInUp"
                            style="visibility: visible; animation-name: fadeInUp;">
                            <ul>
                                <li>
                                    <a href="https://bp2m.pcr.ac.id/main/searchResult/?inisial={{ $lecturer->inisial }}"
                                        target="_blank">
                                        Lihat Data Selengkapnya
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
