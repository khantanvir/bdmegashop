@extends('bdpanel')

@section('content')
    <section class="breadcrumb-area mt-15">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Terms of Service
                            </li>
                        </ol>
                    </nav>
                    <h5>Terms of Service</h5>
                </div>
            </div>
        </div>
    </section>

    <section class="deliver-info py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-content p-4 bg-white shadow-sm rounded">

                        <h2 class="mb-4">Terms of Service – BD Mega Shop</h2>

                        <p>
                            Welcome to <strong>BD Mega Shop</strong>. By accessing and using our 
                            website, products, and services, you agree to comply with the following 
                            Terms of Service. Please read these terms carefully before using our 
                            website or making any purchases.
                        </p>

                        <p>
                            These terms are designed to ensure a safe, transparent, and reliable 
                            shopping experience for all customers. If you do not agree with any 
                            part of these terms, please discontinue the use of our services.
                        </p>

                        <h3 class="mt-4">1. Acceptance of Terms</h3>
                        <p>
                            By using BD Mega Shop, you confirm that you are at least legally 
                            eligible to enter into agreements and comply with these Terms of 
                            Service. Your continued use of our website means you accept all 
                            policies, guidelines, and conditions.
                        </p>

                        <h3 class="mt-4">2. Products & Services</h3>
                        <p>
                            We strive to ensure that all product information, descriptions, 
                            pricing, and images are accurate. However, minor variations may 
                            occur due to product updates, photography, or manufacturer changes.
                        </p>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Product Availability</h5>
                                    <p>
                                        Product availability may change without prior notice. 
                                        Some products may go out of stock at any time.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Pricing Policy</h5>
                                    <p>
                                        Prices may be updated or changed without prior notice 
                                        based on market conditions or supplier changes.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <h3 class="mt-4">3. Order & Payment</h3>

                        <p>
                            When placing an order through BD Mega Shop, customers agree to 
                            provide accurate information including name, phone number, and 
                            delivery address.
                        </p>

                        <ul class="list-unstyled ps-3">
                            <li>✔ Orders are confirmed after verification.</li>
                            <li>✔ Payment methods may vary depending on availability.</li>
                            <li>✔ BD Mega Shop reserves the right to cancel suspicious orders.</li>
                            <li>✔ Incorrect information may result in delivery delays.</li>
                        </ul>

                        <h3 class="mt-4">4. Shipping & Delivery</h3>

                        <p>
                            We work to ensure timely delivery across Bangladesh. However, 
                            delivery timelines may vary due to location, weather conditions, 
                            courier delays, or unforeseen circumstances.
                        </p>

                        <div class="alert alert-warning mt-3">
                            Delivery times are estimated and may occasionally be delayed 
                            due to external factors beyond our control.
                        </div>

                        <h3 class="mt-4">5. Return & Refund Policy</h3>

                        <p>
                            Customers may request returns or replacements for damaged or 
                            incorrect products, subject to our return policy guidelines.
                        </p>

                        <ul class="list-unstyled ps-3">
                            <li>✔ Products must be unused and in original condition.</li>
                            <li>✔ Claims should be made within the allowed return period.</li>
                            <li>✔ Refund approval depends on product inspection.</li>
                            <li>✔ Some products may not qualify for return due to hygiene or usage reasons.</li>
                        </ul>

                        <h3 class="mt-4">6. User Responsibilities</h3>

                        <p>
                            Customers are responsible for maintaining accurate account 
                            information and using our website lawfully.
                        </p>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Proper Website Usage</h5>
                                    <p>
                                        Users must not misuse, hack, damage, or disrupt 
                                        the functionality of our website.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Accurate Information</h5>
                                    <p>
                                        Customers must provide valid details to avoid 
                                        order cancellation or delivery problems.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <h3 class="mt-4">7. Intellectual Property</h3>

                        <p>
                            All website content including logos, text, graphics, images, 
                            and branding belongs to BD Mega Shop and may not be copied, 
                            reproduced, or reused without permission.
                        </p>

                        <h3 class="mt-4">8. Limitation of Liability</h3>

                        <p>
                            BD Mega Shop shall not be responsible for indirect damages, 
                            delays, or losses caused by third-party services, delivery 
                            disruptions, or technical issues.
                        </p>

                        <h3 class="mt-4">9. Changes to Terms</h3>

                        <p>
                            We reserve the right to update or modify these Terms of Service 
                            at any time without prior notice. Updated terms will be posted 
                            on this page.
                        </p>

                        <h3 class="mt-4">10. Contact Us</h3>

                        <p>
                            If you have any questions regarding our Terms of Service, 
                            please contact our support team.
                        </p>

                        <ul class="list-unstyled ps-3">
                            <li><strong>Email:</strong> info@bdmegashop.com</li>
                            <li><strong>Phone:</strong> +8801732930067</li>
                            <li><strong>Working Hours:</strong> Saturday – Thursday, 9:00 AM – 9:00 PM</li>
                        </ul>

                        <div class="alert alert-info mt-4">
                            <strong>Agreement Notice:</strong> By continuing to use BD Mega Shop, 
                            you agree to follow our Terms of Service and shopping policies.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection