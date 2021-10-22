<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AlertNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;

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
    }

    public function index()
    {
        return view('product');
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
