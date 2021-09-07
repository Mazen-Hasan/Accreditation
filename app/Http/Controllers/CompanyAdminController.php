<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function companyParticipants($id)
    {
//        if (request()->ajax()) {
//            $companies = DB::select('select * from companies_view inner join companies where event_id = ?' ,[$id]);
////            $companies = DB::select('select * from companies_view');
//            return datatables()->of($companies)
//                ->addColumn('action', function ($data) {
//                    $button = '<a href="../company-edit/'.$data->id.'/'.$data->event_id.'" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
//                    $button .= '&nbsp;&nbsp;';
//                    $button .= '<a href="javascript:void(0);" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
//                    $button .= '&nbsp;&nbsp;';
//                    $button .= '<a href="' . route('companyAccreditCat', $data->id) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="Company Accreditation Size"><i class="mdi mdi-grid-large menu-items"></i></a>';
//                    return $button;
//                })
////                ->addColumn('event_id', function($event_id){
////                    return $event_id;
////                })
//                ->rawColumns(['action'])
//                ->make(true);
//        }

        if (request()->ajax()) {
//            var_dump('ji');
//            exit;
            $where = array('company_admin_id' => Auth::user()->id);
            $company = Company::where($where)->first();
            $participants = DB::select('select * from paticipants where company = ?' ,[$company->id]);
//            var_dump($company->id);
//            exit;
            return datatables()->of($participants)
                ->addColumn('name', function($row){
                    return $row->first_name.' '.$row->last_name;
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
        return view('pages.CompanyAdmin.company-participants')->with('eventid',$id);
    }
}
