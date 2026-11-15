<?php 

namespace App\Traits\Menu;

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
use App\Models\Menu\MainMenu;
use App\Models\Menu\SubMenu;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use App\Traits\Utility;
use App\Traits\Service;
//use Intervention\Image\Facades\Image;

trait PermissionData{
    use Utility, Service;
    public static function create_main_menu(Request $request){
        $main_menu_id = $request->main_menu_id;
        if(!empty($main_menu_id)){
            $menu = MainMenu::where('id',$main_menu_id)->first();
        }else{
            $menu = new MainMenu();
        }
        $menu->title = $request->title;
        $menu->save();
        return $menu->id;
    }
    public static function menu_status_change(Request $request){
        $status = MainMenu::where('id',$request->menu_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Data Not Found! Server Error!'
            );
            return $data;
        }
        $msg = '';
        if($status->status==1){
            $update = MainMenu::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated!';
        }else{
            $update = MainMenu::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated!';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return $data;
    }

    public static function sub_menu_create(Request $request){
        $sub_menu_id = $request->sub_menu_id;
        if(!empty($sub_menu_id)){
            $menu = SubMenu::where('id',$sub_menu_id)->first();
        }else{
            $menu = new SubMenu();
        }
        $menu->title = $request->title;
        $menu->main_menu_id = $request->main_menu_id;
        $menu->process = $request->process;
        $menu->save();
        return $menu->id;
    }
    public static function sub_menu_status_change(Request $request){
        $status = SubMenu::where('id',$request->sub_menu_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Data Not Found! Server Error!'
            );
            return $data;
        }
        $msg = '';
        if($status->status==1){
            $update = SubMenu::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated!';
        }else{
            $update = SubMenu::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated!';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return $data;
    }
}