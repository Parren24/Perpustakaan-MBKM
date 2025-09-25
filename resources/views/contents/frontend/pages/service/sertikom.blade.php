@extends('layouts.frontend.main')

{{-- @breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" /> --}}

@section('content')
    <x-frontend.page-header>
        Sertifikasi Kompetensi
    </x-frontend.page-header>

    <div class="container">
        <div class="row">
            <p class="my-4"><span style="font-size:26px"><span style="color:#0000CD"><img alt=""
                            src="https://pcr.ac.id/uploads/media/pcr_media20250810030913.jpg"
                            style="height:900px; width:900px" /></span></span></p>

            <p class="my-4"><img alt="" src="https://pcr.ac.id/uploads/media/pcr_media20250903034444.png"
                    style="height:900px; width:635px" /></p>

            <p><span style="font-size:18px"><span style="color:#000000"><strong>Daftar Sertifikasi</strong></span></span>
            </p>

            <table class="table table-bordered table-striped rounded rounded-4" cellpadding="1" cellspacing="1"
                style="width:100%">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center"><strong>No</strong></td>
                        <td style="text-align:center"><strong>Judul Pelatihan</strong></td>
                        <td style="text-align:center"><strong>Durasi</strong></td>
                        <td style="text-align:center"><strong>Metode</strong></td>
                        <td style="text-align:center"><strong>Penyelenggara Pelatihan</strong></td>
                        <td style="text-align:center"><strong>Penyelenggara Ujian Sertifikasi</strong></td>
                        <td style="text-align:center"><strong>Harga Pelatihan &amp; Sertifikasi</strong></td>
                        <td style="text-align:center"><strong>Minimal Peserta</strong></td>
                        <td style="text-align:center"><strong>Lembaga Sertifikasi</strong></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Radio Frequency Engineer</td>
                        <td>6 hari (5 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama LSP : LSP Telekomunikasi Digital Indonesia<br />
                            Nomor SK : 2935/BNSP/XII/2024<br />
                            Nomor Lisensi : BNSP-LSP-1596-ID<br />
                            Masa Berlaku : 6 Desember 2029<br />
                            Metode Penyelenggara : 1 hari Luring<br />
                            Tempat Pelatihan : PCR<br />
                            Tempat Uji Kompetensi : TUK PCR</td>
                        <td>Rp. 16.500.000</td>
                        <td>6 orang</td>
                        <td>LSP Telekomunikasi Digital&nbsp;</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Teknisi Instalasi dan Aktivasi Fiber Optik</td>
                        <td>6 hari (5 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama LSP : LSP Telekomunikasi Digital Indonesia<br />
                            Nomor SK : 2935/BNSP/XII/2024<br />
                            Nomor Lisensi : BNSP-LSP-1596-ID<br />
                            Masa Berlaku : 6 Desember 2029<br />
                            Metode Penyelenggara : 1 hari Luring<br />
                            Tempat Pelatihan : PCR<br />
                            Tempat Uji Kompetensi : TUK PCR</td>
                        <td>Rp. 16.250.000</td>
                        <td>6 orang</td>
                        <td>LSP Telekomunikasi Digital&nbsp;</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Customer Experience Tester (CET) / Walk Tester (WT) / Drivetester</td>
                        <td>5 hari (4 hari pelatihan + 1 hari sertifikasi</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama LSP : LSP Telekomunikasi Digital Indonesia<br />
                            Nomor SK : 2935/BNSP/XII/2024<br />
                            Nomor Lisensi : BNSP-LSP-1596-ID<br />
                            Masa Berlaku : 6 Desember 2029<br />
                            Metode Penyelenggara : 1 hari Luring<br />
                            Tempat Pelatihan : PCR<br />
                            Tempat Uji Kompetensi : TUK PCR</td>
                        <td>Rp. 16.500.000</td>
                        <td>6 orang</td>
                        <td>LSP Telekomunikasi Digital&nbsp;</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Junior Network Administrator</td>
                        <td>6 hari (5 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama LSP : LSP Telekomunikasi Digital Indonesia<br />
                            Nomor SK : 2935/BNSP/XII/2024<br />
                            Nomor Lisensi : BNSP-LSP-1596-ID<br />
                            Masa Berlaku : 6 Desember 2029<br />
                            Metode Penyelenggara : 1 hari Luring<br />
                            Tempat Pelatihan : PCR<br />
                            Tempat Uji Kompetensi : TUK PCR</td>
                        <td>Rp. 15.500.000</td>
                        <td>6 orang</td>
                        <td>LSP Telekomunikasi Digital&nbsp;</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>IT Auditor</td>
                        <td>7 hari (6 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama Penyelenggara:<br />
                            PCR berkerja sama<br />
                            dengan LSP P3 Informatika<br />
                            Nomor SK:-<br />
                            Nomor lisensi:-<br />
                            Masa berlaku: -<br />
                            Metode: Luring<br />
                            Penyelenggaraan: PCR<br />
                            Tempat Pelatihan: PCR<br />
                            Tempat Ujian: PCR<br />
                            Kompetensi: IT Auditor</td>
                        <td>Rp. 17.500.000</td>
                        <td>6 orang</td>
                        <td>LSP P3 Informatika</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>ICT Project Manager</td>
                        <td>7 hari (6 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama Penyelenggara:<br />
                            PCR berkerja sama<br />
                            dengan LSP P3 Informatika<br />
                            Nomor SK:-<br />
                            Nomor lisensi:-<br />
                            Masa berlaku: -<br />
                            Metode: Luring<br />
                            Penyelenggaraan: PCR<br />
                            Tempat Pelatihan: PCR<br />
                            Tempat Ujian: PCR<br />
                            Kompetensi: IT Auditor</td>
                        <td>Rp. 17.500.000</td>
                        <td>6 orang</td>
                        <td>LSP P3 Informatika</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Data Scientist</td>
                        <td>7 hari (6 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama Penyelenggara:<br />
                            PCR berkerja sama<br />
                            dengan LSP P3 Informatika<br />
                            Nomor SK:-<br />
                            Nomor lisensi:-<br />
                            Masa berlaku: -<br />
                            Metode: Luring<br />
                            Penyelenggaraan: PCR<br />
                            Tempat Pelatihan: PCR<br />
                            Tempat Ujian: PCR<br />
                            Kompetensi: IT Auditor</td>
                        <td>Rp. 17.500.000</td>
                        <td>6 orang</td>
                        <td>LSP P3 Informatika</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Certified Ethical Hacker</td>
                        <td>7 hari (6 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama Penyelenggara:<br />
                            PCR berkerja sama<br />
                            dengan EC-Council<br />
                            Nomor SK:-<br />
                            Nomor lisensi:-<br />
                            Masa berlaku: -<br />
                            Metode: Luring<br />
                            Penyelenggaraan: Politeknik Caltex Riau<br />
                            Tempat Pelatihan: Politeknik Caltex Riau<br />
                            Tempat Ujian: Politeknik Caltex Riau<br />
                            Kompetensi: CEH</td>
                        <td>Rp. 17.500.000</td>
                        <td>6 orang</td>
                        <td>EC Council</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Computer Hacking Forensic Investigator (CHFI)</td>
                        <td>7 hari (6 hari pelatihan + 1 hari sertifikasi)</td>
                        <td>Luring</td>
                        <td>Nama Lembaga Pelatihan:<br />
                            Politeknik Caltex Riau<br />
                            Nomor SK:<br />
                            Masa Berlaku: -<br />
                            Metode Penyelenggaraan:<br />
                            Luring<br />
                            Tempat Pelatihan:<br />
                            Politeknik Catex Riau<br />
                            Tempat Ujian Kompetensi:<br />
                            Politeknik Caltex Riau</td>
                        <td>Nama Penyelenggara:<br />
                            PCR berkerja sama<br />
                            dengan EC-Council<br />
                            Nomor SK:-<br />
                            Nomor lisensi:-<br />
                            Masa berlaku: -<br />
                            Metode: Luring<br />
                            Penyelenggaraan: Politeknik Caltex Riau<br />
                            Tempat Pelatihan: Politeknik Caltex Riau<br />
                            Tempat Ujian: Politeknik Caltex Riau<br />
                            Kompetensi: CHFI</td>
                        <td>Rp. 17.500.000</td>
                        <td>6 orang</td>
                        <td>EC Council</td>
                    </tr>
                </tbody>
            </table>

            <p>&nbsp;<a href="https://forms.gle/kTVeNDY7gSnpGA7p6" class="btn btn-default btn-highlighted mt-4"><span
                        style="font-size:20px">Klik Disini untuk
                        Daftar</span></a></p>

        </div>
    </div>
@endsection
