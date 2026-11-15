<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-width="fullwidth" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> {{ (!empty($page_title))?$page_title:'UKMC | Dashboard' }} </title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Wcsrm Software Private Limited">
	<meta name="keywords" content="bootstrap template, admin panel bootstrap, bootstrap dashboard, admin, admin dashboard template, dashboard template, html css templates, dashboard, template dashboard,  bootstrap dashboard template, dashboard html css, bootstrap admin dashboard,  bootstrap admin, dashboard template, bootstrap5 admin template">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admin/assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    
    <!-- Choices JS -->
    <script src="{{ asset('admin/assets/libs/choices.js') }}/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('admin/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('admin/assets/css/styles.css') }}" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('admin/assets/css/icons.css') }}" rel="stylesheet" >

    <!-- Node Waves Css -->
    <link href="{{ asset('admin/assets/libs/node-waves/waves.min.css') }}" rel="stylesheet" > 
    <link href="{{ asset('admin/assets/libs/@yaireo/tagify/tagify.css') }}" rel="stylesheet" >

    <!-- Simplebar Css -->
    <link href="{{ asset('admin/assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet" >
    
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/choices.js') }}/public/assets/styles/choices.min.css') }}">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/flatpickr/flatpickr.min.css') }}">

    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/@tarekraafat/autocomplete.js') }}/css/autoComplete.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.css" integrity="sha512-DIW4FkYTOxjCqRt7oS9BFO+nVOwDL4bzukDyDtMO7crjUZhwpyrWBFroq+IqRe6VnJkTpRAS6nhDvf0w+wHmxg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>

@include('admin.layout.switcher')
<!-- End Switcher -->

     <!-- Loader -->
<div id="loader" >
    <img src="{{ asset('admin/assets/images/media/loader.svg') }}" alt="">
</div>
<!-- Loader -->

    <div class="page">
         <!-- app-header -->
@include('admin.layout.header')
<!-- /app-header -->
         <!-- Start::app-sidebar -->
@include('admin.layout.menus.sidebar')
<!-- End::app-sidebar -->

        <!-- Start::app-content -->
        <div class="main-content app-content">
            @yield('admincontent')
        </div> 
        <!-- End::app-content -->


        <!-- Footer Start -->
<footer class="footer mt-auto py-3 bg-white text-center">
    <div class="container">
        <span class="text-muted"> Copyright © <span id="year"></span> <a
                href="javascript:void(0);" class="text-dark fw-medium">Zynix</a>.
            Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="https://wcsrm.com/" target="_blank">
                <span class="fw-medium text-primary">Spruko</span>
            </a> All
            rights
            reserved
        </span>
    </div>
</footer>
<!-- Footer End -->
         <div class="modal fade" id="header-responsive-search" tabindex="-1" aria-labelledby="header-responsive-search" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="input-group">
                    <input type="text" class="form-control border-end-0" placeholder="Search Anything ..."
                        aria-label="Search Anything ..." aria-describedby="button-addon2">
                    <button class="btn btn-primary" type="button"
                        id="button-addon2"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>

   
<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow lh-1"><i class="ti ti-arrow-big-up fs-16"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="{{ asset('admin/assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Defaultmenu JS -->
<script src="{{ asset('admin/assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
<script src="{{ asset('admin/assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('admin/assets/js/sticky.js') }}"></script>
<script src="{{ asset('admin/assets/libs/@yaireo/tagify/tagify.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('admin/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/simplebar.js') }}"></script>

<!-- Auto Complete JS -->
<script src="{{ asset('admin/assets/libs/@tarekraafat/autocomplete.js') }}/autoComplete.min.js') }}"></script>

<!-- Color Picker JS -->
<script src="{{ asset('admin/assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

<!-- Date & Time Picker JS -->
<script src="{{ asset('admin/assets/libs/flatpickr/flatpickr.min.js') }}"></script>


    <!-- Apex Charts JS -->
    <script src="{{ asset('admin/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Podcast dashboard -->
    <script src="{{ asset('admin/assets/js/podcast-dashboard.js') }}"></script>
    <script src="{{ asset('admin/assets/js/tagify.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
 
   
<!-- Custom-Switcher JS -->
<script src="{{ asset('admin/assets/js/custom-switcher.min.js') }}"></script>
<script src="{{ asset('web/js/iziToast.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('web/css/toastr.css') }}">
<script src="{{ asset('web/js/toastr.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script>
    @if(Session::has('success'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
    }
            toastr.success("{{ session('success') }}");
    @endif

    @if(Session::has('error'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "timeOut": "10000",
        "positionClass": "toast-top-right",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
    }
            toastr.error("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
    }
            toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "timeOut": "10000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
    }
            toastr.warning("{{ session('warning') }}");
    @endif
</script>



    
</body>

</html> 