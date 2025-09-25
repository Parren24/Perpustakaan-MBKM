<section class="service-entry divider-dark-lg kurikulum-section">
    <h2 class="wow fadeInUp" data-wow-delay="0.4s" id="kurikulum">
        <span>Kurikulum</span>
    </h2>

    <div class="our-faq-section ">
        <div class="faq-accordion" id="kurikulum-accordion">
            @for ($i = 0; $i < 8; $i++)
                <div class="accordion-item wow fadeInUp" data-wow-delay="{{ 0.2 * ($i + 1) }}s">
                    <h2 class="accordion-header" id="heading-{{ $i + 1 }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $i + 1 }}" aria-expanded="false"
                            aria-controls="collapse-{{ $i + 1 }}">
                            Semester {{ $i + 1 }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $i + 1 }}" class="accordion-collapse collapse"
                        aria-labelledby="heading-{{ $i + 1 }}" data-bs-parent="#kurikulum-accordion">
                        <div class="accordion-body">
                            <table class="kurikulum-grid">
                                <thead>
                                    <tr>
                                        <th>Mata Kuliah</th>
                                        <th>Kode</th>
                                        <th>SKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Bahasa Inggris</td>
                                        <td>WK407</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Bahasa Inggris</td>
                                        <td>WK407</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Bahasa Inggris</td>
                                        <td>WK407</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Bahasa Inggris</td>
                                        <td>WK407</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Bahasa Inggris</td>
                                        <td>WK407</td>
                                        <td>3</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endfor

        </div>
    </div>


</section>
