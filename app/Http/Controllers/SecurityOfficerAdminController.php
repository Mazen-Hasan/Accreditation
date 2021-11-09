<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\FocalPoint;
use App\Models\EventCompany;
use App\Models\SelectOption;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class SecurityOfficerAdminController extends Controller
{

    public function index()
    {
        $events = DB::select('select * from event_security_officers_view where security_officer_id = ? and approval_option in (1,3) ', [Auth::user()->id]);
        return view('pages.SecurityOfficerAdmin.security-officer-admin')->with('events', $events);
    }

    public function securityOfficerCompanies($id)
    {
        $where = array('id' => $id);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view where event_id = ?', [$id]);
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('securityOfficerCompanyParticipants', [$data->id, $data->event_id]) . '" id="company-participant" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Participants"><i class="fas fa-users"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.SecurityOfficerAdmin.security-officer-companies')->with('eventid', $id)->with('event_name', $event->name);
    }

    public function Invite($companyId)
    {
        $where = array('id' => $companyId);
        $company = Company::where($where)->first();
        $where = array('id' => $company->focal_point_id);
        $focalpoint = FocalPoint::where($where)->first();
        $post = Company::updateOrCreate(['id' => $companyId],
            [
                'company_admin_id' => $focalpoint->account_id,
                'status' => 3
            ]);
        $focalpointUpdate = FocalPoint::updateOrCreate(['id' => $focalpoint->id],
            [
                'company_id' => $companyId
            ]);
        return Response::json($post);
    }

    public function securityOfficerCompanyParticipants($companyId, $eventId)
    {
        $dataTableColumuns = array();

        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();
        if($companyId != 0){
            $where = array('id' => $companyId);
            $company = Company::where($where)->get()->first();
            $company_admin_id = $company->id;
        }else{
            $company_admin_id = $event->id;
        }

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        Schema::dropIfExists('temp_' . $company_admin_id);
        Schema::create('temp_' . $company_admin_id, function ($table) use ($templateFields) {
            $table->string('id');
            foreach ($templateFields as $templateField) {
                $dataTableColumuns[] = $templateField->label_en;
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        if ($companyId == 0) {
            $where = array('event_id' => $eventId,'status'=>1);
        } else {
            $where = array('event_id' => $eventId, 'company_id' => $company->id,'status'=>1);
        }
        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            if ($companyId != 0) {
                $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            } else {
                $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            }
            //$staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            $staffDataValues = array();
            $staffDataValues[] = $companyStaff->id;
            foreach ($staffDatas as $staffData) {
                if ($staffData->slug == 'select') {
                    $where = array('template_field_id' => $staffData->template_field_id, 'value_id' => $staffData->value);
                    $value = TemplateFieldElement::where($where)->first();
                    $staffDataValues[] = $value->value_en;
                } else {
                    $staffDataValues[] = $staffData->value;
                }
            }
            $alldata[] = $staffDataValues;
        }
        $query = '';
        foreach ($alldata as $data) {
            $query = 'insert into temp_' . $company_admin_id . ' (id';
            foreach ($templateFields as $templateField) {
                $query = $query . ',' . preg_replace('/\s+/', '_', $templateField->label_en);
            }
            $query = $query . ') values (';
            foreach ($data as $staffDataValue) {
                $query = $query . '"' . $staffDataValue . '",';
            }
            $query = substr($query, 0, strlen($query) - 1);
            $query = $query . ')';
            DB::insert($query);
        }
        if (request()->ajax()) {
            $participants = DB::select('select t.* , c.* from temp_' . $company_admin_id . ' t inner join company_staff c on t.id = c.id');
            return datatables()->of($participants)
                ->addColumn('status', function ($data) {
                    $status_value = "initaited";
                    switch ($data->status) {
                        case 0:
                            $status_value = "Initiated";
                            break;
                        case 1:
                            $status_value = "Waiting Security Officer Approval";
                            break;
                        case 2:
                            $status_value = "Waiting Event Admin Approval";
                            break;
                        case 3:
                            $status_value = "Approved by security officer";
                            break;
                        case 4:
                            $status_value = "Rejected by security officer";
                            break;
                        case 5:
                            $status_value = "Rejected by event admin";
                            break;
                        case 6:
                            $status_value = "Approved by event admin";
                            break;
                        case 7:
                            $status_value = "Rejected with correction by security officer";
                            break;
                        case 8:
                            $status_value = "Rejected with correction by event admin";
                            break;
                        case 9:
                            $status_value = "Badge generated";
                            break;
                        case 10:
                            $status_value = "Badge printed";
                            break;
                    }
                    return $status_value;
                })
                ->addColumn('action', function ($data) {
                    $button = '';
                    $button .= '<a href="' . route('securityParticipantDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    switch ($data->status) {
                        case 1:
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' . $data->id . '" data-original-title="Edit" title="Approve"><i class="fas fa-vote-yea"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $data->id . '" data-original-title="Edit" title="Reject"><i class="fas fa-ban"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $data->id . '" data-original-title="Edit" title="Reject with correction"><i class="far fa-window-close"></i></a>';
                            break;
                        case 7:
                            $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->security_officer_reject_reason . '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                            break;
                    }
                    return $button;
                })
                ->addColumn('image', function ($data) {
                    $image = '';
                    //$image .= '<a href="' . route('templateFormDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $image .= '<img src="'. asset('storage/badges/'.$data->Personal_Image).'" alt="Personal" class="pic-img" style="margin-left:40px">';
                    return $image;
                })
                ->rawColumns(['image','status', 'action'])
                ->make(true);
        }
        return view('pages.SecurityOfficerAdmin.security-officer-company-participants')->with('dataTableColumns', $dataTableColumuns)->with('company_id', $companyId)->with('event_id', $eventId);
    }

    public function Approve($staffId)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();

        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;
        if ($approval == 1) {
            DB::update('update company_staff set status = ? where id = ?', [3, $staffId]);
            $event_companies = EventCompany::where(['event_id'=>$eventId, 'company_id'=> $companyId])->first();
            $focal_point = DB::select('select * from focal_points f where f.id = ?', [$event_companies->focal_point_id]);
//            NotificationController::sendAlertNotification($focal_point[0]->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approved', Route('templateFormDetails' , [$staffId]));

            $notification_type = Config::get('enums.notification_types.PAP');
            NotificationController::sendNotification($notification_type, $event->name, $company->name, $focal_point[0]->account_id, $staffId,
                $event->name . ': ' . $company->name . ': ' . 'Participant approved',
                Route('templateFormDetails' , [$staffId]));
        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [3, $staffId]);
                $event_companies = EventCompany::where(['event_id'=>$eventId, 'company_id'=> $companyId])->first();
                $focal_point = DB::select('select * from focal_points f where f.id = ?', [$event_companies->focal_point_id]);
//                NotificationController::sendAlertNotification($focal_point[0]->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approved', Route('templateFormDetails' , [$staffId]));

                $notification_type = Config::get('enums.notification_types.PAP');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $focal_point[0]->account_id, $staffId,
                    $event->name . ': ' . $company->name . ': ' . 'Participant approved',
                    Route('templateFormDetails' , [$staffId]));
            }
        }
        return Response::json($event);
    }

    public function Reject($staffId)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();

        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;
        $eventCompanies = EventCompany::where(['company_id'=> $companyId ,'event_id'=> $eventId])->first();
        $focalPoint = FocalPoint::where(['id'=>$eventCompanies->focal_point_id])->first();
        if ($approval == 1) {
            DB::update('update company_staff set status = ? where id = ?', [4, $staffId]);
//            NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant rejected', Route('templateFormDetails' , $staffId));

            $notification_type = Config::get('enums.notification_types.PRE');
            NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                $event->name . ': ' . $company->name . ': ' . 'Participant rejected', Route('templateFormDetails' , $staffId));
        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [4, $staffId]);
