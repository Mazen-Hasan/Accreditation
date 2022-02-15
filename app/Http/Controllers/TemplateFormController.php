<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\SelectOption;
use App\Models\StaffData;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\CompanyAccreditaionCategory;
use Illuminate\Support\Facades\Schema;

class TemplateFormController extends Controller
{
    public function index($participant_id,$companyId,$eventId)
    {

        $where = array('id' => $companyId);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $eventId);
        $event = Event::where($where)->first();

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }

        $fieldsCount = 0;

        $options = array();
        $form = '<div class="row">';
        $attachmentForm = '';
        $attachmentFormHidden = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenField('participant_id', 'participant_id', '');
        } else {
            $form .= $this->createHiddenField('participant_id', 'participant_id', $participant_id);
        }
        $form .= $this->createHiddenField('company_id', 'company_id', $companyId);
        $form .= $this->createHiddenField('event_id', 'event_id', $eventId);
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
                        if (strtolower($templateField->label_en) == 'company' or strtolower($templateField->label_en) == 'event') {
                            if (strtolower($templateField->label_en) == 'company') {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $company->name);
                            } else {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $event->name);
                            }
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        if (strtolower($templateField->label_en) == 'company' or strtolower($templateField->label_en) == 'event') {
                            $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'number':
                    if ($participant_id == 0) {
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextArea(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if ($participant_id == 0) {
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->mandatory, '');
                    } else {
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->mandatory, $templateField->value);
                    }
                    break;

                case 'select':
					if (strtolower($templateField->label_en) == 'accreditation category') {
                        if ($participant_id == 0) {
                            $fieldElements = DB::select('select * from template_field_elements f inner join event_company_accrediation_categories_view e on e.accredit_cat_id = f.value_id where f.template_field_id = ? and e.size <> e.inserted and company_id =? and event_id = ?', [$templateField->id,$companyId,$eventId]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, '');
                        } else {
                            $fieldElements = DB::select('select * from template_field_elements f inner join event_company_accrediation_categories_view e on e.accredit_cat_id = f.value_id where f.template_field_id = ? and company_id =? and event_id = ?', [$templateField->template_field_id,$companyId,$eventId]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, $templateField->value);
                        }
                    }else{
                        if ($participant_id == 0) {
                            $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->id]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, '');
                        } else {
                            $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->template_field_id]);
                            foreach ($fieldElements as $fieldElement) {
                                $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                                $options [] = $option;
                            }
                            $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, $templateField->value);
                        }
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if ($participant_id == 0) {
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, 0, '');
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, '');
                    } else {
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, 0, $templateField->value);
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                    }


                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }

        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.TemplateForm.template-form-add')->with('form', $form)->with('attachmentForm', $attachmentForm)->with('subCompany_nav', $subCompany_nav)->with('companyId',$companyId)->with('eventId',$eventId);
    }

    public function searchParticipants($fullName, $companyID){
        $returnedParticipants = array();
        $data = array();

        //search return id & full name
        $participants = DB::select('select s.staff_id, s.value from staff_data s where s.key = "Full_name" and lower(s.value) like ? AND s.staff_id IN(
        SELECT i.staff_id FROM staff_data i WHERE i.key = "company_id" AND i.value = ?)',
            [ strtolower($fullName) . '%', $companyID]);

        //get staff ids
        $staff_ids = array();
        foreach ($participants as $participant){
            $staff_ids  [] = $participant->staff_id;
        }

        //get staff data based on ids
        $participantsData = DB::table('staff_data')->select(['staff_id','key','value'])->whereIn('staff_id', $staff_ids)->get();

        $data = array();
        foreach ($participantsData as $participantData){
            $data [$participantData->staff_id][$participantData->key] = $participantData->value;
        }

        return Response()->json([
            "searchRes" => $participants,
            "list" => $data
        ]);
    }

    public function createHiddenField($id, $label, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextField($id, $label, $mandatory, $min_char, $max_char, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if ($min_char) {
            $minChar = '" minlength="' . $min_char;
        }
        if ($max_char) {
            $maxChar = '"maxlength="' . $max_char;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . $minChar . $maxChar . '"' . $required . ' value="' . $value . '" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createNumberField($id, $label, $mandatory, $min_value, $max_value, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if ($min_value) {
            $minChar = '" min="' . $min_value;
        }
        if ($max_value) {
            $maxChar = '"max="' . $max_value;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="number" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . $minChar . $maxChar . '"' . $required . ' value="' . $value . '" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createTextArea($id, $label, $mandatory)
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

    public function createDate($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="date" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . ' value="' . $value . '" />';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createSelect($id, $label, $elements, $value)
    {
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  id="' . $id . '" name="' . $label . '">';
        foreach ($elements as $element) {
            $selectField .= '<option ';
            if ($element->key == $value) {
                $selectField .= ' selected="selected"';
            }
            $selectField .= ' value="' . $element->key . '">' . $element->value . '</option>';
        }

        $selectField .= '</select></div></div></div>';
        return $selectField;
    }

    public function createAttachment($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $attachmentField = '<form id=form_' . $id . '" name="badgeForm" class="form-horizontal  img-upload" enctype="multipart/form-data" action="javascript:void(0)">';
        $attachmentField .= '<div class="row"><div class="col-md-5"><label>' . $label . '</label></div>';
        $attachmentField .= '<div class="col-md-4"><div class="col-sm-12"><input type="file" id="file_' . $id . '" name=file_"' . $id . '" required=""></div></div>';
        $attachmentField .= '<div class="col-md-3"><button type="submit" id="btn-upload_' . $id . '" value="Upload">Upload</button></div></div>';
        $attachmentField .= '<div class="row"><div class="col-md-12"><div class="form-group col">';
        $attachmentField .= '<label id="file_type_error_' . $id . '"></label><div style="background-color: #ffffff00!important;" class="progress">';
        $attachmentField .= '<div id="file-progress-bar_' . $id . '" class="progress-bar"></div></div></div></div></div></form>';

        return $attachmentField;
    }

    public function createMultiSelect($id, $label, $elements)
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

    public function store(Request $request)
    {
    	$dataTableColumuns = array();
        $where = array('id' => $request->event_id);
        $event = Event::where($where)->get()->first();
        if($request->company_id != 0){
            $where = array('id' => $request->company_id);
            $company = Company::where($where)->get()->first();
            //$company_admin_id = $company->id;
        }
        // }else{
        //     $company_admin_id = $event->id;
        // }
        $company_admin_id = $event->id;
        // $where = array('template_id' => $event->event_form);
        // $templateFields = TemplateField::where($where)->get()->all();
    	$where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        //Schema::dropIfExists('temp_' . $company_admin_id);
        if(!Schema::hasTable('temp_' . $company_admin_id)){
            Schema::create('temp_' . $company_admin_id, function ($table) use ($templateFields) {
                $table->string('id');
                foreach ($templateFields as $templateField) {
                    $dataTableColumuns[] = $templateField->label_en;
                    $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
                }
            });
        }
        $participant_id = $request->participant_id;
        $companyStaff = CompanyStaff::updateOrCreate(['id' => $participant_id],
            ['event_id' => $request->event_id,
                'company_id' => $request->company_id,
                'security_officer_id' => '0',
                'security_officer_decision' => '0',
                'security_officer_decision_date' => null,
                'security_officer_reject_reason' => '',
                'event_admin_id' => '0',
                'event_admin_decision' => '0',
                'event_admin_decision_date' => null,
                'event_admin_reject_reason' => '',
                'status' => '0'
            ]);
		$staff = CompanyStaff::updateOrCreate(['id' => $companyStaff->id],
            ['identifier'=> '#'. md5($request->event_id.'-'.$request->company_id.'-'.$companyStaff->id)
            ]);
        $where = array('id' => $request->event_id);
        $event = Event::where($where)->get()->first();
        // $query = 'update templates t set t.is_locked = 1, t.can_unlock = 0 where t.id = ' . $event->event_form;

    	$query = 'update templates t set t.is_locked = 1, t.can_unlock = 0 where t.id = "' . $event->event_form .'"';
    	DB::update($query);

        $data = $request->all();

        foreach ($data as $key => $value) {
            if ($key != 'participant_id') {
                if ($participant_id != null) {
                    if($key == 'Accreditation_category'){
                        $staffdata = StaffData::where(['staff_id'=>$companyStaff->id,'key'=>$key])->first();
                        if($staffdata->value != $value){
                            $query = 'update company_accreditaion_categories set inserted = inserted - 1 where accredit_cat_id = ? and company_id = ? and event_id = ?';
                            DB::update($query,[$staffdata->value,$request->company_id,$request->event_id]);
                            $query = 'update company_accreditaion_categories set inserted = inserted + 1 where accredit_cat_id = ? and company_id = ? and event_id = ?';
                            DB::update($query,[$value,$request->company_id,$request->event_id]);
                        }
                    }
                    $query = 'update staff_data s set s.value = "' . $value . '" where s.staff_id = ' . $companyStaff->id . ' and s.key ="' . $key . '" ';
                    DB::update($query);
                } else {
                    $staffData = StaffData::updateOrCreate(['staff_id' => $companyStaff->id, 'key' => $key],
                        ['staff_id' => $companyStaff->id,
                            'key' => $key,
                            'value' => $value
                        ]);
                        if($key == 'Accreditation_category'){
                            $query = 'update company_accreditaion_categories set inserted = inserted + 1 where accredit_cat_id = ? and company_id = ? and event_id = ?';
                            DB::update($query,[$value,$request->company_id,$request->event_id]);
                        }

                }
            }
        }
		if ($request->company_id == 0) {
            $where = array('event_id' => $request->event_id,'id'=> $companyStaff->id);
        } else {
            $where = array('event_id' => $request->event_id, 'company_id' => $company->id, 'id' => $companyStaff->id);
        }
        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            if ($request->company_id != 0) {
                $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            } else {
                $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            }
            //$staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            $staffDataValues = array();
            foreach ($staffDatas as $staffData) {
                if ($staffData->slug == 'select') {
                    $where = array('template_field_id' => $staffData->template_field_id, 'value_id' => $staffData->value);
                    $value = TemplateFieldElement::where($where)->first();
                    $staffDataValues[] = $value->value_en;
                } else {
                    $staffDataValues[] = $staffData->value;
                }
            }
            $staffDataValues[] = $companyStaff->id;
            $alldata[] = $staffDataValues;
        }
        $query = '';
        foreach ($alldata as $data) {
            if ($participant_id != null) {
                $query = 'update `temp_' . $company_admin_id.'` set ';
                foreach ($templateFields as $templateField) {
                    $query = $query. preg_replace('/\s+/', '_', $templateField->label_en). ' = ? ,' ;
                }
                $query = substr($query, 0, strlen($query) - 1);
                $query = $query. ' where id = ?';
                DB::update($query,$data);
            }else{
                $query = 'insert into `temp_' . $company_admin_id . '` (';
                $tries = 0;
                foreach ($templateFields as $templateField) {
                    if($tries == 0){
                        $query = $query . preg_replace('/\s+/', '_', $templateField->label_en);
                        $tries = 1;
                    }else{
                        $query = $query . ',' . preg_replace('/\s+/', '_', $templateField->label_en);
                    }
                }
                $query = $query . ',id) values (';
                foreach ($data as $staffDataValue) {
                    $query = $query . '"' . $staffDataValue . '",';
                }
                $query = substr($query, 0, strlen($query) - 1);
                $query = $query . ')';
                DB::insert($query);
            }
        }
        return Response::json($companyStaff);
    }

    public function details($participant_id)
    {
        $where = array('id'=> $participant_id);
        $compnyStaff = CompanyStaff::where($where)->first();

        $where = array('id' => $compnyStaff->company_id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $compnyStaff->event_id);
        $event = Event::where($where)->first();

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }
        //$participants = DB::select('select t.* , c.* from temp_' . $company->id . ' t inner join company_staff c on t.id = c.id where c.id = ?', [$participant_id]);
        $participants = DB::select('select * from company_staff c where c.id = ?', [$participant_id]);
    	$status_value = "Initaited";
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
                    $status_value = "Needs review and correction by security officer";
                    break;
                case 8:
                    $status_value = "Needs review and correction by event admin";
                    break;
                case 9:
                    $status_value = "Badge generated";
                    break;
                case 10:
                    $status_value = "Badge printed";
                    break;
            }
        }

        $fieldsCount = 1;

        $options = array();
        $form = '<div class="row">';
        $form .= $this->createStatusFieldLabel("Status",  $status_value);
        // $form .= '</div>';
        if ($status == 8) {
            // $form .= '<div class="row">';
            $form .= $this->createStatusFieldLabel("Reject reason", $event_reject_reason);
            // $form .= '</div>';
        }
        if ($status == 7) {
            // $form = '<div class="row">';
            $form .= $this->createStatusFieldLabel("Reject reason", $security_officer_reject_reason);
            // $form .= '</div>';
        }
        // $form .= '<div class="row">';
        $attachmentForm = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenFieldLabel('participant_id',  '');
        } else {
            $form .= $this->createHiddenFieldLabel('participant_id',  $participant_id);
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
                        $form .= $this->createNumberFieldLabel($templateField->label_en, '');
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
                        $form .= $this->createDateLabel($templateField->label_en, '');
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
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en, 0);
                        $form .= $this->createHiddenFieldLabel($templateField->label_en, '');
                    } else {
                        if($templateField->label_en == 'Personal Image'){
                            $image = $this->createPersonalImage($templateField->value);
                            $form = $image.$form;
                        }else{
                        	$attachmentForm .= $this->createAttachmentLabel($templateField->label_en, $templateField->value);
                         	$form .= $this->createHiddenFieldLabel($templateField->label_en, $templateField->value);
                        }
                    }


                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }
        $buttons = '';
        if($event->status < 3){
            switch ($status) {
                case 0:
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="' . route('templateForm', [$participant_id,$company->id,$event->id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="javascript:void(0);" id="send_request" data-toggle="tooltip" data-original-title="Delete" data-id="' . $participant_id . '" class="delete btn btn-danger">Send Request</a>';
                    break;
                case 7:
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="' . route('templateForm', [$participant_id,$company->id,$event->id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    break;
                case 8:
                    $buttons .= '&nbsp;&nbsp;';
                    $buttons .= '<a href="' . route('templateForm', [$participant_id,$company->id,$event->id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    break;
            }
        }
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.TemplateForm.template-form-details')->with('form', $form)->with('attachmentForm', $attachmentForm)
            ->with('buttons', $buttons)->with('subCompany_nav', $subCompany_nav)->with('companyId',$company->id)
            ->with('eventId',$event->id)->with('event_name', $event->name)->with('company_name', $company->name);
    }

	public function createStatusFieldLabel($label, $value)
    {
        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" value="'. $value .'" disabled/>';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createHiddenFieldLabel($id, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextFieldLabel($label, $value)
    {
	     $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" value="'. $value .'" disabled/>';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createNumberFieldLabel($label, $value)
    {
        $numberfield = '<div class="col-md-6"><div class="form-group col">';
        $numberfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $numberfield .= '<input type="text" value="'. $value .'" disabled/>';
        $numberfield .= '</div></div></div>';

        return $numberfield;
    }

    public function createTextAreaLabel( $label, $value)
    {
        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea disabled>' . $value . '</textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createDateLabel($label, $value)
    {
        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="text" value="'. $value .'" disabled/>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createSelectLabel($label, $elements, $value)
    {
        $selectValue = '';
        foreach ($elements as $element) {
            if ($element->key == $value) {
                $selectValue = $element->value;
            }
        }


    	$selectfield = '<div class="col-md-6"><div class="form-group col">';
        $selectfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectfield .= '<input type="text" value="'. $selectValue .'" disabled/>';
        $selectfield .= '</div></div></div>';

        return $selectfield;

    }

    public function createAttachmentLabel($label, $value)
    {
    	$textfield = '<div class="col-md-6"><div class="row"><div class="form-group col">';
        $textfield .= '<label>' . $label . "</label></div>";
        $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-label="' . $label . '"  data-src="' . $value . '" data-original-title="Preview" class="edit btn btn-danger preview-badge">Preview</a>';
        $textfield .= '<div class="col-md-6">' . $button . '</div>';
        $textfield .= '</div></div><div class="col-md-6"></div></div></div>';

        return $textfield;
    }

    public function createPersonalImage($value){
        $personalImage = '';
        $personalImage = $personalImage .'<div class="row>';
        $personalImage = $personalImage .'<div class="form-group col">';
        $personalImage = $personalImage .'<img id="paticipant_iamge" src="'. asset('badges/'.$value).'" alt="Personal" class="pic-img">';
        $personalImage = $personalImage .'</div></div>';
        return $personalImage;
    }








//     public function createStatusFieldLabel($label, $value)
//     {
//         $textfield = '<div class="col-md-8" style="height:100px"><div class="row"><div class="col-md-6">';
//         $textfield .= '<label>' . $label . "  : </label></div>";
//         $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger; color:red;
//         text-align: center;padding:10px">' . $value . '</label></div>';
//         $textfield .= '</div></div>';

//         return $textfield;
//     }

//     public function createHiddenFieldLabel($id, $value)
//     {
//         $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

//         return $textfield;
//     }

//     public function createTextFieldLabel($label, $value)
//     {

//         $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
//         $textfield .= '<label>' . $label . "  : </label></div>";
//         $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
//         text-align: center; background-color: darkgray; padding:10px">' . $value . '</label></div>';
//         $textfield .= '</div></div>';

//         return $textfield;
//     }

//     public function createNumberFieldLabel($label, $value)
//     {

//         $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
//         $textfield .= '<label>' . $label . "  : </label></div>";
//         $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
//         text-align: center; background-color: darkgray;padding:10px">' . $value . '</label></div>';
//         $textfield .= '</div></div>';

//         return $textfield;
//     }

//     public function createTextAreaLabel($id, $label, $mandatory)
//     {
//         $required = '';
//         if ($mandatory == '1') {
//             $required = 'required=""';
//         }

//         $datefield = '<div class="col-md-6"><div class="form-group col">';
//         $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
//         $datefield .= '<textarea id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . '></textarea>';
//         $datefield .= '</div></div></div>';

//         return $datefield;
//     }

//     public function createDateLabel($label, $value)
//     {
//         $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
//         $textfield .= '<label>' . $label . "  : </label></div>";
//         $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
//         text-align: center;background-color: darkgray;padding:10px">' . $value . '</label></div>';
//         $textfield .= '</div></div>';

//         return $textfield;
//     }

//     public function createSelectLabel($label, $elements, $value)
//     {

//         $selectValue = '';
//         foreach ($elements as $element) {
//             if ($element->key == $value) {
//                 $selectValue = $element->value;
//             }
//         }
//         $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
//         $textfield .= '<label>' . $label . "  : </label></div>";
//         $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;
//         text-align: center;background-color: darkgray;padding:10px">' . $selectValue . '</label></div>';
//         $textfield .= '</div></div>';

//         return $textfield;
//     }

//     public function createAttachmentLabel($label, $value)
//     {

//         $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
//         $textfield .= '<label>' . $label . "  : </label></div>";
//         $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-label="' . $label . '"  data-src="' . $value . '" data-original-title="Preview" class="edit btn btn-danger preview-badge">Preview</a>';
//         $textfield .= '<div class="col-md-6" style="height:70px">' . $button . '</div>';
//         $textfield .= '</div></div>';

//         return $textfield;
//     }

//     public function createMultiSelectLabel($id, $label, $elements)
//     {
//         $selectField = '<div class="col-md-6"><div class="form-group col">';
//         $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
//         $selectField .= '<select  multiple id="' . $id . '" name="' . $label . '[]">';
//         foreach ($elements as $element) {
//             $selectField .= '<option value="' . $element->key . '">' . $element->value . '</option>';
//         }

//         $selectField .= '</select></div></div></div>';
//         return $selectField;
//     }

//     public function createPersonalImage($value){
//         $personalImage = '';
//         $personalImage = $personalImage .'<div class="row>';
//         $personalImage = $personalImage .'<div class="form-group col">';
//         $personalImage = $personalImage .'<img id="paticipant_iamge" src="'. asset('badges/'.$value).'" alt="Personal" class="pic-img">';
//         $personalImage = $personalImage .'</div></div>';
//         return $personalImage;
//     }
}

