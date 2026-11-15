<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\login\LoginController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\bdmegashop\home\HomeController;
use App\Http\Controllers\bdmegashop\product\ProductController;
use App\Http\Controllers\bdmegashop\customer\CustomerAuthController;
use App\Http\Controllers\bdmegashop\order\OrderController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('about', 'about')->name('about');
    Route::get('help-and-support', 'help_and_support')->name('help-and-support');
    Route::get('privacy-policy', 'privacy_policy')->name('privacy-policy');
    Route::get('terms-of-service', 'terms_of_service')->name('terms-of-service');
    Route::get('sale-and-discount', 'sales_and_discount')->name('sale-and-discount');
});

Route::controller(OrderController::class)->group(function () {
    Route::get('checkout','checkout')->name('checkout');
    Route::post('place-order','place_order')->name('order.place');
});
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'all')->name('products.all');
    Route::get('/products/live-search', 'live_search')->name('products.live_search');
    Route::get('/details/{id?}', 'details');
    Route::post('/cart/add', 'add_to_cart')->name('cart.add');
    Route::post('/cart/update', 'update_cart')->name('cart.update');
    Route::post('/cart/remove', 'remove_from_cart')->name('cart.remove');
    Route::post('/cart/clear', 'clear_cart')->name('cart.clear');
    Route::get('/cart/data', 'get_cart_data')->name('cart.data');
    Route::post('/wishlist/toggle', 'toggle_wishlist')->name('wishlist.toggle');
});
Route::controller(CustomerAuthController::class)->group(function () {
    Route::get('customer-login', 'login')->name('customer.login');
    Route::post('customer-login-post', 'login_post')->name('customer.login.post');
    Route::post('customer-register-post', 'register_post')->name('customer.register.post');
    Route::get('customer-logout', 'logout')->name('customer.logout');
    Route::get('log-out', 'logout');

    Route::middleware('customer.auth')->group(function () {
        Route::get('customer-dashboard', 'dashboard')->name('customer.dashboard');
        Route::get('profile', 'dashboard')->name('customer.profile');
        Route::post('customer-profile-update', 'update_profile')->name('customer.profile.update');
        Route::post('customer-change-password', 'change_password')->name('customer.password.change');
    });
});

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('user-login-post', 'user_login');
    Route::get('sign-out', 'sign_out');
    Route::get('reset-password', 'reset_password');
    Route::post('reset-password-post', 'reset_password_post');
    Route::get('reset-password-form/{token?}', 'reset_password_form');
    Route::post('reset-password-form-post', 'reset_password_form_post');
});

Route::group([], base_path('routes/admin/dashboard.php'));
Route::group([], base_path('routes/blog.php'));

Route::get('admin-proxy-opac', function () {
    $url = 'https://mc.247lib.com/OPAC/';

    try {
        // Fetch remote page and follow redirects
        $response = Http::withoutVerifying()
            ->withOptions(['allow_redirects' => true])
            ->get($url);

        if ($response->failed()) {
            return response('Remote site returned error: ' . $response->status(), $response->status());
        }

        $html = $response->body();

        // Fix relative URLs for assets (CSS, JS, images, links)
        $base = 'https://mc.247lib.com/OPAC/';
        $html = preg_replace(
            [
                '/(<[^>]+?)(href|src)=(["\'])(?!https?:|\/)([^"\']+)\3/i',
            ],
            '$1$2=$3' . $base . '$4$3',
            $html
        );

        // Return modified HTML
        return response($html, 200)
            ->header('Content-Type', $response->header('Content-Type', 'text/html'))
            ->header('X-Frame-Options', 'ALLOWALL')
            ->header('Content-Security-Policy', "frame-ancestors *");
    } catch (\Exception $e) {
        return response('Error loading remote page: ' . $e->getMessage(), 500);
    }
});