//                NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant rejected', Route('templateFormDetails' , $staffId));

                $notification_type = Config::get('enums.notification_types.PRE');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                    $event->name . ': ' . $company->name . ': ' . 'Participant rejected', Route('templateFormDetails' , $staffId));
            }
        }
        return Response::json($event);
    }

    public function RejectToCorrect($staffId, $reason)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();

        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;
        $eventCompanies = EventCompany::where(['company_id'=> $companyId ,'event_id'=> $eventId])->first();
        $focalPoint = FocalPoint::where(['id'=>$eventCompanies->focal_point_id])->first();
        if ($approval == 1) {
            DB::update('update company_staff set status = ? where id = ?', [7, $staffId]);
            DB::update('update company_staff set security_officer_reject_reason = ? where id = ?', [$reason, $staffId]);
//            NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant returend for correction', Route('templateFormDetails' , $staffId));

            $notification_type = Config::get('enums.notification_types.PRC');
            NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                $event->name . ': ' . $company->name . ': ' . 'Participant returned for correction',
                Route('templateFormDetails' , $staffId));
        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [7, $staffId]);
                DB::update('update company_staff set security_officer_reject_reason = ? where id = ?', [$reason, $staffId]);
//                NotificationController::sendAlertNotification($focalPoint->account_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant returend for correction', Route('templateFormDetails' , $staffId));

                $notification_type = Config::get('enums.notification_types.PRC');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $focalPoint->account_id, $staffId,
                    $event->name . ': ' . $company->name . ': ' . 'Participant returned for correction',
                    Route('templateFormDetails' , $staffId));
            }
        }
        return Response::json($event);
    }

    public function details($participant_id)
    {

        $where = array('id' => $participant_id);
        $participant = CompanyStaff::where($where)->first();

        $where = array('id' => $participant->company_id);
        $company = Company::where($where)->first();

        $company_admin_id = $company->company_admin_id;


        $where = array('id' => $participant->event_id);
        $event = Event::where($where)->first();

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }
        //$participants = DB::select('select t.* , c.* from temp' . $company_admin_id . ' t inner join company_staff c on t.id = c.id where c.id = ?', [$participant_id]);
        $participants = DB::select('select * from company_staff c where c.id = ?', [$participant_id]);
        $status_value = "initaited";
        $status = 0;
        $event_reject_reason = '';
        $security_officer_reject_reason = '';
        foreach ($participants as $participant) {
            $status = $participant->status;
            $event_reject_reason = $participant->event_admin_reject_reason;
            $security_officer_reject_reason = $participant->security_officer_reject_reason;
            switch ($participant->status) {
                case 0:
                    $status_value = "Initiated";
                    break;
                case 1:
                    $status_value = "waiting Security Officer Approval";
                    break;
                case 2:
                    $status_value = "waiting Event Admin Approval";
                    break;
                case 3:
                    $status_value = "approved by security officer";
                    break;
                case 4:
                    $status_value = "rejected by security officer";
                    break;
                case 5:
                    $status_value = "rejected by event admin";
                    break;
                case 6:
                    $status_value = "approved by event admin";
                    break;
                case 7:
                    $status_value = "rejected with correction by security officer";
                    break;
                case 8:
                    $status_value = "rejected with correction by event admin";
                    break;
                case 9:
                    $status_value = "Badge generated";
                    break;
                case 10:
                    $status_value = "Badge printed";
                    break;
            }
        }
        $fieldsCount = 0;
        $form = '';
        $options = array();
        $form .= '<div class="row">';
        $form .= $this->createStatusFieldLabel("status", "Status", 0, 1, 1, $status_value);
        $form .= '</div>';
        if ($status == 8) {
            $form .= '<div class="row">';
            $form .= $this->createStatusFieldLabel("reject_reason", "Reject Reason", 0, 1, 1, $event_reject_reason);
            $form .= '</div>';
        }
        if ($status == 7) {
            $form .= '<div class="row">';
            $form .= $this->createStatusFieldLabel("reject_reason", "Reject Reason", 0, 1, 1, $security_officer_reject_reason);
            $form .= '</div>';
        }
        $form .= '<div class="row">';
        $attachmentForm = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenFieldLabel('participant_id', 'participant_id', '');
        } else {
            $form .= $this->createHiddenFieldLabel('participant_id', 'participant_id', $participant_id);
        }
        foreach ($templateFields as $templateField) {
            $options = [];
            if ($fieldsCount % 2 == 0) {
                if ($fieldsCount > 0) {
                    $form .= '</div>';
                }
                $form .= '<div class="row">';
            }
            $fieldsCount++;

            switch ($templateField->slug) {
                case 'text':
                    if ($participant_id == 0) {
                        $form .= $this->createTextFieldLabel($templateField->label_en, $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        $form .= $this->createTextFieldLabel($templateField->label_en, $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'number':
                    if ($participant_id == 0) {
                        $form .= $this->createNumberFieldLabel($templateField->label_en, $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        $form .= $this->createNumberFieldLabel($templateField->label_en, $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextAreaLabel($templateField->label_en, $templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if ($participant_id == 0) {
                        $form .= $this->createDateLabel($templateField->label_en, $templateField->label_en, $templateField->mandatory, '');
                    } else {
                        $form .= $this->createDateLabel($templateField->label_en, $templateField->label_en, $templateField->mandatory, $templateField->value);
                    }
                    break;

                case 'select':
                    if ($participant_id == 0) {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $templateField->label_en, $options, '');
                    } else {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->template_field_id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $templateField->label_en, $options, $templateField->value);
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if ($participant_id == 0) {
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, $templateField->label_en, 0, '');
                        $form .= $this->createHiddenFieldLabel($templateField->label_en, $templateField->label_en, '');
                    } else {
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, $templateField->label_en, 0, $templateField->value);
                        $form .= $this->createHiddenFieldLabel($templateField->label_en, $templateField->label_en, $templateField->value);
                        if($templateField->label_en == 'Personal Image'){
                            $image = $this->createPersonalImage($templateField->value);
                            $form = $image.$form;
                        }
                    }


                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }
        $buttons = '';
        switch ($status) {
            case 1:
                $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Aprrove</a>';
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject</a>';
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject with correction</a>';
                break;
        }
        return view('pages.SecurityOfficerAdmin.security-officer-participant-details')->with('form', $form)->with('attachmentForm', $attachmentForm)->with('buttons', $buttons)->with('companyId', $participant->company_id)->with('eventId', $participant->event_id);
    }


    public function createStatusFieldLabel($id, $label, $mandatory, $min_char, $max_char, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $textfield = '<div class="col-md-8" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger; color:red;
        text-align: center;
        padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createHiddenFieldLabel($id, $label, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextFieldLabel($id, $label, $mandatory, $min_char, $max_char, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center;
        background-color: darkgray;
        padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createNumberFieldLabel($id, $label, $mandatory, $min_value, $max_value, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }


        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center;
        background-color: darkgray;
        padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createTextAreaLabel($id, $label, $mandatory)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . '></textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createDateLabel($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }


        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center;
        background-color: darkgray;
        padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createSelectLabel($id, $label, $elements, $value)
    {

        $selectValue = '';
        foreach ($elements as $element) {
            if ($element->key == $value) {
                $selectValue = $element->value;
            }
        }
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center;
        background-color: darkgray;
        padding:10px">' . $selectValue . '</label></div>';
        $textfield .= '</div></div>';


        return $textfield;
    }

    public function createAttachmentLabel($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }


        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "  : </label></div>";
        $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-label="' . $label . '"  data-src="' . $value . '" data-original-title="Preview" class="edit btn btn-danger preview-badge">Preview</a>';
        $textfield .= '<div class="col-md-6" style="height:70px">' . $button . '</div>';
        $textfield .= '</div></div>';
        return $textfield;
    }

    public function createMultiSelectLabel($id, $label, $elements)
    {
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  multiple id="' . $id . '" name="' . $label . '[]">';
        foreach ($elements as $element) {
            $selectField .= '<option value="' . $element->key . '">' . $element->value . '</option>';
        }

        $selectField .= '</select></div></div></div>';
        return $selectField;
    }

    public function createPersonalImage($value){
        $personalImage = '';
        $personalImage = $personalImage .'<div class="row>';
        $personalImage = $personalImage .'<div class="form-group col">';
        $personalImage = $personalImage .'<img id="paticipant_iamge" src="'. asset('storage/badges/'.$value).'" alt="Personal" class="pic-img">';
        $personalImage = $personalImage .'</div></div>';
        return $personalImage;
    }

}
