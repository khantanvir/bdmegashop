<?php 

namespace App\Traits\User;

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
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use App\Traits\Utility;
use App\Traits\Service;
use Illuminate\Support\Facades\Hash;

//use Intervention\Image\Facades\Image;

trait UserData{
    use Utility, Service;

    
    public static function create_user(Request $request){
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name.' '.$request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();
        return $user->id;
    }
    public static function edit_user(Request $request){
        $user = User::where('id',$request->user_id)->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name.' '.$request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();
        return $user->id;
    }
    public static function user_status_change(Request $request){
        $status = User::where('id',$request->user_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Data Not Found! Server Error!'
            );
            return $data;
        }
        $msg = '';
        if($status->active==1){
            $update = User::where('id',$status->id)->update(['active'=>0]);
            $msg = 'DeActivated!';
        }else{
            $update = User::where('id',$status->id)->update(['active'=>1]);
            $msg = 'Activated!';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return $data;
    }
}