<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Company;
use App\Models\FocalPoint;
use App\Models\CompanyStaff;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;

class SecurityOfficerAdminController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            //$companies = DB::select('select * from companies_view where event_id = ?' ,$event_id );
            $companies = DB::select('select * from companies_view');
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('companyEdit', $data->id) . '" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="invite-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Invite Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('companyAccreditCat', $data->id) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="Company Accreditation Size"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    return $button;
                })
//                ->addColumn('event_id', function($event_id){
//                    return $event_id;
//                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $events = DB::select('select * from events_view where security_officer_id = ? and approval_option in (1,3) ', [Auth::user()->id]);
//        var_dump($events);
//        exit;
//        $events = DB::select('select * from events_view  , events_view v');
        return view('pages.SecurityOfficerAdmin.security-officer-admin')->withEvents($events);
    }

    public function securityOfficerCompanies($id)
    {
        $where = array('id' => $id);
        $event  = Event::where($where)->first();
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view where event_id = ?' ,[$id]);
//            $companies = DB::select('select * from companies_view');
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    // $button = '<a href="../company-edit/'.$data->id.'/'.$data->event_id.'" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    // $button .= '&nbsp;&nbsp;';
                    // $button .= '<a href="javascript:void(0);" id="invite-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-name="'.$data->name.'" data-focalpoint="'.$data->focal_point.'" class="delete btn btn-danger" title="Invite Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    // $button .= '&nbsp;&nbsp;';
                    // $button .= '<a href="' . route('companyAccreditCat',[$data->id , $data->event_id]) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="Company Accreditation Size"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    // $button .= '&nbsp;&nbsp;';
                    $button = '<a href="' . route('securityOfficerCompanyParticipants',[$data->id , $data->event_id]) . '" id="company-participant" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-facebook" title="Company Participants"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    return $button;
                })
