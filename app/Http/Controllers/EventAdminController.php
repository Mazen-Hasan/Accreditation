<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\EventCompany;
use App\Models\FocalPoint;
use App\Models\SelectOption;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class EventAdminController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view');
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('companyEdit', $data->id) . '" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-company" title="Edit Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="invite-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Invite Company"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('companyAccreditCat', $data->id) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="Company Accreditation Size"><i class="mdi mdi-grid-large menu-items"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $events = DB::select('select * from event_admins_view where event_admin = ?', [Auth::user()->id]);
        return view('pages.EventAdmin.event-admin')->with('events', $events);
    }

    public function eventCompanies($id)
    {
        $where = array('id' => $id);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view where event_id = ? and parent_id is null', [$id]);
            return datatables()->of($companies)
                ->addColumn('action', function ($data) {
                    $button = '<a href="../company-edit/' . $data->id . '/' . $data->event_id . '" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="invite-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-name="' . $data->name . '" data-focalpoint="' . $data->focal_point . '" title="Invite"><i class="far fa-share-square"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('companyAccreditCat', [$data->id, $data->event_id]) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Accreditation Size"><i class="fas fa-sitemap"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('eventCompanyParticipants', [$data->id, $data->event_id]) . '" id="company-participant" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Participants"><i class="fas fa-users"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.EventAdmin.event-companies')->with('eventid', $id)->with('event_name', $event->name);
    }

    public function Invite($companyId, $eventId)
    {
        // $where = array('id' => $companyId);
        // $company = Company::where($where)->first();
        // $where = array('id' => $company->focal_point_id);
        // $focalpoint = FocalPoint::where($where)->first();
        $post = EventCompany::updateOrCreate(['company_id' => $companyId,'event_id'=>$eventId],
            [
                //'company_admin_id' => $focalpoint->account_id,
                'status' => 3
            ]);
        // $focalpointUpdate = FocalPoint::updateOrCreate(['id' => $focalpoint->id],
        //     [
        //         'company_id' => $companyId
        //     ]);
        return Response::json($post);
    }

    public function eventCompanyParticipants($companyId, $eventId)
    {
        $dataTableColumuns = array();
        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();
        $event_name = $event->name;

        if ($companyId != 0) {
            $where = array('id' => $companyId);
            $company = Company::where($where)->get()->first();
            $company_name = $company->name;
            $company_admin_id = $company->company_admin_id;
        } else {
            $company_admin_id = '_Event' . $event->event_admin;
            $company_name = '';
        }

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        Schema::dropIfExists('temp' . $company_admin_id);
        Schema::create('temp' . $company_admin_id, function ($table) use ($templateFields, $companyId) {
            $table->string('id');
            foreach ($templateFields as $templateField) {
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        if ($companyId == 0) {
            $where = array('event_id' => $eventId);
        } else {
            $where = array('event_id' => $eventId, 'company_id' => $company->id);
        }

        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            // $staffDatas = StaffData::where($where)->get()->all();
            if ($companyId != 0) {
                $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            } else {
                $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            }
            $staffDataValues = array();
            $staffDataValues[] = $companyStaff->id;
            $count = 0;
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
            $query = '';
            if ($companyId == 0) {
                $query = $query . 'insert into temp' . $company_admin_id . ' (id';
            } else {
                $query = $query . 'insert into temp' . $company_admin_id . ' (id';
            }
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
            $participants = DB::select('select t.* , c.* from temp' . $company_admin_id . ' t inner join company_staff c on t.id = c.id');
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
                    $button .= '<a href="' . route('participantDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->print_status == 0) {
                        $button .= '<a href="javascript:void(0);" id="generate-badge" data-toggle="tooltip" data-original-title="Generate" data-id="' . $data->id . '" title="Generate"><i class="fas fa-cogs"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    } else {
                        $printed = $data->print_status == 2 ? 'printed' : '';
                        $button .= '<a href="javascript:void(0);" id="preview-badge" data-toggle="tooltip" data-original-title="Preview" data-id="' . $data->id . '" class="preview-badge"' . $printed . '" title="Preview"><i class="far fa-eye"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }

                    switch ($data->status) {
                        case 2:
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' . $data->id . '" data-original-title="Edit" title="Approve"><i class="fas fa-vote-yea"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $data->id . '" data-original-title="Edit" title="Reject"><i class="fas fa-ban"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $data->id . '" data-original-title="Edit" title="Reject with correction"><i class="far fa-window-close"></i></a>';
                            break;
                        case 7:
                            $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->security_officer_reject_reason . '" class="delete btn btn-danger">Reject Reason</a>';
                            break;
                        case 8:
                            $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->event_admin_reject_reason . '" class="delete btn btn-danger">Reject Reason</a>';
                            break;
                    }
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('pages.EventAdmin.event-company-participants')->with('dataTableColumns', $dataTableColumuns)->with('company_id', $companyId)->with('event_id', $eventId)->with('company_name',$company_name)->with('event_name',$event_name);
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
        if ($approval == 2) {
            DB::update('update company_staff set status = ? where id = ?', [6, $staffId]);

        } else {
            if ($approval == 3) {
                NotificationController::sendAlertNotification($event->security_officer, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', '/security-officer-participant-details/' . $staffId);
                DB::update('update company_staff set security_officer_id = ? where id = ?', [$event->security_officer, $staffId]);
                DB::update('update company_staff set status = ? where id = ?', [1, $staffId]);
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

        $approval = $event->approval_option;
        if ($approval == 2) {
            DB::update('update company_staff set status = ? where id = ?', [5, $staffId]);

        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [5, $staffId]);
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

        $approval = $event->approval_option;
        if ($approval == 2) {
            DB::update('update company_staff set status = ? where id = ?', [8, $staffId]);
            DB::update('update company_staff set event_admin_reject_reason = ? where id = ?', [$reason, $staffId]);

        } else {
            if ($approval == 3) {
                DB::update('update company_staff set status = ? where id = ?', [8, $staffId]);
                DB::update('update company_staff set event_admin_reject_reason = ? where id = ?', [$reason, $staffId]);
            }
        }
        return Response::json($event);
    }

    public function details($participant_id)
    {

        $where = array('id' => $participant_id);
        $participant = CompanyStaff::where($where)->first();

        $where = array('id' => $participant->event_id);
        $event = Event::where($where)->first();
        $event_name = $event->name;

        $where = array('id' => $participant->company_id);
        $company = Company::where($where)->first();
        $company_name = $company->name;

        $company_admin_id = '_Event' . $event->event_admin;

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }
        $participants = DB::select('select t.* , c.* from temp' . $company_admin_id . ' t inner join company_staff c on t.id = c.id where c.id = ?', [$participant_id]);
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
        }

        $fieldsCount = 0;
        $form = '';
        $options = array();
        $form .= '<div class="row">';
        $form .= $this->createStatusFieldLabel("Status",  $status_value);
        $form .= '</div>';
        if ($status == 8) {
            $form .= '<div class="row">';
            $form .= $this->createStatusFieldLabel("Reject Reason", $event_reject_reason);
            $form .= '</div>';
        }
        if ($status == 7) {
            $form = '<div class="row">';
            $form .= $this->createStatusFieldLabel("Reject Reason", $security_officer_reject_reason);
            $form .= '</div>';
        }
        $form .= '<div class="row">';
        $attachmentForm = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenFieldLabel('participant_id', '');
        } else {
            $form .= $this->createHiddenFieldLabel( 'participant_id', $participant_id);
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
                        $form .= $this->createTextFieldLabel($templateField->label_en, '');
                    } else {
                        $form .= $this->createTextFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'number':
                    if ($participant_id == 0) {
                        $form .= $this->createNumberFieldLabel( $templateField->label_en, '');
                    } else {
                        $form .= $this->createNumberFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextAreaLabel($templateField->label_en, $templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if ($participant_id == 0) {
                        $form .= $this->createDateLabel($templateField->label_en,  '');
                    } else {
                        $form .= $this->createDateLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'select':
                    if ($participant_id == 0) {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $options, '');
                    } else {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->template_field_id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $options, $templateField->value);
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if ($participant_id == 0) {
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, '');
                        $form .= $this->createHiddenFieldLabel($templateField->label_en, '');
                    } else {
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, $templateField->value);
                        $form .= $this->createHiddenFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }
        $buttons = '';
        switch ($status) {
            case 2:
                $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip" id="approve"  data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Aprrove</a>';
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject</a>';
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="javascript:void(0)" data-toggle="tooltip"  id="reject_with_correction" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Reject with correction</a>';
                break;
        }
        return view('pages.EventAdmin.event-participant-details')->with('form', $form)->with('attachmentForm', $attachmentForm)->with('companyId', $participant->company_id)->with('eventId', $participant->event_id)->with('buttons', $buttons)->with('event_name',$event_name)->with('company_name', $company_name);
    }


    public function createStatusFieldLabel($label, $value)
    {
        $textfield = '<div class="col-md-8" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "</label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger; color:red;
        text-align: center; padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createHiddenFieldLabel($id, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextFieldLabel($label, $value)
    {
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "</label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center; background-color: darkgray; padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createNumberFieldLabel($label, $value)
    {
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "</label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center; background-color: darkgray; padding:10px">' . $value . '</label></div>';
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

    public function createDateLabel($label, $value)
    {
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "</label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center; background-color: darkgray;padding:10px">' . $value . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createSelectLabel($label, $elements, $value)
    {
        $selectValue = '';
        foreach ($elements as $element) {
            if ($element->key == $value) {
                $selectValue = $element->value;
            }
        }

        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "</label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
        text-align: center; background-color: darkgray; padding:10px">' . $selectValue . '</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createAttachmentLabel($label, $value)
    {
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label . "</label></div>";
        $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-label="' . $label . '"  data-src="' . $value . '" data-original-title="Preview" class="edit btn btn-danger preview-badge">Preview</a>';
        $textfield .= '<div class="col-md-6" style="height:70px">' . $button . '</div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

}
