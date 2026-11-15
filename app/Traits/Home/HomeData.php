<?php 

namespace App\Traits\Home;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Blog\Blog;
use App\Models\Event\Event;
use App\Models\Event\EventPeople;
use App\Models\GoverningBody\GoverningBody;
use App\Models\GoverningBody\GoverningBodyCategory;
use App\Models\Home\AwardingBody;
use App\Models\Home\HomeBanner;
use App\Models\Home\WhyUkmc;
use App\Models\Student\HandbookModule;
use App\Models\Student\Policies;
use App\Models\Student\Prospectus;
use App\Models\Student\StudentHandBook;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use App\Traits\Utility;
use App\Traits\Service;
//use Intervention\Image\Facades\Image;

trait HomeData{
    use Utility, Service;
    public static function change_banner(Request $request){
        $banner_id = $request->banner_id;
        if(!empty($banner_id)){
            $banner = HomeBanner::where('id',$banner_id)->first();
        }else{
            $banner = new HomeBanner();
        }
        $banner->title = $request->title;
        $banner->description = $request->description;
        
        if($request->hasFile('image')) {
            if (File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }
            $image_directory = 'web/images/main/banner/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,1280,1920);
            $banner->image = $image_path;
        }
        //left button\
        $banner->left_btn_text = $request->left_btn_text;
        $banner->left_btn_link = $request->left_btn_link;
        if(!empty($request->is_view_left_btn) && $request->is_view_left_btn=='yes') {
            $banner->is_view_left_btn = 'yes';
        }else{
            $banner->is_view_left_btn = 'no';
        }
        //right button
        $banner->right_btn_text = $request->right_btn_text;
        $banner->right_btn_link = $request->right_btn_link;
        if(!empty($request->is_view_right_btn) && $request->is_view_right_btn=='yes') {
            $banner->is_view_right_btn = 'yes';
        }else{
            $banner->is_view_right_btn = 'no';
        }
        $banner->save();
        return $banner->id;
    }
    public static function add_awarding_body(Request $request){
        $awarding_id = $request->awarding_body_id;
        if(!empty($awarding_id)){
            $awarding = AwardingBody::where('id',$awarding_id)->first();
        }else{
            $awarding = new AwardingBody();
        }
        $awarding->title = $request->title;
        
        if($request->hasFile('image')) {
            if (File::exists(public_path($awarding->image))) {
                File::delete(public_path($awarding->image));
            }
            $image_directory = 'web/images/awarding/logo/';
            $image_path = Utility::upload_direct_image($request->image,$image_directory);
            $awarding->image = $image_path;
        }
        $awarding->save();
        return $awarding->id;
    }
    public static function add_why_ukmc_data(Request $request){
        $why_ukmc_id = $request->why_ukmc_id;
        if(!empty($why_ukmc_id)){
            $why_ukmc = WhyUkmc::where('id',$why_ukmc_id)->first();
        }else{
            $why_ukmc = new WhyUkmc();
        }
        $why_ukmc->title = $request->title;
        $why_ukmc->description = $request->description;
        
        if($request->hasFile('image')) {
            if (File::exists(public_path($why_ukmc->image))) {
                File::delete(public_path($why_ukmc->image));
            }
            $image_directory = 'web/images/whyukmc/pic/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,451,740);
            $why_ukmc->image = $image_path;
        }
        $why_ukmc->save();
        return $why_ukmc->id;
    }
    public static function add_governing_body_category_data(Request $request){
        $governing_body_category_id = $request->governing_body_category_id;
        if(!empty($governing_body_category_id)){
            $governing_body_category = GoverningBodyCategory::where('id',$governing_body_category_id)->first();
        }else{
            $governing_body_category = new GoverningBodyCategory();
        }
        $governing_body_category->title = $request->title;
        $governing_body_category->description = $request->description;
        $governing_body_category->save();
        return $governing_body_category->id;
    }
    public static function add_governing_body_data(Request $request){
        $governing_body_id = $request->governing_body_id;
        if(!empty($governing_body_id)){
            $body = GoverningBody::where('id',$governing_body_id)->first();
        }else{
            $body = new GoverningBody();
        }
        $body->name = $request->name;
        $body->description = $request->description;
        $body->designation = $request->designation;
        $body->governing_body_category_id = $request->governing_body_category_id;
        $body->slug = Service::slug_create($request->name);
        
        if($request->hasFile('image')) {
            if (File::exists(public_path($body->image))) {
                File::delete(public_path($body->image));
            }
            $image_directory = 'web/images/governingbody/pic/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,500,500);
            $body->image = $image_path;
        }
        $body->save();
        return $body->id;
    }
    public static function create_handbook(Request $request){
        $handboook_id = $request->handbook_id;
        if(!empty($handboook_id)){
            $handbook = StudentHandBook::where('id',$handboook_id)->first();
        }else{
            $handbook = new StudentHandBook();
            $handbook->url = Utility::generate_hankbook_url($request->title,$handboook_id);
        }
        $handbook->title = $request->title;
        $handbook->description = $request->description;
        if($request->hasFile('image')) {
            if (File::exists(public_path($handbook->image))) {
                File::delete(public_path($handbook->image));
            }
            $image_directory = 'web/images/handbook/img/main/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,505,505);
            $handbook->image = $image_path;
        }
        $handbook->save();
        return $handbook->id;
    }
    public static function create_handbook_module(Request $request){
        $module = new HandbookModule();
        $module->campus = $request->campus;
        $module->student_handbook_id = $request->hand_book_module_id;
        $module->url = rand(1000,9999).time();
        if($request->hasFile('file')) {
            if (File::exists(public_path($module->file))) {
                File::delete(public_path($module->file));
            }
            $image_directory = 'web/images/handbook/file/';
            $image_path = Utility::upload_direct_file($request->file,$image_directory);
            $module->file = $image_path;
        }
        if($request->hasFile('image')) {
            if (File::exists(public_path($module->image))) {
                File::delete(public_path($module->image));
            }
            $image_directory = 'web/images/handbook/image/main/';
            $image_path = Utility::upload_image_to_public($request->image,$image_directory,637,445);
            $module->image = $image_path;
        }
        $module->save();
        return $module->id;
    }
    public static function create_prospectus(Request $request){
        $prospectus_id = $request->prospectus_id;
        if(!empty($prospectus_id)){
            $handbook = Prospectus::where('id',$prospectus_id)->first();
        }else{
            $handbook = new Prospectus();
        }
        $handbook->title = $request->title;
        $handbook->description = $request->description;
        
        if($request->hasFile('file')) {
            if (File::exists(public_path($handbook->file))) {
                File::delete(public_path($handbook->file));
            }
            $image_directory = 'web/images/prospectus/file/';
            $image_path = Utility::upload_direct_file($request->file,$image_directory);
            $handbook->file = $image_path;
        }
        $handbook->save();
        return $handbook->id;
    }
    public static function create_policies(Request $request){
        $policies_id = $request->policies_id;
        if(!empty($policies_id)){
            $handbook = Policies::where('id',$policies_id)->first();
        }else{
            $handbook = new Policies();
        }
        $handbook->title = $request->title;
        $handbook->description = $request->description;
        
        if($request->hasFile('file')) {
            if (File::exists(public_path($handbook->file))) {
                File::delete(public_path($handbook->file));
            }
            $image_directory = 'web/images/policies/file/';
            $image_path = Utility::upload_direct_file($request->file,$image_directory);
            $handbook->file = $image_path;
        }
        $handbook->save();
        return $handbook->id;
    }

}