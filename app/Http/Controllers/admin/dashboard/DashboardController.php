<?php

namespace App\Http\Controllers\admin\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $data['page_title'] = 'UKMC | Dashboard';
        return view('admin.dashboard.index',$data);
    }
}
