<?php

namespace App\Http\Controllers;


use App\Mail\EventAdminAssign;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventSecurityCategory;
use App\Models\EventSecurityOfficer;
use App\Models\EventType;
use App\Models\SecurityCategory;
use App\Models\SelectOption;
use App\Models\Template;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $events = DB::select('select * from events_view');
            return datatables()->of($events)

                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('EventController.show', $data->id) . '" data-toggle="tooltip"  id="event-details" data-id="' . $data->id . '" data-original-title="Details" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventSecurityCategories', $data->id) . '" data-toggle="tooltip"  id="event-security-categories" data-id="' . $data->id . '" data-original-title="Edit" title="Event security categories"><i class="fas fa-users-cog"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventAdmins', $data->id) . '" data-toggle="tooltip"  id="event-admins" data-id="' . $data->id . '" data-original-title="Edit" title="Event admins"><i class="fas fa-user-cog"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventSecurityOfficers', $data->id) . '" data-toggle="tooltip"  id="event-security-officers" data-id="' . $data->id . '" data-original-title="Edit" title="Event security officers"><i class="fas fa-user-shield"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Event.events');
    }

    public function eventAdmins($event_id)
    {
        if (request()->ajax()) {
            $event = DB::select('select * from event_admins_info_view where event_id=?',[$event_id]);
            return datatables()->of($event)

                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-admin"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $where = array('id' => $event_id);
        $event = Event::where($where)->first();

        $event_admins = DB::select('select * from event_admin_users_view e where e.user_id NOT in (select ea.user_id from event_admins ea where ea.event_id = ? )',[$event_id]);
        return view('pages.Event.eventAdmins')->with('event', $event)->with('eventAdmins',$event_admins);
    }

    public function eventAdminsAdd(Request $request)
    {
        $post = EventAdmin::updateOrCreate(['id' => 0],
            ['event_id' => $request->event_id,
                'user_id' => $request->admin_id,
            ]);

        if($post != null){
            $where = array('id' => $request->event_id);
            $event = Event::where($where)->first();

            NotificationController::sendNotification($event->name, '', $request->admin_id, '','', '/event-admin');
        }

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
        if (request()->ajax()) {
            $event = DB::select('select * from event_security_officers_info_view where event_id=?',[$event_id]);
            return datatables()->of($event)

                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-security-officer"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $where = array('id' => $event_id);
        $event = Event::where($where)->first();

        $security_officer = DB::select('select * from security_officer_users_view s where s.user_id NOT in (select eso.user_id from event_security_officers eso where eso.event_id = ? )',[$event_id]);
        return view('pages.Event.eventSecurityOfficers')->with('event', $event)->with('securityOfficers',$security_officer);
    }

    public function eventSecurityOfficersAdd(Request $request)
    {
        $post = EventSecurityOfficer::updateOrCreate(['id' => 0],
            ['event_id' => $request->event_id,
                'user_id' => $request->security_officer_id,
            ]);
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
        if (request()->ajax()) {
            $event = DB::select('select * from event_security_categories_info_view where event_id=?',[$event_id]);
            return datatables()->of($event)

                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-event-security-category"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $where = array('id' => $event_id);
        $event = Event::where($where)->first();

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
        $post = Event::updateOrCreate(['id' => $postId],
            ['name' => $request->name,
//                'event_admin' => $request->event_admin,
                'location' => $request->location,
                'size' => $request->size,
                'organizer' => $request->organizer,
                'owner' => $request->owner,
                'event_type' => $request->event_type,
                'period' => $period_days,
                'accreditation_period' => $accredition_period_days,
                'status' => $request->status,
                'approval_option' => $request->approval_option,
//                'security_officer' => $request->security_officer,
                'event_form' => $request->event_form,
                'event_start_date' => $request->event_start_date,
                'event_end_date' => $request->event_end_date,
                'accreditation_start_date' => $request->accreditation_start_date,
                'accreditation_end_date' => $request->accreditation_end_date,
                'creation_date' => $request->creation_date,
                'creator' => Auth::user()->id
            ]);
        if ($postId == null) {
            $counter = 1;
            foreach ($request->security_categories as $security_category) {
                $help = EventSecurityCategory::updateOrCreate(['id' => $postId],
                    ['event_id' => $post->id,
                        'security_category_id' => $security_category,
                        'order' => $counter,
                        'creation_date' => $request->creation_date,
                        'creator' => $request->creator
                    ]);
                $counter = $counter + 1;
            }

            foreach ($request->event_admins as $event_admin) {
                $help = EventAdmin::updateOrCreate(['id' => $postId],
                    ['event_id' => $post->id,
                        'user_id' => $event_admin
                    ]);
            }

            foreach ($request->security_officers as $security_officer) {
                $help = EventSecurityOfficer::updateOrCreate(['id' => $postId],
                    ['event_id' => $post->id,
                        'user_id' => $security_officer
                    ]);
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

        $sql = 'select CONCAT(COALESCE(c.name,"")," ",COALESCE(c.middle_name,"")," ",COALESCE(c.last_name,"")) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
        $query = $sql;
        $contacts = DB::select($query);
        $ownersSelectOption = array();
        foreach ($contacts as $contact) {
            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
            $ownersSelectOption[] = $ownerSelectOption;
        }

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

        $templates = Template::get()->where('status', '=', '1');
        $templatesSelectOption = array();
        foreach ($templates as $template) {
            $templateSelectOption = new SelectOption($template->id, $template->name);
            $templatesSelectOption[] = $templateSelectOption;
        }

        return view('pages.Event.event-add')->with('owners', $ownersSelectOption)->with('organizers', $organizersSelectOption)->with('eventAdmins', $eventAdmins)
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
        $sql = 'select CONCAT(COALESCE(c.name,"")," ",COALESCE(c.middle_name,"")," ",COALESCE(c.last_name,"")) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
        $query = $sql;
        $contacts = DB::select($query);
        $ownersSelectOption = array();
        foreach ($contacts as $contact) {
            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
            $ownersSelectOption[] = $ownerSelectOption;
        }

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
//                    if ($data->status == 1) {
//                        $button .= '<a href="javascript:void(0);" id="deActivate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">  Deactivate</a>';
//                    }else{
//                        $button .= '<a href="javascript:void(0);" id="activate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
//                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.Event.event-edit')->with('owners', $ownersSelectOption)->with('organizers', $organizersSelectOption)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions', $approvalOptions)->with('eventTypes', $eventTypesSelectOption)
            ->with('eventStatuss', $eventStatuss)->with('eventForms', $templatesSelectOption)->with('event', $event)->with('securityCategories', $securityCategoriesSelectOption);;
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

}
