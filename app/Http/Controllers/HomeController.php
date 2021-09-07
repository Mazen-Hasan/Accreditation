<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        switch ($role) {
            case 'super-admin':
                return view('pages.event.events');
                break;
            case 'event-admin':
                return Redirect::to('event-admin');
                break;
            case 'company-admin':
                return Redirect::to('company-admin');
                break;
            default:
                return view('pages.event.events');
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
