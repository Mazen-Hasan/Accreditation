<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;

trait EmailTrait {

    static public function getEmailTemplate($emailTemplateType, $event_name, $company_name, $url) {

        $emailTemplate  = DB::select('select * from email_view v where v.slug=?',[$emailTemplateType]);

        $emailData = array(
            'subject' => $emailTemplate[0]->email_template_subject,
            'content' => str_ireplace(['@event_name','@company_name','@url'],[$event_name, $company_name, $url], $emailTemplate[0]->content));
        
        return $emailData;
    }
}
