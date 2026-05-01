<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="description"
        content="{{ config('app.name') }} - Fast and reliable US business formation and compliance.">
    <meta name="keywords" content="LLC formation, business registration, registered agent, EIN, US company setup">
    <meta name="author" content="{{ config('app.name') }}">

    <!-- Page Title -->
    <title>{{ config('app.name') . ' | ' . ($title ?? 'Official US Business Formation') }}</title>

    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts Css-->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
    <!-- SlickNav Css -->
    <link href="{{ asset('css/slicknav.min.css') }}" rel="stylesheet">
    <!-- Swiper Css -->
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <!-- Font Awesome Icon Css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Animated Css -->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <!-- Magnific Popup Core Css File -->
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <!-- Mouse Cursor Css File -->
    <link rel="stylesheet" href="{{ asset('css/mousecursor.css') }}">
    <!-- Main Custom Css -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" media="screen">

    <style>
        body,
        .btn-default,
        .nav-link,
        .about-footer-content,
        .footer-links ul li a {
            font-family: 'Quicksand', sans-serif !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Quicksand', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -1px !important;
        }

        .faqs-content-list ul li i {
            color: var(--primary-color, #A3E635);
            margin-top: 4px;
        }

        .faqs-content-list ul li::before {
            display: none !important;
        }

        .faqs-content-list ul {
            list-style: none;
            padding-left: 0;
        }

        .faqs-content-list ul li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }

        @media (min-width: 992px) {
            .service-item {
                height: 100% !important;
                min-height: auto !important;
                padding: 30px !important;
                display: flex !important;
                flex-direction: column !important;
            }

            .service-item-body {
                padding-top: 0 !important;
                flex-grow: 1 !important;
                display: flex !important;
                flex-direction: column !important;
            }

            .service-item-content {
                flex-grow: 1 !important;
            }

            .service-item-content p {
                margin-bottom: 0 !important;
            }

            .service-item-btn {
                margin-top: auto !important;
                padding-top: 20px !important;
            }

            .service-item-header {
                margin-bottom: 20px !important;
            }
        }
    </style>

    @livewireStyles
</head>

