<div>
    <!-- Page Header Section Start -->
    <div class="page-header parallaxie">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Terms of Service</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('web.home') }}">home |</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Terms of Service</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header Section End -->

    <!-- Terms Section Start -->
    <div class="page-terms page-policy py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="policy-content wow fadeInUp" data-wow-delay="0.2s">
                        <div class="last-updated mb-4">
                            <strong>Last Updated:</strong> {{ date('F j, Y') }}
                        </div>

                        <section class="mb-5">
                            <h2>1. Introduction</h2>
                            <p>Welcome to {{ config('app.name') }}. By accessing our website and using our services, you agree to be bound by the following terms and conditions. Please read them carefully.</p>
                            <p>Our services include business formation, compliance management, and related business advisory services. These terms govern your use of our platform and any services provided through it.</p>
                        </section>

                        <section class="mb-5">
                            <h2>2. Services Provided</h2>
                            <p>{{ config('app.name') }} provides a platform to assist users in forming business entities in the United States. We are not a law firm and do not provide legal or tax advice. Our services are designed to simplify the administrative process of business registration.</p>
                            <ul>
                                <li>LLC and Corporation Formation</li>
                                <li>Registered Agent Services</li>
                                <li>Compliance and Annual Report Filing</li>
                                <li>Tax ID (EIN) Application Assistance</li>
                            </ul>
                        </section>

                        <section class="mb-5">
                            <h2>3. User Responsibilities</h2>
                            <p>You are responsible for providing accurate, complete, and up-to-date information for any filing or service requested. You must ensure that you have the legal right to form the business entity and use the proposed business name.</p>
                            <p>You agree to maintain the confidentiality of your account credentials and are responsible for all activities that occur under your account.</p>
                        </section>

                        <section class="mb-5">
                            <h2>4. Payments and Fees</h2>
                            <p>Fees for our services are clearly stated on our pricing page. All fees are non-refundable once the filing process has been initiated with the relevant government authorities. We are not responsible for delays caused by government agencies.</p>
                            <p>Recurring services, such as Registered Agent or Compliance monitoring, will be billed on an annual basis unless canceled in accordance with our cancellation policy.</p>
                        </section>

                        <section class="mb-5">
                            <h2>5. Limitation of Liability</h2>
                            <p>To the maximum extent permitted by law, {{ config('app.name') }} shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of our services.</p>
                            <p>Our total liability for any claim arising out of our services shall not exceed the amount paid by you for the specific service in question.</p>
                        </section>

                        <section class="mb-5">
                            <h2>6. Governing Law</h2>
                            <p>These terms shall be governed by and construed in accordance with the laws of the State of New York, without regard to its conflict of law principles.</p>
                        </section>

                        <section class="mb-5">
                            <h2>7. Changes to Terms</h2>
                            <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on our website. Your continued use of our services after such changes constitutes your acceptance of the new terms.</p>
                        </section>

                        <div class="contact-policy mt-5">
                            <p>If you have any questions regarding these Terms of Service, please contact us at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Terms Section End -->
</div>