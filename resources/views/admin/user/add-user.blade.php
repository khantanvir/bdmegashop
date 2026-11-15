@extends('adminpanel')
@section('admincontent')
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<div class="container-fluid">
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            {{-- <h1 class="page-title fw-medium fs-18 mb-2"></h1> --}}
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Create User
            </div>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <form method="post" action="{{ URL::to('add-user-data-post') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Home page -->
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" id="first_name" aria-describedby="first name" placeholder="Enter first name">
                                @if($errors->has('first_name'))
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" aria-describedby="Last name" placeholder="Enter last name">
                                @if($errors->has('last_name'))
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                           </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Select Role</label>
                                <select name="role" class="form-select">
                                    <option value="">--Select--</option>
                                    @foreach ($role_list as $row)
                                        <option value="{{ $row }}">{{ $row }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('role'))
                                    <span class="text-danger">{{ $errors->first('role') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" id="email" aria-describedby="Email" placeholder="Enter Email">
                                @if($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" id="phone" aria-describedby="phone" placeholder="Enter phone number">
                                @if($errors->has('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" name="password" class="form-control" id="phone" aria-describedby="password" placeholder="Enter password">
                                @if($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="text" name="password_confirmation" class="form-control" id="confirm_password" aria-describedby="confirm password" placeholder="Enter confirm password">
                                @if($errors->has('password_confirmation'))
                                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection