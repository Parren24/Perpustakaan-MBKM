<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="service-sidebar">
                <div class="service-catagery-list wow fadeInUp">
                    <h3>Daftar Isi</h3>
                    <ul>
                        <li><a href="#tentang-program-studi">Tentang Program Studi</a></li>
                        <li><a href="#prospek-karir">Prospek Karir</a></li>
                        <li><a href="#sejarah">Sejarah</a></li>
                        <li><a href="#visi-misi">Visi & Misi</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="service-single-content">
                <div class="service-featured-image">
                    <figure class="image-anime reveal">
                        <img src="{{ $kontenProdi->tentang->filemedia }}" alt="">
                    </figure>
                </div>

                @include('contents.frontend.partials.main.academic.jurusan.prodi.about')
                @include('contents.frontend.partials.main.academic.jurusan.prodi.prospek')
                @include('contents.frontend.partials.main.academic.jurusan.prodi.history')
                @include('contents.frontend.partials.main.academic.jurusan.prodi.visi-misi')
                {{-- @include('contents.frontend.partials.main.academic.jurusan.prodi.kurikulum') --}}
            </div>
        </div>
    </div>
</div>
