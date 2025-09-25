@push('head')
    <style>
        .extra-content {
            margin-top: 20px;
            color: #fff;
        }
    </style>
@endpush

<div class="page-header bg-section" style="background-image: url('{{ $image }}');">
    <div class="page-header-box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-content">
                        <h1 class="wow fadeInUp">
                            {{ $slot }}
                        </h1>
                        <nav class="wow fadeInUp" data-wow-delay="0.25s">
                            <ol class="breadcrumb">
                                @foreach ($breadcrumb_items as $item)
                                    @php
                                        // Check if item is second to last item for mobile breadcrumb
                                        $is_second_to_last = $loop->count > 1 && $loop->last === false && $loop->index === $loop->count - 2;

                                        $name = $item['name'] ?? 'Home';
                                        $url = $item['url'] ?? '#';
                                    @endphp

                                    <li
                                        class="breadcrumb-item {{ $loop->last ? 'active' : '' }} {{ $is_second_to_last ? 'mobile-visible' : '' }}">
                                        <span class="mobile-visible mobile-only">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </span>
                                        <a href="{{ $url }}">{{ strip_tags($name) }}</a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                        @isset($extra_content)
                            <div class="extra-content wow fadeInUp" data-wow-delay="0.5s">
                                {{ $extra_content }}
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
