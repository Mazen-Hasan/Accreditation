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
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ?',[$template_id]);
        }


    //var_dump($templateFields);
        $fieldsCount =  0;

        $options = array();
        $form = '<div class="row">';
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
                    $form .= $this->createTextField($templateField->label_en,$templateField->label_en,
                        $templateField->mandatory,$templateField->min_char,$templateField->max_char,'');
                    }else{
                        $form .= $this->createTextField($templateField->label_en,$templateField->label_en,
                        $templateField->mandatory,$templateField->min_char,$templateField->max_char,$templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextArea($templateField->label_en,$templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if($participant_id == 0){
                        $form .= $this->createDate($templateField->label_en,$templateField->label_en, $templateField->mandatory,'');
                    }else{
                        $form .= $this->createDate($templateField->label_en,$templateField->label_en, $templateField->mandatory,$templateField->value);
                    }
                    break;

                case 'select':
                    if($participant_id == 0){
                    $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->id]);
                    foreach ($fieldElements as $fieldElement){
                        $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                        $options [] = $option;
                    }
                    $form .= $this->createSelect($templateField->label_en,$templateField->label_en, $options,'');
                }else{
                    $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->template_field_id]);
                    foreach ($fieldElements as $fieldElement){
                        $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                        $options [] = $option;
                    }
                    $form .= $this->createSelect($templateField->label_en,$templateField->label_en, $options,$templateField->value);
                }
                    break;

                case 'multiple':
                    $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->id]);

                    foreach ($fieldElements as $fieldElement){
                        $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                        $options [] = $option;
                    }
                    $form .= $this->createMultiSelect($templateField->label_en,$templateField->label_en, $options);
                    break;

            }
        }

        if($fieldsCount % 2 == 1){
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }

    //var_dump($form);
        return view('pages.TemplateForm.template-form-add')->with('form',$form);
    }

    public function createTextField($id, $label, $mandatory, $min_char, $max_char, $value){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label .  '"'. $required. ' value="'.$value.'" />';
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

    public function createAttachment($id, $label, $mandatory){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $attachmentfield = '<form id="upload-form" method="post">';
        $attachmentfield .= '<div class="col-md-6"><div class="form-group col">';
        $attachmentfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $attachmentfield .= '<input type="file" id="'. $id  .  '" name="'. $id .  '"'. $required. '/>';
        $attachmentfield .='<button type="submit" id="upload-file">Upload</button>';
        $attachmentfield .= '</div></div></div>';
        $attachmentfield .= '</form>';

        return $attachmentfield;
    }


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

}

