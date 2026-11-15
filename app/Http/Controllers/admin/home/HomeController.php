<?php

namespace App\Http\Controllers\admin\home;

use App\Http\Controllers\Controller;
use App\Models\Career\ApplyJob;
use App\Models\Career\Career;
use App\Models\GoverningBody\GoverningBody;
use App\Models\GoverningBody\GoverningBodyCategory;
use App\Models\Home\AwardingBody;
use App\Models\Home\HomeBanner;
use App\Models\Home\WhyUkmc;
use App\Models\Recruitment\RecruitmentPartner;
use App\Models\Student\HandbookDownload;
use App\Models\Student\HandbookModule;
use App\Models\Student\Policies;
use App\Models\Student\PoliciesDownload;
use App\Models\Student\Prospectus;
use App\Models\Student\StudentHandBook;
use App\Models\Stusent\ProspectusDownload;
use App\Traits\Career\CareerData;
use Illuminate\Http\Request;
use App\Traits\Home\HomeData;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller{
    use HomeData, CareerData;
    public function banner_image(){
        $data['page_title'] = 'UKMC | Banner Image';
        $data['home_menu'] = true;
        $data['banner_image'] = true;
        $data['banner_data'] = HomeBanner::where('id',1)->first();
        return view('admin.home.banner_image',$data);
    }
    public function create_banner_data_post(Request $request){
        $request->validate([
            'title'=>'',
            'description'=>'',
        ]);
        $get_data = HomeData::change_banner($request);
        Session::flash('success','Banner Data Saved');
        return redirect('banner-image');
    }
    public function list_awarding_body($id=NULL){
        $data['page_title'] = 'UKMC | Awarding Body';
        $data['home_menu'] = true;
        $data['awarding_body_sub'] = true;
        $data['awarding_body_list'] = AwardingBody::orderBy('created_at','desc')->paginate(10);
        if(!empty($id)){
            $data['awarding_data'] = AwardingBody::where('id',$id)->first();
        }
        return view('admin.home.awarding-body-list',$data);
    }
    public function store_awarding_body_data(Request $request){
        $request->validate([
            'title'=>'required',
        ]);
        $get_data = HomeData::add_awarding_body($request);
        Session::flash('success','Awarding Body Saved');
        return redirect('list-awarding-body');
    }
    public function awarding_body_status_change(Request $request){
        $status = AwardingBody::where('id',$request->awarding_body_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = AwardingBody::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated';
        }else{
            $update = AwardingBody::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function why_ukmc($id=NULL){
        $data['page_title'] = 'UKMC | Why UKMC';
        $data['home_menu'] = true;
        $data['why_ukmc_menu'] = true;
        $data['why_ukmc_list'] = WhyUkmc::orderBy('created_at','desc')->paginate(10);
        if(!empty($id)){
            $data['why_ukmc_data'] = WhyUkmc::where('id',$id)->first();
        }else{
            $data['why_ukmc_data'] = [];
        }
        return view('admin.home.why_ukmc',$data);
    }
    public function store_why_ukmc_data(Request $request){
        $request->validate([
            'title'=>'required',
            'description'=>'required'
        ]);
        $get_data = HomeData::add_why_ukmc_data($request);
        Session::flash('success','Why UKMC Data Saved');
        return redirect('admin-why-ukmc');
    }
    public function why_ukmc_status_change(Request $request){
        $status = WhyUkmc::where('id',$request->why_ukmc_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = WhyUkmc::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated';
        }else{
            $update = WhyUkmc::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function list_recruitment_partner(){
        $data['page_title'] = 'UKMC | Recruitment Partner';
        $data['home_menu'] = true;
        $data['recruitment_partner_menu'] = true;
        $data['recruitment_partner_list'] = RecruitmentPartner::orderBy('id','desc')->paginate(10);
        return view('admin.home.recruitment-partner-list',$data);
    }
    public function total_applicant_list($id=NULL){
        $data['page_title'] = 'UKMC | Total Applicant';
        $data['career_menu'] = true;
        $data['list_of_career'] = true;
        $data['total_applicants'] = ApplyJob::where('job_id',$id)->orderBy('is_selected','desc')->orderBy('id','desc')->paginate(30);
        return view('admin.career.applicant-list',$data);
    }

    public function craeer_list(){
        $data['page_title'] = 'UKMC | Career';
        $data['career_menu'] = true;
        $data['list_career_menu'] = true;
        $data['list_of_career'] = Career::withCount('applicant_list')->orderBy('created_at','desc')->paginate(10);
        return view('admin.career.list',$data);
    }
    public function create_career($id=NULL){
        $data['page_title'] = 'UKMC | Create Job';
        $data['career_menu'] = true;
        $data['add_career_menu'] = true;
        if(!empty($id)){
            $data['career_data'] = Career::where('id',$id)->first();
        }
        return view('admin.career.create',$data);
    }
    public function create_career_data_post(Request $request){
        $request->validate([
            'title'=>'required',
            'location'=>'required',
            'job_type'=>'required',
            'department'=>'required',
            'salary_range'=>'required',
            'salary_status'=>'required',
            'job_post_date'=>'required',
            'job_expire_date'=>'required',
            'job_level'=>'required',
            'experience'=>'required',
            'education'=>'required',
            'job_description'=>'required',
        ]);
        $get_data = CareerData::create_career($request);
        Session::flash('success','Job Post Data Saved');
        return redirect('create-career/'.$get_data);
    }
    public function governing_body_category($id=NULL){
        $data['page_title'] = 'UKMC | Governing Body Category';
        $data['governing_body_menu'] = true;
        $data['governing_body_category_menu'] = true;
        $data['governing_body_category_list'] = GoverningBodyCategory::orderBy('created_at','desc')->paginate(10);
        if(!empty($id)){
            $data['governing_body_category_data'] = GoverningBodyCategory::where('id',$id)->first();
        }
        return view('admin.governing_body.governing_body_category_list',$data);
    }
    public function store_governing_body_category_data(Request $request){
        $request->validate([
            'title'=>'required',
        ]);
        $get_data = HomeData::add_governing_body_category_data($request);
        Session::flash('success','Governing Body Category Saved');
        return redirect('governing-body-category');
    }
    public function governing_body_category_status_change(Request $request){
        $status = GoverningBodyCategory::where('id',$request->category_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Category Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = GoverningBodyCategory::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Category Activated';
        }else{
            $update = GoverningBodyCategory::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Category Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function create_governing_body($id=NULL){
        $data['page_title'] = 'UKMC | Governing Body';
        $data['governing_body_menu'] = true;
        $data['add_governing_body_menu'] = true;
        $data['category_list'] = GoverningBodyCategory::orderBy('created_at','desc')->get();
        if(!empty($id)){
            $data['governing_body_data'] = GoverningBody::where('id',$id)->first();
        }
        return view('admin.governing_body.create_governing_body',$data);
    }
    public function store_governing_body_data(Request $request){
        $request->validate([
            'name'=>'required',
            'designation'=>'required',
            'governing_body_category_id'=>'required',
            'description'=>'required',
        ]);
        $get_data = HomeData::add_governing_body_data($request);
        Session::flash('success','Governing Body Saved');
        return redirect('create-governing-body/'.$get_data);
    }
    public function governing_body_list(){
        $data['page_title'] = 'UKMC | Governing Body List';
        $data['governing_body_menu'] = true;
        $data['all_governing_body_menu'] = true;
        $data['list_of_govering_body'] = GoverningBody::with('category')->orderBy('created_at','desc')->paginate(10);
        return view('admin.governing_body.list',$data);
    }
    public function governing_body_status_change(Request $request){
        $status = GoverningBody::where('id',$request->governing_body_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Governing Body Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = GoverningBody::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated';
        }else{
            $update = GoverningBody::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function hand_book_module_delete($id=NULL){
        $getData = HandbookModule::where('id',$id)->first();
        if(!$getData){
            Session::flash('error','Handbook Module Data Not Found! Server Error!');
            return redirect()->back();
        }
        if(File::exists(public_path($getData->file))){
            File::delete(public_path($getData->file));
        }
        if(File::exists(public_path($getData->image))){
            File::delete(public_path($getData->image));
        }
        HandbookModule::where('id',$id)->delete();
        Session::flash('success','Handbook Module Deleted');
        return redirect()->back();
    }
    public function handbook_module_list($id=NULL){
        $data['page_title'] = 'UKMC | Handbook Module List';
        $data['handbook_menu'] = true;
        $data['all_handbook_menu'] = true;
        $data['hand_book_id'] = $id;
        $data['list_of_handbook_module'] = HandbookModule::with('handbook_module')->where('student_handbook_id',$id)->orderBy('id','desc')->paginate(10);
        return view('admin.handbook.hand_book_module_list',$data);
    }
    public function hand_book_module_data_post(Request $request){
        $request->validate([
            'campus' => 'required',
            'file' => 'required',
            'image' => 'required',
        ]);
        $get_data = HomeData::create_handbook_module($request);
        Session::flash('success','Handbook Module Saved');
        return redirect()->back();
    }
    public function create_handbook($id=NULL){
        $data['page_title'] = 'UKMC | Create Handbook';
        $data['handbook_menu'] = true;
        $data['create_handbook_menu'] = true;
        if(!empty($id)){
            $data['handbook_data'] = StudentHandBook::where('id',$id)->first();
        }
        return view('admin.handbook.create_handbook',$data);
    }
    public function all_handbook(){
        $data['page_title'] = 'UKMC | Handbook List';
        $data['handbook_menu'] = true;
        $data['all_handbook_menu'] = true;
        $data['list_of_handbook'] = StudentHandBook::withCount('total_downloads')->orderBy('created_at','desc')->paginate(10);
        return view('admin.handbook.all',$data);
    }
    public function handbook_downloader_list($id=NULL){
        $data['page_title'] = 'UKMC | Handbook Downloader List';
        $data['handbook_menu'] = true;
        $data['all_handbook_menu'] = true;
        $data['list_of_downloader'] = HandbookDownload::where('student_handbook_id',$id)->orderBy('created_at','desc')->paginate(30);
        return view('admin.handbook.handbook_downloader',$data);
    }
    public function store_student_handbook_data(Request $request){
        $request->validate([
            'title'=>'required',
            'description'=>'required',
        ]);
        $get_data = HomeData::create_handbook($request);
        Session::flash('success','Handbook Saved');
        return redirect('create-handbook/'.$get_data);
    }
    public function handbook_status_change(Request $request){
        $status = StudentHandBook::where('id',$request->handbook_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Handbook Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = StudentHandBook::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated';
        }else{
            $update = StudentHandBook::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function create_prospectus($id=NULL){
        $data['page_title'] = 'UKMC | Create Prospectus';
        $data['prospectus_menu'] = true;
        $data['create_prospectus_menu'] = true;
        if(!empty($id)){
            $data['prospectus_data'] = Prospectus::where('id',$id)->first();
        }
        return view('admin.prospectus.create_prospectus',$data);
    }
    public function all_prospectus(){
        $data['page_title'] = 'UKMC | Prospectus List';
        $data['prospectus_menu'] = true;
        $data['all_prospectus_menu'] = true;
        $data['list_of_prospectus'] = Prospectus::orderBy('created_at','desc')->paginate(10);
        return view('admin.prospectus.all',$data);
    }
    public function store_prospectus_data(Request $request){
        $request->validate([
            'title'=>'required',
            'description'=>'required',
        ]);
        $get_data = HomeData::create_prospectus($request);
        Session::flash('success','Prospectus Saved');
        return redirect('create-prospectus/'.$get_data);
    }
    public function prospectus_status_change(Request $request){
        $status = Prospectus::where('id',$request->prospectus_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Prospectus Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = Prospectus::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated';
        }else{
            $update = Prospectus::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function prospectus_downloader_list($id=NULL){
        $data['page_title'] = 'UKMC | Prospectus Downloader List';
        $data['prospectus_menu'] = true;
        $data['all_prospectus_menu'] = true;
        $data['list_of_downloader'] = ProspectusDownload::where('prospectus_id',$id)->orderBy('created_at','desc')->paginate(30);
        return view('admin.prospectus.prospectus_download',$data);
    }
    public function create_policies($id=NULL){
        $data['page_title'] = 'UKMC | Create Policies';
        $data['policies_menu'] = true;
        $data['create_policies_menu'] = true;
        if(!empty($id)){
            $data['policies_data'] = Policies::where('id',$id)->first();
        }
        return view('admin.policies.create_policies',$data);
    }
    public function all_policies(){
        $data['page_title'] = 'UKMC | Policies List';
        $data['policies_menu'] = true;
        $data['all_policies_menu'] = true;
        $data['list_of_policies'] = Policies::orderBy('created_at','desc')->paginate(10);
        return view('admin.policies.all',$data);
    }
    public function store_policies_data(Request $request){
        $request->validate([
            'title'=>'required',
            'description'=>'required',
        ]);
        $get_data = HomeData::create_policies($request);
        Session::flash('success','Policies Saved');
        return redirect('create-policies/'.$get_data);
    }
    public function policies_status_change(Request $request){
        $status = Policies::where('id',$request->policies_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Policies Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->status==1){
            $update = Policies::where('id',$status->id)->update(['status'=>0]);
            $msg = 'Activated';
        }else{
            $update = Policies::where('id',$status->id)->update(['status'=>1]);
            $msg = 'Deactivated';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
    public function policies_downloader_list($id=NULL){
        $data['page_title'] = 'UKMC | Policies Downloader List';
        $data['policies_menu'] = true;
        $data['all_policies_menu'] = true;
        $data['list_of_downloader'] = PoliciesDownload::where('policies_id',$id)->orderBy('created_at','desc')->paginate(30);
        return view('admin.policies.policies_download',$data);
    }
    public function applicant_status_change(Request $request){
        $status = ApplyJob::where('id',$request->applicant_id)->first();
        if(!$status){
            $data['result'] = array(
                'key'=>101,
                'val'=>'Data Not Found! Server Error!'
            );
            return response()->json($data,200);
        }
        $msg = '';
        if($status->is_selected==1){
            $update = ApplyJob::where('id',$status->id)->update(['is_selected'=>0]);
            $msg = 'Remove from Selection';
        }else{
            $update = ApplyJob::where('id',$status->id)->update(['is_selected'=>1]);
            $msg = 'Selected This Applicant';
        }
        $data['result'] = array(
            'key'=>200,
            'val'=>$msg
        );
        return response()->json($data,200);
    }
}
