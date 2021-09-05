<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventAdminController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

            $events = DB::select('select * from _view where company_admin_id = ?', [Auth::user()->id]);

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
        return view('pages.company.company');
    }
}
