<?php 

namespace App\Traits\Event;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Blog\Blog;
use App\Models\Career\ApplyJob;
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

trait EventData{
    use Utility, Service;
    public static function create_event(Request $request){
        $event_id = $request->event_id;
        if(!empty($event_id)){
            $event = Event::where('id',$event_id)->first();
        }else{
            $event = new Event();
            $event->is_featured = 1;
        }
        $event->title = $request->title;
        $event->description = $request->description;
        $event->short_title = $request->short_title;
        $event->long_description = $request->long_description;
        $event->location = $request->location;
        $event->duration = $request->duration;
        $event->total_seat = $request->total_seat;
        $event->event_date = $request->event_date;
        $event->publish_time = $request->publish_time;
        $event->slug = Service::slug_create($request->title);
        $event->event_status = $request->event_status;
        if($request->hasFile('image')) {
            if (File::exists(public_path($event->image))) {
                File::delete(public_path($event->image));
            }
            $image_directory = 'web/images/event/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,306,308);
            $event->image = $image_path;
        }
        if($request->hasFile('recent_image')) {
            if (File::exists(public_path($event->recent_image))) {
                File::delete(public_path($event->recent_image));
            }
            $image_directory = 'web/images/event/recent/';
            $image_path = Utility::upload_image_to_public($request->recent_image,$image_directory,180,416);
            $event->recent_image = $image_path;
        }
        if($request->hasFile('detail_image')) {
            if (File::exists(public_path($event->detail_image))) {
                File::delete(public_path($event->detail_image));
            }
            $image_directory = 'web/images/event/details/';
            $image_path = Utility::upload_image_to_public($request->detail_image,$image_directory,493,632);
            $event->detail_image = $image_path;
        }
        if($request->hasFile('banner_image')) {
            if (File::exists(public_path($event->banner_image))) {
                File::delete(public_path($event->banner_image));
            }
            $image_directory = 'web/images/event/banner_image/';
            $image_path = Utility::upload_image_to_public($request->banner_image,$image_directory,440,1440);
            $event->banner_image = $image_path;
        }
        $event->save();
        return $event->id;
    }
    public static function join_event(Request $request){
        $people = new EventPeople();
        $people->event_id = $request->event_id;
        $people->name = $request->name;
        $people->email = $request->email;
        $people->phone = $request->phone;
        $people->save();
        return $people;
    }
    public static function save_data_to_session($id=NULL){
        if(empty($id)){
            return false;
        }
        $get_ids = Session::get('event_ids');
        if(!empty($get_ids)){
            $arr = explode(",",$get_ids);
            if(in_array($id,$arr)){
                Session::put('event_ids',$get_ids);
            }else{
                $update = $get_ids.','.$id;
                Session::put('event_ids',$update);
            }
        }else{
            Session::put('event_ids',$id);
        }
        return $id;
    }
    public static function apply_job(Request $request){
        $apply_job = new ApplyJob();
        $apply_job->job_id = $request->job_id;
        $apply_job->name = $request->name;
        $apply_job->email = $request->email;
        $apply_job->phone = $request->phone;
        $apply_job->gender = $request->gender;
        $apply_job->nationality = $request->nationality;
        $apply_job->date_of_birth = $request->date_of_birth;
        $apply_job->cover_letter = $request->cover_letter;
        if($request->hasFile('resume')) {
            
            $image_directory = 'web/job/apply/'.$request->job_id.'/';
            $image_path = Utility::upload_direct_file($request->resume,$image_directory);
            $apply_job->resume = $image_path;
        }
        $apply_job->save();
        return $apply_job;
    }
    public static function save_job_id_to_session($id=NULL){
        if(empty($id)){
            return false;
        }
        $get_ids = Session::get('job_ids');
        if(!empty($get_ids)){
            $arr = explode(",",$get_ids);
            if(in_array($id,$arr)){
                Session::put('job_ids',$get_ids);
            }else{
                $update = $get_ids.','.$id;
                Session::put('job_ids',$update);
            }
        }else{
            Session::put('job_ids',$id);
        }
        return $id;
    }
}