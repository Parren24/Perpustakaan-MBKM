@props([
    'slides' => [],
    'hero_data' => null,
    'site_identity' => null,
])

<section class="hero bg-section bg-hero hero-slider-layout hero-gsap-layout">
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach ($slides as $slideIndex => $slide)
                <div class="swiper-slide" data-slide-index="{{ $slideIndex }}"
                     data-titles='@json(data_get($slide, 'titles', ['Selamat Datang']))'>
                    <div class="hero-slide">
                        <div class="hero-slider-video">
                            {{-- Overlay --}}
                            <div class="hero-slider-video-overlay" style="opacity: 0.5; background-color: #000000;"></div>

                            {{-- Media (Video or Image) --}}
                            @if (data_get($slide, 'media.type') === 'video')
                                <video autoplay muted loop>
                                    <source
                                            src="{{ data_get($slide, 'media.src', 'theme/frontend/videos/profil-pcr.mp4') }}"
                                            type="video/mp4">
                                </video>
                            @else
                                <div class="hero-slider-image"
                                     style="background-image: url('{{ data_get($slide, 'media.src') }}');">
                                </div>
                            @endif
                        </div>

                        <div class="hero-section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="hero-content">
                                            <div class="section-title">
                                                @if (data_get($slide, 'subtitle'))
                                                    <h3 class="wow fadeInUp">{!! data_get($slide, 'subtitle') !!}</h3>
                                                @endif
                                                <div class="titles">
                                                    <h1 class="hero-title wow fadeInUp"
                                                        data-slide-index="{{ $slideIndex }}">
                                                        <span class="dynamic-title"></span>
                                                    </h1>
                                                </div>
                                            </div>

                                            {{-- CTA Buttons --}}
                                            @if (data_get($slide, 'cta_buttons'))
                                                <div class="d-flex flex-column gap-4">
                                                    <div class="hero-btn wow fadeInUp">
                                                        @foreach (data_get($slide, 'cta_buttons', []) as $cta)
                                                            <a href="{{ data_get($cta, 'url', '#') }}"
                                                               class="{{ data_get($cta, 'class', 'btn-default') }}"
                                                               @if (data_get($cta, 'target')) target="{{ data_get($cta, 'target') }}" @endif>
                                                                {{ data_get($cta, 'text', 'Button') }}
                                                            </a>
                                                        @endforeach
                                                    </div>

                                                    {{-- Social Media --}}
                                                    @if (data_get($slide, 'show_social_media', true))
                                                        <div class="hero-btn wow fadeInUp">
                                                            @foreach (data_get($site_identity, 'social_media', []) as $social)
                                                                <a href="{{ data_get($social, 'url', '#') }}"
                                                                   target="_blank"
                                                                   class="btn-default btn-secondary btn-social"
                                                                   title="{{ data_get($social, 'name', 'Social Media') }}">
                                                                    <i
                                                                       class="{{ data_get($social, 'icon', 'fa-solid fa-link') }}"></i>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="hero-pagination"></div>
    </div>
</section>

