<?php 

namespace App\Traits\Campus;

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

trait CampusData{
    use Utility, Service;
    public static function create_campus(Request $request){
        $campus_id = $request->campus_id;
        if(!empty($campus_id)){
            $campus = Campus::where('id',$campus_id)->first();
        }else{
            $campus = new Campus();
        }
        $campus->campus_name = $request->campus_name;
        $campus->overview = $request->overview;
        $campus->address = $request->address;
        $campus->longitude = $request->longitude;
        $campus->latitude = $request->latitude;
        $campus->google_map_link = $request->google_map_link;
        $campus->description = $request->description;
        $campus->main_description = $request->main_description;
        $campus->facility_description = $request->facility_description;
        $campus->long_description = $request->long_description;  
        
        $campus->publish_time = $request->publish_time;
        $campus->slug = Service::slug_create($request->campus_name);
        $campus->campus_status = $request->campus_status;
        if($request->hasFile('homepage_image')) {
            if (File::exists(public_path($campus->homepage_image))) {
                File::delete(public_path($campus->homepage_image));
            }
            $image_directory = 'web/images/campus/';
            $image_path = Utility::upload_image_to_public($request->homepage_image,$image_directory,1440,2560);
            $campus->homepage_image = $image_path;
        }
        if($request->hasFile('banner_image')) {
            if (File::exists(public_path($campus->banner_image))) {
                File::delete(public_path($campus->banner_image));
            }
            $image_directory = 'web/images/campus/banner/';
            $image_path = Utility::upload_image_to_public($request->banner_image,$image_directory,320,1440);
            $campus->banner_image = $image_path;
        }
        if($request->hasFile('body_image1')) {
            if (File::exists(public_path($campus->body_image1))) {
                File::delete(public_path($campus->body_image1));
            }
            $image_directory = 'web/images/campus/body/';
            $image_path = Utility::upload_image_to_public($request->body_image1,$image_directory,325,346);
            $campus->body_image1 = $image_path;
        }
        if($request->hasFile('body_image2')) {
            if (File::exists(public_path($campus->body_image2))) {
                File::delete(public_path($campus->body_image2));
            }
            $image_directory = 'web/images/campus/body/';
            $image_path = Utility::upload_image_to_public($request->body_image2,$image_directory,423,344);
            $campus->body_image2 = $image_path;
        }
        if($request->hasFile('body_image3')) {
            if (File::exists(public_path($campus->body_image3))) {
                File::delete(public_path($campus->body_image3));
            }
            $image_directory = 'web/images/campus/body/';
            $image_path = Utility::upload_image_to_public($request->body_image3,$image_directory,245,261);
            $campus->body_image3 = $image_path;
        }
        if($request->hasFile('main_image')) {
            if (File::exists(public_path($campus->main_image))) {
                File::delete(public_path($campus->main_image));
            }
            $image_directory = 'web/images/campus/main/';
            $image_path = Utility::upload_image_to_public($request->main_image,$image_directory,620,632);
            $campus->main_image = $image_path;
        }
        if($request->hasFile('facility_image')) {
            if (File::exists(public_path($campus->facility_image))) {
                File::delete(public_path($campus->facility_image));
            }
            $image_directory = 'web/images/campus/facility/';
            $image_path = Utility::upload_image_to_public($request->facility_image,$image_directory,339,631);
            $campus->facility_image = $image_path;
        }
        $campus->save();
        return $campus->id;
    }
    
    
}