<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;

trait EmailTrait {

    static public function getEmailTemplate($emailTemplateType, $event_name, $company_name, $url) {

        $emailTemplate  = DB::select('select v.content from email_view v where v.slug=?',[$emailTemplateType]);

        return str_ireplace(['@event_name','@company_name','@url'],[$event_name, $company_name, $url], $emailTemplate[0]->content);
    }
}
