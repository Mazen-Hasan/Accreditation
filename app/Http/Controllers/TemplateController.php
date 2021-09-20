<?php

namespace App\Http\Controllers;

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

class TemplateController extends Controller
{
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
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-template">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('templateFields', $data->id) . '" id="template-fields" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="fields">Fields<i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('templateBadge', $data->id) . '" id="template-Badge" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-facebook" title="badge">Badge<i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="javascript:void(0);" id="deActivate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger"> Deactivate</a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="activate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
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
        $template_id = $request->template_id;
        $post = Template::updateOrCreate(['id' => $template_id],
            ['name' => $request->name,
                'status' => $request->status,
                'creator' => Auth::user()->id
            ]);

        $query = 'select p.id, p.label_ar, p.label_en, p.mandatory, p.min_char, p.max_char, p.field_type_id  from pre_defined_fields  p';
        $pre_defined_fields_res = DB::select($query);


        foreach ($pre_defined_fields_res as $row) {
            $templateField = TemplateField::updateOrCreate(['id' => 0],
                ['template_id' => $post->id,
                    'label_ar' => $row->label_ar,
                    'label_en' => $row->label_en,
                    'mandatory' => $row->mandatory,
                    'min_char' => $row->min_char,
                    'max_char' => $row->max_char,
                    'field_type_id' => $row->field_type_id,
                ]);

                $where = array('predefined_field_id' => $row->id);
                $pre_defined_field_elements_res = PreDefinedFieldElement::where($where)->get()->all();


                foreach ($pre_defined_field_elements_res as $row_filed_elements) {
                    $templateFieldElement = TemplateFieldElement::updateOrCreate(['id' => 0],
                        ['value_ar' => $row_filed_elements->value_ar	,
                            'value_en' => $row_filed_elements->value_en,
                            'value_id' => $row_filed_elements->value_id,
                            'order' => $row_filed_elements->order,
                            'template_field_id' => $templateField->id,
                        ]);
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
}
