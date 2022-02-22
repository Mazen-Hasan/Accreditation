<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\EventCompany;
use App\Models\FocalPoint;
use App\Models\SelectOption;
use App\Models\TemplateField;
use App\Models\CompanyAccreditaionCategory;
use App\Models\TemplateFieldElement;
use App\Models\StaffData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Psy\Command\WhereamiCommand;

class EventAdminController extends Controller
{

    public function index()
    {
        //$events = DB::select('select * from event_admins_view where event_admin = ? and status = ? and event_end_date >= CURRENT_DATE()', [Auth::user()->id,1]);
        $events = DB::select('select * from event_admins_view where event_admin = ? and status < ?', [Auth::user()->id,4]);
        return view('pages.EventAdmin.event-admin')->with('events', $events);
    }

    public function eventCompanies($id)
    {
        $where = array('id' => $id);
        $event = Event::where($where)->first();
        $hasSize = 1;
        $companies = DB::select('select * from companies_view where event_id = ? and parent_id is null', [$id]);
        foreach($companies as $company){
            if($company->size == 0){
                $hasSize = 0;
            }
        }
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view where event_id = ? and parent_id is null', [$id]);
            return datatables()->of($companies)
                ->addColumn('action', function ($data) use ($event) {
                    $button = "";
                    if($event->status < 3){
                        $button .= '<a href="' . route('companyEdit', [$data->id, $data->event_id]) . '"  data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        if($data->status > 0){
                            $button .= '<a href="javascript:void(0);" id="invite-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-name="' . $data->name . '" data-focalpoint="' . $data->focal_point . '" title="Invite"><i class="far fa-share-square"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                        }
                    }
                    $button .= '<a href="' . route('companyAccreditCat', [$data->id, $data->event_id]) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Accreditation Size"><i class="fas fa-sitemap"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventCompanyParticipants', [$data->id, $data->event_id]) . '" id="company-participant" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Participants"><i class="fas fa-users"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.EventAdmin.event-companies')->with('eventid', $id)->with('event_name', $event->name)->with('event_status',$event->status)->with('hasSize',$hasSize);
    }

    public function getData($id,$values){
        $totalSize = DB::select('select * from companies_view where event_id = ? and parent_id is null', [$id]);
        //$totalSize = Template::latest()->get();
        $size = 10;
        $whereCondition = "";
        if($values != null){
            if(str_contains($values,",")){
                $comands = explode(",",$values);
                $skip = $size * $comands[0];
                $c_size = sizeof($comands);
                $i = 1;
                while($i < sizeof($comands)){
                    $token = $comands[$i];
                    $i = $i + 1;
                    $complexityType = $comands[$i];
                    if($complexityType == "C"){
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $i = $i + 1;
                        $operator = $comands[$i];
                        $i = $i + 1;
                        $condition2 = $comands[$i];
                        $i = $i + 1;
                        $condition2token = $comands[$i];
                        $whereCondition =  $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token) . " ".$operator ." ". TemplateController::getConditionPart($token,$condition2,$condition2token);
                    }else{
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $whereCondition = $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token);
                    }
                    $i = $i + 1;
                }
                $totalSize = DB::select('select * from companies_view where event_id = ? and parent_id is null '. $whereCondition, [$id]);
                $templates = DB::select('select * from companies_view where event_id = ? and parent_id is null '. $whereCondition." LIMIT ". $size. " OFFSET ". $skip, [$id]);
            }else{
                $skip = $size * $values;
                $templates = DB::select("select * from companies_view where event_id = ? and parent_id is null  LIMIT ". $size. " OFFSET ". $skip, [$id]);
            }
        }
        return Response::json(array(
            'success' =>true,
            'code' => 1,
            'size' => round(sizeof($totalSize)/2),
            'templates' => $templates,
            'message' => 'hi'
        ));
        //return Response::json($templates);
    }

    public function Invite($companyId, $eventId)
    {
        $post = EventCompany::updateOrCreate(['company_id' => $companyId,'event_id'=>$eventId],
            [
                'status' => 3
            ]);
    
    	$focal_point = DB::select('select * from focal_points f where f.id = ?', [$post->focal_point_id]);
        $event = Event::where(['id'=>$eventId])->first();
        $company = Company::where(['id'=>$companyId])->first();
        // NotificationController::sendAlertNotification($focal_point[0]->account_id, 0, $event->name . ': ' . $company->name . ': ' . 'Event invitation', Route('companyParticipants' , [$companyId, $eventId]));

    	$notification_type = Config::get('enums.notification_types.EIN');
        NotificationController::sendNotification($notification_type, $event->name, $company->name, $focal_point[0]->account_id, 0,
            $event->name . ': ' . $company->name . ': ' . 'Event invitation', Route('companyParticipants' , [$companyId, $eventId]));
        
        return Response::json($post);
    }

