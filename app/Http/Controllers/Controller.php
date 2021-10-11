<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function getNotifications(){
    //     $notifications = auth()->user()->notifications()
    //                            ->orderBy('read_at', 'asc')
    //                            ->orderBy('created_at', 'desc')
    //                            ->get();

    //     return Response::json($notifications);

    // }

}
