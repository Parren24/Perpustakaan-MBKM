@isset($buttons)
    @push('head')
        <style>
            .prototype-btns {
                position: fixed;
                bottom: 0;
                right: 24px;
                z-index: 9999;
                text-align: center;
                transition: all 0.3s var(--bezier-main);
                transform: translateY(100%);

                & .prototype-heading-wrapper {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    justify-content: center;

                    & a {
                        color: var(--white-color);
                        font-size: 14px;
                        text-decoration: underline;
                        transition: color 0.3s var(--bezier-main);

                        &:hover {
                            color: var(--accent-color);
                        }
                    }
                }

                & .prototype-btns-wrapper {
                    display: flex;
                    flex-direction: column;
                    border-radius: 12px 12px 0 0;
                    padding: 16px;
                    background-color: rgb(var(--primary-main-rgb), 0.25);
                    backdrop-filter: blur(8px);
                    gap: 8px;

                    & .prototype-heading {
                        color: var(--white-color);
                        font-size: 18px;
                        font-weight: 600;
                        background-color: var(--primary-main);
                        padding: 12px 24px;
                        border-radius: 999px;
                        width: fit-content;
                    }

                    & .prototype-group {
                        display: flex;
                        gap: 8px;
                        align-items: center;
                    }
                }


                & .prototype-btns-toggle {
                    --size: 32px;
                    --icon-size: calc(var(--size) / 2 - 2px);

                    all: unset;
                    position: absolute;
                    background-color: var(--primary-main);
                    color: var(--white-color);
                    border-radius: 999px;
                    width: var(--size);
                    height: var(--size);
                    display: grid;
                    place-items: center;
                    cursor: pointer;
                    top: calc(var(--size) / 4 * -1);
                    right: calc(var(--size) / 4);
                    transition: all 0.3s var(--bezier-main);
                    z-index: 9999;

                    & i {
                        font-size: var(--icon-size);
                    }


                }

                &.active {
                    transform: translateY(0);

                }

                &:not(.active) {
                    & .prototype-btns-toggle {
                        translate: 0 -44px;
                    }
                }
            }

            @media only screen and (max-width: 991px) {
                .prototype-btns {
                    left: 0;
                    right: 0;
                }
            }
        </style>
    @endpush
@endisset

@php
    $version = request()->get('version', 'v1');

    $versionName = match ($version) {
        'v1' => 'Utama',
        'v2' => 'Fix',
        'v3' => 'Alternatif',
        default => 'Utama',
    };
@endphp

@isset($buttons)
    <div class="prototype-btns">
        <button class="prototype-btns-toggle">
            <i class="fa-solid fa-chevron-up"></i>
        </button>

        <div class="prototype-btns-wrapper">

            <div class="prototype-heading-wrapper">
                <h3 class="prototype-heading">
                    <span>Prototype Warna | {{ $versionName }}</span>
                </h3>
                <a href="{{ route('frontend.dev.changelog') }}">Changelog</a>
            </div>
            <div class="prototype-group">
                <button data-version="v1" class="btn btn-default btn-highlighted">
                    Utama
                </button>
                <button data-version="v2" class="btn btn-default btn-highlighted">
                    Fix
                </button>
                <button data-version="v3" class="btn btn-default btn-highlighted">
                    Alternatif
                </button>
            </div>

        </div>
    </div>
@endisset

@if ($version === 'v3')
    @push('head')
        <style>
            :root {
                /* --primary-main: var(--primary-700); */
                --primary-main-rgb: 9, 64, 116 !important;
                --primary-main: rgb(var(--primary-main-rgb)) !important;
                --secondary-main: #fe9000 !important;
            }
        </style>
    @endpush
@elseif ($version === 'v2')
    @push('head')
        <style>
            :root {
                --primary-main-rgb: 9, 64, 116 !important;
                --primary-main: rgb(var(--primary-main-rgb)) !important;
                --secondary-main: rgb(var(--secondary-fix-rgb-700)) !important;
            }
        </style>
    @endpush
@elseif ($version === 'v1')
    @push('head')
        <style>
            :root {
                --primary-main-rgb: var(--primary-rgb-950) !important;
                --primary-main: var(--primary-950) !important;
                --secondary-main: var(--secondary-600) !important;
            }
        </style>
    @endpush
@endif

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const DEFAULT = 'v1';

            const activeCookie = document.cookie.split('; ').find(row => row.startsWith('prototype_version='));
            const btnsActive = document.cookie.split('; ').find(row => row.startsWith('prototype_btns_show='));
            const toggleButton = document.querySelector('.prototype-btns-toggle');
            const activeVersion = activeCookie ? activeCookie.split('=')[1] : DEFAULT;

            // Set the show state based on the cookie value
            const prototypeBtns = document.querySelector('.prototype-btns');
            if (btnsActive && btnsActive.split('=')[1] === 'true') {
                prototypeBtns.classList.add('active');
                toggleButton.querySelector('i').classList.add('fa-chevron-down');
                toggleButton.querySelector('i').classList.remove('fa-chevron-up');
            } else {
                prototypeBtns.classList.remove('active');
                toggleButton.querySelector('i').classList.add('fa-chevron-up');
                toggleButton.querySelector('i').classList.remove('fa-chevron-down');
            }

            // Set url search params based on the active version if not default
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);

            if (
                activeCookie !== DEFAULT && activeVersion !== DEFAULT && (
                    // If the version in the URL is different from the active version or if the cookie is set to a different version
                    (searchParams.get('version') && searchParams.get('version') !== activeVersion) ||
                    // Or if the cookie is set and the active version is not default
                    (activeCookie != DEFAULT && searchParams.get('version') !== activeVersion)
                )
            ) {
                searchParams.set('version', activeVersion);
                url.search = searchParams.toString();
                window.location.href = url.toString();
            } else if (activeVersion === DEFAULT) {
                // If the active version is default, remove the version from the URL
                searchParams.delete('version');
                url.search = searchParams.toString();
                window.history.replaceState({}, '', url.toString());
            }

            // Add click event listener to the toggle button
            toggleButton.addEventListener('click', function() {
                prototypeBtns.classList.toggle('active');
                toggleButton.querySelector('i').classList.toggle('fa-chevron-down');
                toggleButton.querySelector('i').classList.toggle('fa-chevron-up');

                // Set cookie to remember the state (prototype_btns_active)
                document.cookie =
                    `prototype_btns_show=${prototypeBtns.classList.contains('active') ? 'true' : 'false'}; path=/; max-age=31536000`;
            });

            // Add click event listener to the version buttons
            const versionButtons = document.querySelectorAll('.prototype-group button');
            versionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const version = this.getAttribute('data-version');
                    document.cookie = `prototype_version=${version}; path=/; max-age=31536000`;

                    const url = new URL(window.location.href);
                    const searchParams = new URLSearchParams(url.search);
                    searchParams.set('version', version);
                    url.search = searchParams.toString();
                    window.location.href = url.toString();
                });
            });
        })
    </script>
@endpush
