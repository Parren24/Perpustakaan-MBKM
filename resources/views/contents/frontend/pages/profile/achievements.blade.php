@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="achievements-page content-page">
        <section class="achievements-content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="achievements-btns wow fadeInUp" data-wow-delay="0.2s">
                            <div class="achievements-hover"></div>
                            @foreach (data_get($content, 'achievements_categories', []) as $index => $category)
                                <button data-index="{{ $index }}">
                                    {{ strip_tags(data_get($category, 'title')) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="achievements-slides">
            @foreach (data_get($content, 'achievements_categories', []) as $index => $category)
                <div class="achievements-item">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title divider-dark-lg">
                                    <h3 class="wow fadeInUp">
                                        {{ data_get($category, 'subtitle') }}
                                    </h3>
                                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                                        {!! data_get($category, 'title') !!}
                                    </h2>
                                    <p class="wow fadeInUp" data-wow-delay="0.5s">
                                        {{ data_get($category, 'description') }}
                                    </p>
                                </div>
                            </div>
                            @foreach (data_get($category, 'achievements', []) as $achievement)
                                <div class="col-lg-6">
                                    @include('contents.frontend.partials.main.profile.achievements.achievement_item', ['item' => $achievement])
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="service-catagery-list wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                    <ul>
                                        <li>
                                            <a href="{{ route('frontend.articles.labels', [data_get($category, 'id')]) }}">
                                                Lihat Prestasi Lainnya
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttonContainer = document.querySelector('.achievements-btns');
            const buttons = document.querySelectorAll('.achievements-btns button');
            const page = document.querySelector('.achievements-page');
            const slides = document.querySelectorAll('.achievements-slides .achievements-item');

            page.style.setProperty('--slide-height', `${slides[0].offsetHeight}px`);
            page.style.setProperty('--transition-duration', '0.6s');
            page.style.setProperty('--slide-index', '0');
            page.style.setProperty('--hover-index', '0');
            page.style.setProperty('--hover-visible', '0');
            // Set the first button as active
            buttons[0].classList.add('active');

            buttons.forEach(button => {
                const index = button.getAttribute('data-index');

                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    buttons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to the clicked button
                    this.classList.add('active');

                    // Change the variable of slide index
                    page.style.setProperty('--slide-index', index);

                    // get the --transition-duration value of the page as number, and remove the 's' at the end
                    const transitionDuration = parseFloat(getComputedStyle(page).getPropertyValue(
                        '--transition-duration').trim().replace('s', ''));

                    // set height of the page to the height of the current slide
                    const currentSlide = slides[index];
                    page.style.setProperty('--slide-height', `${currentSlide.offsetHeight}px`);

                    // set the height of the page to the height of the current slide
                    page.style.setProperty('--slide-index', index);
                    page.style.setProperty('--hover-index', index);
                });

                button.addEventListener('mouseover', function() {
                    page.style.setProperty('--hover-index', index);
                    page.style.setProperty('--hover-visible', '1');
                });
            });

            buttonContainer.addEventListener('mouseleave', function() {
                page.style.setProperty('--hover-visible', '0');

            });
        })
    </script>
@endpush
