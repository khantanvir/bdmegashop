<?php

use App\Http\Controllers\admin\bdmegashop\product\ProductController;
use App\Http\Controllers\admin\dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\blog\BlogController;
use App\Http\Controllers\admin\home\HomeController;
use App\Http\Controllers\admin\setting\SettingController;
use App\Http\Controllers\admin\user\UserController;
use App\Http\Controllers\admin\bdmegashop\order\OrderController;
use App\Http\Controllers\admin\bdmegashop\order\AdminOrderController;


Route::middleware(['menu.access'])->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
        });
        Route::controller(AdminOrderController::class)->group(function () {
            Route::get('/admin-sale', 'admin_sale')->name('admin_sale');
            Route::get('/admin-sale/search-products', 'search_products')->name('admin_sale.search_products');
            Route::get('/admin-sale/product-quick-view/{id}', 'product_quick_view')->name('admin_sale.product_quick_view');
            Route::post('/admin-sale-complete', 'admin_sale_complete')->name('admin_sale_complete');
        });
        Route::controller(OrderController::class)->group(function () {

            Route::get('/order-list', 'order_list')->name('order_list');
            Route::get('/order-details/{id?}', 'order_details')->name('order_details');
            Route::post('/order-status-update', 'order_status_update')->name('order_status_update');
            Route::post('/order-delivery-charge-update', 'order_delivery_charge_update')->name('order_delivery_charge_update');
            Route::get('/order-processing-info/{id}', 'order_processing_info')->name('order_processing_info');
            Route::post('/order-processing-update', 'order_processing_update')->name('order_processing_update');
        });
        Route::controller(ProductController::class)->group(function () {
            Route::get('/create-product/{id?}', 'create_product')->name('create_product');
            Route::post('create-product-data-post', 'create_product_data_post');
            Route::get('get_sub_categories_by_category/{id?}','get_sub_categories_by_category');
            Route::get('all-products','all_products');
            Route::post('product-status-change','product_status_change');
            Route::get('create-product-details/{id?}','product_details');
            Route::post('upload-details-page-image','upload_details_page_image');
            Route::post('update-product-stock','update_product_stock');
        });
        Route::controller(BlogController::class)->group(function () {
            Route::post('blog-upload-image', 'upload_image');
            Route::post('blog-status-change', 'blog_status_change');
            Route::get('image/upload','upload_image_page');
            Route::get('create-blog/image/upload','upload_image_page');
            Route::get('list-blog', 'list_blog');
            Route::get('/create-blog/new','create_blog');
            Route::get('create-blog/{id?}', 'create_blog');
            Route::post('create-blog-data-post', 'create_blog_data_post');
            Route::get('blog-categories/{id?}', 'all_blog_categories');
            Route::post('store-blog-category-data','create_blog_category');
            Route::post('blog-category_status_change','category_status_change');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('create-main-menu/{id?}','create_main_menu');
            Route::post('store-main-menu-data','store_main_menu_data');
            Route::post('main-menu-status-change','main_menu_status_change');
            Route::get('permission-settings','permission_settings');
            Route::post('sub-menu-data-post','sub_menu_data_post');
            Route::post('sub-menu-status-change','sub_menu_status_change');
            Route::get('menu-permission/{id?}','menu_permission');
        });

        Route::controller(UserController::class)->group(function () {
            // Route::get('add-user/{id?}','add_user');
            Route::get('add-user','add_user');
            Route::get('edit-user/{id?}','edit_user');
            Route::post('add-user-data-post','add_user_data_post');
            Route::post('edit-user-data-post','edit_user_data_post');
            Route::post('user-status-change','user_status_change');
            Route::get('all-user','all_user')->name('all-user');
            Route::get('user-permission/{id?}','user_permission');
            Route::post('user-permission-assign','user_permission_assign');
        });
    });
});
