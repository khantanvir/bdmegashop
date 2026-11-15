<?php 

namespace App\Traits\Course;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Blog\Blog;
use App\Models\Campus\Campus;
use App\Models\Course\Course;
use App\Models\Course\CourseCategory;
use App\Models\Event\Event;
use App\Models\Event\EventPeople;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use App\Traits\Utility;
use App\Traits\Service;
//use Intervention\Image\Facades\Image;

trait CourseData{
    use Utility, Service;
    public static function create_course(Request $request){
        $course_id = $request->course_id;
        if(!empty($course_id)){
            $course = Course::where('id',$course_id)->first();
        }else{
            $course = new Course();
        }
        $course->title = $request->title;
        $course->short_overview = $request->short_overview;
        $course->category = $request->category;
        $course->course_category_id = $request->course_category_id;
        $course->short_description = $request->short_description;
        $course->course_overview = $request->course_overview;
        $course->curriculum = $request->curriculum;
        $course->entry_requirements = $request->entry_requirements;
        $course->teaching = $request->teaching;
        $course->assessments = $request->assessments;
        $course->level = $request->level;
        $course->awarding_body = $request->awarding_body;
        $course->duration = $request->duration;
        $course->course_fees = $request->course_fees;
        $course->mode_of_study = $request->mode_of_study;
        $course->delivery = $request->delivery;
        $course->intakes = $request->intakes;
        $course->campus = $request->campus;
        $course->youtube_link = $request->youtube_link;
        $course->slug = Service::slug_create($request->title);
        
        if($request->hasFile('main_image')) {
            if (File::exists(public_path($course->main_image))) {
                File::delete(public_path($course->main_image));
            }
            $image_directory = 'web/images/course/main/';
            $image_path = Utility::upload_image_to_public($request->main_image,$image_directory,260,308);
            $course->main_image = $image_path;
        }
        if($request->hasFile('banner_image')) {
            if (File::exists(public_path($course->banner_image))) {
                File::delete(public_path($course->banner_image));
            }
            $image_directory = 'web/images/course/banner/';
            $image_path = Utility::upload_image_to_public($request->banner_image,$image_directory,320,1440);
            $course->banner_image = $image_path;
        }
        if($request->hasFile('extra1')) {
            if (File::exists(public_path($course->extra1))) {
                File::delete(public_path($course->extra1));
            }
            $image_directory = 'web/images/course/youtube/pic/';
            $image_path = Utility::upload_image_to_public($request->extra1,$image_directory,220,384);
            $course->extra1 = $image_path;
        }
        $course->save();
        return $course->id;
    }
    public static function create_category(Request $request){
        $category_id = $request->category_id;
        if(!empty($category_id)){
            $category = CourseCategory::where('id',$category_id)->first();
        }else{
            $category = new CourseCategory();
        }
        $category->title = $request->title;
        $category->save();
        return $category->id;
    }
    
    
}