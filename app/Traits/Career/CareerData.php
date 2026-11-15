<?php 

namespace App\Traits\Career;

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
use App\Models\Career\Career;
use App\Models\Event\Event;
use App\Models\Event\EventPeople;
use App\Models\Podcast\Podcast;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use App\Traits\Utility;
use App\Traits\Service;
//use Intervention\Image\Facades\Image;

trait CareerData{
    use Utility, Service;
    public static function create_career(Request $request){
        $career_id = $request->career_id;
        if(!empty($career_id)){
            $career = Career::where('id',$career_id)->first();
        }else{
            $career = new Career();
            $career->job_reference_number = self::generate_code();
        }
        $career->title = $request->title;
        $career->location = $request->location;
        $career->job_type = $request->job_type;
        $career->department = $request->department;
        $career->salary_range = $request->salary_range;
        $career->salary_status = $request->salary_status;
        $career->job_post_date = $request->job_post_date;
        $career->job_expire_date = $request->job_expire_date;
        $career->job_level = $request->job_level;
        $career->experience = $request->experience;
        $career->education = $request->education;
        $career->job_description = $request->job_description;
        $career->requirements = $request->requirements;
        $career->long_description = $request->long_description;
        $career->slug = Service::slug_create($request->title).'-'.Str::random(5);
        
        if($request->hasFile('banner_image')) {
            if (File::exists(public_path($career->banner_image))) {
                File::delete(public_path($career->banner_image));
            }
            $image_directory = 'web/images/career/banner/';
            $image_path = Utility::upload_image_to_public($request->banner_image,$image_directory,320,1440);
            $career->banner_image = $image_path;
        }
        $career->save();
        return $career->id;
    }
    public static function generate_code(){
        $numbers1 = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $letters1 = 'UK';
        $numbers2 = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        $letters2 = 'MC';
        return "#$numbers1$letters1$numbers2$letters2";
    }
    public static function create_podcast(Request $request){
        $podcast_id = $request->podcast_id;
        if(!empty($podcast_id)){
            $podcast = Podcast::where('id',$podcast_id)->first();
        }else{
            $podcast = new Podcast();
        }
        $podcast->short_title = $request->short_title;
        $podcast->episode = $request->episode;
        $podcast->title = $request->title;
        $podcast->description = $request->description;
        $podcast->youtube_link = $request->youtube_link;

        if($request->hasFile('image')) {
            if (File::exists(public_path($podcast->image))) {
                File::delete(public_path($podcast->image));
            }
            $image_directory = 'web/images/podcast/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,549,901);
            $podcast->image = $image_path;
        }
        $podcast->save();
        return $podcast->id;
    }
    
    
}