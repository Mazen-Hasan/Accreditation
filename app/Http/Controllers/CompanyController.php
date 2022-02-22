<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use App\Models\City;
use App\Models\Company;
use App\Models\EventCompany;
use App\Models\CompanyAccreditaionCategory;
use App\Models\CompanyCategory;
use App\Models\Country;
use App\Models\Event;
use App\Models\EventCompnay;
use App\Models\EventCompanyAccreditaionCategory;
use App\Models\FocalPoint;
use App\Models\SelectOption;
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Company.company');
    }

    public function eventCompanies()
    {
        if (request()->ajax()) {
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
        $where = array('id' => $request->focal_point);
        $focal_point = FocalPoint::where($where)->first();
        if ($companyId == null) {
            $company = Company::updateOrCreate(['id' => $companyId],
                ['name' => $request->company_name,
                    'address' => $request->address,
                    'telephone' => $request->telephone,
                    'website' => $request->website,
                    'country_id' => $request->country,
                    'city_id' => $request->city,
                    'category_id' => $request->category,
                ]);
            $event_company = EventCompany::updateOrCreate(['id' => 0],
                ['event_id' => $request->event_id,
                'company_id' => $company->id,
                'status' => $request->company_status,
                'focal_point_id' => $request->focal_point,
                'size' => $request->size,
                'need_management' => $request->need_management
            ]);
            $companies = DB::select('select * from companies_view where id = ? and event_id = ?',[$company->id,$request->event_id]);
            foreach($companies as $company1){
                $company = $company1;
            }
        } else {

            $where = array('id' => $companyId);
            $company = Company::where($where)->first();
            $status = $company->status;
            if ($request->company_status == 0) {
                $status = 0;
            } else {
                if ($company->status != 3) {
                    $status = $request->company_status;
                }
            }
            $company = Company::updateOrCreate(['id' => $companyId],
                ['name' => $request->company_name,
                    'address' => $request->address,
                    'telephone' => $request->telephone,
                    'website' => $request->website,
                    'country_id' => $request->country,
                    'city_id' => $request->city,
                    'category_id' => $request->category,
                ]);
                $event_company = EventCompany::updateOrCreate(['event_id' => $request->event_id,'company_id' => $companyId],
                [
                'status' => $request->company_status,
                'focal_point_id' => $request->focal_point,
                'size' => $request->size,
                'need_management' => $request->need_management
            ]); 
        }

        return Response::json($company);
    }

    public function edit($id, $eventid)
    {
        $where = array('id' => $eventid);
        $event = Event::where($where)->first();
        $companies = DB::select('select * from companies_view where id = ? and event_id = ?', [$id,$eventid]);
        foreach($companies as $company){
            $post = $company;
        }
        $eventcompanies = EventCompany::where(['company_id'=>$post->id,'event_id'=>$eventid])->first();
        $where = array('id' => $eventcompanies->focal_point_id);
        //$where = array('status' => 1);
        $contacts = FocalPoint::where($where)->get()->all();
        $focalPointsOption = array();
        foreach ($contacts as $contact) {
            $focalPointSelectOption = new SelectOption($contact->id, $contact->name . ' ' . $contact->last_name);
            $focalPointsOption[] = $focalPointSelectOption;
        }

        $countrysSelectOptions = array();
//         $countries = Country::get()->all();

//         foreach ($countries as $country) {
//             $countrySelectOption = new SelectOption($country->id, $country->name);
//             $countrysSelectOptions[] = $countrySelectOption;
//         }
    	$countries = DB::select('select DISTINCT(ccc.country_id), c.country_name from country_cities_view ccc inner join country_cities_view c on ccc.country_id = c.country_id');
        foreach ($countries as $country) {
            $countrySelectOption = new SelectOption($country->country_id, $country->country_name);
            $countrysSelectOptions[] = $countrySelectOption;
        }

        $citysSelectOptions = array();
        //$cities = City::get()->all();
		$cities = City::where(['country_id'=>$post->country_id])->get()->all();
        foreach ($cities as $city) {
            $citySelectOption = new SelectOption($city->id, $city->name);
            $citysSelectOptions[] = $citySelectOption;
        }

        $where = array('status' => 1);
        $categorysSelectOptions = array();
        $categories = CompanyCategory::where($where)->get()->all();

        foreach ($categories as $category) {
            $categorySelectOption = new SelectOption($category->id, $category->name);
            $categorysSelectOptions[] = $categorySelectOption;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        $companyStatus1 = new SelectOption(1, 'Active');
        $companyStatus2 = new SelectOption(0, 'InActive');
        $companyStatuss = [$companyStatus1, $companyStatus2];

        $accreditationManagement1 = new SelectOption(0, 'Managed By Event Admin');
        $accreditationManagement2 = new SelectOption(1, 'Managed By Company Admin');
        $accreditationManagements = [$accreditationManagement1,$accreditationManagement2];
    
        $allwoedSize = $event->size;
        $allwoedSize = $allwoedSize + $post->size;
        $eventcompaniess = EventCompany::where(['event_id'=> $eventid,'parent_id'=> null])->get()->all();
        foreach($eventcompaniess as $eventcompnays){
            $allwoedSize = $allwoedSize - $eventcompnays->size; 
        }

        if (request()->ajax()) {
            $companyAccreditationCategories = DB::select('select * from company_accreditaion_categories_view where company_id = ?', [$id]);
            return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" title="Edit Size"><i class="fas fa-chart-pie"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.Company.company-edit')->with('company', $post)->with('countrys', $countrysSelectOptions)->with('citys', $citysSelectOptions)->with('focalPoints', $focalPointsOption)
            ->with('categorys', $categorysSelectOptions)->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('eventid', $eventid)->with('event_name', $event->name)->with('company_name', $post->name)->with('statuss', $companyStatuss)->with('allowedSize',$allwoedSize)->with('accreditationManagements',$accreditationManagements);
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
        $where = array('id' => $id);
        $event = Event::where($where)->first();
        $where = array('status' => 1);
        $contacts = FocalPoint::where($where)->get()->all();
        $focalPointsOption = array();
        // foreach ($contacts as $contact) {
        //     $focalPointSelectOption = new SelectOption($contact->id, $contact->name . ' ' . $contact->middle_name . ' ' . $contact->last_name);
        //     $focalPointsOption[] = $focalPointSelectOption;
        // }

        $countrysSelectOptions = array();
        //$countries = Country::get()->all();
		$countries = DB::select('select DISTINCT(ccc.country_id), c.country_name from country_cities_view ccc inner join country_cities_view c on ccc.country_id = c.country_id');
        foreach ($countries as $country) {
            $countrySelectOption = new SelectOption($country->country_id, $country->country_name);
        	//$countrySelectOption = new SelectOption($country->id, $country->name);
            $countrysSelectOptions[] = $countrySelectOption;
        }

        $citysSelectOptions = array();
        $cities = City::get()->all();

        // foreach ($cities as $city) {
        //     $citySelectOption = new SelectOption($city->id, $city->name);
        //     $citysSelectOptions[] = $citySelectOption;
        // }

        $where = array('status' => 1);
        $categorysSelectOptions = array();
        $categories = CompanyCategory::where($where)->get()->all();

        foreach ($categories as $category) {
            $categorySelectOption = new SelectOption($category->id, $category->name);
            $categorysSelectOptions[] = $categorySelectOption;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        $companyStatus1 = new SelectOption(1, 'Active');
        $companyStatus2 = new SelectOption(0, 'InActive');
        $companyStatuss = [$companyStatus1, $companyStatus2];
    
        $allwoedSize = $event->size;
        $eventcompanies = EventCompany::where(['event_id'=> $id,'parent_id'=> null])->get()->all();
        foreach($eventcompanies as $eventcompnay){
            $allwoedSize = $allwoedSize - $eventcompnay->size; 
        }

        return view('pages.Company.company-add')->with('countrys', $countrysSelectOptions)->with('citys', $citysSelectOptions)->with('focalPoints', $focalPointsOption)
            ->with('categorys', $categorysSelectOptions)->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('eventid', $id)->with('event_name', $event->name)->with('statuss', $companyStatuss)->with('allowedSize',$allwoedSize);
    }

    public function companyAccreditCat($Id, $eventId)
    {
        $companies = DB::select('select * from companies_view where id = ? and event_id = ?', [$Id,$eventId]);
        foreach($companies as $company1){
            $company = $company1;
        }

        $where = array('id' => $eventId);
        $event = Event::where($where)->first();

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
//         $accreditationCategories = AccreditationCategory::where($where)->get()->all();

//         foreach ($accreditationCategories as $accreditationCategory) {
//             $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
//             $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
//         }
    
        $accreditationCategories = DB::select('select * from event_accreditation_categories_view where event_id = ?',[$eventId]);
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->accreditation_category_id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }
        $companyAccreditationCategories = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$Id, $eventId]);
        $status = 0;
        $remainingSize = $company->size;
        foreach ($companyAccreditationCategories as $companyAccreditationCategory) {
            $status = $companyAccreditationCategory->status;
            $remainingSize = $remainingSize - $companyAccreditationCategory->size;
        }

        if (request()->ajax()) {
            $companyAccreditationCategories = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$Id, $eventId]);
            return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) use ($event) {
                    $button = "";
                    if($event->status < 3){
                        $button .= '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" title="Edit Size"><i class="fas fa-chart-pie"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip" data-original-title="Delete" data-size="' . $data->size . '" data-id="' . $data->id . '" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Company.company-accreditation-size-new')->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('companyId', $Id)->with('eventId', $eventId)->with('status', $status)->with('event_name', $event->name)->with('company_name', $company->name)->with('company_size', $company->size)->with('remaining_size', $remainingSize)->with('event_status', $event->status);
    }

    public function editCompanyAccreditSize($id)
    {

        $where = array('id' => $id);
        $post = CompanyAccreditaionCategory::where($where)->first();
        return Response::json($post);
    }

    public function storeCompanyAccrCatSize($id, $accredit_cat_id, $size, $company_id, $event_id)
    {
        $where = array('company_id'=>$company_id, 'event_id' => $event_id);
        $eventcompnay = EventCompany::where($where)->first();
        try {
            $post = CompanyAccreditaionCategory::updateOrCreate(['id' => $id],
                ['size' => $size,
                    'event_company_id' => $eventcompnay->id,
                    'accredit_cat_id' => $accredit_cat_id,
                    'company_id' => $company_id,
                    'parent_id' => $eventcompnay->parent_id,
                    'event_id' => $event_id,
                    'status' => 2
                ]);

        } catch (\Exception $e) {
            return Response::json(array(
                'code' => 400,
                'message' => $e->getMessage()
            ), 400);
        }
        return Response::json($post);
    }

    public function destroyCompanyAccreditCat($id)
    {
        $post = CompanyAccreditaionCategory::where('id', $id)->delete();
        return Response::json($post);

    }

    public function Approve($companyId, $eventId)
    {
        $where = array('company_id' => $companyId, 'event_id' => $eventId);
        $companyAccreditCategories = CompanyAccreditaionCategory::where($where)
            ->update(['status' => 2]);
        return Response::json($companyAccreditCategories);

    }

    public function getCities($countrytId)
    {
        $cities = DB::select('select * from cities c where c.country_id = ? ',[$countrytId]);

        $subcount = 0;
        $citySelectOptions = array();
        foreach ($cities as $city) {
            // if ($subcount == 0) {
            //     $compnaySelectOption = new SelectOption(0, 'All');
            //     $companySelectOptions[] = $compnaySelectOption;
            //     $subcount = 1;
            // }
            $citySelectOption = new SelectOption($city->id, $city->name);
            $citySelectOptions[] = $citySelectOption;
        }
        return Response::json($citySelectOptions);
    }

}
