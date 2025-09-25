<section class="page-contact-us contact-section">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-7 col-md-9">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        {{ data_get($content, 'header') }}
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($content, 'title') !!}
                    </h2>
                </div>
                <!-- Section Title End -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <!-- Contact Information Start -->
                <div class="contact-information">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp">
                        <div class="contact-info-contant">
                            <h3>{{ data_get($content, 'contact_sections.phone.title') }}</h3>
                            <p>{{ data_get($content, 'contact_sections.phone.description') }}</p>
                        </div>
                        @foreach (data_get($content, 'contact_sections.phone.details', []) as $phone)
                            <div class="contact-info-group wow fadeInUp" data-wow-delay="0.1s">
                                <h4 class="mt-2">{{ data_get($phone, 'label') }}</h4>
                                <div class="contact-info-body">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div class="contact-info-title">
                                        <h3>{{ data_get($phone, 'number') }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.25s">
                        <div class="contact-info-contant">
                            <h3>{{ data_get($content, 'contact_sections.email.title') }}</h3>
                            <p>{{ data_get($content, 'contact_sections.email.description') }}</p>
                        </div>
                        @foreach (data_get($content, 'contact_sections.email.details', []) as $email)
                            <div class="contact-info-group wow fadeInUp" data-wow-delay="0.1s">
                                <h4 class="mt-2">{{ data_get($email, 'label') }}</h4>
                                <div class="contact-info-body">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="contact-info-title">
                                        <h3>{{ data_get($email, 'email') }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.5s">
                        <div class="contact-info-contant">
                            <h3>{{ data_get($content, 'contact_sections.address.title') }}</h3>
                            <p>{{ data_get($content, 'contact_sections.address.description') }}</p>
                        </div>
                        @foreach (data_get($content, 'contact_sections.address.details', []) as $address)
                            <div class="contact-info-group wow fadeInUp" data-wow-delay="0.1s">
                                <h4 class="mt-2">{{ data_get($address, 'label') }}</h4>
                                <div class="contact-info-body">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div class="contact-info-title">
                                        <h3>{{ data_get($address, 'address') }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if (data_get($content, 'contact_sections.address.map_embed_url'))
                            <div class="contact-map mt-4">
                                <iframe src="{{ data_get($content, 'contact_sections.address.map_embed_url') }}" width="100%" height="250" class="rounded-5" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        @endif
                    </div>
                    <!-- Contact Info Item End -->
                </div>
                <!-- Contact Information End -->
            </div>

            <div class="col-lg-6">
                <!-- Contact Us Form Start -->
                <div class="contact-us-form divider-dark-lg">
                    <!-- Contact Us Title Start -->
                    <div class="contact-us-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'form.title') }}
                        </h3>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            {{ data_get($content, 'form.description') }}
                        </p>
                    </div>
                    <!-- Contact Us Title End -->

                    <!-- Contact Us Form Start -->
                    <form id="formContact" action="{{ data_get($content, 'form.action_url') }}" method="POST" data-toggle="validator" class="wow fadeInUp"
                          data-wow-delay="0.4s">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12 mb-4">
                                <input type="text" name="name" class="form-control" id="name"
                                       placeholder="{{ data_get($content, 'form.fields.name.placeholder') }}" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group col-md-12 mb-4">
                                <input type="email" name="email" class="form-control" id="email"
                                       placeholder="{{ data_get($content, 'form.fields.email.placeholder') }}" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group col-md-12 mb-4">
                                <input type="text" name="subject" class="form-control" id="subject"
                                       placeholder="{{ data_get($content, 'form.fields.subject.placeholder') }}" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group col-md-12 mb-5">
                                <textarea name="message" class="form-control" id="message" rows="4" placeholder="{{ data_get($content, 'form.fields.message.placeholder') }}" required></textarea>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-form-btn">
                                    <button type="submit" id="submitBtn" class="btn-default">Kirim Pesan</button>
                                    <span id="loadingIndicator" class="ms-3 text-primary d-none">Mengirim...</span>
                                    <div id="msgSubmit" class="alert alert-success hidden mt-3 d-none"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const formContact = document.getElementById('formContact');
                            const submitBtn = document.getElementById('submitBtn');
                            const loadingIndicator = document.getElementById('loadingIndicator');
                            const msgSubmit = document.getElementById('msgSubmit');

                            if (formContact) {
                                formContact.addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    // Reset messages and show loading
                                    msgSubmit.classList.add('hidden');
                                    msgSubmit.classList.remove('text-success', 'text-danger');
                                    msgSubmit.textContent = '';
                                    loadingIndicator.classList.remove('d-none');
                                    submitBtn.disabled = true;

                                    const formData = new FormData(formContact);
                                    const jsonData = {};
                                    formData.forEach((value, key) => {
                                        jsonData[key] = value;
                                    });

                                    fetch(formContact.action, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                            },
                                            body: JSON.stringify(jsonData)
                                        })
                                        .then(response => response.json().then(data => ({
                                            status: response.status,
                                            body: data
                                        })))
                                        .then(response => {
                                            loadingIndicator.classList.add('d-none');
                                            submitBtn.disabled = false;
                                            msgSubmit.classList.remove('d-none');

                                            if (response.status === 200) {
                                                msgSubmit.textContent = response.body.message;
                                                msgSubmit.classList.add('text-success');
                                                formContact.reset();
                                                // Optional: Fade out success message after a few seconds
                                                setTimeout(() => {
                                                    msgSubmit.classList.add('d-none');
                                                }, 5000);
                                            } else {
                                                let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                                                if (response.body.errors) {
                                                    errorMessage = Object.values(response.body.errors).flat().join('\n');
                                                } else if (response.body.message) {
                                                    errorMessage = response.body.message;
                                                }
                                                msgSubmit.textContent = errorMessage;
                                                msgSubmit.classList.add('text-danger');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            loadingIndicator.classList.add('d-none');
                                            submitBtn.disabled = false;
                                            msgSubmit.classList.remove('d-none');
                                            msgSubmit.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                                            msgSubmit.classList.add('text-danger');
                                        });
                                });
                            }
                        });
                    </script>
                    <!-- Contact Us Form End -->
                </div>
                <!-- Contact Us Form End -->

                <div class="contact-social">
                    <div class="contact-us-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'social_media.title') }}
                        </h3>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            {{ data_get($content, 'social_media.description') }}
                        </p>
                    </div>

                    <div class="social-icons wow fadeInUp" data-wow-delay="0.4s">
                        @foreach (data_get($content, 'social_media.links', []) as $social)
                            <a href="{{ data_get($social, 'url') }}" target="_blank" class="social-icon">
                                <i class="{{ data_get($social, 'icon') }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="d-block d-lg-none">
                    <div class="divider-dark-lg mb-5"></div>
                </div>
            </div>
        </div>
    </div>
</section>
