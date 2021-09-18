<?php

namespace App\Http\Controllers;

use App\Models\CompanyStaff;
use App\Models\SelectOption;
use App\Models\StaffData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateFormController extends Controller
{
    public function index($template_id)
    {

        $templateFields = DB::select('select * from template_fields_view v where v.template_id = ?',[$template_id]);
        $fieldsCount =  0;

        $options = array();
        $form = '<div class="row">';
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
                    $form .= $this->createTextField($templateField->label_en,$templateField->label_en,
                        $templateField->mandatory,$templateField->min_char,$templateField->max_char);
                    break;

                case 'textarea':
                    $form .= $this->createTextArea($templateField->label_en,$templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    $form .= $this->createDate($templateField->label_en,$templateField->label_en, $templateField->mandatory);
                    break;

                case 'select':
                    $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?',[$templateField->id]);

                    foreach ($fieldElements as $fieldElement){
                        $option = new SelectOption($fieldElement->value_id,$fieldElement->value_en);
                        $options [] = $option;
                    }
                    $form .= $this->createSelect($templateField->label_en,$templateField->label_en, $options);
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

        return view('pages.TemplateForm.template-form-add')->with('form',$form);
    }

    public function createTextField($id, $label, $mandatory, $min_char, $max_char){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label .  '"'. $required. ' />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createSelect($id, $label, $elements){
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  id="'. $id .'" name="'. $label .'">';
        foreach ($elements as $element){
            $selectField .= '<option value="' . $element->key .'">' .$element->value .'</option>';
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

    public function createDate($id, $label, $mandatory){
        $required = '';
        if($mandatory == '1'){
            $required =  'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="date" id="'. $id  .  '" name="'. $id .'" placeholder="enter ' . $label .  '"'. $required. ' />';
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


    public function store(Request $request)
    {
        $companyStaff   =   CompanyStaff::updateOrCreate(['id' => 0],
            ['event_id'  => 3,
                'company_id' => 14,
                'status' => '0'
            ]);

        $data = $request->all();

        foreach ($data as $key => $value) {
            $staffData   =   StaffData::updateOrCreate(['staff_id' => $companyStaff->id],
                ['key'  => $key,
                    'value' => $value
                ]);
        }

        return Response::json($companyStaff);
    }
}
