<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            $companies = DB::select('select * from companies_view where company_admin_id = ?', [Auth::user()->id]);

//            $companies = Company::join('countries','companies.country_id','=','countries.id')
//                ->join('cities','companies.city_id','=','cities.id')
//                ->join('users','companies.company_admin_id','=','users.id')
//                ->join('contacts','companies.focal_point_id','=','contacts.id')
//                ->join('company_categories','companies.category_id','=','company_categories.id')
//                ->select(['companies.id', 'companies.name', 'companies.category_id',
//                    'companies.country_id',
//                    'companies.city_id',
//                    'companies.focal_point_id',
//                    'companies.company_admin_id',
//
//                    'company_categories.name as category',
//                    'countries.name as  country', 'companies.address',
//                    'companies.website', 'companies.telephone',
//                    'cities.name as city','users.name as company_admin',
//                    DB::raw("CONCAT(contacts.name,' ',contacts.middle_name,' ',contacts.last_name) AS focal_point")]);

            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('companyEdit', $data->id) . '" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">Deletee</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.company.company');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $compnyId = $request->category_id;
        $company = Company::updateOrCreate(['id' => $compnyId],
            ['name' => $request->name,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'website' => $request->website,
                'focal_point_id' => $request->focal_point_id,
                'company_admin_id' => $request->company_admin_id,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
            ]);

        return Response::json($company);
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $post = Contact::where($where)->first();

        $contactStatus1 = new SelectOption(1, 'Active');
        $contactStatus2 = new SelectOption(0, 'InActive');
        $contactStatuss = [$contactStatus1, $contactStatus2];


        $where = array('status' => 1);
        $titlesSelectOptions = array();
        $titles = Title::where($where)->get()->all();

        foreach ($titles as $title) {
            $titlesSelectOption = new SelectOption($title->id, $title->title_label);
            $titlesSelectOptions[] = $titlesSelectOption;
        }

        if (request()->ajax()) {
            $where = array('contact_id' => $id);
            return datatables()->of(ContactTitle::where($where)->get()->all())
                ->addColumn('title_label', function ($data) {
                    $result = '';
                    $where = array('id' => $data->title_id, 'status' => 1);
                    $title = Title::where($where)->first();
                    $result = $title->title_label;
                    return $result;
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post" id="remove-contact_title">Remove</a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('pages.contact.contact-edit')->with('post', $post)->with('contactStatuss', $contactStatuss)->with('titlesSelectOptions', $titlesSelectOptions);
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

}