    public function eventCompanyParticipants($companyId, $eventId)
    {
        $addable = 1;
        $companyAccrediationCategories = CompanyAccreditaionCategory::where(['company_id'=>$companyId,'event_id'=>$eventId])->get()->all();
        if($companyAccrediationCategories == null){
            $addable = 2;
        }else{
            $size = 0;
            $inserted = 0;
            $status = 0;
            $count = 0;
            foreach($companyAccrediationCategories as $companyAccrediationCategory){
                $size = $size + $companyAccrediationCategory->size;
                $inserted = $inserted + $companyAccrediationCategory->inserted;
                $status = $status + $companyAccrediationCategory->status;
                $count = $count + 1;
            }
            if($size == $inserted){
                if($size == 0){
                    $addable = 2;
                }else{
                    $addable = 0;
                }
            }
            if($status > 0){
                if($status/2 != $count){
                    $addable = 3;
                }
            }else{
                $addable = 3;
            }
        }


        $dataTableColumuns = array();
        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();
        $event_name = $event->name;

        if ($companyId != 0) {
            $where = array('id' => $companyId);
            $company = Company::where($where)->get()->first();
            $company_name = $company->name;
            $company_admin_id = $company->company_admin_id;
        } else {
            $company_admin_id = '_Event' . $event->event_admin;
            $company_name = '';
        }

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        // Schema::dropIfExists('temp' . $company_admin_id);
        // Schema::create('temp' . $company_admin_id, function ($table) use ($templateFields, $companyId) {
        //     $table->string('id');
        //     foreach ($templateFields as $templateField) {
        //         $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
        //     }
        // });
    	if(!Schema::hasTable('temp_' . $eventId)){
            Schema::create('temp_' . $eventId, function ($table) use ($templateFields) {
                $table->string('id');
                foreach ($templateFields as $templateField) {
                    $dataTableColumuns[] = $templateField->label_en;
                    $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
                }
            });
        }
//         if ($companyId == 0) {
//             $where = array('event_id' => $eventId);
//         } else {
//             $where = array('event_id' => $eventId, 'company_id' => $company->id);
//         }

//         $companyStaffs = CompanyStaff::where($where)->get()->all();
//         $alldata = array();
//         foreach ($companyStaffs as $companyStaff) {
//             $where = array('staff_id' => $companyStaff->id);
//             if ($companyId != 0) {
//                 $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
//             } else {
//                 $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
//             }
//             $staffDataValues = array();
//             $staffDataValues[] = $companyStaff->id;
//             $count = 0;
//             foreach ($staffDatas as $staffData) {
//                 if ($staffData->slug == 'select') {
//                     $where = array('template_field_id' => $staffData->template_field_id, 'value_id' => $staffData->value);
//                     $value = TemplateFieldElement::where($where)->first();
//                     $staffDataValues[] = $value->value_en;
//                 } else {
//                     $staffDataValues[] = $staffData->value;
//                 }
//             }
//             $alldata[] = $staffDataValues;
//         }
//         $query = '';
//         foreach ($alldata as $data) {
//             $query = '';
//             if ($companyId == 0) {
//                 $query = $query . 'insert into temp' . $company_admin_id . ' (id';
//             } else {
//                 $query = $query . 'insert into temp' . $company_admin_id . ' (id';
//             }
//             foreach ($templateFields as $templateField) {
//                 $query = $query . ',' . preg_replace('/\s+/', '_', $templateField->label_en);
//             }
//             $query = $query . ') values (';
//             foreach ($data as $staffDataValue) {
//                 $query = $query . '"' . $staffDataValue . '",';
//             }
//             $query = substr($query, 0, strlen($query) - 1);
//             $query = $query . ')';
//             DB::insert($query);
//         }
        if (request()->ajax()) {
            //$participants = DB::select('select t.* , c.* from temp' . $company_admin_id . ' t inner join company_staff c on t.id = c.id');
            if($companyId != 0){
               	$eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
            	$companies = "'".$companyId."'";
            	if($eventcompanies != null){
                	foreach($eventcompanies as $eventcompnay){
                    	$companies = $companies.",'".$eventcompnay->company_id."'";
                	}
            	}
            	$participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')');
                //$participants = DB::select('select t.* , c.* from temp_' . $eventId . ' t inner join company_staff c on t.id = c.id where c.company_id = ?',[$companyId]);
            }else{
                $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id');
            }
            return datatables()->of($participants)
                ->addColumn('status', function ($data) {
                    $status_value = "initaited";
                    switch ($data->status) {
                        case 0:
                            $status_value = "Initiated";
                            break;
                        case 1:
                            $status_value = "Waiting Security Officer Approval";
                            break;
                        case 2:
                            $status_value = "Waiting Event Admin Approval";
                            break;
                        case 3:
                            $status_value = "Approved by security officer";
                            break;
                        case 4:
                            $status_value = "Rejected by security officer";
                            break;
                        case 5:
                            $status_value = "Rejected by event admin";
                            break;
                        case 6:
                            $status_value = "Approved by event admin";
                            break;
                        case 7:
                            $status_value = "Needs review and correction by security officer";
                            break;
                        case 8:
                            $status_value = "Needs review and correction by event admin";
                            break;
                        case 9:
                            $status_value = "Badge generated";
                            break;
                        case 10:
                            $status_value = "Badge printed";
                            break;
                    }
                    return $status_value;
                })
                ->addColumn('action', function ($data) use ($event) {
                    $button = '';
                    $button .= '<a href="' . route('participantDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($event->status < 3){    
                        switch ($data->status) {
                            case 2:
                                $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' . $data->id . '" data-original-title="Edit" title="Approve"><i class="fas fa-vote-yea"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $data->id . '" data-original-title="Edit" title="Reject"><i class="fas fa-ban"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $data->id . '" data-original-title="Edit" title="Return for correction"><i class="far fa-window-close"></i></a>';
                                break;
                            case 1:
                                $button .= '<a href="' . route('eventParticipantAdd', [$data->id,$data->company_id,$data->event_id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                break;
                            case 7:
                                $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->security_officer_reject_reason . '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                $button .= '<a href="' . route('eventParticipantAdd', [$data->id,$data->company_id,$data->event_id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                                break;
                            case 8:
                                $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->event_admin_reject_reason . '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                                break;
                            case 6:
                            case 3:
                                if ($data->print_status == 0) {
                                    $button .= '<a href="javascript:void(0);" id="generate-badge" data-toggle="tooltip" data-original-title="Generate" data-id="' . $data->id . '" title="Generate"><i class="fas fa-cogs"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                } else {
                                    $printed = $data->print_status == 2 ? 'printed' : '';
                                    $button .= '<a href="javascript:void(0);" id="preview-badge" data-toggle="tooltip" data-original-title="Preview" data-id="' . $data->id . '" class="preview-badge"' . $printed . '" title="Preview"><i class="far fa-eye"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                }
                                break;
                        }
                    }
                    return $button;
                })
                ->addColumn('image', function ($data) {
                    $image = '';
                    //$image .= '<a href="' . route('templateFormDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $image .= '<img src="'. asset('badges/'.$data->Personal_Image).'" alt="Personal" class="pic-img" style="margin-left:40px">';
                    return $image;
                })
                ->addColumn('identifier', function ($data) {
                    return $data->identifier;
                })
                ->rawColumns(['identifier','image','status', 'action'])
                ->make(true);
        }
        return view('pages.EventAdmin.event-company-participants')->with('dataTableColumns', $dataTableColumuns)->with('company_id', $companyId)->with('event_id', $eventId)->with('company_name',$company_name)->with('event_name',$event_name)->with('addable',$addable)->with('event_status',$event->status);
    }

    public function Approve($staffId)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();

        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;

        $event_security_officers = DB::select('select * from event_security_officers_view e where e.id=?',[$eventId]);

        if ($approval == 1) {
            DB::update('update company_staff set status = ? where id = ?', [6, $staffId]);
            $event_companies = EventCompany::where(['event_id'=>$eventId, 'company_id'=> $companyId])->first();
            $focal_point = DB::select('select * from focal_points f where f.id = ?', [$event_companies->focal_point_id]);
            // NotificationController::sendAlertNotification($focal_point[0]->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approved', Route('templateFormDetails' , [$staffId]));

        	app('App\Http\Controllers\GenerateBadgeController')->generate($staffId);
        
        	$notification_type = Config::get('enums.notification_types.PAP');
            NotificationController::sendNotification($notification_type, $event->name, $company->name, $focal_point[0]->account_id, $staffId,
                $event->name . ': ' . $company->name . ': ' . 'Participant approved',
                Route('templateFormDetails' , [$staffId]));
        
        } else {
            if ($approval == 3) {
            	DB::update('update company_staff set status = ? where id = ?', [1, $staffId]);
                foreach ($event_security_officers as $event_security_officer){
                    // NotificationController::sendAlertNotification($event_security_officer->security_officer_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', Route('securityParticipantDetails' , $staffId));
                	
                	$notification_type = Config::get('enums.notification_types.PAP');
                    NotificationController::sendNotification($notification_type, $event->name, $company->name, $event_security_officer->security_officer_id, $staffId,
                        $event->name . ': ' . $company->name . ': ' . 'Participant approval',
                        Route('securityParticipantDetails' , $staffId));
                }
//                NotificationController::sendAlertNotification($event->security_officer, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', '/security-officer-participant-details/' . $staffId);
//                DB::update('update company_staff set security_officer_id = ? where id = ?', [$event->security_officer, $staffId]);
            }
        }
        return Response::json($event);
    }

    public function getPaticipantsData($companyId,$eventId,$values){
        if($companyId != 0){
            $eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
            $companies = "'".$companyId."'";
            if($eventcompanies != null){
                foreach($eventcompanies as $eventcompnay){
                    $companies = $companies.",'".$eventcompnay->company_id."'";
                }
            }
            $totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')');
        }else{
            $totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id');
        }
        //$totalSize = DB::select('select * from companies_view where event_id = ? and parent_id is null', [$id]);
        //$totalSize = Template::latest()->get();
        $size = 10;
        $whereCondition = "";
        if($values != null){
            if(str_contains($values,",")){
                $comands = explode(",",$values);
                $skip = $size * $comands[0];
                $c_size = sizeof($comands);
                $i = 1;
                while($i < sizeof($comands)){
                    $token = $comands[$i];
                    $i = $i + 1;
                    $complexityType = $comands[$i];
                    if($complexityType == "C"){
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $i = $i + 1;
                        $operator = $comands[$i];
                        $i = $i + 1;
                        $condition2 = $comands[$i];
                        $i = $i + 1;
                        $condition2token = $comands[$i];
                        $whereCondition =  $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token) . " ".$operator ." ". TemplateController::getConditionPart($token,$condition2,$condition2token);
                    }else{
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $whereCondition = $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token);
                    }
                    $i = $i + 1;
                }
                if($companyId != 0){
                    $eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
                    $companies = "'".$companyId."'";
                    if($eventcompanies != null){
                        foreach($eventcompanies as $eventcompnay){
                            $companies = $companies.",'".$eventcompnay->company_id."'";
                        }
                    }
                    //$totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')');
                    $totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')'. $whereCondition);
                    $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')'. $whereCondition." LIMIT ". $size. " OFFSET ". $skip);
                }else{
                    //$totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id');
                    $totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where 1=1 '. $whereCondition);
                    $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where 1=1 '. $whereCondition." LIMIT ". $size. " OFFSET ". $skip);
                }
                //$totalSize = DB::select('select * from companies_view where event_id = ? and parent_id is null '. $whereCondition, [$id]);
                //$participants = DB::select('select * from companies_view where event_id = ? and parent_id is null '. $whereCondition." LIMIT ". $size. " OFFSET ". $skip, [$id]);
            }else{
                $skip = $size * $values;
                if($companyId != 0){
                    $eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
                    $companies = "'".$companyId."'";
                    if($eventcompanies != null){
                        foreach($eventcompanies as $eventcompnay){
                            $companies = $companies.",'".$eventcompnay->company_id."'";
                        }
                    }
                    $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.') LIMIT '. $size. " OFFSET ". $skip);
                }else{
                    $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id LIMIT '. $size. " OFFSET ". $skip);
                }
                //$participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id  LIMIT '. $size. " OFFSET ". $skip);
            }
        }
        return Response::json(array(
            'success' =>true,
            'code' => 1,
            'size' => round(sizeof($totalSize)/2),
            'templates' => $participants,
            'message' => 'hi'
        ));
        //return Response::json($templates);
    }

    public function Reject($staffId)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();

        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;
        $eventCompanies = EventCompany::where(['company_id'=> $companyId ,'event_id'=> $eventId])->first();
        $focalPoint = FocalPoint::where(['id'=>$eventCompanies->focal_point_id])->first();
        if ($approval == 1) {
            DB::update('update company_staff set status = ? where id = ?', [5, $staffId]);
        	// NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant rejected', Route('templateFormDetails' , $staffId));

        	$notification_type = Config::get('enums.notification_types.PRE');
            NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                $event->name . ': ' . $company->name . ': ' . 'Participant rejected', 
                Route('templateFormDetails' , $staffId));
        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [5, $staffId]);
            	// NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant rejected', Route('templateFormDetails' , $staffId));
            	$notification_type = Config::get('enums.notification_types.PRE');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                    $event->name . ': ' . $company->name . ': ' . 'Participant rejected', 
                    Route('templateFormDetails' , $staffId));
            }
        }
        return Response::json($event);
    }

    public function RejectToCorrect($staffId, $reason)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();
    
        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;
        $eventCompanies = EventCompany::where(['company_id'=> $companyId ,'event_id'=> $eventId])->first();
        $focalPoint = FocalPoint::where(['id'=>$eventCompanies->focal_point_id])->first();

        $approval = $event->approval_option;
        if ($approval == 1) {
            DB::update('update company_staff set status = ? where id = ?', [8, $staffId]);
            DB::update('update company_staff set event_admin_reject_reason = ? where id = ?', [$reason, $staffId]);
        	// NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant returend for correction', Route('templateFormDetails' , $staffId));
			
        	$notification_type = Config::get('enums.notification_types.PRC');
            NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                $event->name . ': ' . $company->name . ': ' . 'Participant returned for correction', 
                Route('templateFormDetails' , $staffId));
        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [8, $staffId]);
                DB::update('update company_staff set event_admin_reject_reason = ? where id = ?', [$reason, $staffId]);
            	// NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant returend for correction', Route('templateFormDetails' , $staffId));
            	
            	$notification_type = Config::get('enums.notification_types.PRC');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                    $event->name . ': ' . $company->name . ': ' . 'Participant returned for correction', 
                    Route('templateFormDetails' , $staffId));
            }
        }
        return Response::json($event);
    }

    public function details($participant_id)
    {

        $where = array('id' => $participant_id);
        $participant = CompanyStaff::where($where)->first();

        $where = array('id' => $participant->event_id);
        $event = Event::where($where)->first();
        $event_name = $event->name;

        $where = array('id' => $participant->company_id);
        $company = Company::where($where)->first();
        $company_name = $company->name;

        //$company_admin_id = '_Event' . $event->event_admin;

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }
        //$participants = DB::select('select t.* , c.* from temp' . $company_admin_id . ' t inner join company_staff c on t.id = c.id where c.id = ?', [$participant_id]);
        $participants = DB::select('select * from company_staff c where c.id = ?', [$participant_id]);
    	$status_value = "Initaited";
        $status = 0;
        $event_reject_reason = '';
        $security_officer_reject_reason = '';
        foreach ($participants as $participant) {
            $status = $participant->status;
            $event_reject_reason = $participant->event_admin_reject_reason;
            $security_officer_reject_reason = $participant->security_officer_reject_reason;
            switch ($participant->status) {
                case 0:
                    $status_value = "Initiated";
                    break;
                case 1:
                    $status_value = "Waiting Security Officer Approval";
                    break;
                case 2:
                    $status_value = "Waiting Event Admin Approval";
                    break;
                case 3:
                    $status_value = "Approved by security officer";
                    break;
                case 4:
                    $status_value = "Rejected by security officer";
                    break;
                case 5:
                    $status_value = "Rejected by event admin";
                    break;
                case 6:
                    $status_value = "Approved by event admin";
                    break;
                case 7:
                    $status_value = "Needs review and correction by security officer";
                    break;
                case 8:
                    $status_value = "Needs review and correction by event admin";
                    break;
                case 9:
                    $status_value = "Badge generated";
                    break;
                case 10:
                    $status_value = "Badge printed";
                    break;
            }
        }

        $fieldsCount = 1;
        $form = '';
        $options = array();
        $form .= '<div class="row">';
        $form .= $this->createStatusFieldLabel("Status",  $status_value);
        // $form .= '</div>';
        if ($status == 8) {
            // $form .= '<div class="row">';
            $form .= $this->createStatusFieldLabel("Reject Reason", $event_reject_reason);
            // $form .= '</div>';
        }
        if ($status == 7) {
            // $form .= '<div class="row">';
            $form .= $this->createStatusFieldLabel("Reject Reason", $security_officer_reject_reason);
            // $form .= '</div>';
        }
        // $form .= '<div class="row">';
        $attachmentForm = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenFieldLabel('participant_id', '');
        } else {
            $form .= $this->createHiddenFieldLabel( 'participant_id', $participant_id);
        }
        foreach ($templateFields as $templateField) {
            $options = [];
            if ($fieldsCount % 2 == 0) {
                if ($fieldsCount > 0) {
                    $form .= '</div>';
                }
                $form .= '<div class="row">';
            }
            $fieldsCount++;

            switch ($templateField->slug) {
                case 'text':
                    if ($participant_id == 0) {
                        $form .= $this->createTextFieldLabel($templateField->label_en, '');
                    } else {
                        $form .= $this->createTextFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'number':
                    if ($participant_id == 0) {
                        $form .= $this->createNumberFieldLabel( $templateField->label_en, '');
                    } else {
                        $form .= $this->createNumberFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextAreaLabel($templateField->label_en, $templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if ($participant_id == 0) {
                        $form .= $this->createDateLabel($templateField->label_en,  '');
                    } else {
                        $form .= $this->createDateLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'select':
                    if ($participant_id == 0) {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $options, '');
                    } else {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->template_field_id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $options, $templateField->value);
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if ($participant_id == 0) {
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, '');
                        $form .= $this->createHiddenFieldLabel($templateField->label_en, '');
                    } else {

                        if($templateField->label_en == 'Personal Image'){
                            $image = $this->createPersonalImage($templateField->value);
                            $form = $image.$form;
                        }else{
                            $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, $templateField->value);
                        	$form .= $this->createHiddenFieldLabel($templateField->label_en, $templateField->value);
                        }
                    }
                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }
        $buttons = '';
        if($event->status < 3){
            switch ($status) {
                case 1:
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="' . route('eventParticipantAdd', [$participant_id,$company->id,$event->id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $buttons .= '&nbsp;&nbsp;';
                    break;
                case 2:
                    $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Aprrove</a>';
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject</a>';
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Return for correction</a>';
                    break;
            }
        }
        return view('pages.EventAdmin.event-participant-details')->with('form', $form)->with('attachmentForm', $attachmentForm)->with('companyId', $participant->company_id)->with('eventId', $participant->event_id)->with('buttons', $buttons)->with('event_name',$event_name)->with('company_name', $company_name);
    }

public function createStatusFieldLabel($label, $value)
    {
        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" value="'. $value .'" disabled/>';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createHiddenFieldLabel($id, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextFieldLabel($label, $value)
    {
	     $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" value="'. $value .'" disabled/>';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createNumberFieldLabel($label, $value)
    {
        $numberfield = '<div class="col-md-6"><div class="form-group col">';
        $numberfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $numberfield .= '<input type="text" value="'. $value .'" disabled/>';
        $numberfield .= '</div></div></div>';

        return $numberfield;
    }

    public function createTextAreaLabel( $label, $value)
    {
        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea disabled>' . $value . '</textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createDateLabel($label, $value)
    {
        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="text" value="'. $value .'" disabled/>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createSelectLabel($label, $elements, $value)
    {
        $selectValue = '';
        foreach ($elements as $element) {
            if ($element->key == $value) {
                $selectValue = $element->value;
            }
        }

    
    	$selectfield = '<div class="col-md-6"><div class="form-group col">';
        $selectfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectfield .= '<input type="text" value="'. $selectValue .'" disabled/>';
        $selectfield .= '</div></div></div>';

        return $selectfield;

    }

    public function createAttachmentLabel($label, $value)
    {
    	$textfield = '<div class="col-md-6"><div class="row"><div class="form-group col">';
        $textfield .= '<label>' . $label . "</label></div>";
        $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-label="' . $label . '"  data-src="' . $value . '" data-original-title="Preview" class="edit btn btn-danger preview-badge">Preview</a>';
        $textfield .= '<div class="col-md-6">' . $button . '</div>';
        $textfield .= '</div></div><div class="col-md-6"></div></div></div>';

        return $textfield;
    }

    public function createPersonalImage($value){
        $personalImage = '';
        $personalImage = $personalImage .'<div class="row>';
        $personalImage = $personalImage .'<div class="form-group col">';
        $personalImage = $personalImage .'<img id="paticipant_iamge" src="'. asset('badges/'.$value).'" alt="Personal" class="pic-img">';
        $personalImage = $personalImage .'</div></div>';
        return $personalImage;
    }

    public function eventParticipantAdd($participant_id,$companyId,$eventId)
    {
        $where = array('id' => $companyId);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $eventId);
        $event = Event::where($where)->first();

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }
        $fieldsCount = 0;
        $options = array();
        $form = '<div class="row">';
        $attachmentForm = '';
        $attachmentFormHidden = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenField('participant_id', 'participant_id', '');
        } else {
            $form .= $this->createHiddenField('participant_id', 'participant_id', $participant_id);
        }
        $form .= $this->createHiddenField('company_id', 'company_id', $companyId);
        $form .= $this->createHiddenField('event_id', 'event_id', $eventId);
        foreach ($templateFields as $templateField) {
            $options = [];
            if ($fieldsCount % 2 == 0) {
                if ($fieldsCount > 0) {
                    $form .= '</div>';
                }
                $form .= '<div class="row">';
            }
            $fieldsCount++;

            switch ($templateField->slug) {
                case 'text':
                    if ($participant_id == 0) {
                        if (strtolower($templateField->label_en) == 'company' or strtolower($templateField->label_en) == 'event') {
                            if (strtolower($templateField->label_en) == 'company') {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $company->name);
                            } else {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $event->name);
                            }
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        if (strtolower($templateField->label_en) == 'company' or strtolower($templateField->label_en) == 'event') {
                            $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'number':
                    if ($participant_id == 0) {
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextArea(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if ($participant_id == 0) {
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->mandatory, '');
                    } else {
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->mandatory, $templateField->value);
                    }
                    break;

                case 'select':
                    if (strtolower($templateField->label_en) == 'accreditation category') {
                        if ($participant_id == 0) {
                            $fieldElements = DB::select('select * from template_field_elements f inner join event_company_accrediation_categories_view e on e.accredit_cat_id = f.value_id where f.template_field_id = ? and e.size <> e.inserted and company_id =? and event_id = ?', [$templateField->id,$companyId,$eventId]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, '');
                        } else {
                            $fieldElements = DB::select('select * from template_field_elements f inner join event_company_accrediation_categories_view e on e.accredit_cat_id = f.value_id where f.template_field_id = ? and company_id =? and event_id = ?', [$templateField->template_field_id,$companyId,$eventId]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, $templateField->value);
                        }
                    }else{
                        if ($participant_id == 0) {
                            $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->id]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, '');
                        } else {
                            $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->template_field_id]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, $templateField->value);
                        } 
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if ($participant_id == 0) {
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, 0, '');
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, '');
                    } else {
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, 0, $templateField->value);
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                    }


                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }

        $subCompany_nav = 1;
        return view('pages.EventAdmin.event-Participant-Add')->with('form', $form)->with('attachmentForm', $attachmentForm)->with('subCompany_nav', $subCompany_nav)->with('companyId',$companyId)->with('eventId',$eventId);
    }

    public function createHiddenField($id, $label, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextField($id, $label, $mandatory, $min_char, $max_char, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if ($min_char) {
            $minChar = '" minlength="' . $min_char;
        }
        if ($max_char) {
            $maxChar = '"maxlength="' . $max_char;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . $minChar . $maxChar . '"' . $required . ' value="' . $value . '" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createNumberField($id, $label, $mandatory, $min_value, $max_value, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if ($min_value) {
            $minChar = '" min="' . $min_value;
        }
        if ($max_value) {
            $maxChar = '"max="' . $max_value;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="number" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . $minChar . $maxChar . '"' . $required . ' value="' . $value . '" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createTextArea($id, $label, $mandatory)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . '></textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createDate($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="date" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . ' value="' . $value . '" />';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createSelect($id, $label, $elements, $value)
    {
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  id="' . $id . '" name="' . $label . '">';
        foreach ($elements as $element) {
            $selectField .= '<option ';
            if ($element->key == $value) {
                $selectField .= ' selected="selected"';
            }
            $selectField .= ' value="' . $element->key . '">' . $element->value . '</option>';
        }

        $selectField .= '</select></div></div></div>';
        return $selectField;
    }

    public function createAttachment($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $attachmentField = '<form id=form_' . $id . '" name="badgeForm" class="form-horizontal  img-upload" enctype="multipart/form-data" action="javascript:void(0)">';
        $attachmentField .= '<div class="row"><div class="col-md-5"><label>' . $label . '</label></div>';
        $attachmentField .= '<div class="col-md-4"><div class="col-sm-12"><input type="file" id="file_' . $id . '" name=file_"' . $id . '" required=""></div></div>';
        $attachmentField .= '<div class="col-md-3"><button type="submit" id="btn-upload_' . $id . '" value="Upload">Upload</button></div></div>';
        $attachmentField .= '<div class="row"><div class="col-md-12"><div class="form-group col">';
        $attachmentField .= '<label id="file_type_error_' . $id . '"></label><div style="background-color: #ffffff00!important;" class="progress">';
        $attachmentField .= '<div id="file-progress-bar_' . $id . '" class="progress-bar"></div></div></div></div></div></form>';

        return $attachmentField;
    }

    public function createMultiSelect($id, $label, $elements)
    {
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  multiple id="' . $id . '" name="' . $label . '[]">';
        foreach ($elements as $element) {
            $selectField .= '<option value="' . $element->key . '">' . $element->value . '</option>';
        }

        $selectField .= '</select></div></div></div>';
        return $selectField;
    }

    public function eventStoreParticipant(Request $request)
    {
    	$dataTableColumuns = array();

        $where = array('id' => $request->event_id);
        $event = Event::where($where)->get()->first();
        if($request->company_id != 0){
            $where = array('id' => $request->company_id);
            $company = Company::where($where)->get()->first();
            //$company_admin_id = $company->id;
        }
        // }else{
        //     $company_admin_id = $event->id;
        // }
        $company_admin_id = $event->id;
        // $where = array('template_id' => $event->event_form);
        // $templateFields = TemplateField::where($where)->get()->all();
    	$where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        //Schema::dropIfExists('temp_' . $company_admin_id);
        if(!Schema::hasTable('temp_' . $company_admin_id)){
            Schema::create('temp_' . $company_admin_id, function ($table) use ($templateFields) {
                $table->string('id');
                foreach ($templateFields as $templateField) {
                    $dataTableColumuns[] = $templateField->label_en;
                    $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
                }
            });
        }
        $participant_id = $request->participant_id;
        $status = 6;
        $approval_option = $event->approval_option;
        if($approval_option != 1){
            $status = 1; 
        }
        $companyStaff = CompanyStaff::updateOrCreate(['id' => $participant_id],
            ['event_id' => $request->event_id,
                'company_id' => $request->company_id,
                'security_officer_id' => '0',
                'security_officer_decision' => '0',
                'security_officer_decision_date' => null,
                'security_officer_reject_reason' => '',
                'event_admin_id' => '0',
                'event_admin_decision' => '0',
                'event_admin_decision_date' => null,
                'event_admin_reject_reason' => '',
                'status' => $status
            ]);
        $staff = CompanyStaff::updateOrCreate(['id' => $companyStaff->id],
            ['identifier'=> '#'. md5($request->event_id.'-'.$request->company_id.'-'.$companyStaff->id)
            ]);
    
        $where = array('id' => $request->event_id);
        $event = Event::where($where)->get()->first();
        $query = "update templates t set t.is_locked = 1 where t.id = '" . $event->event_form."'";
        DB::update($query);
        $data = $request->all();


		foreach ($data as $key => $value) {
            if ($key != 'participant_id') {
                if ($participant_id != null) {
                    if($key == 'Accreditation_category'){
                        $staffdata = StaffData::where(['staff_id'=>$companyStaff->id,'key'=>$key])->first();
                        if($staffdata->value != $value){
                            $query = 'update company_accreditaion_categories set inserted = inserted - 1 where accredit_cat_id = ? and company_id = ? and event_id = ?';
                            DB::update($query,[$staffdata->value,$request->company_id,$request->event_id]);
                            $query = 'update company_accreditaion_categories set inserted = inserted + 1 where accredit_cat_id = ? and company_id = ? and event_id = ?';
                            DB::update($query,[$value,$request->company_id,$request->event_id]);
                        }  
                    }
                    $query = 'update staff_data s set s.value = "' . $value . '" where s.staff_id = ' . $companyStaff->id . ' and s.key ="' . $key . '" ';
                    DB::update($query);
                } else {
                    $staffData = StaffData::updateOrCreate(['staff_id' => $companyStaff->id, 'key' => $key],
                        ['staff_id' => $companyStaff->id,
                            'key' => $key,
                            'value' => $value
                        ]);
                        if($key == 'Accreditation_category'){
                            $query = 'update company_accreditaion_categories set inserted = inserted + 1 where accredit_cat_id = ? and company_id = ? and event_id = ?';
                            DB::update($query,[$value,$request->company_id,$request->event_id]);
                        }

                }
            }
        }
    	if ($request->company_id == 0) {
            $where = array('event_id' => $request->event_id,'id'=> $companyStaff->id);
        } else {
            $where = array('event_id' => $request->event_id, 'company_id' => $company->id, 'id' => $companyStaff->id);
        }
        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            if ($request->company_id != 0) {
                $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            } else {
                $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            }
            //$staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            $staffDataValues = array();
            foreach ($staffDatas as $staffData) {
                if ($staffData->slug == 'select') {
                    $where = array('template_field_id' => $staffData->template_field_id, 'value_id' => $staffData->value);
                    $value = TemplateFieldElement::where($where)->first();
                    $staffDataValues[] = $value->value_en;
                } else {
                    $staffDataValues[] = $staffData->value;
                }
            }
            $staffDataValues[] = $companyStaff->id;
            $alldata[] = $staffDataValues;
        }
        $query = '';
        foreach ($alldata as $data) {
            if ($participant_id != null) {
                $query = 'update `temp_' . $company_admin_id.'` set ';
                foreach ($templateFields as $templateField) {
                    $query = $query. preg_replace('/\s+/', '_', $templateField->label_en). ' = ? ,' ;
                }
                $query = substr($query, 0, strlen($query) - 1);
                $query = $query. ' where id = ?';
                DB::update($query,$data);
            }else{
                $query = 'insert into `temp_' . $company_admin_id . '` (';
                $tries = 0;
                foreach ($templateFields as $templateField) {
                    if($tries == 0){
                        $query = $query . preg_replace('/\s+/', '_', $templateField->label_en);
                        $tries = 1;
                    }else{
                        $query = $query . ',' . preg_replace('/\s+/', '_', $templateField->label_en);
                    }
                }
                $query = $query . ',id) values (';
                foreach ($data as $staffDataValue) {
                    $query = $query . '"' . $staffDataValue . '",';
                }
                $query = substr($query, 0, strlen($query) - 1);
                $query = $query . ')';
                DB::insert($query);
            }
        }

        if ($approval_option != 1) {
            $event_security_officers = DB::select('select * from event_security_officers_view e where e.id=?',[$event->id]);
            //DB::update('update company_staff set status = ? where id = ?', [1, $companyStaff->id]);
            foreach ($event_security_officers as $event_security_officer){                
                $notification_type = Config::get('enums.notification_types.PAP');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $event_security_officer->security_officer_id, $companyStaff->id,
                    $event->name . ': ' . $company->name . ': ' . 'Participant approval',
                    Route('securityParticipantDetails' , $companyStaff->id));
            }
        }else{
            app('App\Http\Controllers\GenerateBadgeController')->generate($companyStaff->id);
        }

        return Response::json($companyStaff);
    }


}
