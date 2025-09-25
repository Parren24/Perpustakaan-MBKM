@php
    $lecturers = $dosenListGroup ?? null;
@endphp
<section class="lecturers-section">
    <div class="container ">
        <div class="row">
            <div class="col-12">
                <div class="section-title wow fadeInOut">
                    <h2 class="wow fadeInUp" id="profil-dosen">
                        Profil <span>Dosen</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="prodi-dosen">
                    <div class="row">
                        @foreach ($lecturers as $index => $lecturer)
                            <div class="col-lg-2 col-md-6">
                                <a href="{{ route('frontend.academic.lecturer-profile', [
                                    'slugLecturer' => createSlug($lecturer->nama_pegawai),
                                ]) }}"
                                    data-cursor-text="Lihat" class="team-member-item wow fadeInUp hoverable-card"
                                    data-wow-delay="0.4s">
                                    <div class="prodi-dosen-image">
                                        <figure class="image-anime">
                                            <img src="{{ publicMedia($lecturer->filemedia_pegawai, 'jurusan/dosen') }}"
                                                alt="Profil Dosen">
                                        </figure>
                                    </div>

                                    <div class="team-content">
                                        <h3>
                                            {{ ($lecturer->gelar_depan_pegawai ? $lecturer->gelar_depan_pegawai . ' ' : '') . $lecturer->nama_pegawai . ($lecturer->gelar_belakang_pegawai ? ' ' . $lecturer->gelar_belakang_pegawai : '') }}
                                        </h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
