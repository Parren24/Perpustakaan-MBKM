@props(['pageConfig'])

{{-- Page Title --}}

@section('title', strip_tags(data_get($pageConfig, 'seo.title')))

{{-- SEO Meta Tags --}}
@section('meta.description', data_get($pageConfig, 'seo.description'))
@section('meta.keywords', data_get($pageConfig, 'seo.keywords'))
@section('meta.canonical', data_get($pageConfig, 'seo.canonical'))
@section('meta.robots', 'index, follow')

{{-- Open Graph Meta Tags --}}
@section('meta.og.title', data_get($pageConfig, 'seo.title'))
@section('meta.og.description', data_get($pageConfig, 'seo.description'))
@section('meta.og.image', data_get($pageConfig, 'seo.og_image'))
@section('meta.og.type', data_get($pageConfig, 'seo.og_type'))
@section('meta.og.url', data_get($pageConfig, 'seo.canonical'))

@push('head')
    {{-- Structured Data for SEO --}}
    <script type="application/ld+json">
        {!! json_encode(data_get($pageConfig, 'seo.structured_data'), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- Breadcrumb Structured Data --}}
    <script type="application/ld+json">
        {!! json_encode(data_get($pageConfig, 'seo.breadcrumb_structured_data'), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
