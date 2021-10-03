<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;
use App\Models\CompanyStaff;
use App\Models\SelectOption;
use App\Models\StaffData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateFormController extends Controller
{
    public function index($participant_id)
    {

        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $company->event_id);
        $event = Event::where($where)->first();

    	$template_id = $event->event_form;
        if($participant_id != 0){
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?',[$participant_id,$event->event_form]);
        }else{
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order',[$template_id]);
        }


    //var_dump($templateFields);
        $fieldsCount =  0;

        $options = array();
        $form = '<div class="row">';
        $attachmentForm ='';
        if($participant_id == 0){
            $form .= $this->createHiddenField('participant_id','participant_id','');
        }else{
            $form .= $this->createHiddenField('participant_id','participant_id',$participant_id);
        }
        foreach ($templateFields as $templateField){
            $options = [];
            if($fieldsCount % 2 == 0){
                if($fieldsCount > 0){
                    $form .= '</div>';
                }
                $form .= '<div class="row">';
            }
            $fieldsCount++;

            switch ($templateField->slug){
                case 'text':
                    if($participant_id == 0){
                        if(strtolower($templateField->label_en) ==  'company' or strtolower($templateField->label_en) ==  'event'){
                            if(strtolower($templateField->label_en) ==  'company') {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $company->name);
                            }else{
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $event->name);
                            }
                            break;
                        }
                    $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,
                        $templateField->mandatory,$templateField->min_char,$templateField->max_char,'');
                    }else{
                        if(strtolower($templateField->label_en) ==  'company' or strtolower($templateField->label_en) ==  'event'){
                            $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,
                        $templateField->mandatory,$templateField->min_char,$templateField->max_char,$templateField->value);
                    }
                    break;

                case 'number':
                    if($participant_id == 0){
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,
                            $templateField->mandatory,$templateField->min_char,$templateField->max_char,'');
                    }else{
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,
                            $templateField->mandatory,$templateField->min_char,$templateField->max_char,$templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextArea(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if($participant_id == 0){
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en),$templateField->label_en, $templateField->mandatory,'');
                    }else{
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en),$templateField->label_en, $templateField->mandatory,$templateField->value);
                    }
                    break;

                case 'select':
                    if($participant_id == 0){
                    $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->id]);
                    foreach ($fieldElements as $fieldElement){
                        $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                        $options [] = $option;
                    }
                    $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en),$templateField->label_en, $options,'');
                }else{
                    $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->template_field_id]);
                    foreach ($fieldElements as $fieldElement){
                        $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                        $options [] = $option;
                    }
                    $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en),$templateField->label_en, $options,$templateField->value);
                }
                    break;

                case 'file':
            		$fieldsCount --;
                    if($participant_id == 0){
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,0,'');
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en),$templateField->label_en, '');
                    }
                    else{
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en),$templateField->label_en,0,$templateField->value);
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en),$templateField->label_en, $templateField->value);
                    }


                    break;
            }
        }
        if($fieldsCount % 2 == 1){
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }

    //var_dump($form);
        return view('pages.TemplateForm.template-form-add')->with('form',$form)->with('attachmentForm', $attachmentForm);
    }

    public function createTextField($id, $label, $mandatory, $min_char, $max_char, $value){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if($min_char){
            $minChar = '" minlength="' . $min_char;
        }
        if($max_char){
            $maxChar = '"maxlength="' . $max_char;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label . $minChar . $maxChar . '"'. $required. ' value="'.$value.'" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createNumberField($id, $label, $mandatory, $min_value, $max_value, $value){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if($min_value){
            $minChar = '" min="' . $min_value;
        }
        if($max_value){
            $maxChar = '"max="' . $max_value;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="number" id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label . $minChar . $maxChar. '"'. $required. ' value="'.$value.'" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createHiddenField($id, $label, $value){
        $textfield = '<input type="hidden" id="'. $id  .  '" name="'. $id .'" value="'.$value.'" />';

        return $textfield;
    }

    public function createSelect($id, $label, $elements,$value){
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  id="'. $id .'" name="'. $label .'">';
        foreach ($elements as $element){
            $selectField .= '<option ';
            if($element->key == $value){
                $selectField .= ' selected="selected"';
            }
            $selectField .=' value="' . $element->key .'">' .$element->value .'</option>';
        }

        $selectField .= '</select></div></div></div>';
        return  $selectField;
    }

    public function createMultiSelect($id, $label, $elements){
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  multiple id="'. $id .'" name="'. $label .'[]">';
        foreach ($elements as $element){
            $selectField .= '<option value="' . $element->key .'">' .$element->value .'</option>';
        }

        $selectField .= '</select></div></div></div>';
        return  $selectField;
    }

    public function createDate($id, $label, $mandatory, $value){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="date" id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label .  '"'. $required. ' value="'.$value.'" />';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createTextArea($id, $label, $mandatory){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label .  '"'. $required. '></textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createAttachment($id, $label, $mandatory, $value){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $attachmentField = '<form id=form_' . $id . '" name="badgeForm" class="form-horizontal  img-upload" enctype="multipart/form-data" action="javascript:void(0)">';
        $attachmentField .= '<div class="row"><div class="col-md-5"><label>'. $label . '</label></div>';
        $attachmentField .= '<div class="col-md-4"><div class="col-sm-12"><input type="file" id="file_'. $id .'" name=file_"'. $id .'"></div></div>';
        $attachmentField .= '<div class="col-md-3"><button type="submit" id="btn-upload_'. $id .'" value="Upload">Upload</button></div></div>';
        $attachmentField .= '<div class="row"><div class="col-md-12"><div class="form-group col">';
        $attachmentField .= '<label id="file_type_error_'. $id .'"></label><div style="background-color: #ffffff00!important;" class="progress">';
        $attachmentField .= '<div id="file-progress-bar_'. $id .'" class="progress-bar"></div></div></div></div></div></form>';

        return $attachmentField;
    }

    // public function store(Request $request)
    // {
    //     $where = array('company_admin_id' => Auth::user()->id);
    //     $company = Company::where($where)->get()->first();
    //     $participant_id = $request->participant_id;
    //     $companyStaff   =   CompanyStaff::updateOrCreate(['id' => $participant_id],
    //         ['event_id'  => $company->event_id,
    //             'company_id' => $company->id,
    //             'security_officer_id' => '0',
    //             'security_officer_decision' => '0',
    //             'security_officer_decision_date' => null,
    //             'security_officer_reject_reason' => '',
    //             'event_admin_id' => '0',
    //             'event_admin_decision' => '0',
    //             'event_admin_decision_date' => null,
    //             'event_admin_reject_reason' => '',
    //             'status' => '0'
    //         ]);
    //     $data = $request->all();

    //     foreach ($data as $key => $value) {

    //         if($key != 'participant_id'){
    //             if($participant_id != null){
    //                 $query = 'update staff_data s set s.value = "'.$value.'" where s.staff_id = '.$companyStaff->id.' and s.key ="'.$key.'" ';
    //                 DB::update($query);
    //             }else{
    //                 $staffData   =   StaffData::updateOrCreate(['staff_id'=>$companyStaff->id,'key' => $key],
    //                     ['staff_id' => $companyStaff->id,
    //                         'key'  => $key,
    //                         'value' => $value
    //                     ]);
    //             }
    //         }
    //     }
    //     return Response::json($companyStaff);
    // }

    public function store(Request $request)
    {
        //$this->uploadFile($request);
        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();
        $participant_id = $request->participant_id;
        $companyStaff   =   CompanyStaff::updateOrCreate(['id' => $participant_id],
            ['event_id'  => $company->event_id,
                'company_id' => $company->id,
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
        // var_dump($participant_id);
        // exit;
        $data = $request->all();
        // var_dump($data);
        // exit;

//        var_dump($request->file);

        // if($request->file('image')) {
        //     $fileName = time().'.'.$request->file->extension();
        //     $request->file->move(public_path('uploads'), $fileName);
        // }



        foreach ($data as $key => $value) {
//            DB::insert('insert  into staff_data values');
            // $staffData   =   StaffData::create(
                // ['staff_id' => $companyStaff->id,
                //     'key'  => $key,
                //     'value' => $value
                // ]);

                if($key != 'participant_id'){
                    if($participant_id != null){
                        // $staffData   =   StaffData::updateOrCreate(['staff_id'=>$participant_id,'key' => $key],
                        //     ['staff_id' => $participant_id,
                        //         'key'  => $key,
                        //         'value' => $value
                        //     ]);
                            $query = 'update staff_data s set s.value = "'.$value.'" where s.staff_id = '.$companyStaff->id.' and s.key ="'.$key.'" ';
                            DB::update($query);
                        }else{
                            $staffData   =   StaffData::updateOrCreate(['staff_id'=>$companyStaff->id,'key' => $key],
                            ['staff_id' => $companyStaff->id,
                                'key'  => $key,
                                'value' => $value
                            ]);
                            // $query = 'update StaffData s set s.value = "'.$value.'" where s.staff_id = '.$companyStaff->id.' and s.key ="'.$key.'" ';
                        }
                        // var_dump($staffData);
                        // exit;
                }
        }

        return Response::json($companyStaff);
    }

	public function details($participant_id)
    {

        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $company->event_id);
        $event = Event::where($where)->first();

    	$template_id = $event->event_form;
        if($participant_id != 0){
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?',[$participant_id,$event->event_form]);
        }else{
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order',[$template_id]);
        }
        $participants = DB::select('select t.* , c.* from temp'.Auth::user()->id. ' t inner join company_staff c on t.id = c.id where c.id = ?',[$participant_id]);
        $status_value = "initaited";
        $status = 0;
        $event_reject_reason = '';
        $security_officer_reject_reason = '';
        foreach($participants as $participant){
            $status = $participant->status;
            $event_reject_reason = $participant->event_admin_reject_reason;
            $security_officer_reject_reason = $participant->security_officer_reject_reason;
            switch($participant->status){
                case 0:
                    $status_value =  "Initiated";
                    break;
                case 1:
                    $status_value =  "Waiting Security Officer Approval";
                    break;
                case 2:
                    $status_value =  "Waiting Event Admin Approval";
                    break;
                case 3:
                    $status_value =  "Approved by security officer";
                    break;
                case 4:
                    $status_value =  "Rejected by security officer";
                    break;
                case 5:
                    $status_value =  "Rejected by event admin";
                    break;
                case 6:
                    $status_value =  "Approved by event admin";
                    break;
                case 7:
                    $status_value =  "Rejected with correction by security officer";
                    break;
                case 8:
                    $status_value =  "Rejected with correction by event admin";
                    break;
                case 9:
                    $status_value =  "Badge generated";
                    break;
                case 10:
                    $status_value =  "Badge printed";
                    break;
            }
        }


    //var_dump($templateFields);
        $fieldsCount =  0;

        $options = array();
        $form = '<div class="row">';       
        $form .= $this->createStatusFieldLabel("status","Status",0,1,1,$status_value);
        $form .= '</div>';
        if($status == 8){
            $form = '<div class="row">';       
            $form .= $this->createStatusFieldLabel("reject_reason","Reject Reason",0,1,1, $event_reject_reason);
            $form .= '</div>';
        }
        if($status == 7){
            $form = '<div class="row">';       
            $form .= $this->createStatusFieldLabel("reject_reason","Reject Reason",0,1,1, $security_officer_reject_reason);
            $form .= '</div>';
        }
        $form .= '<div class="row">';
        $attachmentForm ='';
        if($participant_id == 0){
            $form .= $this->createHiddenFieldLabel('participant_id','participant_id','');
        }else{
            $form .= $this->createHiddenFieldLabel('participant_id','participant_id',$participant_id);
        }
        foreach ($templateFields as $templateField){
            $options = [];
            if($fieldsCount % 2 == 0){
                if($fieldsCount > 0){
                    $form .= '</div>';
                }
                $form .= '<div class="row">';
            }
            $fieldsCount++;

            switch ($templateField->slug){
                case 'text':
                    if($participant_id == 0){
                        $form .= $this->createTextFieldLabel($templateField->label_en, '');
                    }else{
                        $form .= $this->createTextFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'number':
                    if($participant_id == 0){
                        $form .= $this->createNumberFieldLabel($templateField->label_en, '');
                    }else{
                        $form .= $this->createNumberFieldLabel($templateField->label_en, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextAreaLabel($templateField->label_en,$templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if($participant_id == 0){
                        $form .= $this->createDateLabel($templateField->label_en,$templateField->label_en, $templateField->mandatory,'');
                    }else{
                        $form .= $this->createDateLabel($templateField->label_en,$templateField->label_en, $templateField->mandatory,$templateField->value);
                    }
                    break;

                case 'select':
                    if($participant_id == 0){
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->id]);
                        foreach ($fieldElements as $fieldElement){
                            $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $options,'');
                    }else{
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->template_field_id]);
                        foreach ($fieldElements as $fieldElement){
                            $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelectLabel($templateField->label_en, $options,$templateField->value);
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if($participant_id == 0){
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en,$templateField->label_en,0,'');
                        $form .= $this->createHiddenFieldLabel($templateField->label_en,$templateField->label_en, '');
                    }
                    else{
                        $attachmentForm .= $this->createAttachmentLabel($templateField->label_en,$templateField->label_en,0,$templateField->value);
                        $form .= $this->createHiddenFieldLabel($templateField->label_en,$templateField->label_en, $templateField->value);
                    }


                    break;
            }
        }
        if($fieldsCount % 2 == 1){
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
            //$form .= $this->createStatusFieldLabel("status","Status",0,1,1,$status_value);
        }
        $buttons = '';
        switch($status){

            case 0:
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="' . route('templateForm', $participant_id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="javascript:void(0);" id="send_request" data-toggle="tooltip" data-original-title="Delete" data-id="'.$participant_id.'" class="delete btn btn-danger">Send Request</a>';
                break;
            case 7:
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="' . route('templateForm', $participant_id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                break;
            case 8:
                $buttons .= '&nbsp;&nbsp;';
                $buttons .= '<a href="' . route('templateForm', $participant_id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $participant_id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                break;
        }
    //var_dump($form);
        return view('pages.TemplateForm.template-form-details')->with('form',$form)->with('attachmentForm', $attachmentForm)->with('buttons',$buttons);
    }

    public function createStatusFieldLabel($label, $value){

        $textfield = '<div class="col-md-8" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label. "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger; color:red;text-align: center';
        $textfield .= 'padding:10px">'.$value.'</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createTextFieldLabel($label, $value){

        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label. "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;text-align: center;';
        $textfield .= 'background-color: darkgray;padding:10px">'.$value.'</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createNumberFieldLabel( $label, $value){

        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label. "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger;text-align: center;';
        $textfield .= 'background-color: darkgray;padding:10px">'.$value.'</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createHiddenFieldLabel($id, $label, $value){
        $textfield = '<input type="hidden" id="'. $id  .  '" name="'. $id .'" value="'.$value.'" />';

        return $textfield;
    }

    public function createSelectLabel($label, $elements,$value){
        $selectValue = '';
        foreach ($elements as $element){
            if($element->key == $value){
                $selectValue = $element->value;
            }
        }
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label. "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger';
        $textfield .= 'text-align: center;background-color: darkgray;padding:10px">'.$selectValue.'</label></div>';
        $textfield .= '</div></div>';

        return  $textfield;
    }

    public function createMultiSelectLabel($id, $label, $elements){
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  multiple id="'. $id .'" name="'. $label .'[]">';
        foreach ($elements as $element){
            $selectField .= '<option value="' . $element->key .'">' .$element->value .'</option>';
        }

        $selectField .= '</select></div></div></div>';
        return  $selectField;
    }

    public function createDateLabel($id, $label, $mandatory, $value){
        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label. "  : </label></div>";
        $textfield .= '<div class="col-md-6" style="height:70px"><label style="font-size: larger';
        $textfield .= 'text-align: center;background-color: darkgray;padding:10px">'.$value.'</label></div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

    public function createTextAreaLabel($id, $label, $mandatory){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label .  '"'. $required. '></textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createAttachmentLabel($id, $label, $mandatory, $value){

        $textfield = '<div class="col-md-6" style="height:100px"><div class="row"><div class="col-md-6">';
        $textfield .= '<label>' . $label. "  : </label></div>";
        $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-label="'.$label.'"  data-src="' . $value . '" data-original-title="Preview" class="edit btn btn-danger preview-badge">Preview</a>';
        $textfield .= '<div class="col-md-6" style="height:70px">'.$button.'</div>';
        $textfield .= '</div></div>';

        return $textfield;
    }

//
//    public function store(Request $request)
//    {
//        //$this->uploadFile($request);
//        $where = array('company_admin_id' => Auth::user()->id);
//        $company = Company::where($where)->get()->first();
//        $participant_id = $request->participant_id;
//        $companyStaff   =   CompanyStaff::updateOrCreate(['id' => $participant_id],
//            ['event_id'  => $company->event_id,
//                'company_id' => $company->id,
//                'security_officer_id' => '0',
//                'security_officer_decision' => '0',
//                'security_officer_decision_date' => null,
//                'security_officer_reject_reason' => '',
//                'event_admin_id' => '0',
//                'event_admin_decision' => '0',
//                'event_admin_decision_date' => null,
//                'event_admin_reject_reason' => '',
//                'status' => '0'
//            ]);
//        // var_dump($participant_id);
//        // exit;
//        $data = $request->all();
//        // var_dump($data);
//        // exit;
//
////        var_dump($request->file);
//
//        // if($request->file('image')) {
//        //     $fileName = time().'.'.$request->file->extension();
//        //     $request->file->move(public_path('uploads'), $fileName);
//        // }
//
//        foreach ($data as $key => $value) {
////            DB::insert('insert  into staff_data values');
//            // $staffData   =   StaffData::create(
//                // ['staff_id' => $companyStaff->id,
//                //     'key'  => $key,
//                //     'value' => $value
//                // ]);
//
//                if($key != 'participant_id'){
//                    if($participant_id != null){
//                        // $staffData   =   StaffData::updateOrCreate(['staff_id'=>$participant_id,'key' => $key],
//                        //     ['staff_id' => $participant_id,
//                        //         'key'  => $key,
//                        //         'value' => $value
//                        //     ]);
//                            $query = 'update staff_data s set s.value = "'.$value.'" where s.staff_id = '.$companyStaff->id.' and s.key ="'.$key.'" ';
//                            DB::update($query);
//                        }else{
//                            $staffData   =   StaffData::updateOrCreate(['staff_id'=>$companyStaff->id,'key' => $key],
//                            ['staff_id' => $companyStaff->id,
//                                'key'  => $key,
//                                'value' => $value
//                            ]);
//                            // $query = 'update StaffData s set s.value = "'.$value.'" where s.staff_id = '.$companyStaff->id.' and s.key ="'.$key.'" ';
//                        }
//                        // var_dump($staffData);
//                        // exit;
//                }
//        }
//
//        return Response::json($companyStaff);
//    }

}

