<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait LogTrait {

    static public function supperAdminLog($actionId, $actionResult, $params, $exception) {

        $supperAdminLog  = DB::insert('INSERT INTO super_admin_log (user_id, action_id, action_result, params, exception_desc)
        VALUES (?, ?, ?, ?, ?)',[Auth::user()->id, $actionId, $actionResult,  $params ,  $exception ]);
    }
}
