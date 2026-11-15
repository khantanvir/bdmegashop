<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
Use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Blog\Blog;
use App\Models\Student\StudentHandBook;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use App\Traits\Service;
//use Intervention\Image\Facades\Image;

trait Utility{
    use Service;
    public static function upload_image($image, $directory = 'uploads/', $allowedExtensions = ['jpg', 'png', 'jpeg']){
        $ext = $image->getClientOriginalExtension();
        if (!$ext || !in_array($ext, $allowedExtensions)) {
            Session::flash('error', 'Invalid Document! Please upload JPG, PNG, or JPEG.');
            return null;
        }
        $imageFileName = $imageFileName = preg_replace('/\s+/', '-', $image->getClientOriginalName()) . '-' . rand(11, 99) . '.' . $ext;
        if (!Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->makeDirectory($directory);
            chmod(storage_path('app/' . $directory), 0775);
        }
        $image->storeAs($directory, $imageFileName, 'local');
        return $directory . $imageFileName;
    }
    public static function upload_image_to_public($image, $directory,$height,$width, $allowedExtensions = ['jpg', 'png', 'jpeg']){
        $ext = $image->getClientOriginalExtension();
        if (!$ext || !in_array($ext, $allowedExtensions)) {
            Session::flash('error', 'Invalid Document! Please upload JPG, PNG, or JPEG.');
            return null;
        }
        $imageFileName = rand(11, 99).'-'.preg_replace('/\s+/', '-', $image->getClientOriginalName());
        $targetPath = public_path($directory);
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0775, true);
        }
        //resize image
        $image = Image::read($image->path());
        $image->resize($width, $height, function ($constraint){
            $constraint->aspectRatio();
        })->save($targetPath . $imageFileName);
        //$image->move($targetPath, $imageFileName);
        return $directory . $imageFileName;
    }
    public static function set_alt_image_tag($arr = null) {
        if (!is_array($arr) || empty($arr)) {
            return false;
        }
        $returnArr = [];
        foreach ($arr as $key => $val) {
            if (isset($val->value)) { // Check if 'value' key exists
                $returnArr[] = $val->value;
            }
        }
        return implode(',', $returnArr);
    }
    public static function get_unique_tags(){
        $tags = [];
        $currentDateTime = Carbon::now()->toDateTimeString();
        $blog_data = Blog::with('category')->where('publish_time','<',$currentDateTime)->where('blog_status','!=','Draft')->where('status',0)->get();
        foreach ($blog_data as $key => $row) {
            if(!empty($row->tags)){
                $array = explode(',', $row->tags);
                foreach ($array as $key => $value) {
                    $tags[] = $value;
                }
            }
        }
        return array_unique($tags);
    }
    public static function upload_direct_image($image,$directory, $allowedExtensions = ['jpg', 'png', 'jpeg']){
        $ext = $image->getClientOriginalExtension();
        if (!$ext || !in_array($ext, $allowedExtensions)) {
            Session::flash('error', 'Invalid Document! Please upload JPG, PNG, or JPEG.');
            return null;
        }
        $doc_file_name = $image->getClientOriginalName();
        $doc_file_name = 'UKMC-'.Service::get_random_str_number().'-'.Service::slug_create($doc_file_name).'.'.$ext;
        $upload_path1 = $directory;
        Service::createDirectory($upload_path1);
        $image->move(public_path($upload_path1), $doc_file_name);
        return $upload_path1.$doc_file_name;
    }
    public static function upload_direct_file($image,$directory, $allowedExtensions = ['pdf', 'docx', 'doc']){
        $ext = $image->getClientOriginalExtension();
        if (!$ext || !in_array($ext, $allowedExtensions)) {
            Session::flash('error', 'Invalid Document! Please upload PDF, DOCX, or DOC.');
            return null;
        }
        $doc_file_name = $image->getClientOriginalName();
        $doc_file_name = 'UKMC-'.Service::get_random_str_number().'-'.Service::slug_create($doc_file_name).'.'.$ext;
        $upload_path1 = $directory;
        Service::createDirectory($upload_path1);
        $image->move(public_path($upload_path1), $doc_file_name);
        return $upload_path1.$doc_file_name;
    }
    public static function roles(){
        $roles = array(
            'admin',
            'marketing',
            'hr',
            'academic',
        );
        return $roles;
    }
}
