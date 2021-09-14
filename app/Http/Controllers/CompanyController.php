<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use App\Models\City;
use App\Models\Company;
use App\Models\Event;
use App\Models\CompanyAccreditaionCategory;
use App\Models\Country;
use App\Models\SelectOption;
use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
//            var_dump($event_id);
//            exit;
            $companies = DB::select('select * from companies_view');

//            $companies = DB::select('select * from companies_view where event_id = ?' ,$event_id );
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('companyEdit', $data->id) . '" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('companyAccreditCat', $data->id) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="Company Accreditation Size"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Company.company');
    }

    public function eventCompanies()
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
        return view('pages.Company.company');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $companyId = $request->company_Id;
        $company = Company::updateOrCreate(['id' => $companyId],
            ['name' => $request->name,
                'event_id' => $request->event_id,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'website' => $request->website,
                'focal_point_id' => $request->focal_point,
                'company_admin_id' => Auth::user()->id,
                'country_id' => $request->country,
                'city_id' => $request->city,
                'category_id' => $request->category,
                'size' => $request->size,
            ]);
        if($companyId == null) {
            foreach ($request->accreditationCategories as $accreditationCategory) {
                $help = CompanyAccreditaionCategory::updateOrCreate(['id' => 0],
                    ['accredit_cat_id' => $accreditationCategory,
                        'company_id' => $company->id,
                        'subcompany_id' => $company->id,
                        'status' => 0,
                        'event_id' => $request->event_id,
                        'size' => 0
                    ]);
            }
        }

        return Response::json($company);
    }

    public function edit($id, $eventid)
    {
        $where = array('id' => $id);
        $post = Company::where($where)->first();

        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Focal Point")';
        $query = $sql;
        $contacts = DB::select($query);
        $focalPointsOption = array();
        foreach($contacts as $contact)
        {
            $focalPointSelectOption = new SelectOption($contact->id, $contact->name);
            $focalPointsOption[] = $focalPointSelectOption;
        }

        $countrysSelectOptions = array();
        $countries = Country::get()->all();

        foreach ($countries as $country) {
            $countrySelectOption = new SelectOption($country->id, $country->name);
            $countrysSelectOptions[] = $countrySelectOption;
        }

        $citysSelectOptions = array();
        $cities = City::get()->all();

        foreach ($cities as $city) {
            $citySelectOption = new SelectOption($city->id, $city->name);
            $citysSelectOptions[] = $citySelectOption;
        }

        $where = array('status' => 1);
        $categorysSelectOptions = array();
        $categories = CompanyCategory::where($where)->get()->all();

        foreach($categories as $category)
        {
            $categorySelectOption = new SelectOption($category->id, $category->name);
            $categorysSelectOptions[] = $categorySelectOption;
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
            //$companyAccreditationCategories= DB::select('select * from company_accreditaion_categories_view where company_id = ?',$companyId);
            $companyAccreditationCategories= DB::select('select * from company_accreditaion_categories_view where company_id = ?',[$id]);
            return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company">Edit size</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company">Remove Accreditiation Category</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }



//
//
//
//        $contactStatus1 = new SelectOption(1,'Active');
//        $contactStatus2 = new SelectOption(0,'InActive');
//        $contactStatuss = [$contactStatus1,$contactStatus2];

        return view('pages.Company.company-edit')->with('company',$post)->with('countrys',$countrysSelectOptions)->with('citys',$citysSelectOptions)->with('focalPoints',$focalPointsOption)
            ->with('categorys', $categorysSelectOptions)->with('accreditationCategorys',$accreditationCategorysSelectOptions)->with('eventid',$eventid);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = Company::where('id', $id)->delete();

        return Response::json($post);
    }

    public function companyAdd($id)
    {
        $sql = 'select CONCAT(c.name," ",c.middle_name," ",c.last_name) "name" , c.id "id" from contacts c inner join contact_titles ct on c.id = ct.contact_id where ct.title_id = (select id from titles where title_label = "Focal Point")';
        $query = $sql;
        $contacts = DB::select($query);
        $focalPointsOption = array();
        foreach($contacts as $contact)
        {
            $focalPointSelectOption = new SelectOption($contact->id, $contact->name);
            $focalPointsOption[] = $focalPointSelectOption;
        }

        $countrysSelectOptions = array();
        $countries = Country::get()->all();

        foreach ($countries as $country) {
            $countrySelectOption = new SelectOption($country->id, $country->name);
            $countrysSelectOptions[] = $countrySelectOption;
        }

        $citysSelectOptions = array();
        $cities = City::get()->all();

        foreach ($cities as $city) {
            $citySelectOption = new SelectOption($city->id, $city->name);
            $citysSelectOptions[] = $citySelectOption;
        }

        $where = array('status' => 1);
        $categorysSelectOptions = array();
        $categories = CompanyCategory::where($where)->get()->all();

        foreach($categories as $category)
        {
            $categorySelectOption = new SelectOption($category->id, $category->name);
            $categorysSelectOptions[] = $categorySelectOption;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach($accreditationCategories as $accreditationCategory)
        {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        return view('pages.company.company-add')->with('countrys',$countrysSelectOptions)->with('citys',$citysSelectOptions)->with('focalPoints',$focalPointsOption)
            ->with('categorys', $categorysSelectOptions)->with('accreditationCategorys',$accreditationCategorysSelectOptions)->with('eventid',$id);
    }

    public function companyAccreditCat($Id,$eventId)
    {
        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach($accreditationCategories as $accreditationCategory)
        {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }
        $companyAccreditationCategories= DB::select('select * from company_accreditaion_categories where company_id = ? and event_id = ?' ,[$Id, $eventId]);
        $status = 0;
        foreach($companyAccreditationCategories as $companyAccreditationCategory){
            $status = $companyAccreditationCategory->status;
        }

        if (request()->ajax()) {
            //$companyAccreditationCategories= DB::select('select * from company_accreditaion_categories_view where company_id = ?',$companyId);
            $companyAccreditationCategories= DB::select('select * from company_accreditaion_categories_view where company_id = ? and event_id = ?' ,[$Id, $eventId]);
            return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company">Edit size</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company">Remove Accreditiation Category</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Company.company-accreditation-size-new')->with('accreditationCategorys',$accreditationCategorysSelectOptions)->with('companyId', $Id)->with('eventId',$eventId)->with('status',$status);
    }

    public function editCompanyAccreditSize($id){

        $where = array('id' => $id);
        $post = CompanyAccreditaionCategory::where($where)->first();
        return Response::json($post);
    }

    public function storeCompanyAccrCatSize($id,$accredit_cat_id,$size,$company_id,$event_id){
        // $where = array('event_admin' => Auth::user()->id);
        // $event = Event::where($where)->get()->first();
        $post = CompanyAccreditaionCategory::updateOrCreate(['id' => $id],
            ['size' => $size,
                'accredit_cat_id' => $accredit_cat_id,
                'company_id'=> $company_id,
                'subcompany_id' =>$company_id,
                'event_id' => $event_id,
                'status'=> 2
            ]);
        return Response::json($post);
    }

    public function destroyCompanyAccreditCat($id){
        $post = CompanyAccreditaionCategory::where('id', $id)->delete();
        return Response::json($post);

    }

    public function Approve($companyId,$eventId){
        $where = array('company_id' => $companyId,'event_id'=>$eventId);
        //$post = CompanyAccreditaionCategory::where($where);
        $companyAccreditCategories = CompanyAccreditaionCategory::where($where)
        ->update(['status'=>2]);
        return Response::json($companyAccreditCategories);

    }

}
