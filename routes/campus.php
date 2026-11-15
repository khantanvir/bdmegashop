<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\blog\BlogController;
use App\Http\Controllers\web\event\EventController;
use App\Http\Controllers\web\campus\CampusController;
use App\Http\Controllers\web\course\CourseController;


Route::controller(CampusController::class)->group(function () {
    Route::get('all-campus','all_campus');
    Route::get('campus/{id?}','campus');
});
Route::controller(CourseController::class)->group(function () {
    Route::get('all-courses','all_courses');
    Route::get('course-details/{id?}','course_details');
});