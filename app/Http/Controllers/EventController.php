<?php

namespace App\Http\Controllers;

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
        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function eventAdd()
    {
        $owner1 = new SelectOption(1,'owner1');
        $owner2 = new SelectOption(2,'owner2');
        $owner3 = new SelectOption(3,'owner3');
        $owner4 = new SelectOption(4,'owner4');
        $owners = [$owner1,$owner2,$owner3,$owner4];

        $organizer1 = new SelectOption(1,'organizer1');
        $organizer2 = new SelectOption(2,'organizer2');
        $organizer3 = new SelectOption(3,'organizer3');
        $organizers = [$organizer1,$organizer2,$organizer3];

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

        $eventType1 = new SelectOption(1,'Sportive');
        $eventType2 = new SelectOption(2,'Health');
        $eventType3 = new SelectOption(3,'Diplomatic');
        $eventTypes = [$eventType1,$eventType2,$eventType3];

        $eventStatus1 = new SelectOption(1,'Active');
        $eventStatus2 = new SelectOption(2,'InActive');
        $eventStatuss = [$eventStatus1,$eventStatus2];

        $eventForm1 = new SelectOption(1,'Template 1');
        $eventForm2 = new SelectOption(2,'Template 2');
        $eventForm3 = new SelectOption(3,'Template 3');
        $eventForms = [$eventForm1,$eventForm2,$eventForm3];

        return view('pages.event.event-add')->with('owners',$owners)->with('organizers',$organizers)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions',$approvalOptions)->with('eventTypes',$eventTypes)
            ->with('eventStatuss',$eventStatuss)->with('eventForms',$eventForms);
    }



    public function edit($id)
    {
        $where = array('id' => $id);
        $post  = Event::where($where)->first();

        $owner1 = new SelectOption(1,'owner1');
        $owner2 = new SelectOption(2,'owner2');
        $owner3 = new SelectOption(3,'owner3');
        $owner4 = new SelectOption(4,'owner4');
        $owners = [$owner1,$owner2,$owner3,$owner4];

        $organizer1 = new SelectOption(1,'organizer1');
        $organizer2 = new SelectOption(2,'organizer2');
        $organizer3 = new SelectOption(3,'organizer3');
        $organizers = [$organizer1,$organizer2,$organizer3];

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

        $eventType1 = new SelectOption(1,'Sportive');
        $eventType2 = new SelectOption(2,'Health');
        $eventType3 = new SelectOption(3,'Diplomatic');
        $eventTypes = [$eventType1,$eventType2,$eventType3];

        $eventStatus1 = new SelectOption(1,'Active');
        $eventStatus2 = new SelectOption(2,'InActive');
        $eventStatuss = [$eventStatus1,$eventStatus2];

        $eventForm1 = new SelectOption(1,'Template 1');
        $eventForm2 = new SelectOption(2,'Template 2');
        $eventForm3 = new SelectOption(3,'Template 3');
        $eventForms = [$eventForm1,$eventForm2,$eventForm3];

        return view('pages.event.event-edit')->with('owners',$owners)->with('organizers',$organizers)->with('eventAdmins', $eventAdmins)
            ->with('securityOfficers', $securityOfficers)->with('approvalOptions',$approvalOptions)->with('eventTypes',$eventTypes)
            ->with('eventStatuss',$eventStatuss)->with('eventForms',$eventForms)->with('post',$post);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = Event::where('id',$id)->delete();

        return Response::json($post);
    }
}