@push('script')
    <script>
        (() => {
            document.addEventListener('DOMContentLoaded', function() {
                let currentSlideIndex = 0;
                let autoplayTimeout;

                const gsap = window.gsap;

                // Hero Slider Configuration
                const swiperConfig = @json(data_get($hero_data, 'slider_config', []));
                const heroSwiper = new Swiper(".hero-gsap-layout .swiper", swiperConfig);

                // Get slide data from HeroService
                const slides = @json($slides);
                const slideDurations = @json(data_get($hero_data, 'slide_durations', []));

                // Extract slide delays
                const slideDelays = slideDurations.map(duration => duration.slide_duration || 8000);
                // const slideDelays = slideDurations.map(duration => 30000);
                // console.log('GSAP Hero - Slide delays:', slideDelays);

                // GSAP APPROACH - Advanced title animations
                const slideTitles = {};
                let currentTitleIndex = {};
                let titleIntervals = {};
                let videoEndedListeners = {};

                // JavaScript implementation of getSmartLength function
                function getSmartLength(str) {
                    // Remove HTML tags
                    const strippedHtml = str.replace(/<[^>]*>/g, '');
                    // Remove non-alphanumeric characters except spaces
                    const cleanStr = strippedHtml.replace(/[^a-zA-Z0-9\s]/g, '');
                    return cleanStr.length;
                }

                // JavaScript implementation of smartSplit function (matching PHP logic)
                function smartSplit(text, maxLength = 23, delimiters = [',']) {
                    if (!text) return [''];

                    const lines = [];
                    let remainingText = text.trim();

                    while (remainingText.length > 0) {
                        let foundDelimiterSplit = false;

                        // Check for delimiters first (matching PHP logic)
                        for (const delimiter of delimiters) {
                            const pos = remainingText.indexOf(delimiter);
                            if (pos !== -1) {
                                const segmentBeforeDelimiter = remainingText.substring(0, pos);
                                if (getSmartLength(segmentBeforeDelimiter) <= maxLength) {
                                    const line = remainingText.substring(0, pos + delimiter.length);
                                    lines.push(line.trim());
                                    remainingText = remainingText.substring(pos + delimiter.length).trimStart();
                                    foundDelimiterSplit = true;
                                    break;
                                }
                            }
                        }

                        if (foundDelimiterSplit) {
                            continue;
                        }

                        // Find potential break point based on effective length
                        let potentialLineBreakPointRaw = 0;
                        for (let i = 0; i < remainingText.length; i++) {
                            const tempSubstring = remainingText.substring(0, i + 1);
                            const effectiveLengthAtIndex = getSmartLength(tempSubstring);

                            if (effectiveLengthAtIndex >= maxLength) {
                                potentialLineBreakPointRaw = i + 1;
                                break;
                            }
                            potentialLineBreakPointRaw = i + 1;
                        }

                        // If remaining text fits within maxLength
                        if (potentialLineBreakPointRaw === remainingText.length) {
                            lines.push(remainingText.trim());
                            break;
                        }

                        let lineBreakPoint = potentialLineBreakPointRaw;
                        const lastSpaceBeforePotential = remainingText.substring(0, potentialLineBreakPointRaw)
                            .lastIndexOf(' ');

                        // Check if word is cut off
                        let isWordCutOff = false;
                        if (potentialLineBreakPointRaw > 0 &&
                            remainingText[potentialLineBreakPointRaw - 1] &&
                            !/\s/.test(remainingText[potentialLineBreakPointRaw - 1])) {
                            if (remainingText[potentialLineBreakPointRaw] &&
                                !/\s/.test(remainingText[potentialLineBreakPointRaw])) {
                                isWordCutOff = true;
                            }
                        }

                        if (isWordCutOff) {
                            // Find word boundaries
                            let wordStartRaw = potentialLineBreakPointRaw - 1;
                            while (wordStartRaw > 0 &&
                                remainingText[wordStartRaw - 1] &&
                                !/\s/.test(remainingText[wordStartRaw - 1])) {
                                wordStartRaw--;
                            }

                            let wordEndRaw = potentialLineBreakPointRaw;
                            while (wordEndRaw < remainingText.length &&
                                remainingText[wordEndRaw] &&
                                !/\s/.test(remainingText[wordEndRaw])) {
                                wordEndRaw++;
                            }

                            const prefix = remainingText.substring(wordStartRaw, potentialLineBreakPointRaw);
                            const suffix = remainingText.substring(potentialLineBreakPointRaw, wordEndRaw);

                            const prefixEffectiveLength = getSmartLength(prefix);
                            const suffixEffectiveLength = getSmartLength(suffix);

                            // Apply "shortest path" rule
                            if (suffixEffectiveLength < prefixEffectiveLength) {
                                if (wordStartRaw > 0) {
                                    const potentialSplitBeforeWord = remainingText.substring(0, wordStartRaw)
                                        .lastIndexOf(' ');
                                    lineBreakPoint = (potentialSplitBeforeWord !== -1) ?
                                        potentialSplitBeforeWord : wordStartRaw;
                                } else {
                                    lineBreakPoint = potentialLineBreakPointRaw;
                                }
                            } else {
                                lineBreakPoint = (lastSpaceBeforePotential !== -1) ? lastSpaceBeforePotential :
                                    potentialLineBreakPointRaw;
                            }
                        } else {
                            lineBreakPoint = (lastSpaceBeforePotential !== -1) ? lastSpaceBeforePotential :
                                potentialLineBreakPointRaw;
                        }

                        // Ensure valid break point
                        if (lineBreakPoint < 0) {
                            lineBreakPoint = potentialLineBreakPointRaw;
                        }
                        if (lineBreakPoint > remainingText.length) {
                            lineBreakPoint = remainingText.length;
                        }

                        const line = remainingText.substring(0, lineBreakPoint);
                        lines.push(line.trim());

                        remainingText = remainingText.substring(lineBreakPoint).trimStart();
                    }

                    return lines.filter(line => line.length > 0);
                }

                // Extract titles from slides data and pre-process them with smartSplit
                slides.forEach((slide, slideIndex) => {
                    const rawTitles = slide.titles || [];
                    // Process each title with smartSplit
                    slideTitles[slideIndex] = rawTitles.map(title => smartSplit(title, 23, [',']));
                    currentTitleIndex[slideIndex] = 0;
                });

                // console.log('GSAP Hero - Processed slide titles:', slideTitles);

                // GSAP title animation function with smartSplit lines
                function animateTitle(slideIndex, titleLines) {
                    const titleElement = document.querySelector(
                        `.hero-title[data-slide-index="${slideIndex}"] .dynamic-title`);

                    if (!titleElement) return;

                    // Clear previous content
                    titleElement.innerHTML = '';

                    // Create line divs from smartSplit processed lines
                    titleLines.forEach((line, lineIndex) => {
                        const lineDiv = document.createElement('div');
                        lineDiv.className = 'line';
                        lineDiv.innerHTML = line;
                        titleElement.appendChild(lineDiv);

                        // Split line into characters for animation
                        const chars = line.replace(/<[^>]*>/g, '').split(
                            ''); // Remove HTML tags for character splitting
                        lineDiv.innerHTML = '';

                        // Create spans for each character
                        chars.forEach((char, charIndex) => {
                            const span = document.createElement('span');
                            span.textContent = char === ' ' ? '\u00A0' :
                                char; // Non-breaking space
                            span.style.display = 'inline-block';
                            lineDiv.appendChild(span);
                        });
                    });

                    // Set title element visible
                    gsap.set(titleElement, {
                        visibility: 'visible'
                    });

                    // Animate each character with GSAP
                    const charSpans = titleElement.querySelectorAll('span');
                    gsap.fromTo(charSpans, {
                        opacity: 0,
                        y: 30,
                        rotationX: -90
                    }, {
                        opacity: 1,
                        y: 0,
                        rotationX: 0,
                        duration: 0.2,
                        delay: (i) => i * 0.02, // Stagger animation
                        ease: "power4.out",
                        stagger: 0.02
                    });

                    // console.log(`🎬 GSAP animated title for slide ${slideIndex}: "${titleLines.join(' ').substring(0, 30)}..."`);
                }

                // Advanced title rotation with GSAP and smartSplit
                function rotateTitle(slideIndex) {
                    const titleElement = document.querySelector(
                        `.hero-title[data-slide-index="${slideIndex}"] .dynamic-title`);

                    if (!titleElement || !slideTitles[slideIndex] || slideTitles[slideIndex].length <= 1) {
                        return;
                    }

                    const titles = slideTitles[slideIndex];

                    // Fade out current title with GSAP
                    gsap.to(titleElement, {
                        opacity: 0,
                        y: -20,
                        duration: 0.4,
                        ease: "power2.in",
                        onComplete: () => {
                            // Update index for next title
                            currentTitleIndex[slideIndex] = (currentTitleIndex[slideIndex] + 1) %
                                titles.length;
                            const nextTitleLines = titles[currentTitleIndex[slideIndex]];

                            // Animate new title with smartSplit lines
                            animateTitle(slideIndex, nextTitleLines);

                            // Fade in new title
                            gsap.to(titleElement, {
                                opacity: 1,
                                y: 0,
                                duration: 0.4,
                                ease: "power2.out",
                                delay: 0.2
                            });
                        }
                    });
                }

                // Start title rotation for a slide
                function startTitleRotation(slideIndex) {
                    // Clear existing interval
                    if (titleIntervals[slideIndex]) {
                        clearInterval(titleIntervals[slideIndex]);
                    }

                    const titleElement = document.querySelector(
                        `.hero-title[data-slide-index="${slideIndex}"] .dynamic-title`);

                    if (!titleElement || !slideTitles[slideIndex] || slideTitles[slideIndex].length <= 1) {
                        // console.log(`⏹️ GSAP - Slide ${slideIndex} has ${slideTitles[slideIndex]?.length || 0} titles - no rotation needed`);

                        // Still animate the single title with smartSplit lines
                        if (slideTitles[slideIndex] && slideTitles[slideIndex].length === 1) {
                            animateTitle(slideIndex, slideTitles[slideIndex][0]);
                        }
                        return;
                    }

                    // console.log(`🔄 GSAP - Starting title rotation for slide ${slideIndex} with ${slideTitles[slideIndex].length} titles`);

                    // Animate first title immediately with smartSplit lines
                    animateTitle(slideIndex, slideTitles[slideIndex][0]);

                    // Start rotation every 5 seconds
                    titleIntervals[slideIndex] = setInterval(() => {
                        rotateTitle(slideIndex);
                    }, 5000);
                }

                // Stop title rotation for a slide
                function stopTitleRotation(slideIndex) {
                    if (titleIntervals[slideIndex]) {
                        clearInterval(titleIntervals[slideIndex]);
                        // console.log(`⏹️ GSAP - Stopped title rotation for slide ${slideIndex}`);
                    }
                }

                // Initialize all titles
                slides.forEach((slide, slideIndex) => {
                    const titleElement = document.querySelector(
                        `.hero-title[data-slide-index="${slideIndex}"] .dynamic-title`);
                    if (titleElement) {
                        if (slideIndex === 0) {
                            gsap.set(titleElement, {
                                opacity: 1,
                                visibility: 'visible'
                            });
                        } else {
                            gsap.set(titleElement, {
                                opacity: 0,
                                visibility: 'hidden'
                            });
                        }
                    }
                });

                // Start rotation for first slide
                startTitleRotation(0);

                // Custom autoplay with video handling
                function startCustomAutoplay() {
                    clearTimeout(autoplayTimeout);

                    const slideDelay = slideDelays[currentSlideIndex] || 20000;
                    // console.log(`⏰ GSAP - Slide ${currentSlideIndex} will advance after ${slideDelay} ms`);

                    autoplayTimeout = setTimeout(() => {
                        // console.log(`🔄 GSAP - Auto-advancing from slide ${currentSlideIndex}`);
                        heroSwiper.slideNext();
                        currentSlideIndex = (currentSlideIndex + 1) % slideDelays.length;
                    }, slideDelay);
                }

                startCustomAutoplay();

                // Enhanced slide change handler
                heroSwiper.on('slideChange', function() {
                    // console.log(`🔄 GSAP - Slide changed to index: ${heroSwiper.realIndex}`);
                    const oldSlide = currentSlideIndex;
                    currentSlideIndex = heroSwiper.realIndex;

                    // Hide old slide title with GSAP
                    const oldTitleElement = document.querySelector(
                        `.hero-title[data-slide-index="${oldSlide}"] .dynamic-title`);
                    if (oldTitleElement) {
                        gsap.to(oldTitleElement, {
                            opacity: 0,
                            visibility: 'hidden',
                            duration: 0.3
                        });
                    }

                    // Show new slide title with GSAP
                    const newTitleElement = document.querySelector(
                        `.hero-title[data-slide-index="${currentSlideIndex}"] .dynamic-title`);
                    if (newTitleElement) {
                        gsap.to(newTitleElement, {
                            opacity: 1,
                            visibility: 'visible',
                            duration: 0.3,
                            delay: 0.2
                        });
                    }

                    // Stop rotation on old slide
                    stopTitleRotation(oldSlide);

                    // Start rotation on new slide
                    startTitleRotation(currentSlideIndex);

                    // Handle video ended listeners
                    const currentSlide = slides[currentSlideIndex];
                    const video = document.querySelector(
                        `.swiper-slide[data-slide-index="${currentSlideIndex}"] video`);

                    if (video && currentSlide.media && currentSlide.media.type === 'video') {
                        // Clear previous listener
                        if (videoEndedListeners[currentSlideIndex]) {
                            video.removeEventListener('ended', videoEndedListeners[currentSlideIndex]);
                        }

                        // Add new listener
                        videoEndedListeners[currentSlideIndex] = () => {
                            // console.log(`📹 GSAP - Video ended on slide ${currentSlideIndex}`);
                            heroSwiper.slideNext();
                        };
                        video.addEventListener('ended', videoEndedListeners[currentSlideIndex]);
                    }

                    // Restart autoplay
                    startCustomAutoplay();
                });

                // console.log('🚀 GSAP Hero Slider initialized successfully');
            });
        })();
    </script>
@endpush
