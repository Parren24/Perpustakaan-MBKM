<style>
    .value-list {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        /* gap: 20px; */

        & .value-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100px;

            & span {
                width: 100%;
                text-align: center;
                border: 0 solid transparent;
                border-color: var(--gray-200);
                border-style: solid;
                border-left-width: 3px;
                font-size: 48px;
                font-weight: 700;
                line-height: 36px;
            }

            & p {
                margin-top: 4px;
                font-size: 14px;
                font-weight: 600;
                text-transform: uppercase;
                color: var(--gray-500);
            }

            &:nth-child(odd) span {
                color: var(--primary-main);
            }

            &:nth-child(even) span {
                color: var(--accent-color);
            }

            &:last-child span {
                border-right-width: 3px;
            }
        }
    }
</style>

<section class="page-testimonial pcr-value-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="value-list">
                    @php
                        $values = data_get($content, 'values.items');
                    @endphp

                    @foreach ($values as $val)
                        <div class="value-item">
                            <span>{{ data_get($val, 'short', '') }}</span>
                            <p>{{ data_get($val, 'title', '') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 ml-lg-3 ml-0">
                <div class="section-title">
                    @if (data_get($content, 'values.subtitle'))
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'values.subtitle') }}
                        </h3>
                    @endif
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        <span>{{ data_get($content, key: 'values.title') }}</span>
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s">
                        {{ data_get($content, 'values.subtitle') }}
                    </p>
                </div>
            </div>
            <div class="col-lg-12">
                @foreach ($values as $val)
                    <div class="client-testimonial-item wow fadeInUp">
                        <div class="client-testimonial-author">
                            <div class="client-author-image">
                                <i class="{{ data_get($val, 'icon') }}"></i>
                            </div>

                            <div class="client-author-content">
                                <h3>{{ data_get($val, 'title') }}</h3>
                                <p>
                                    ({{ data_get($val, 'meaning') }})
                                </p>
                            </div>
                        </div>

                        <div class="client-testimonial-content">
                            <p>
                                {{ data_get($val, 'description') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
