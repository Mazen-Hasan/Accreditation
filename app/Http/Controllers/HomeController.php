<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        return view('pages.event.events');

        $role = Auth::user()->roles->first()->slug;
        // var_dump($role);
        // exit;
        switch ($role) {
            case 'super-admin':
                return view('pages.Event.events');
                break;
            case 'event-admin':
                return Redirect::to('event-admin');
                break;
            case 'company-admin':
                return Redirect::to('company-admin');
                break;
            case 'security-officer':
                return Redirect::to('security-officer-admin');
                break;
            case 'data-entry':
                // var_dump('here');
                // exit;
                return Redirect::to('dataentry-participants');
                break;
        }
    }

//    public function redirectTo() {
//        $role = Auth::user()->roles->first()->slug;
//        switch ($role) {
//            case 'super-admin':
//                return view('pages.event.events');
//                break;
//            case 'event-admin':
//                return Route('event-admin');
//                break;
//        }
//    }

}
