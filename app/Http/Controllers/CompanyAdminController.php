<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use App\Models\Company;
use App\Models\CompanyAccreditaionCategory;
use App\Models\Gender;
use App\Models\NationalityClass;
use App\Models\Participant;
use App\Models\Religion;
use App\Models\SelectOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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

        $events = DB::select('select c.* from events_view c inner join companies cc on c.id = cc.event_id where cc.company_admin_id = ?', [Auth::user()->id]);
//        var_dump($events);
//        exit;
//        $events = DB::select('select * from events_view  , events_view v');
        return view('pages.CompanyAdmin.company-admin')->withEvents($events);
    }

    public function companyParticipants()
    {
        if (request()->ajax()) {
            $where = array('company_admin_id' => Auth::user()->id);
            $company = Company::where($where)->get()->first();
            //$participants = DB::select('select * from company_participants_view');
            $participants = DB::select('select * from company_participants_view where company = ?' ,[$company->id]);
//            $participants = DB::select('select * from participants');
            return datatables()->of($participants)
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->last_name;
                })
                ->addColumn('action', function ($data) {
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button = '<a href="' . route('companyParticipantEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    //$button .= '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">   Delete</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.CompanyAdmin.company-participants');
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
            $status = 1;
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
        return view('pages.CompanyAdmin.company-accreditation-size')->with('accreditationCategorys',$accreditationCategorysSelectOptions)->with('companyId', $company->id)->with('eventId',$eventId)->with('status',$status);
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
}
