<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\TemplateField;
use App\Models\CompanyAccreditaionCategory;
use App\Models\Gender;
use App\Models\NationalityClass;
use App\Models\Participant;
use App\Models\Religion;
use App\Models\SelectOption;
use App\Models\TemplateFieldElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class CompanyAdminController extends Controller
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
                    $button .= '<a href="javascript:void(0);" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
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

        $events = DB::select('select c.* , cc.need_management need_management , cc.name company_name from events_view c inner join companies cc on c.id = cc.event_id where cc.company_admin_id = ? and cc.status <> ?', [Auth::user()->id,0]);
//        var_dump($events);
//        exit;
//        $events = DB::select('select * from events_view  , events_view v');
        return view('pages.CompanyAdmin.company-admin')->withEvents($events);
    }

    public function companyParticipants()
    {
        $dataTableColumuns = array();

        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $company->event_id);
        $event = Event::where($where)->get()->first();

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->get()->all();

        foreach($templateFields as $templateField){
            $dataTableColumuns[] = $templateField->label_en;
        }
        Schema::dropIfExists('temp'.Auth::user()->id);
        Schema::create('temp'.Auth::user()->id, function ($table) use($templateFields) {
            $table->string('id');
            foreach($templateFields as $templateField){
                $dataTableColumuns[] = $templateField->label_en;
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();

        $where = array('event_id' => $company->event_id,'company_id' => $company->id);
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
            $query = 'insert into temp'.Auth::user()->id.' (id';
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
            $participants = DB::select('select t.* , c.* from temp'.Auth::user()->id. ' t inner join company_staff c on t.id = c.id');
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
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    // $button = '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    // $button .= '&nbsp;&nbsp;';
                    switch($data->status){
                        case 0:
                            $button .= '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0);" id="send_request" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">Send Request</a>';
                            break;
                        case 7:
                            $button .= '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" data-reason="'.$data->security_officer_reject_reason.'" class="delete btn btn-danger">Reject Reason</a>';
                            break;
                        case 8:
                            $button .= '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" data-reason="'.$data->event_admin_reject_reason.'" class="delete btn btn-danger">Reject Reason</a>';
                            break;
                    }
                    return $button;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('pages.CompanyAdmin.company-participants')->with('dataTableColumns',$dataTableColumuns);
    }


    public function companyParticipantAdd()
    {
        $accreditationCategories = AccreditationCategory::get()->all();
        $accreditationCategoriesSelectOption = array();
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorySelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategoriesSelectOption[] = $accreditationCategorySelectOption;
        }
        $nationalClassess = NationalityClass::get()->all();
        $classess = array();
        foreach ($nationalClassess as $nationalClass) {
            $class = new SelectOption($nationalClass->id, $nationalClass->name);
            $classess[] = $class;
        }
        $gendersItems = Gender::get()->all();
        $genders = array();
        foreach ($gendersItems as $gendersItem) {
            $gender = new SelectOption($gendersItem->id, $gendersItem->name);
            $genders[] = $gender;
        }
        $religionsItems = Religion::get()->all();
        $religions = array();
        foreach ($religionsItems as $religionsItem) {
            $religion = new SelectOption($religionsItem->id, $religionsItem->name);
            $religions[] = $religion;
        }


        return view('pages.CompanyAdmin.company-participant-add')->with('classess', $classess)->with('genders', $genders)->with('accreditationCategoriesSelectOption', $accreditationCategoriesSelectOption)->with('religionsSelectOption',$religions);
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
        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();
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
                'company' => $company->id,
                'subCompany' => $company->id,
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
        $nationalClassess = NationalityClass::get()->all();
        $classess = array();
        foreach ($nationalClassess as $nationalClass) {
            $class = new SelectOption($nationalClass->id, $nationalClass->name);
            $classess[] = $class;
        }
        $gendersItems = Gender::get()->all();
        $genders = array();
        foreach ($gendersItems as $gendersItem) {
            $gender = new SelectOption($gendersItem->id, $gendersItem->name);
            $genders[] = $gender;
        }
        $religionsItems = Religion::get()->all();
        $religions = array();
        foreach ($religionsItems as $religionsItem) {
            $religion = new SelectOption($religionsItem->id, $religionsItem->name);
            $religions[] = $religion;
        }

        return view('pages.CompanyAdmin.company-participant-edit')->with('post', $post)->with('classess', $classess)->with('genders', $genders)->with('accreditationCategoriesSelectOption', $accreditationCategoriesSelectOption)->with('religionsSelectOption',$religions);;
    }

    public function companyAccreditCategories($eventId)
    {
        //$eventId = $request->eventId;

        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();
        $companyAccreditationCategories= DB::select('select * from company_accreditaion_categories where company_id = ? and event_id = ?',[$company->id,$eventId]);
        $status = 1;
        foreach($companyAccreditationCategories as $companyAccreditationCategory)
        {
            $status = $companyAccreditationCategory->status;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach($accreditationCategories as $accreditationCategory)
        {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        if (request()->ajax()) {
            $where = array('company_admin_id' => Auth::user()->id,'event_id' =>$eventId);
            $company = Company::where($where)->get()->first();
            //$companyAccreditationCategories= DB::select('select * from company_accreditaion_categories_view where company_id = ?',$companyId);
            $companyAccreditationCategories= DB::select('select * from company_accreditaion_categories_view where company_id = ? and event_id = ?',[$company->id,$eventId]);
            $companyAccreditationCategoriesStatuss= DB::select('select * from company_accreditaion_categories where company_id = ? and event_id = ?',[$company->id,$eventId]);
            $status = 0;
            foreach($companyAccreditationCategoriesStatuss as $companyAccreditationCategoriesStatus)
            {
                $status = $companyAccreditationCategoriesStatus->status;
            }
            // $status = 1;
            if($status == 0){
            return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company">Edit size</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company">Remove Accreditiation Category</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
            }else{
                if($status == 1){
                return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) {
                    $button = 'Waiting for approval';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);  
                }else{
                    return datatables()->of($companyAccreditationCategories)
                    ->addColumn('action', function ($data) {
                        $button = 'Approved';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                }
            }
        }
        return view('pages.CompanyAdmin.company-accreditation-size')->with('accreditationCategorys',$accreditationCategorysSelectOptions)->with('companyId', $company->id)->with('eventId',$eventId)->with('status',$status)->with('event_name',$event->name)->with('company_name',$company->name);
    }

    public function editCompanyAccreditSize($id){

        $where = array('id' => $id);
        $post = CompanyAccreditaionCategory::where($where)->first();
        return Response::json($post);
    }

    public function storeCompanyAccrCatSize($id,$accredit_cat_id,$size,$company_id,$event_id){

        $post = CompanyAccreditaionCategory::updateOrCreate(['id' => $id],
            ['size' => $size,
                'accredit_cat_id' => $accredit_cat_id,
                'company_id'=> $company_id,
                'subcompany_id' =>$company_id,
                'event_id' => $event_id,
                'status'=> 0
            ]);
        return Response::json($post);
    }

    public function destroyCompanyAccreditCat($id){
        $post = CompanyAccreditaionCategory::where('id', $id)->delete();
        return Response::json($post);

    }

    public function sendApproval($companyId,$eventId){
        $where = array('company_id' => $companyId,'event_id'=>$eventId);
        //$post = CompanyAccreditaionCategory::where($where);
        $companyAccreditCategories = CompanyAccreditaionCategory::where($where)
        ->update(['status'=>1]);
        return Response::json($companyAccreditCategories);

    }

    public function sendRequest($staffId){
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id'=> $eventId);
        $event = Event::where($eventWhere)->first();

        $approval = $event->approval_option;
        if($approval == 1){
            $updateQuery = 'update company_staff set security_officer_id = '.$event->security_officer.' where id = '.$staffId;
            // var_dump($updateQuery);
            // exit;
            DB::update($updateQuery);
            DB::update('update company_staff set status = ? where id = ?',[1,$staffId]);
            // $compamyStaff = CompanyStaff::where($where)
            // ->update(['status'=>1,'security_officer_id'=> $event->security_officer]);

        }else{
            if($approval == 2){
                DB::update('update company_staff set event_admin_id = ? where id = ?',[$event->event_admin,$staffId]);
                DB::update('update company_staff set status = ? where id = ?',[2,$staffId]);
                // $compamyStaff = CompanyStaff::where($where)
                // ->update(['status'=>2,'event_admin_id'=> $event->event_admin]);
            }else{
                DB::update('update company_staff set security_officer_id = ? where id = ?',[$event->security_officer,$staffId]);
                DB::update('update company_staff set status = ? where id = ?',[1,$staffId]);
                // $compamyStaff = CompanyStaff::where($where)
                // ->update(['status'=>1,'security_officer_id'=> $event->security_officer]);
            }
        }
        return Response::json($event);

    }
}
