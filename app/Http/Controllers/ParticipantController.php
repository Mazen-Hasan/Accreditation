<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use App\Models\Company;
use App\Models\Participant;
use App\Models\SelectOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $where = array('company_admin_id' => Auth::user()->id);
            $company = Company::where($where)->first();
            $participants = DB::select('select * from paticipants where company = ?', [$company->id]);
            return datatables()->of($participants)
                ->addColumn('name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('action', function ($data) {
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button = '<a href="' . route('participantEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    //$button .= '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">   Delete</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.participant.participants');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //xdebug_break();
        $postId = $request->post_id;
        $post = Participant::updateOrCreate(['id' => $postId],
            ['first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'first_name_ar' => $request->first_name_ar,
                'last_name_ar' => $request->last_name_ar,
                'nationality' => $request->nationality,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'position' => $request->position,
                'religion' => $request->religion,
                'address' => $request->address,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'company' => Auth::user()->id,
                'subCompany' => Auth::user()->id,
                'passport_number' => $request->passport_number,
                'id_number' => $request->id_number,
                'class' => $request->class,
                'accreditation_category' => $request->accreditation_category,
                'creator' => $request->creator,
            ]);
//        if ($postId == null) {
//            $counter = 1;
//            foreach ($request->security_categories as $security_category) {
//                $help = EventSecurityCategory::updateOrCreate(['id' => $postId],
//                    ['event_id' => $post->id,
//                        'security_category_id' => $security_category,
//                        'order' => $counter,
//                        'creation_date' => $request->creation_date,
//                        'creator' => $request->creator
//                    ]);
//                $counter = $counter + 1;
//            }
//        }
        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function participantAdd()
    {
//        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Organizer")';
//        $query = $sql;
//        $contacts = DB::select($query);
//        $organizersSelectOption = array();
//        foreach ($contacts as $contact) {
//            $organizerSelectOption = new SelectOption($contact->id, $contact->name);
//            $organizersSelectOption[] = $organizerSelectOption;
//        }
//        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Owner")';
//        $query = $sql;
//        $contacts = DB::select($query);
//        $ownersSelectOption = array();
//        foreach ($contacts as $contact) {
//            $ownerSelectOption = new SelectOption($contact->id, $contact->name);
//            $ownersSelectOption[] = $ownerSelectOption;
//        }

        $accreditationCategories = AccreditationCategory::get()->all();
        $accreditationCategoriesSelectOption = array();
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorySelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategoriesSelectOption[] = $accreditationCategorySelectOption;
        }

//        $eventTypes = EventType::get()->all();
//        $eventTypesSelectOption = array();
//        foreach ($eventTypes as $eventType) {
//            $eventTypeSelectOption = new SelectOption($eventType->id, $eventType->name);
//            $eventTypesSelectOption[] = $eventTypeSelectOption;
//        }


        $class1 = new SelectOption(1, 'Citizen');
        $class2 = new SelectOption(2, 'Visitor');
        $class3 = new SelectOption(3, 'Resident');
        $classess = [$class1, $class2, $class3];

        $gender1 = new SelectOption(1, 'Male');
        $gender2 = new SelectOption(2, 'Female');
        $genders = [$gender1, $gender2];


        return view('pages.participant.participant-add')->with('classess', $classess)->with('genders', $genders)->with('accreditationCategoriesSelectOption', $accreditationCategoriesSelectOption);
    }


    public function edit($id)
    {
        $where = array('id' => $id);
        $post = Participant::where($where)->first();

        $accreditationCategories = AccreditationCategory::get()->all();
        $accreditationCategoriesSelectOption = array();
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorySelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategoriesSelectOption[] = $accreditationCategorySelectOption;
        }

//        $eventTypes = EventType::get()->all();
//        $eventTypesSelectOption = array();
//        foreach ($eventTypes as $eventType) {
//            $eventTypeSelectOption = new SelectOption($eventType->id, $eventType->name);
//            $eventTypesSelectOption[] = $eventTypeSelectOption;
//        }


        $class1 = new SelectOption(1, 'Citizen');
        $class2 = new SelectOption(2, 'Visitor');
        $class3 = new SelectOption(3, 'Resident');
        $classess = [$class1, $class2, $class3];

        $gender1 = new SelectOption(1, 'Male');
        $gender2 = new SelectOption(2, 'Female');
        $genders = [$gender1, $gender2];

        return view('pages.participant.participant-edit')->with('post', $post)->with('classess', $classess)->with('genders', $genders)->with('accreditationCategoriesSelectOption', $accreditationCategoriesSelectOption);
    }

//    public function remove($event_security_category_id)
//    {
//        $where = array('id' => $event_security_category_id);
//        $post = EventSecurityCategory::where($where)->delete();
//        return Response::json($post);
//    }

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
        $post = Participant::where('id', $id)->delete();

        return Response::json($post);
    }


//    public function storeEventSecurityCategory($event_id, $security_category_id)
//    {
//        //xdebug_break();
////        $contactId = $request->post_id;
////        $titleId = $request->contactTitle;
//        $post = EventSecurityCategory::updateOrCreate(['id' => 0],
//            ['event_id' => $event_id,
//                'security_category_id' => $security_category_id,
//                'order' => 100
//            ]);
//        return Response::json($post);
//    }

}

