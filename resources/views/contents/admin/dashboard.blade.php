@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
{{-- <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar> --}}
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Dashboard</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<!--begin::Content container-->
<div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000" data-delay="0">
    <!-- Header Section -->
    <!-- Charts Row 1 -->
    <div class="row g-5 g-xl-8">
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Statistik Peminjaman</span>
                        <span class="text-muted fw-bold fs-7">Jumlah peminjaman per bulan tahun {{ $pageData->chartYear }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="loansChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Buku Belum Dikembalikan</span>
                        <span class="text-muted fw-bold fs-7">Berdasarkan bulan peminjaman tahun {{ $pageData->chartYear }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="unreturnedChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row g-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Pendapatan Denda</span>
                        <span class="text-muted fw-bold fs-7">Total nominal denda per bulan tahun {{ $pageData->chartYear }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="penaltiesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- ApexCharts is now bundled in app.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categories = @json($pageData -> chartMonths);

        // Chart 1: Loans
        var optionsLoans = {
            series: [{
                name: 'Jumlah Peminjaman',
                data: @json($pageData -> chartLoans)
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: categories
            },
            yaxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            colors: ['#009EF7'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " Transaksi"
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#loansChart"), optionsLoans).render();

        // Chart 2: Unreturned
        var optionsUnreturned = {
            series: [{
                name: 'Belum Kembali',
                data: @json($pageData -> chartUnreturned)
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: categories
            },
            colors: ['#F1416C'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " Buku"
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#unreturnedChart"), optionsUnreturned).render();

        // Chart 3: Penalties
        var optionsPenalties = {
            series: [{
                name: 'Total Denda',
                data: @json($pageData -> chartPenalties)
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            xaxis: {
                categories: categories
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(value);
                    }
                }
            },
            colors: ['#50CD89'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(val);
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#penaltiesChart"), optionsPenalties).render();
    });
</script>
@endpush