@extends('layouts.frontend.base')

@push('head')
    @include('contents.frontend.partials.common.head-data')

    <style >
        .changelog-page {
            & .history-entry {
                & ul > li > ul {
                    margin-left: 1rem;
                    margin-top: 0.5rem;
                }
            }
        }
    </style>
@endpush

@section('base-content')
    <main class="changelog-page">
        <div class="container mt-5 mb-5">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>
                            <span>Perubahan</span> Terbaru
                        </h2>
                        <p>
                            Terakhir diperbarui pada: {{ $lastModified->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>

                    <div class="history-content">
                        <div class="history-entry">
                            {!! $modifiedHtmlContent !!}

                            <h2>Akhir Dokumentasi</h2>

                            <div class="history-description">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('contents.frontend.partials.common.prototype-btns', ['buttons' => env('APP_ENV') !== 'production'])
@endsection

@push('script')
    @include('contents.frontend.partials.common.script-data')
@endpush
