<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertNotification;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index()
    {
        return view('product');
    }
    
    // public static function sendAlertNotification($userId , $participantId, $eventName , $companyName, $participantName , $action, $url) {
    //     $userSchema = User::where(array('id' => $userId))->first();
    //     $participantData = [
    //         'event_name' => $eventName,
    //         'participant_name' => $participantName,
    //         'company_name' => $companyName,
    //         'action' => $action,
    //         'Url' => url($url),
    //         'participant_id' => $participantId
    //     ];
  
    //     Notification::send($userSchema, new AlertNotification($participantData));
   
    //     //dd('Task completed!');
    // }

    public static function sendAlertNotification($userId , $participantId,$text, $url) {
        $userSchema = User::where(array('id' => $userId))->first();
        $participantData = [
            'text' => $text,
            'Url' => url($url),
            'participant_id' => $participantId
        ];
  
        Notification::send($userSchema, new AlertNotification($participantData));
   
        //dd('Task completed!');
    }

    public function getNotifications() {
        $notifications = array();
        foreach (auth()->user()->unreadNotifications as $notification){
            $notifications[] = $notification;
        }
        return Response::json($notifications);
        //dd('Task completed!');
    }

    public function markAsRead($id){
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return Response::json([
            "errMsg" => 'Success',
            "errCode" => '1'
        ]);
    }
}
