@extends('bdpanel')
@section('content')
    <!-- BreadCrumb Start-->
    <section class="breadcrumb-area mt-15">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Account</li>
                        </ol>
                    </nav>
                    <h5>Customer Account</h5>
                </div>
            </div>
        </div>
    </section>
    <!-- BreadCrumb End-->

    <!-- Account-Login -->
    <section class="account-sign">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <!-- Sign In -->
                <div class="col-lg-6 col-md-12">
                    <div class="account-sign-in">
                        <h5 class="text-center">Sign in</h5>
                        <form method="POST" action="{{ url('customer-login-post') }}">
                            @csrf
                            <div class="form__div">
                                <input type="email" name="email" class="form__input" placeholder=" "
                                    value="{{ session('customer_email', old('email')) }}" required>
                                <label for="email" class="form__label">Email Address</label>
                            </div>
                            @error('email')
                                <small class="text-danger d-block mb-3" style="margin-top: -16px;">{{ $message }}</small>
                            @enderror

                            <div class="form__div mb-0">
                                <input type="password" name="password" id="loginPassword" class="form__input" placeholder=" "
                                    value="{{ session('customer_password', '') }}" required>
                                <label for="loginPassword" class="form__label">Password</label>
                            </div>
                            @error('password')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror

                            <div class="password-info d-flex align-items-center justify-content-between flex-wrap">
                                <div class="password-info-left">
                                    <input type="checkbox" id="rememberMe" name="remember_me" value="1"
                                        {{ session('customer_remember') ? 'checked' : '' }}>
                                    <label for="rememberMe" class="mb-0">Remember me</label>
                                </div>
                                <div class="password-info-left">
                                    <input type="checkbox" id="showLoginPass" onchange="document.getElementById('loginPassword').type = this.checked ? 'text' : 'password'">
                                    <label for="showLoginPass" class="mb-0">Show Password</label>
                                </div>
                            </div>
                            <button type="submit" class="btn bg-primary" style="color: #fff;">Sign in</button>
                        </form>
                    </div>
                </div>

                <!-- Sign Up -->
                <div class="col-lg-6 col-md-12">
                    <div class="account-sign-up">
                        <h5 class="text-center">Sign up</h5>
                        <form method="POST" action="{{ url('customer-register-post') }}">
                            @csrf
                            <div class="form__div">
                                <input type="text" name="name" class="form__input" placeholder=" "
                                    value="{{ old('name') }}" required>
                                <label class="form__label">Full Name</label>
                            </div>
                            @error('name')
                                <small class="text-danger d-block mb-3" style="margin-top: -16px;">{{ $message }}</small>
                            @enderror

                            <div class="form__div">
                                <input type="email" name="email" class="form__input" placeholder=" "
                                    value="{{ old('email') }}" required>
                                <label class="form__label">Email Address</label>
                            </div>

                            <div class="form__div">
                                <input type="text" name="phone" class="form__input" placeholder=" "
                                    value="{{ old('phone') }}">
                                <label class="form__label">Phone Number</label>
                            </div>
                            @error('phone')
                                <small class="text-danger d-block mb-3" style="margin-top: -16px;">{{ $message }}</small>
                            @enderror

                            <div class="form__div">
                                <input type="password" name="password" id="regPassword" class="form__input" placeholder=" " required>
                                <label class="form__label">Password</label>
                            </div>

                            <div class="form__div mb-0">
                                <input type="password" name="password_confirmation" id="regPasswordConfirm" class="form__input" placeholder=" " required>
                                <label class="form__label">Repeat Password</label>
                            </div>

                            <div class="password-info-show">
                                <input type="checkbox" id="showRegPass" onchange="var t = this.checked ? 'text' : 'password'; document.getElementById('regPassword').type = t; document.getElementById('regPasswordConfirm').type = t;">
                                <label for="showRegPass" class="mb-0">Show Password</label>
                            </div>
                            <button type="submit" class="btn bg-primary" style="color: #fff;">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

