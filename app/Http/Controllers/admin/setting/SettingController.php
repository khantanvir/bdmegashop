<?php

namespace App\Http\Controllers\admin\setting;

use App\Http\Controllers\Controller;
use App\Models\Menu\MainMenu;
use App\Models\Menu\SubMenu;
use App\Traits\Menu\PermissionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller{
    use PermissionData;
    // Permission settings
    public function permission_settings()
    {
        $data['page_title'] = 'UKMC | Permission settings';
        return view('admin.setting.permission-settings',$data);
    }

    public function create_main_menu($id=NULL)
    {
        if(!empty($id)){
            $data['menu_data'] = MainMenu::where('id',$id)->first();
        }
        $data['menu_list'] = MainMenu::orderBy('created_at','desc')->get();
        $data['setting_menu'] = true;
        $data['main_menu_menu'] = true;
        $data['page_title'] = 'UKMC | Menu permission settings';
        return view('admin.setting.create-main-menu',$data);
    }
    public function store_main_menu_data(Request $request){
        $request->validate([
            'title' => 'required',
        ]);
        $data = PermissionData::create_main_menu($request);
        Session::flash('success','Main menu Updated.');
        return redirect()->back();
    }
    public function main_menu_status_change(Request $request){
        $data = PermissionData::menu_status_change($request);
        return response()->json($data,200);
    }
    public function menu_permission(Request $request,$id=NULL)
    {
        $data['setting_menu'] = true;
        $data['menu_permission_menu'] = true;
        $data['page_title'] = 'UKMC | Menu permission';
        $data['menu_list'] = MainMenu::where('status',0)->orderBy('created_at','desc')->get();
        //$data['sub_menu_list'] = SubMenu::with('main_menu')->orderBy('created_at','desc')->paginate(25);
        $get_search = $request->search;
        $get_list_main_menu_id = $request->list_main_menu_id;
        Session::put('get_search',$get_search);
        Session::put('get_list_main_menu_id',$get_list_main_menu_id);
        if(!empty($id)){
            $data['sub_menu'] = SubMenu::find($id);
        }
        $data['sub_menu_list'] = SubMenu::query()
        ->with(['main_menu'])
        ->when($get_search, function ($query, $get_search) {
            return $query->where('title', 'like', '%' . $get_search . '%');
        })
        ->when($get_list_main_menu_id, function ($query, $get_list_main_menu_id) {
            return $query->where('main_menu_id',$get_list_main_menu_id);
        })
        ->orderBy('id','desc')
        ->paginate(25);
        $data['get_search'] = Session::get('get_search');
        $data['get_list_main_menu_id'] = Session::get('get_list_main_menu_id');
        return view('admin.setting.menu-permission',$data);
    }
    public function sub_menu_status_change(Request $request){
        $data = PermissionData::sub_menu_status_change($request);
        return response()->json($data,200);
    }
    public function sub_menu_data_post(Request $request){
        $request->validate([
            'title' => 'required',
            'main_menu_id' => 'required',
            'process' => 'required',
        ]);
        $data = PermissionData::sub_menu_create($request);
        Session::flash('success','Sub menu Updated.');
        return redirect('menu-permission');
    }
    
}
