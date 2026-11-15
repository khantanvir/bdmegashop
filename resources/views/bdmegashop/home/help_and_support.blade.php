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
                                Help & Support
                            </li>
                        </ol>
                    </nav>
                    <h5>Help & Support</h5>
                </div>
            </div>
        </div>
    </section>

    <section class="deliver-info py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-content p-4 bg-white shadow-sm rounded">

                        <h2 class="mb-4">Welcome to BD Mega Shop Help & Support</h2>

                        <p>
                            At <strong>BD Mega Shop</strong>, customer satisfaction is our top 
                            priority. We are committed to providing reliable support and ensuring 
                            a smooth shopping experience for every customer. If you have any 
                            questions, concerns, or need assistance regarding orders, products, 
                            delivery, or payments, our support team is always ready to help.
                        </p>

                        <p>
                            We understand that online shopping may sometimes require additional 
                            assistance, and that is why we strive to offer quick and effective 
                            customer support to resolve issues as soon as possible.
                        </p>

                        <h3 class="mt-4">How Can We Help You?</h3>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Order Support</h5>
                                    <p>
                                        Need help with your order? Contact us for order tracking, 
                                        confirmation, cancellations, or delivery updates.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Product Information</h5>
                                    <p>
                                        If you need more information about any product, our team 
                                        can assist you with specifications, features, and usage details.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Payment Assistance</h5>
                                    <p>
                                        Facing payment issues? We can help with payment confirmation, 
                                        transaction problems, and available payment methods.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Delivery Support</h5>
                                    <p>
                                        Need an update on delivery? We provide delivery tracking 
                                        and estimated arrival information for your orders.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Return & Replacement</h5>
                                    <p>
                                        If you received a damaged or incorrect product, contact 
                                        us for return, replacement, or support regarding product issues.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Customer Care</h5>
                                    <p>
                                        Our customer care team is always available to answer 
                                        your questions and ensure a better shopping experience.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <h3 class="mt-4">Frequently Asked Questions</h3>

                        <div class="accordion mt-3" id="faqAccordion">

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqOne">
                                    <button class="accordion-button" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapseOne">
                                        How can I place an order?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        You can easily place an order by selecting your desired 
                                        product, adding it to the cart, and completing checkout.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqTwo">
                                    <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapseTwo">
                                        How long does delivery take?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        Delivery time depends on your location. Normally, orders 
                                        are delivered within a few working days across Bangladesh.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqThree">
                                    <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapseThree">
                                        What should I do if I receive a damaged product?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        Please contact our support team immediately with product 
                                        images and order details for quick assistance.
                                    </div>
                                </div>
                            </div>

                        </div>

                        <h3 class="mt-4">Contact Our Support Team</h3>

                        <p>
                            If you need any assistance, please feel free to contact us. 
                            Our support team is always ready to help and provide solutions 
                            to your concerns.
                        </p>

                        <ul class="list-unstyled ps-3">
                            <li><strong>Email:</strong> info@bdmegashop.com</li>
                            <li><strong>Phone:</strong> +8801732930067</li>
                            <li><strong>Working Hours:</strong> Saturday – Thursday, 9:00 AM – 9:00 PM</li>
                        </ul>

                        <div class="alert alert-info mt-4">
                            <strong>Need Help?</strong> We are committed to providing the best 
                            support experience for our valued customers.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection