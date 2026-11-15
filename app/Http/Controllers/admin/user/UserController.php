<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Models\Menu\MainMenu;
use App\Models\Menu\RoleAssign;
use App\Models\User;
use App\Traits\Service;
use App\Traits\User\UserData;
use App\Traits\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller{
    use Service, Utility, UserData;
    public function add_user(){
        $data['user_menu'] = true;
        $data['add_user_menu'] = true;
        $data['page_title'] = 'UKMC | Add User';
        $data['role_list'] = Utility::roles();
        return view('admin.user.add-user',$data);
    }
    public function edit_user($id=NULL){
        $data['user_menu'] = true;
        $data['add_user_menu'] = true;
        $data['page_title'] = 'UKMC | Edit User';
        $data['role_list'] = Utility::roles();
        $data['user_data'] = User::where('id',$id)->first();
        return view('admin.user.edit-user',$data);
    }
    public function add_user_data_post(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
        ]);
        $data = UserData::create_user($request);
        return redirect()->route('all-user')->with('success','User Added Successfully!');
    }
    public function edit_user_data_post(Request $request){
        $request->validate([
            'user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
        ]);
        $data = UserData::edit_user($request);
        return redirect()->route('all-user')->with('success','User Updated Successfully!');
    }

    public function all_user(){
        $data['user_menu'] = true;
        $data['all_user_menu'] = true;
        $data['page_title'] = 'UKMC | All User';
        $data['user_list'] = User::orderBy('id','desc')->paginate(20);
        return view('admin.user.all-user',$data);
    }
    public function user_status_change(Request $request){
        $data = UserData::user_status_change($request);
        return response()->json($data,200);
    }
    public function user_permission($id=NULL){
        $data['user_menu'] = true;
        $data['user_permission_menu'] = true;
        $data['page_title'] = 'User | User Permission';
        $userData = User::where('id',$id)->first();
        if(!$userData){
            Session::flash('error','User Data Not Found! Server Error!');
            return redirect()->back();
        }
        $data['main_list'] = MainMenu::with('sub_menu_list')->where('status',0)->get();
        $data['user_id'] = $id;
        $id_array = [];
        $get_assigns = RoleAssign::where('user_id',$id)->get();
        if(!empty($get_assigns)){
            foreach($get_assigns as $row){
                $id_array[] = $row->sub_menu_id;
            }
        }
        $data['id_array'] = $id_array;
        return view('admin.user.user-permission',$data);
        
    }
    public function user_permission_assign(Request $request){
        $user_data = User::where('id',$request->user_id)->first();
        if(!$user_data){
            Session::flash('error','Internal Server Error!');
            return redirect()->back();
        }
        $sub_menu_ids = $request->sub_menu_ids;
        //delete perivious assign
        $delete = RoleAssign::where('user_id',$user_data->id)->delete();
        if(!empty($sub_menu_ids)){
            foreach($sub_menu_ids as $row){
                if($row){
                    $assign = new RoleAssign();
                    $assign->user_id = $user_data->id;
                    $assign->sub_menu_id = $row;
                    $assign->save();
                }
            }
        }
        Session::flash('success','Assigned Successfully!');
        return redirect()->back();
    }
}