//                ->addColumn('event_id', function($event_id){
//                    return $event_id;
//                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.SecurityOfficerAdmin.security-officer-companies')->with('eventid',$id)->with('event_name',$event->name);
    }

    public function Invite($companyId){
        $where = array('id' => $companyId);
        $company = Company::where($where)->first();
        $where = array('id' => $company->focal_point_id);
        $focalpoint = FocalPoint::where($where)->first();
        $post = Company::updateOrCreate(['id' => $companyId],
        [
            'company_admin_id' => $focalpoint->account_id,
            'status' => 3
        ]);
        $focalpointUpdate = FocalPoint::updateOrCreate(['id' => $focalpoint->id],
        [
            'company_id' => $companyId
        ]);
        return Response::json($post);
    }

    public function securityOfficerCompanyParticipants($companyId,$eventId){
        $dataTableColumuns = array();

        $where = array('id' => $companyId);
        $company = Company::where($where)->get()->first();
        $company_admin_id = $company->company_admin_id;
        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->get()->all();

        foreach($templateFields as $templateField){
            $dataTableColumuns[] = $templateField->label_en;
        }
        Schema::dropIfExists('temp'.$company_admin_id);
        Schema::create('temp'.$company_admin_id, function ($table) use($templateFields) {
            $table->string('id');
            foreach($templateFields as $templateField){
                $dataTableColumuns[] = $templateField->label_en;
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        // $where = array('company_admin_id' => Auth::user()->id);
        // $company = Company::where($where)->get()->first();

        $where = array('event_id' => $company->event_id,'company_id' => $company->id,'status' => 1);
        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach($companyStaffs as $companyStaff){
            $where = array('staff_id' => $companyStaff->id);
            // $staffDatas = StaffData::where($where)->get()->all();
            $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?',[$companyStaff->id,$event->event_form]);
            $staffDataValues = array();
            $staffDataValues[] = $companyStaff->id;
            foreach($staffDatas as $staffData){
                if($staffData->slug == 'select' ){
                    $where = array('template_field_id' =>$staffData->template_field_id , 'value_id' => $staffData->value);
                    $value = TemplateFieldElement::where($where)->first();
                    $staffDataValues[] = $value->value_en;
                }else{
                    $staffDataValues[] = $staffData->value;
                }
            }
            $alldata[] = $staffDataValues;
        }        
        // var_dump($alldata);
        // exit;
        $query = '';
        foreach($alldata as $data){
            $query = 'insert into temp'.$company_admin_id.' (id';
            foreach($templateFields as $templateField){
                $query = $query .',' . preg_replace('/\s+/', '_', $templateField->label_en);
            }
            $query = $query . ') values (';
            foreach($data as $staffDataValue){
                $query = $query . '"' . $staffDataValue . '",';
            }
            $query = substr($query,0, strlen($query)-1);
            $query = $query . ')';
            DB::insert($query);
        }
        //DB::insert($query);
        // var_dump($query);
        // exit;
        if (request()->ajax()) {
            $participants = DB::select('select t.* , c.* from temp'.$company_admin_id. ' t inner join company_staff c on t.id = c.id');
            return datatables()->of($participants)
                ->addColumn('status', function($data){
                    $status_value = "initaited";
                    switch($data->status){
                        case 0:
                            $status_value =  "Initaited";
                            break;
                        case 1:
                            $status_value =  "waiting Security Officer Approval";
                            break;
                        case 2:
                            $status_value =  "waiting Event Admin Approval";
                            break;
                        case 3:
                            $status_value =  "approved by security officer";
                            break;
                        case 4:
                            $status_value =  "rejected by security officer";
                            break;
                        case 5:
                            $status_value =  "rejected by event admin";
                            break;
                        case 6:
                            $status_value =  "approved by event admin";
                            break;
                        case 7:
                            $status_value =  "rejected with correction by security officer";                                
                            break;
                        case 8:
                            $status_value =  "rejected with correction by event admin";                                
                            break;   
                    }
                    return $status_value;
                    //return $row->first_name.' '.$row->last_name;
                })
                ->addColumn('action', function ($data) {
                    $button = '';
                    switch($data->status){
                        case 1:
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Aprrove</a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject</a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject with correction</a>';
                            break;
                        case 7:
                            $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" data-reason="'.$data->security_officer_reject_reason.'" class="delete btn btn-danger">Reject Reason</a>';
                            break;
                        // case 8:
                        //     $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" data-reason="'.$data->event_admin_reject_reason.'" class="delete btn btn-danger">Reject Reason</a>';
                        //     break;
                    }
                    return $button;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('pages.SecurityOfficerAdmin.security-officer-company-participants')->with('dataTableColumns',$dataTableColumuns)->with('company_id',$companyId)->with('event_id',$eventId);
    }

    public function Approve($staffId){
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id'=> $eventId);
        $event = Event::where($eventWhere)->first();

        $approval = $event->approval_option;
        if($approval == 1){
            DB::update('update company_staff set status = ? where id = ?',[3,$staffId]);

        }else{
            if($approval == 3){
                DB::update('update company_staff set event_admin_id = ? where id = ?',[$event->event_admin,$staffId]);
                DB::update('update company_staff set status = ? where id = ?',[2,$staffId]);
            }
        }
        return Response::json($event);
    }
    public function Reject($staffId){
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id'=> $eventId);
        $event = Event::where($eventWhere)->first();

        $approval = $event->approval_option;
        if($approval == 1){
            DB::update('update company_staff set status = ? where id = ?',[4,$staffId]);

        }else{
            if($approval == 3){
                //DB::update('update company_staff set security_officer_id = ? where id = ?',[$event->security_officer,$staffId]);
                DB::update('update company_staff set status = ? where id = ?',[4,$staffId]);
            }
        }
        return Response::json($event);
    }

    public function RejectToCorrect($staffId, $reason){
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id'=> $eventId);
        $event = Event::where($eventWhere)->first();

        $approval = $event->approval_option;
        if($approval == 1){
            DB::update('update company_staff set status = ? where id = ?',[7,$staffId]);
            DB::update('update company_staff set security_officer_reject_reason = ? where id = ?',[$reason,$staffId]);

        }else{
            if($approval == 3){
                DB::update('update company_staff set status = ? where id = ?',[7,$staffId]);
                DB::update('update company_staff set security_officer_reject_reason = ? where id = ?',[$reason,$staffId]);
            }
        }
        return Response::json($event);
    }

}
