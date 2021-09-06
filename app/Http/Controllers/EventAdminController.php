<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventAdminController extends Controller
{
    public function index()
    {
        $events = DB::select('select * from events_view where event_admin_id = ?', [Auth::user()->id]);
//        var_dump($events);
//        exit;
//        $events = DB::select('select * from events_view  , events_view v');
        return view('pages.EventAdmin.event-admin')->withEvents($events);
    }
}
