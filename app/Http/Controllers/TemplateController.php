<?php

namespace App\Http\Controllers;

use App\Mail\EventAdminAssign;
use App\Models\FieldType;
use App\Models\PreDefinedFieldElement;
use App\Models\SelectOption;
use App\Models\Template;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Mail;
use Intervention\Image\Size;

class TemplateController extends Controller
{

	public function getData($values){
        //var_dump($values);
        $templates =  Template::latest()->take(6)->get();
        return Response::json($templates);
    }

    public function getData1($values){
        //var_dump($values);
        $size = 10;
        if($values != null){
            if($values != "0"){
                $comands = explode(",",$values);
                $size = sizeof($comands);
                if($size > 2){
                    $condition1 = $comands[0];
                    $condition1token = $comands[1];
                    $operator = $comands[2];
                    $condition2 = $comands[3];
                    $condition2token = $comands[4];
                    $templates = DB::select('select * from templates where '. TemplateController::getConditionPart("name",$condition1,$condition1token) . " ".$operator ." ". TemplateController::getConditionPart("name",$condition2,$condition2token));
                }else{
                    $condition1 = $comands[0];
                    $condition1token = $comands[1];
                    $templates = DB::select('select * from templates where '. TemplateController::getConditionPart("name",$condition1,$condition1token));
                }
            }else{
                $templates =  Template::latest()->take($size)->get();
            }
        }
        //$templates = DB::select('select * from templates where ');
        return Response::json($templates);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Template::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '';
                    if ($data->is_locked == 0) {
                        $button = '<a href="javascript:void(0)" id="edit-template" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }

                    $button .= '<a href="' . route('templateFields', $data->id) . '" id="template-fields" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Fields"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->is_locked == 1) {
                    	if($data->can_unlock == 1){
                            $button .= '<a href="javascript:void(0);" id="unLock-template" data-toggle="tooltip" data-original-title="Unlock" data-id="' . $data->id . '" title="Un-Lock"><i class="fas fa-unlock"></i></a>';
                        }
                    } 
                	else {
                        $button .= '<a href="javascript:void(0);" id="lock-template" data-toggle="tooltip" data-original-title="Lock" data-id="' . $data->id . '" title="Lock"><i class="fas fa-lock"></i></a>';
                    }
                    $button .= '&nbsp;&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="javascript:void(0);" id="deActivate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Deactivate"><i class="fas fa-ban"></i></a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="activate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Activate"><i class="fas fa-check-circle"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Template.templates');
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function templateAdd()
    {
        $fieldTypes = FieldType::get()->all();

        $fieldTypesArray = array();
        foreach ($fieldTypes as $fieldType) {
            $fieldTypesSelectOption = new SelectOption($fieldType->id, $fieldType->name);
            $fieldTypesArray[] = $fieldTypesSelectOption;
        }

        return view('pages.Template.template-add')->with('filedTypes', $fieldTypesArray);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

//        $info = array(
//            'name' => "Alex"
//        );
//
//        Mail::send([], $info, function ($message)
//        {
//            $message->to('e.mazen.hasan@gmail.com', 'Mazen')
//                ->subject('Basic test eMail from Laravel.');
//            $message->from('admin@accrediation.com', 'Admin');
//        });

//        echo "Successfully sent the email";

        $template_id = $request->template_id;
        $post = Template::updateOrCreate(['id' => $template_id],
            ['name' => $request->name,
                'status' => $request->status,
                'is_locked' => $request->has('locked'),
                'creator' => Auth::user()->id
            ]);
    

        if ($template_id == null) {

            $query = 'select p.id, p.label_ar, p.label_en, p.mandatory, p.min_char, p.max_char, p.field_order, p.field_type_id  from pre_defined_fields  p';
            $pre_defined_fields_res = DB::select($query);


            foreach ($pre_defined_fields_res as $row) {
                $templateField = TemplateField::updateOrCreate(['id' => 0],
                    ['template_id' => $post->id,
                        'label_ar' => $row->label_ar,
                        'label_en' => $row->label_en,
                        'mandatory' => $row->mandatory,
                        'min_char' => $row->min_char,
                        'max_char' => $row->max_char,
                     	'field_order' => $row->field_order,
                        'field_type_id' => $row->field_type_id,
                    ]);

                $where = array('predefined_field_id' => $row->id);
                $pre_defined_field_elements_res = PreDefinedFieldElement::where($where)->get()->all();


                foreach ($pre_defined_field_elements_res as $row_filed_elements) {
                    $templateFieldElement = TemplateFieldElement::updateOrCreate(['id' => 0],
                        ['value_ar' => $row_filed_elements->value_ar,
                            'value_en' => $row_filed_elements->value_en,
                            'value_id' => $row_filed_elements->value_id,
                            'order' => $row_filed_elements->order,
                            'template_field_id' => $templateField->id,
                        ]);
                }
            }
        }

        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */


    public function edit($id)
    {
        $where = array('id' => $id);
        $post = Template::where($where)->first();
        return Response::json($post);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = Template::where('id', $id)->delete();

        return Response::json($post);
    }

    public function changeStatus($id, $status)
    {
        $post = Template::updateOrCreate(['id' => $id],
            [
                'status' => $status
            ]);
        return Response::json($post);
    }

    public function changeLock($id, $is_locked)
    {
        $post = Template::updateOrCreate(['id' => $id],
            [
                'is_locked' => $is_locked - 2
            ]);
        return Response::json($post);
    }

    public static function getConditionPart($columnName,$condition,$token){
        $conditionPart = "";
        switch ($condition) {
            case "1":
                $conditionPart = $columnName ." Like " . "'%" . $token . "%'";
                break;
            case "5":
                $conditionPart = $columnName . " Like " . "'" . $token . "%'";
                break;
            case "6":
                $conditionPart = $columnName . " Like " . "'%" . $token . "'";
                break;
            case "3":
                $conditionPart = $columnName . " = " . "'" . $token . "'";
                break;
            case "4":
                $conditionPart = $columnName . " <> " . "'" . $token . "'";
                break;
            case "2":
                $conditionPart = $columnName . " Not Like " . "'%" . $token . "%'";
                break;
        }
        return $conditionPart;
    }
}

