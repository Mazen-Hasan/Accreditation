<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventAdminController extends Controller
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

        $events = DB::select('select * from events_view where event_admin_id = ?', [Auth::user()->id]);
//        var_dump($events);
//        exit;
//        $events = DB::select('select * from events_view  , events_view v');
        return view('pages.EventAdmin.event-admin')->withEvents($events);
    }

    public function eventCompanies($id)
    {
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view where event_id = ?' ,[$id]);
//            $companies = DB::select('select * from companies_view');
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="../company-edit/'.$data->id.'/'.$data->event_id.'" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('companyAccreditCat',[$data->id , $data->event_id]) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="Company Accreditation Size"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    return $button;
                })
//                ->addColumn('event_id', function($event_id){
//                    return $event_id;
//                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.EventAdmin.event-companies')->with('eventid',$id);
    }
}
