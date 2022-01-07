<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\User;
use App\Notifications\AlertNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use App\Http\Traits\EmailTrait;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function sendAlertNotification($userId, $participantId, $text, $url)
    {
        $userSchema = User::where(array('id' => $userId))->first();
        $participantData = [
            'text' => $text,
            'Url' => url($url),
            'participant_id' => $participantId
        ];

        Notification::send($userSchema, new AlertNotification($participantData));

//        $info = array(
//            'name' => "Alex"
//        );
//
//        Mail::send([], $info, function ($message)
//        {
//            $message->to('e.mazen.hasan@gmail.com', 'Mazen')
//                ->subject('Basic test eMail from Laravel.');
//            $message->from('admin@accrediation.com', 'Admin');
//        });
    }

    public function index()
    {
        return view('product');
    }


    public static function sendNotification($type, $event_name, $company_name, $userId, $participantId, $text, $url)
    {
        $userSchema = User::where(array('id' => $userId))->first();
        $participantData = [
            'text' => $text,
            'Url' => url($url),
            'participant_id' => $participantId
        ];

        Notification::send($userSchema, new AlertNotification($participantData));

        $emailData = EmailTrait::getEmailTemplate($type, $event_name, $company_name, $url);

        //Mail::to($userSchema->email)->send(new Email($emailData));

//        Mail::send([], $info, function ($message)
//        {
//            $message->to('e.mazen.hasan@gmail.com', 'Mazen')
//                ->subject('Basic test eMail from Laravel.');
//            $message->from('admin@accrediation.com', 'Admin');
//        });
    }


    public function getNotifications()
    {
        $notifications = array();
        foreach (auth()->user()->unreadNotifications as $notification) {
            $notifications[] = $notification;
        }
        return Response::json($notifications);
    }

    public function markAsRead($id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return Response::json([
            "errMsg" => 'Success',
            "errCode" => '1'
        ]);
    }
}
