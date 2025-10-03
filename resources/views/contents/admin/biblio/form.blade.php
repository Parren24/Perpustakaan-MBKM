@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
<x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
    <x-slot:tools>
    </x-slot:tools>
</x-theme.toolbar>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
    data-delay="0">
    <form id="formData" class="form needs-validation" jf-form="biblio" action="{{ route('app.biblio.update') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $pageData->dataBiblio['id'] ?? '' }}">
        <div class="card">
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <x-form.input
                            name="title"
                            label="Judul Buku"
                            :value="$pageData->dataBiblio['title'] ?? ''"
                            required />
                    </div>
                    <div class="col-md-6">
                        <x-form.input
                            name="author"
                            label="Penulis"
                            :value="$pageData->dataBiblio['author'] ?? ''"
                            required />
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-4">
                        <x-form.input
                            name="publisher"
                            label="Penerbit"
                            :value="$pageData->dataBiblio['publisher'] ?? ''"
                            required />
                    </div>
                    <div class="col-md-4">
                        <x-form.input
                            type="number"
                            name="year"
                            label="Tahun Terbit"
                            :value="$pageData->dataBiblio['year'] ?? ''"
                            required />
                    </div>
                    <div class="col-md-4">
                        <x-form.input
                            type="number"
                            name="stock"
                            label="Stok"
                            :value="$pageData->dataBiblio['stock'] ?? ''"
                            required
                            min="0" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <x-form.input
                            name="description"
                            label="Deskripsi"
                            :value="$pageData->dataBiblio['description'] ?? ''"
                            rows="4" />
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6">
                <x-btn.form action="cancel" class="btn-warning" link="{{ route('app.biblio.index') }}" />
                <x-btn.form action="save" class="act-save" jf-save="biblio" />
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "biblio",
        url: {
            add: `{{ route('app.biblio.store') }}`,
            // edit: `{{ route('app.biblio.data', ['param1' => 'detail']) }}`,
            update: `{{ route('app.biblio.update') }}`

        },
        success: {
            redirect: `{{ route('app.biblio.index') }}`
        }
    });
</script>
@endpush