<?php

namespace App\Http\Controllers;


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
                    $button = '<a href="' . route('eventEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Event.events');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

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

    /**
     * Show the form for editing the specified resource.
     *
     */
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

    public function remove($event_security_category_id)
    {
        $where = array('id' => $event_security_category_id);
        $post = EventSecurityCategory::where($where)->delete();
        return Response::json($post);
    }

//    public function removeEventSecurityCategory($event_id,$security_category_id)
//    {
//        //var_dump($event_id);
//        $where = array('event_id'=> $event_id, 'security_category_id'=> $security_category_id);
//        $post = EventSecurityCategory::where($where)->delete();
//        return Response::json($post);
//    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = Event::where('id', $id)->delete();

        return Response::json($post);
    }


    public function storeEventSecurityCategory($event_id, $security_category_id)
    {
        //xdebug_break();
//        $contactId = $request->post_id;
//        $titleId = $request->contactTitle;
        $post = EventSecurityCategory::updateOrCreate(['id' => 0],
            ['event_id' => $event_id,
                'security_category_id' => $security_category_id,
                'order' => 100
            ]);
        return Response::json($post);
    }

}
