<div>
    <!-- Page Header Section Start -->
    <div class="page-header parallaxie">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Privacy Policy</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('web.home') }}">home |</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header Section End -->

    <!-- Privacy Section Start -->
    <div class="page-privacy page-policy py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="policy-content wow fadeInUp" data-wow-delay="0.2s">
                        <div class="last-updated mb-4">
                            <strong>Last Updated:</strong> {{ date('F j, Y') }}
                        </div>

                        <section class="mb-5">
                            <h2>1. Information We Collect</h2>
                            <p>We collect information that you provide directly to us when you create an account, use our services, or communicate with us. This may include:</p>
                            <ul>
                                <li>Contact information (name, email address, phone number, physical address)</li>
                                <li>Business information (proposed company names, ownership details, business activity)</li>
                                <li>Payment information (credit card details processed through secure third-party providers)</li>
                                <li>Identification documents required for legal compliance and entity formation</li>
                            </ul>
                        </section>

                        <section class="mb-5">
                            <h2>2. How We Use Information</h2>
                            <p>We use the information we collect to provide, maintain, and improve our services, including:</p>
                            <ul>
                                <li>Facilitating business formation and registration with government agencies</li>
                                <li>Providing registered agent services and compliance notifications</li>
                                <li>Processing payments and sending related communications</li>
                                <li>Responding to your inquiries and providing customer support</li>
                                <li>Complying with legal and regulatory obligations</li>
                            </ul>
                        </section>

                        <section class="mb-5">
                            <h2>3. Data Sharing and Disclosure</h2>
                            <p>We share your information with government agencies as required for business formation. We also use third-party service providers (such as payment processors and cloud hosting services) to help us operate our business. We do not sell your personal information to third parties.</p>
                            <p>We may disclose your information if required to do so by law or in response to valid requests by public authorities.</p>
                        </section>

                        <section class="mb-5">
                            <h2>4. Data Security</h2>
                            <p>We implement appropriate technical and organizational measures to protect the security of your personal information. However, please be aware that no method of transmission over the internet or electronic storage is 100% secure.</p>
                        </section>

                        <section class="mb-5">
                            <h2>5. Cookies and Tracking</h2>
                            <p>We use cookies and similar tracking technologies to analyze trends, administer the website, and track users' movements around the website. You can control the use of cookies at the individual browser level.</p>
                        </section>

                        <section class="mb-5">
                            <h2>6. Your Rights</h2>
                            <p>Depending on your location, you may have certain rights regarding your personal information, including the right to access, correct, or delete your data. To exercise these rights, please contact us using the information provided below.</p>
                        </section>

                        <section class="mb-5">
                            <h2>7. Changes to This Policy</h2>
                            <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last Updated" date.</p>
                        </section>

                        <div class="contact-policy mt-5">
                            <p>If you have any questions regarding this Privacy Policy, please contact us at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Privacy Section End -->
</div>