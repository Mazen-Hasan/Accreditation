<?php

namespace App\Http\Controllers;

use App\Http\Traits\ConditionTrait;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompany;
use App\Models\EventCompanyDataEntry;
use App\Models\EventSecurityCategory;
use App\Models\EventSecurityOfficer;
use App\Models\EventType;
use App\Models\SecurityCategory;
use App\Models\SelectOption;
use App\Models\Template;
use App\Models\CompanyAccreditaionCategory;
use App\Models\EventAccreditationCategory;
use DateTime;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Traits\LogTrait;


class EventController extends Controller
{
    public function getData($all, $values){
        $whereStatusCondition = '';
        if($all == 1){
            $whereStatusCondition = 'where 1=1 ';
        }
        else{
            $whereStatusCondition = ' where e.status < 4';
        }
        $totalSize = DB::select('select * from events_view e ' . $whereStatusCondition);
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
                        $whereCondition =  $whereCondition." and ".ConditionTrait::getConditionPart($token,$condition1,$condition1token) . " ".$operator ." ". ConditionTrait::getConditionPart($token,$condition2,$condition2token);
                    }else{
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $whereCondition = $whereCondition." and ".ConditionTrait::getConditionPart($token,$condition1,$condition1token);
                    }
                    $i = $i + 1;
                }
                $totalSize = DB::select('select * from events_view e ' . $whereStatusCondition . $whereCondition);
                $events = DB::select('select * from events_view e ' . $whereStatusCondition. $whereCondition." LIMIT ". $size. " OFFSET ". $skip);
            }else{
                $skip = $size * $values;
                $events = DB::select("select * from events_view e " . $whereStatusCondition . " LIMIT ". $size. " OFFSET ". $skip);
            }
        }

        return Response::json(array(
            'success' =>true,
            'code' => 1,
            'size' => round(sizeof($totalSize)/2),
            'events' => $events,
            'message' => 'hi'
        ));
    }


    public function index()
    {
        if (request()->ajax()) {
            $events = DB::select('select * from events_view e where e.status < 4');
            return datatables()->of($events)

                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('EventController.show', $data->id) . '" data-toggle="tooltip"  id="event-details" data-id="' . $data->id . '" data-original-title="Details" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($data->status < 3){
                        $button .= '<a href="' . route('eventEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0);" id="edit-logo" data-toggle="tooltip" data-id="' . $data->id  . '" data-name="' . $data->name . '" data-l="' . $data->logo . '" data-original-title="Edit" title="Edit Logo"><i class="far fa-image"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    $button .= '<a href="' . route('eventSecurityCategories', $data->id) . '" data-toggle="tooltip"  id="event-security-categories" data-id="' . $data->id . '" data-original-title="Edit" title="Event security categories"><i class="fas fa-users-cog"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventAdmins', $data->id) . '" data-toggle="tooltip"  id="event-admins" data-id="' . $data->id . '" data-original-title="Edit" title="Event admins"><i class="fas fa-user-cog"></i></a>';
                    if($data->approval_option != 1){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('eventSecurityOfficers', $data->id) . '" data-toggle="tooltip"  id="event-security-officers" data-id="' . $data->id . '" data-original-title="Edit" title="Event security officers"><i class="fas fa-user-shield"></i></a>';
                    }
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventAccreditationCategories', $data->id) . '" data-toggle="tooltip"  id="event-accreditation-categories" data-id="' . $data->id . '" data-original-title="Edit" title="Event accreditation categories"><i class="fas fa-users"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($data->status == 3){
                        $button .= '<a href="javascript:void(0);" id="complete-event" data-toggle="tooltip" data-id="' . $data->id . '" data-name="' . $data->name . '" data-original-title="Edit" title="Complete Events"><i class="fas fa-list-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Event.events');
    }

	public function showAll($status)
    {
        if (request()->ajax()) {
            $events = DB::select('select * from events_view');
            return datatables()->of($events)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('EventController.show', $data->id) . '" data-toggle="tooltip"  id="event-details" data-id="' . $data->id . '" data-original-title="Details" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($data->status < 3){
                        $button .= '<a href="' . route('eventEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    $button .= '<a href="' . route('eventSecurityCategories', $data->id) . '" data-toggle="tooltip"  id="event-security-categories" data-id="' . $data->id . '" data-original-title="Edit" title="Event security categories"><i class="fas fa-users-cog"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventAdmins', $data->id) . '" data-toggle="tooltip"  id="event-admins" data-id="' . $data->id . '" data-original-title="Edit" title="Event admins"><i class="fas fa-user-cog"></i></a>';
                    if($data->approval_option != 1){
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('eventSecurityOfficers', $data->id) . '" data-toggle="tooltip"  id="event-security-officers" data-id="' . $data->id . '" data-original-title="Edit" title="Event security officers"><i class="fas fa-user-shield"></i></a>';
                    }
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventAccreditationCategories', $data->id) . '" data-toggle="tooltip"  id="event-accreditation-categories" data-id="' . $data->id . '" data-original-title="Edit" title="Event accreditation categories"><i class="fas fa-users"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($data->status == 3){
                        $button .= '<a href="javascript:void(0);" id="complete-event" data-toggle="tooltip"  data-id="' . $data->id . '" data-name="' . $data->name . '" data-original-title="Edit" title="Complete Events"><i class="fas fa-list-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function eventAdmins($event_id)
    {
        $where = array('id' => $event_id);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $event_admins = DB::select('select * from event_admins_info_view where event_id=?',[$event_id]);
            return datatables()->of($event_admins)
                ->addColumn('action', function ($data) use ($event) {
                    $button = '&nbsp;&nbsp;';
                    if($event->status < 3){
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-admin"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //$event_status = $event->status;
        //$event_admins = DB::select('select * from event_admin_users_view e where e.user_id NOT in (select ea.user_id from event_admins ea where ea.event_id = ? )',[$event_id]);
    	$event_admins = DB::select('select * from event_admin_users_view');
        return view('pages.Event.eventAdmins')->with('event', $event)->with('eventAdmins',$event_admins);
    }

    public function eventAdminsAdd(Request $request)
    {
    	try{
        $post = EventAdmin::updateOrCreate(['id' => 0],
            ['event_id' => $request->event_id,
                'user_id' => $request->admin_id,
            ]);
        }catch (\Exception $e) {
            return Response::json(array(
                'code' => 400,
                'message' => $e->getMessage()
            ), 400);
        }
		$event = Event::where(['id' => $request->event_id,])->first();
        $notification_type = Config::get('enums.notification_types.AEA');
        NotificationController::sendNotification($notification_type, $event->name, '', $request->admin_id, 0, $event->name . ':' . 'Event assignment',
            Route('eventCompanies', [$request->event_id]));

        return Response::json($post);
    }

    public function eventAdminsRemove($event_admin_id)
    {
        $where = array('id' => $event_admin_id);
        $post = EventAdmin::where($where)->delete();
        return Response::json($post);
    }

    public function eventSecurityOfficers($event_id)
    {
        $where = array('id' => $event_id);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $eventsecurity_officer = DB::select('select * from event_security_officers_info_view where event_id=?',[$event_id]);
            return datatables()->of($eventsecurity_officer)
                ->addColumn('action', function ($data) use ($event)  {
                    $button = '&nbsp;&nbsp;';
                    if($event->status < 3){
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-security-officer"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //$security_officer = DB::select('select * from security_officer_users_view s where s.user_id NOT in (select eso.user_id from event_security_officers eso where eso.event_id = ? )',[$event_id]);
    	$security_officer = DB::select('select * from security_officer_users_view');
        return view('pages.Event.eventSecurityOfficers')->with('event', $event)->with('securityOfficers',$security_officer);
    }

    public function eventSecurityOfficersAdd(Request $request)
    {
    	try{
        $post = EventSecurityOfficer::updateOrCreate(['id' => 0],
            ['event_id' => $request->event_id,
                'user_id' => $request->security_officer_id,
            ]);
    	}catch (\Exception $e) {
            return Response::json(array(
                'code' => 400,
                'message' => $e->getMessage()
            ), 400);
        }
        $event = Event::where(['id' => $request->event_id,])->first();

//        NotificationController::sendAlertNotification($request->security_officer_id, 0, $event->name . ':' . 'Event assignment', Route('securityOfficerCompanies', [$request->event_id]));

        $notification_type = Config::get('enums.notification_types.ASO');
        NotificationController::sendNotification($notification_type, $event->name, '', $request->security_officer_id, 0, $event->name . ':' . 'Event assignment',
            Route('securityOfficerCompanies', [$request->event_id]));

        return Response::json($post);
    }

    public function eventSecurityOfficersRemove($security_officer_id)
    {
        $where = array('id' => $security_officer_id);
        $post = EventSecurityOfficer::where($where)->delete();
        return Response::json($post);
    }

    public function eventSecurityCategories($event_id)
    {
        $where = array('id' => $event_id);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $eventsecurity_Categories = DB::select('select * from event_security_categories_info_view where event_id=?',[$event_id]);
            return datatables()->of($eventsecurity_Categories)
                ->addColumn('action', function ($data) use ($event) {
                    $button = '&nbsp;&nbsp;';
                    if($event->status < 3){
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-security-category"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $security_Categories = DB::select('select * from security_categories sc where sc.id NOT in (select esc.security_category_id from event_security_categories esc where esc.event_id = ? )',[$event_id]);
        return view('pages.Event.eventSecurityCategories')->with('event', $event)->with('securityCategories',$security_Categories);
    }

    public function eventSecurityCategoriesAdd(Request $request)
    {
        $post = EventSecurityCategory::updateOrCreate(['id' => 0],
            ['event_id' => $request->event_id,
                'security_category_id' => $request->security_category_id,
            ]);
        return Response::json($post);
    }

    public function eventSecurityCategoriesRemove($security_category_id)
    {
        $where = array('id' => $security_category_id);
        $post = EventSecurityCategory::where($where)->delete();
        return Response::json($post);
    }

    public function eventCheckSameEventOrganizer($organizerId)
    {
        $where = array('organizer' =>  $organizerId);
        $event = Event::where($where)->get()->first();

        if ($event){
            return Response()->json([
                "success" => true,
                "exist" => 1
            ]);
        }
        else
        {
            return Response()->json([
                "success" => true,
                "exist" => 0
            ]);
        }
    }

    public function store(Request $request)
    {
        $postId = $request->post_id;
        $event_end_date = $request->event_end_date;
        $event_start_date = $request->event_start_date;
        $datetime1 = new DateTime($event_end_date);
        $datetime2 = new DateTime($event_start_date);
        $interval = $datetime1->diff($datetime2);
        $period_days = $interval->format('%a');
        $accreditation_end_date = $request->accreditation_end_date;
        $accreditation_start_date = $request->accreditation_start_date;
        $datetime1 = new DateTime($accreditation_end_date);
        $datetime2 = new DateTime($accreditation_start_date);
        $interval = $datetime1->diff($datetime2);
        $accredition_period_days = $interval->format('%a');

        $event_companies  =  null;
    	$company_accreditation_categories = null;
        if($postId == null){
            $where = array('organizer' =>  $request->organizer);
            $event = Event::where($where)->get()->last();
			if($event != null){
            	$where = array('event_id' =>  $event->id);
            	$event_companies = EventCompany::where($where)->get()->all();
            	$event_company_data_entries = EventCompanyDataEntry::where($where)->get()->all();
            }
        }

        $action_id = Config::get('actionEnum.actions.event-add');

        $action_result ='';
        $params = 'eventName=' . $request->name . ', location=' . $request->location . ', size=' . $request->size .
        ', organizer=' . $request->organizer . ', owner=' . $request->owner . ', event_type=' . $request->event_type .
        ', period=' . $period_days . ', accreditation_period=' . $accredition_period_days . ', status=' . $request->status .
        ', approval_option=' . $request->approval_option . ', event_form=' . $request->event_form .
        ', event_start_date=' . $request->event_start_date . ', event_end_date=' . $request->event_end_date .
        ', accreditation_start_date=' . $request->accreditation_start_date . ', accreditation_end_date=' . $request->accreditation_end_date;

        try{
            if($postId != null){
                $mEvent = Event::where(['id'=>$postId])->get()->first();
                $eventSize = $mEvent->size;
                if($eventSize > $request->size){
                    DB::update('update event_companies set size = 0 where event_id =? ',[$postId]);
                    DB::delete('delete from company_accreditaion_categories where event_id = ?',[$postId]);
                }
            }
            $post = Event::updateOrCreate(['id' => $postId],
                ['name' => $request->name,
                    'location' => $request->location,
                    'size' => $request->size,
                    'organizer' => $request->organizer,
                    'owner' => $request->owner,
                    'event_type' => $request->event_type,
                    'period' => $period_days,
                    'accreditation_period' => $accredition_period_days,
                    'status' => $request->status,
                    'approval_option' => $request->approval_option,
                    'event_form' => $request->event_form,
                    'event_start_date' => $request->event_start_date,
                    'event_end_date' => $request->event_end_date,
                    'accreditation_start_date' => $request->accreditation_start_date,
                    'accreditation_end_date' => $request->accreditation_end_date,
                    'creator' => Auth::user()->id
                ]);

            $action_result = Config::get('resultEnum.results.SUCCESS');
            LogTrait::supperAdminLog($action_id, $action_result, $params, 'No');

        } catch (\Exception $e) {
            $action_result = Config::get('resultEnum.results.FAILED');
            LogTrait::supperAdminLog($action_id, $action_result, $params, $e->getMessage());
        }

        if ($postId == null) {
            $counter = 1;
            if($request->security_categories != null){
                foreach ($request->security_categories as $security_category) {
                    $help = EventSecurityCategory::updateOrCreate(['id' => $postId],
                        ['event_id' => $post->id,
                            'security_category_id' => $security_category,
                            'order' => $counter,
                            'creator' => $request->creator
                        ]);
                    $counter = $counter + 1;
                }
            }
            if($request->event_admins != null){
                foreach ($request->event_admins as $event_admin) {
                    $help = EventAdmin::updateOrCreate(['id' => $postId],
                        ['event_id' => $post->id,
                            'user_id' => $event_admin
                        ]);
                        $event = Event::where(['id'=>$post->id])->first();
                        // NotificationController::sendAlertNotification($event_admin, 0, $event->name . ':' . 'Event assignment', Route('eventCompanies' , [$post->id]));

                        $notification_type = Config::get('enums.notification_types.EIN');
                        NotificationController::sendNotification($notification_type, $event->name, '', $event_admin, 0,
                        $event->name . ':' . 'Event assignment', Route('eventCompanies', [$post->id]));
                }
            }
            if($request->security_officers != null){
                foreach ($request->security_officers as $security_officer) {
                    $help = EventSecurityOfficer::updateOrCreate(['id' => $postId],
                        ['event_id' => $post->id,
                            'user_id' => $security_officer
                        ]);
                        $event = Event::where(['id'=>$post->id])->first();
                        // NotificationController::sendAlertNotification($security_officer, 0, $event->name . ':' . 'Event assignment', Route('securityOfficerCompanies' , [$post->id]));

                        $notification_type = Config::get('enums.notification_types.EIN');
                        NotificationController::sendNotification($notification_type, $event->name, '', $security_officer, 0,
                        $event->name . ':' . 'Event assignment', Route('securityOfficerCompanies', [$post->id]));
                }
            }
			if($event_companies != null){
            	foreach ($event_companies as $row){
                	$new_event_company = EventCompany::updateOrCreate(['id' => 0],
                    	['event_id' => $post->id,
                        'company_id' => $row->company_id,
                        'focal_point_id' => $row->focal_point_id,
                        'parent_id' => $row->parent_id,
                        'status' => 1,
                        'size' => 0,
                        'need_management' => 0,
                    	]);

                	foreach ($event_company_data_entries as $row1){
                    	if($row1->company_id == $row->company_id){
                        	$new_event_company_data_entry = EventCompanyDataEntry::updateOrCreate(['id' => 0],
                            	['event_id' => $post->id,
                                'company_id' => $row1->company_id,
                                'event_companies_id' => $new_event_company->id,
                                'data_entry_id' => $row1->data_entry_id,
                                'status' => 1,
                            ]);
                    	}
                	}
                	// if($company_accreditation_categories != null){
                    // 	foreach ($company_accreditation_categories as $row2) {
                    //     	if ($row2->company_id == $row->company_id) {
                    //         	$new_company_accreditation_category = CompanyAccreditaionCategory::updateOrCreate(['id' => 0],
                    //             	['event_id' => $post->id,
                    //               	  'company_id' => $row2->company_id,
                    //               	  'accredit_cat_id' => $row2->accredit_cat_id,
                    //               	  'parent_id' => $row2->parent_id,
                    //               	  'size' => 0,
                    //               	  'status' => 0,
                    //               	  'event_company_id' => $new_event_company->id,
                    //               	  'inserted' => 0,
                    //             	]);
                    //     	}
                    // 	}
                    // }
            	}
            }

        }
        return Response::json($post);
    }

    public function eventAdd()
    {
        $sql = 'select CONCAT(COALESCE(c.name,"")," ",COALESCE(c.middle_name,"")," ",COALESCE(c.last_name,"")) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Organizer")';
        $query = $sql;
        $contacts = DB::select($query);
        $organizersSelectOption = array();
        foreach ($contacts as $contact) {
            $organizerSelectOption = new SelectOption($contact->id, $contact->name);
            $organizersSelectOption[] = $organizerSelectOption;
        }

//        $sql = 'select CONCAT(COALESCE(c.name,"")," ",COALESCE(c.middle_name,"")," ",COALESCE(c.last_name,"")) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
//        $query = $sql;
//        $contacts = DB::select($query);
//        $ownersSelectOption = array();
//        foreach ($contacts as $contact) {
//            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
//            $ownersSelectOption[] = $ownerSelectOption;
//        }

        $securityCategories = SecurityCategory::get()->where('status', '=', '1');
        $securityCategoriesSelectOption = array();
        foreach ($securityCategories as $securityCategory) {
            $securityCategorieSelectOption = new SelectOption($securityCategory->id, $securityCategory->name);
            $securityCategoriesSelectOption[] = $securityCategorieSelectOption;
        }

        $eventTypes = EventType::get()->where('status', '=', '1');
        $eventTypesSelectOption = array();
        foreach ($eventTypes as $eventType) {
            $eventTypeSelectOption = new SelectOption($eventType->id, $eventType->name);
            $eventTypesSelectOption[] = $eventTypeSelectOption;
        }

        $sql = 'SELECT u.id, u.name FROM users u join users_roles ur on u.id = ur.user_id join roles r on ur.role_id = r.id where r.slug = "event-admin"';
        $eventAdminUsers = DB::select($sql);
        $eventAdmins = array();
        foreach ($eventAdminUsers as $eventAdminUser) {
            $eventAdminSelectOption = new SelectOption($eventAdminUser->id, $eventAdminUser->name);
            $eventAdmins[] = $eventAdminSelectOption;
        }

        $sql = 'SELECT u.id, u.name FROM users u join users_roles ur on u.id = ur.user_id join roles r on ur.role_id = r.id where r.slug = "security-officer"';
        $securityOfficerUsers = DB::select($sql);

        $securityOfficers = array();
        foreach ($securityOfficerUsers as $securityOfficerUser) {
            $securityOfficerSelectOption = new SelectOption($securityOfficerUser->id, $securityOfficerUser->name);
            $securityOfficers[] = $securityOfficerSelectOption;
        }

        $approvalOption1 = new SelectOption(1, 'Event Admin Approval');
        $approvalOption2 = new SelectOption(2, 'Security Officer Approval');
        $approvalOption3 = new SelectOption(3, 'Both');
        $approvalOptions = [$approvalOption1, $approvalOption2, $approvalOption3];

        $eventStatus1 = new SelectOption(1, 'Active');
        $eventStatus2 = new SelectOption(2, 'InActive');
        $eventStatuss = [$eventStatus1, $eventStatus2];

         $templates = DB::select('select * from available_templates_view');
        $templatesSelectOption = array();
        foreach ($templates as $template) {
            $templateSelectOption = new SelectOption($template->id, $template->name);
            $templatesSelectOption[] = $templateSelectOption;
        }

        return view('pages.Event.event-add')->with('organizers', $organizersSelectOption)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions', $approvalOptions)->with('eventTypes', $eventTypesSelectOption)
            ->with('eventStatuss', $eventStatuss)->with('eventForms', $templatesSelectOption)->with('securityCategories', $securityCategoriesSelectOption);
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $event = Event::where($where)->first();

        $sql = 'select CONCAT(COALESCE(c.name,"")," ",COALESCE(c.middle_name,"")," ",COALESCE(c.last_name,"")) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Organizer")';
        $query = $sql;
        $contacts = DB::select($query);
        $organizersSelectOption = array();
        foreach ($contacts as $contact) {
            $organizerSelectOption = new SelectOption($contact->id, $contact->name);
            $organizersSelectOption[] = $organizerSelectOption;
        }
//        $sql = 'select CONCAT(COALESCE(c.name,"")," ",COALESCE(c.middle_name,"")," ",COALESCE(c.last_name,"")) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
//        $query = $sql;
//        $contacts = DB::select($query);
//        $ownersSelectOption = array();
//        foreach ($contacts as $contact) {
//            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
//            $ownersSelectOption[] = $ownerSelectOption;
//        }

        $eventTypes = EventType::get()->where('status', '=', '1');
        $eventTypesSelectOption = array();
        foreach ($eventTypes as $eventType) {
            $eventTypeSelectOption = new SelectOption($eventType->id, $eventType->name);
            $eventTypesSelectOption[] = $eventTypeSelectOption;
        }

        $securityCategories = SecurityCategory::get()->where('status', '=', '1');
        $securityCategoriesSelectOption = array();
        foreach ($securityCategories as $securityCategory) {
            $securityCategorieSelectOption = new SelectOption($securityCategory->id, $securityCategory->name);
            $securityCategoriesSelectOption[] = $securityCategorieSelectOption;
        }

        $sql = 'SELECT u.id, u.name FROM users u join users_roles ur on u.id = ur.user_id join roles r on ur.role_id = r.id where r.slug = "event-admin"';
        $eventAdminUsers = DB::select($sql);
        $eventAdmins = array();
        foreach ($eventAdminUsers as $eventAdminUser) {
            $eventAdminSelectOption = new SelectOption($eventAdminUser->id, $eventAdminUser->name);
            $eventAdmins[] = $eventAdminSelectOption;
        }

        $sql = 'SELECT u.id, u.name FROM users u join users_roles ur on u.id = ur.user_id join roles r on ur.role_id = r.id where r.slug = "security-officer"';
        $securityOfficerUsers = DB::select($sql);

        $securityOfficers = array();
        foreach ($securityOfficerUsers as $securityOfficerUser) {
            $securityOfficerSelectOption = new SelectOption($securityOfficerUser->id, $securityOfficerUser->name);
            $securityOfficers[] = $securityOfficerSelectOption;
        }

        $approvalOption1 = new SelectOption(1, 'Event Admin Approval');
        $approvalOption2 = new SelectOption(2, 'Security Officer Approval');
        $approvalOption3 = new SelectOption(3, 'Both');
        $approvalOptions = [$approvalOption1, $approvalOption2, $approvalOption3];

        $eventStatus1 = new SelectOption(1, 'Active');
        $eventStatus2 = new SelectOption(2, 'InActive');
        $eventStatuss = [$eventStatus1, $eventStatus2];


        $templates = Template::get()->where('status', '=', '1');
        $templatesSelectOption = array();
        foreach ($templates as $template) {
            $templateSelectOption = new SelectOption($template->id, $template->name);
            $templatesSelectOption[] = $templateSelectOption;
        }

        if (request()->ajax()) {
            $where = array('event_id' => $id);
            return datatables()->of(EventSecurityCategory::where($where)->get()->all())
                ->addColumn('name', function ($data) {
                    $result = '';
                    $where = array('id' => $data->security_category_id);
                    $securityCategory = SecurityCategory::where($where)->first();
                    $result = $securityCategory->name;
                    return $result;
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post" id="remove-event-security-category">Remove</a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.Event.event-edit')->with('organizers', $organizersSelectOption)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions', $approvalOptions)->with('eventTypes', $eventTypesSelectOption)
            ->with('eventStatuss', $eventStatuss)->with('eventForms', $templatesSelectOption)->with('event', $event)->with('securityCategories', $securityCategoriesSelectOption);;
    }

    public function updateLogo(Request $request){
        $event_id = $request->eventId;
        $logo = $request->logoName;

        $event = Event::updateOrCreate(['id'=>$event_id],
            [
                'logo' => $logo
            ]);

        return Response::json($event);
    }

    public function show($id)
    {
        $event = DB::select('select * from event_datals_view where id=?',[$id]);


        $securityCategories = DB::select('select * from  event_security_categories_info_view where event_id=?',[$id]);
        $securityCategoriesSelectOption = array();
        foreach ($securityCategories as $securityCategory) {
            $securityCategorieSelectOption = new SelectOption($securityCategory->id, $securityCategory->name);
            $securityCategoriesSelectOption[] = $securityCategorieSelectOption;
        }

        $eventAdminUsers = DB::select('select * from event_admins_info_view  where event_id=?',[$id]);
        $eventAdmins = array();
        foreach ($eventAdminUsers as $eventAdminUser) {
            $eventAdminSelectOption = new SelectOption($eventAdminUser->id, $eventAdminUser->name);
            $eventAdmins[] = $eventAdminSelectOption;
        }

        $securityOfficerUsers = DB::select('select * from event_security_officers_info_view where event_id=?',[$id]);
        $securityOfficers = array();
        foreach ($securityOfficerUsers as $securityOfficerUser) {
            $securityOfficerSelectOption = new SelectOption($securityOfficerUser->id, $securityOfficerUser->name);
            $securityOfficers[] = $securityOfficerSelectOption;
        }

        return view('pages.Event.event-details')->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('event', $event[0])
            ->with('securityCategories', $securityCategoriesSelectOption);;
    }

    public function remove($event_security_category_id)
    {
        $where = array('id' => $event_security_category_id);
        $post = EventSecurityCategory::where($where)->delete();
        return Response::json($post);
    }

    public function destroy($id)
    {
        $post = Event::where('id', $id)->delete();

        return Response::json($post);
    }

    public function storeEventSecurityCategory($event_id, $security_category_id)
    {
        $post = EventSecurityCategory::updateOrCreate(['id' => 0],
            ['event_id' => $event_id,
                'security_category_id' => $security_category_id,
                'order' => 100
            ]);
        return Response::json($post);
    }

	public function eventAccreditationCategories($event_id)
    {
        $where = array('id' => $event_id);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $eventaccreditation_categories = DB::select('select * from event_accreditation_categories_view where event_id=?', [$event_id]);
            return datatables()->of($eventaccreditation_categories)
                ->addColumn('action', function ($data) use ($event) {
                    $button = '&nbsp;&nbsp;';
                    if($event->status < 3){
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-accreditation-category"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $accreditation_categories = DB::select('select * from accreditation_categories');
        return view('pages.Event.eventAccreditationCategories')->with('event', $event)->with('accreditationCategories', $accreditation_categories);
    }

    public function eventAccreditationCategoriesAdd(Request $request)
    {
        try{
        $post = EventAccreditationCategory::updateOrCreate(['id' => 0],
            ['event_id' => $request->event_id,
                'accreditation_category_id' => $request->accreditation_category_id,
                'size' => 0,
                'status' => 1
            ]);
        }
        catch (\Exception $e) {
            return Response::json(array(
                'code' => 400,
                'message' => $e->getMessage()
            ), 400);
        }
        return Response::json($post);
    }

    public function eventAccreditationCategoriesRemove($security_category_id)
    {
        $where = array('id' => $security_category_id);
        $post = EventAccreditationCategory::where($where)->delete();
        return Response::json($post);
    }

    public function eventComplete($eventId)
    {
        $post = Event::updateOrCreate(['id'=>$eventId],
            [
                'status' => 4
            ]);

    	// $notification_type = Config::get('enums.notification_types.EIN');
        // NotificationController::sendNotification($notification_type, $event->name, $company->name, $focal_point[0]->account_id, 0,
        //     $event->name . ': ' . $company->name . ': ' . 'Event invitation', Route('companyParticipants' , [$companyId, $eventId]));

        return Response::json($post);
    }
}
