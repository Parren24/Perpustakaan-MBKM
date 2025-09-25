<div class="our-faqs hint-section" style="padding-bottom: 0">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                {{-- Our FAQs Content Start --}}
                <div class="our-faqs-content">
                    {{-- Section Title Start --}}
                    @php
                        $hintsSection = data_get($content ?? null, 'hints_section', []);
                        $hintsTitle = data_get($hintsSection, 'title', 'Petunjuk');
                        $hintsSubtitle = data_get($hintsSection, 'subtitle', 'Ingin <span>mudah menemukan</span> lokasi Politeknik Caltex Riau?');
                        $hintsIntro = data_get($hintsSection, 'intro', 'Politeknik Caltex Riau memiliki lokasi yang strategis dan mudah diakses. Berikut adalah beberapa petunjuk untuk membantu Anda menemukan kampus kami dengan mudah.');
                    @endphp

                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $hintsTitle }}</h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! $hintsSubtitle !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {{ $hintsIntro }}
                        </p>
                    </div>
                    {{-- Section Title End --}}
                </div>
                {{-- Our FAQs Content End --}}
            </div>

            <div class="col-lg-6">
                <div class="our-faq-section">
                    {{-- FAQ Accordion Start --}}
                    <div class="faq-accordion" id="faqaccordion">
                        @php $hints = data_get($content ?? null, 'hints', []); @endphp
                        @if (is_array($hints) && count($hints) > 0)
                            @foreach ($hints as $i => $hint)
                                @php
                                    $hid = 'collapse' . ($i + 1);
                                    $headId = 'heading' . ($i + 1);
                                @endphp
                                <div class="accordion-item wow fadeInUp" data-wow-delay="{{ 0.2 * $i }}s">
                                    <h2 class="accordion-header" id="{{ $headId }}">
                                        <button class="accordion-button {{ $i !== 1 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ $hid }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}" aria-controls="{{ $hid }}">
                                            {{ data_get($hint, 'title', 'Petunjuk ' . ($i + 1)) }}
                                        </button>
                                    </h2>
                                    <div id="{{ $hid }}" class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}" aria-labelledby="{{ $headId }}" data-bs-parent="#faqaccordion">
                                        <div class="accordion-body">
                                            <p>{{ data_get($hint, 'body', '') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    {{-- FAQ Accordion End --}}
                </div>
            </div>
        </div>
    </div>
</div>
