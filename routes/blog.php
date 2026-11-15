<?php

use App\Http\Controllers\admin\campus\CampusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\blog\BlogController;
use App\Http\Controllers\web\event\EventController;
use Illuminate\Support\Facades\Artisan;



Route::controller(BlogController::class)->group(function () {
    Route::get('news','blog');
    Route::get('news-details/{id?}','blog_detail');
    Route::post('contact-us-data-post','contact_us_data_post');
    Route::post('hear-about-us-post','hear_about_us_post');
});

Route::get('/run-migrations', function () {
    Artisan::call('migrate');
    return 'Migrations have been run!';
});
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'All Cached Cleared!';
});
Route::get('/composer-update', function () {
    Artisan::call('composer update');
    return 'Composer Updated Done!';
});

