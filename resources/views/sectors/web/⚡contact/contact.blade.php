<div>
    <!-- Page Header Section Start -->
    <div class="page-header parallaxie">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Contact us</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('web.home') }}">home |</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact us</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header Section End -->

    <!-- Page Contact Us Start -->
    <div class="page-contact-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <!-- Contact Us Content Start -->
                    <div class="contact-us-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <span class="section-sub-title wow fadeInUp">Contact Us</span>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Get in touch with our formation experts
                            </h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Have questions about starting your business?
                                Our team is here to help you navigate the process of LLC formation and compliance.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Contact Info List Start -->
                        <div class="contact-info-list">
                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.2s">
                                <div class="icon-box"><img src="{{ asset('images/icon-phone-accent-secondary.svg') }}"
                                        alt=""></div>
                                <div class="contact-info-item-content">
                                    <h3>Phone Number</h3>
                                    <p><a href="tel:{{ $manager?->phone }}">{{ $manager?->phone ?? '+1 (234) 567-890' }}</a></p>
                                </div>
                            </div>
                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.4s">
                                <div class="icon-box"><img src="{{ asset('images/icon-mail-white.svg') }}" alt=""></div>
                                <div class="contact-info-item-content">
                                    <h3>Email Address</h3>
                                    <p><a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
                                </div>
                            </div>
                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.6s">
                                <div class="icon-box"><img
                                        src="{{ asset('images/icon-location-accent-secondary.svg') }}" alt=""></div>
                                <div class="contact-info-item-content">
                                    <h3>Our Office</h3>
                                    <p>{{ $manager?->address ?? '123 Business Way, Suite 100, New York, NY 10001' }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info List End -->
                    </div>
                    <!-- Contact Us Content End -->
                </div>

                <div class="col-lg-7">
                    <!-- Contact Us Form Start -->
                    <div class="contact-us-form wow fadeInUp" data-wow-delay="0.4s">
                        @if (session()->has('success'))
                            <div class="alert alert-success mb-4"
                                style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 8px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="submit" class="contact-form">
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" wire:model="name" class="form-control" placeholder="Your Name">
                                    @error('name') <span class="text-danger"
                                    style="font-size: 0.875rem;">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <input type="email" wire:model="email" class="form-control"
                                        placeholder="Your Email">
                                    @error('email') <span class="text-danger"
                                    style="font-size: 0.875rem;">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <input type="text" wire:model="subject" class="form-control" placeholder="Subject">
                                    @error('subject') <span class="text-danger"
                                    style="font-size: 0.875rem;">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <textarea wire:model="message" class="form-control" rows="5"
                                        placeholder="Your Message"></textarea>
                                    @error('message') <span class="text-danger"
                                    style="font-size: 0.875rem;">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn-default">
                                        <span wire:loading.remove>Send Message</span>
                                        <span wire:loading>Sending...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Contact Us Form End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Contact Us End -->


</div>