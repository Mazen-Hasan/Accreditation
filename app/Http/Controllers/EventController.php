<?php

namespace App\Http\Controllers;


use App\Models\EventSecurityCategory;
use App\Models\EventType;
use App\Models\SecurityCategory;
use DB;
use App\Models\Event;
use App\Models\SelectOption;
use Illuminate\Http\Request;
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
        if(request()->ajax())
        {
            return datatables()->of(Event::latest()->get())
                ->addColumn('action', function($data){
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button = '<a href="'.route('eventEdit', $data->id).'" data-toggle="tooltip"  id="edit-event" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    //$button .= '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">   Delete</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.event.events');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //xdebug_break();
        $postId = $request->post_id;
        $post   =   Event::updateOrCreate(['id' => $postId],
            ['name' => $request->name,
                'event_admin' => $request->event_admin,
                'location' => $request->location,
                'size' => $request->size,
                'organizer' => $request->organizer,
                'owner' => $request->owner,
                'event_type' => $request->event_type,
                'period' => $request->period,
                'accreditation_period' => $request->accreditation_period,
                'status' => $request->status,
                'approval_option' => $request->approval_option,
                'security_officer' => $request->security_officer,
                'event_form' => $request->event_form,
                'creation_date' => $request->creation_date,
                'creator' => $request->creator
            ]);
        if($postId == null) {
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
        }
        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function eventAdd()
    {
        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Organizer")';
        $query = $sql;
        $contacts = DB::select($query);
        $organizersSelectOption = array();
        foreach($contacts as $contact)
        {
            $organizerSelectOption = new SelectOption($contact->id, $contact->name);
            $organizersSelectOption[] = $organizerSelectOption;
        }
        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
        $query = $sql;
        $contacts = DB::select($query);
        $ownersSelectOption = array();
        foreach($contacts as $contact)
        {
            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
            $ownersSelectOption[] = $ownerSelectOption;
        }

        $securityCategories = SecurityCategory::get()->all();
        $securityCategoriesSelectOption = array();
        foreach($securityCategories as $securityCategory)
        {
            $securityCategorieSelectOption = new SelectOption($securityCategory->id, $securityCategory->name);
            $securityCategoriesSelectOption[] = $securityCategorieSelectOption;
        }

        $eventTypes = EventType::get()->all();
        $eventTypesSelectOption = array();
        foreach($eventTypes as $eventType)
        {
            $eventTypeSelectOption = new SelectOption($eventType->id, $eventType->name);
            $eventTypesSelectOption[] = $eventTypeSelectOption;
        }


        $eventAdmin1 = new SelectOption(1,'eventAdmin1');
        $eventAdmin2 = new SelectOption(2,'eventAdmin2');
        $eventAdmin3 = new SelectOption(3,'eventAdmin3');
        $eventAdmins = [$eventAdmin1,$eventAdmin2,$eventAdmin3];

        $securityOfficer1 = new SelectOption(1,'securityOfficer1');
        $securityOfficer2 = new SelectOption(2,'securityOfficer2');
        $securityOfficer3 = new SelectOption(3,'securityOfficer3');
        $securityOfficers = [$securityOfficer1,$securityOfficer2,$securityOfficer3];

        $approvalOption1 = new SelectOption(1,'Event Admin Approval');
        $approvalOption2 = new SelectOption(2,'Security Officer Approval');
        $approvalOption3 = new SelectOption(3,'Both');
        $approvalOptions = [$approvalOption1,$approvalOption2,$approvalOption3];

        $eventStatus1 = new SelectOption(1,'Active');
        $eventStatus2 = new SelectOption(2,'InActive');
        $eventStatuss = [$eventStatus1,$eventStatus2];

        $eventForm1 = new SelectOption(1,'Template 1');
        $eventForm2 = new SelectOption(2,'Template 2');
        $eventForm3 = new SelectOption(3,'Template 3');
        $eventForms = [$eventForm1,$eventForm2,$eventForm3];

        return view('pages.event.event-add')->with('owners',$ownersSelectOption)->with('organizers',$organizersSelectOption)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions',$approvalOptions)->with('eventTypes',$eventTypesSelectOption)
            ->with('eventStatuss',$eventStatuss)->with('eventForms',$eventForms)->with('securityCategories',$securityCategoriesSelectOption);
    }



    public function edit($id)
    {
        $where = array('id' => $id);
        $post  = Event::where($where)->first();

        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Organizer")';
        $query = $sql;
        $contacts = DB::select($query);
        $organizersSelectOption = array();
        foreach($contacts as $contact)
        {
            $organizerSelectOption = new SelectOption($contact->id, $contact->name);
            $organizersSelectOption[] = $organizerSelectOption;
        }
        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
        $query = $sql;
        $contacts = DB::select($query);
        $ownersSelectOption = array();
        foreach($contacts as $contact)
        {
            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
            $ownersSelectOption[] = $ownerSelectOption;
        }

        $eventTypes = EventType::get()->all();
        $eventTypesSelectOption = array();
        foreach($eventTypes as $eventType)
        {
            $eventTypeSelectOption = new SelectOption($eventType->id, $eventType->name);
            $eventTypesSelectOption[] = $eventTypeSelectOption;
        }

        $securityCategories = SecurityCategory::get()->all();
        $securityCategoriesSelectOption = array();
        foreach($securityCategories as $securityCategory)
        {
            $securityCategorieSelectOption = new SelectOption($securityCategory->id, $securityCategory->name);
            $securityCategoriesSelectOption[] = $securityCategorieSelectOption;
        }

        $eventAdmin1 = new SelectOption(1,'eventAdmin1');
        $eventAdmin2 = new SelectOption(2,'eventAdmin2');
        $eventAdmin3 = new SelectOption(3,'eventAdmin3');
        $eventAdmins = [$eventAdmin1,$eventAdmin2,$eventAdmin3];

        $securityOfficer1 = new SelectOption(1,'securityOfficer1');
        $securityOfficer2 = new SelectOption(2,'securityOfficer2');
        $securityOfficer3 = new SelectOption(3,'securityOfficer3');
        $securityOfficers = [$securityOfficer1,$securityOfficer2,$securityOfficer3];

        $approvalOption1 = new SelectOption(1,'Event Admin Approval');
        $approvalOption2 = new SelectOption(2,'Security Officer Approval');
        $approvalOption3 = new SelectOption(3,'Both');
        $approvalOptions = [$approvalOption1,$approvalOption2,$approvalOption3];

//        $eventType1 = new SelectOption(1,'Sportive');
//        $eventType2 = new SelectOption(2,'Health');
//        $eventType3 = new SelectOption(3,'Diplomatic');
//        $eventTypes = [$eventType1,$eventType2,$eventType3];

        $eventStatus1 = new SelectOption(1,'Active');
        $eventStatus2 = new SelectOption(2,'InActive');
        $eventStatuss = [$eventStatus1,$eventStatus2];

        $eventForm1 = new SelectOption(1,'Template 1');
        $eventForm2 = new SelectOption(2,'Template 2');
        $eventForm3 = new SelectOption(3,'Template 3');
        $eventForms = [$eventForm1,$eventForm2,$eventForm3];

        if(request()->ajax())
        {
            $where = array('event_id' => $id);
            return datatables()->of(EventSecurityCategory::where($where)->get()->all())
                ->addColumn('name', function($data){
                    $result = '';
                    $where = array('id' => $data->security_category_id);
                    $securityCategory = SecurityCategory::where($where)->first();
                    $result = $securityCategory->name;
                    return $result;
                })
                ->addColumn('action', function($data) {
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

        return view('pages.event.event-edit')->with('owners',$ownersSelectOption)->with('organizers',$organizersSelectOption)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions',$approvalOptions)->with('eventTypes',$eventTypesSelectOption)
            ->with('eventStatuss',$eventStatuss)->with('eventForms',$eventForms)->with('post',$post)->with('securityCategories',$securityCategoriesSelectOption);;
    }

    public function remove($event_security_category_id){
        $where = array('id'=> $event_security_category_id);
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
        $post = Event::where('id',$id)->delete();

        return Response::json($post);
    }


    public function storeEventSecurityCategory($event_id,$security_category_id)
    {
        //xdebug_break();
//        $contactId = $request->post_id;
//        $titleId = $request->contactTitle;
        $post   =   EventSecurityCategory::updateOrCreate(['id' => 0],
            ['event_id' => $event_id,
                'security_category_id' => $security_category_id,
                'order'=> 100
            ]);
        return Response::json($post);
    }

}