<body>

    <!-- Preloader Start -->
    <div class="preloader">
        <div class="loading-container">
            <div class="loading"></div>
            <div id="loading-icon">
                <div class="logo-icon-box"
                    style="background: var(--primary-color, #A3E635); width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; transform: rotate(3deg); box-shadow: 0 4px 20px rgba(163, 230, 53, 0.4);">
                    <img src="{{ asset('favicon.ico') }}" alt="{{ config('app.name') }}"
                        style="width: 34px; height: 34px; transform: rotate(-3deg);">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader End -->

    <!-- Header Start -->
    <header class="main-header">
        <div class="header-sticky">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <!-- Logo Start -->
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('web.home') }}">
                        <div class="logo-icon-box"
                            style="background: var(--primary-color, #A3E635); width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; transform: rotate(3deg); margin-right: 14px; box-shadow: 0 4px 15px rgba(163, 230, 53, 0.3);">
                            <img src="{{ asset('favicon.ico') }}" alt="{{ config('app.name') }}"
                                style="width: 28px; height: 28px; transform: rotate(-3deg);">
                        </div>
                        <span
                            style="font-weight: 900; font-size: 28px; color: #fff; letter-spacing: -1.5px; text-transform: uppercase; font-style: italic; line-height: 1;">{{ config('app.name') }}</span>
                    </a>
                    <!-- Logo End -->

                    <!-- Main Menu Start -->
                    <div class="collapse navbar-collapse main-menu">
                        <div class="nav-menu-wrapper">
                            <ul class="navbar-nav mr-auto" id="menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('web.home') }}">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('web.about') }}">About Us</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('web.services') }}">Services</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('web.contact') }}">Contact
                                        Us</a></li>
                            </ul>
                        </div>

                        <!-- Header Btn Start -->
                        <div class="header-btn">
                            <a href="{{ route('app.onboarding') }}" class="btn-default">Get Started</a>
                        </div>
                        <!-- Header Btn End -->
                    </div>
                    <!-- Main Menu End -->
                    <div class="navbar-toggle"></div>
                </div>
            </nav>
            <div class="responsive-menu"></div>
        </div>
    </header>
    <!-- Header End -->

    {{ $slot }}

    <!-- Main Footer Start -->
    <footer class="main-footer bg-section dark-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Footer Header Start -->
                    <div class="footer-header">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Ready to launch your business?</h2>
                            <p class="wow fadeInUp">Join thousands of founders who started their journey with
                                {{ config('app.name') }}.
                            </p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Footer CTA Start -->
                        <div class="footer-cta-btn">
                            <a href="{{ route('app.onboarding') }}" class="btn-default">Get Started Now</a>
                        </div>
                        <!-- Footer CTA End -->
                    </div>
                    <!-- Footer Header End -->
                </div>

                <div class="col-xl-4 col-lg-5">
                    <!-- About Footer Start -->
                    <div class="about-footer">
                        <!-- Footer Logo Start -->
                        <div class="footer-logo">
                            <a class="navbar-brand d-flex align-items-center" href="{{ route('web.home') }}">
                                <div class="logo-icon-box"
                                    style="background: var(--primary-color, #A3E635); width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; transform: rotate(3deg); margin-right: 14px; box-shadow: 0 4px 15px rgba(163, 230, 53, 0.3);">
                                    <img src="{{ asset('favicon.ico') }}" alt="{{ config('app.name') }}"
                                        style="width: 28px; height: 28px; transform: rotate(-3deg);">
                                </div>
                                <span
                                    style="font-weight: 900; font-size: 28px; color: #fff; letter-spacing: -1.5px; text-transform: uppercase; font-style: italic; line-height: 1;">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <!-- Footer Logo End -->

                        <!-- About footer Content Start -->
                        <div class="about-footer-content">
                            <p>Simplifying US business formation for founders worldwide. Fast, compliant, and reliable
                                entity registration.</p>
                        </div>
                        <!-- About footer Content End -->

                        <!-- Footer Social Links Start  -->
                        <div class="footer-social-links">
                            <ul>
                                <li><a href="https://linkedin.com"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                <li><a href="https://twitter.com"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="https://facebook.com"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="https://instagram.com"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="https://tiktok.com"><i class="fa-brands fa-tiktok"></i></a></li>
                            </ul>
                        </div>
                        <!-- Footer Social Links End  -->
                    </div>
                    <!-- About Footer End -->
                </div>

                <div class="col-xl-8 col-lg-7">
                    <!-- Footer Links Box Start -->
                    <div class="footer-links-box">
                        <!-- Footer Links Start -->
                        <div class="footer-links">
                            <h2>Platform</h2>
                            <ul>
                                <li><a href="{{ route('web.home') }}">Home</a></li>
                                <li><a href="{{ route('web.services') }}">Services</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links End -->

                        <!-- Footer Links Start -->
                        <div class="footer-links">
                            <h2>Company</h2>
                            <ul>
                                <li><a href="{{ route('web.about') }}">About Us</a></li>
                                <li><a href="{{ route('web.contact') }}">Contact Us</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links End -->

                        <!-- Footer Links Start -->
                        <div class="footer-links">
                            <h2>Legal</h2>
                            <ul>
                                <li><a href="{{ route('web.privacy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('web.terms') }}">Terms of Service</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links End -->
                    </div>
                    <!-- Footer Links Box End -->
                </div>

                <div class="col-lg-12">
                    <!-- Footer Copyright Text Start -->
                    <div class="footer-copyright-text">
                        <p>Copyright © {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
                    </div>
                    <!-- Footer Copyright Text End -->
                </div>
            </div>
        </div>
    </footer>
    <!-- Main Footer End -->
    <!-- Main Footer End -->

    <!-- Jquery Library File -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js file -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Validator js file -->
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <!-- SlickNav js file -->
    <script src="{{ asset('js/jquery.slicknav.js') }}"></script>
    <!-- Swiper js file -->
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <!-- Counter js file -->
    <script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <!-- Magnific js file -->
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <!-- SmoothScroll -->
    <script src="{{ asset('js/SmoothScroll.js') }}"></script>
    <!-- Parallax js -->
    <script src="{{ asset('js/parallaxie.js') }}"></script>
    <!-- MagicCursor js file -->
    <script src="{{ asset('js/gsap.min.js') }}"></script>
    <script src="{{ asset('js/magiccursor.js') }}"></script>
    <!-- Text Effect js file -->
    <script src="{{ asset('js/SplitText.min.js') }}"></script>
    <script src="{{ asset('js/ScrollTrigger.min.js') }}"></script>
    <!-- YTPlayer js File -->
    <script src="{{ asset('js/jquery.mb.YTPlayer.min.js') }}"></script>
    <!-- Wow js file -->
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <!-- Main Custom js file -->
    <script src="{{ asset('js/function.js') }}"></script>

    @livewireScripts
</body>

</html>