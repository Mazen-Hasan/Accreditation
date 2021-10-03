<?php

namespace App\Http\Controllers;

use App\Models\FieldType;
use App\Models\TemplateField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($template_id)
    {
        if (request()->ajax()) {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ?',[$template_id]);
            return datatables()->of($templateFields)
                ->addColumn('action', function ($data) {
                    $button ='';
                    if(strtolower($data->label_en) != 'company' and strtolower($data->label_en) != 'event'){
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip" id="edit-field"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-feild">Edit</a>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-field"  data-id="' . $data->id . '" data-original-title="Delete" class="delete btn btn-danger delete-field">Delete</a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    if ($data->slug == 'select') {
                        $button .= '<a href="' . route('fieldElements', $data->id) . '" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-facebook"> Elements</a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $fieldTypes = FieldType::get()->all();
        return view('pages.Template.template-fields')->with('template_id',$template_id)->with('fieldTypes', $fieldTypes);
    }

    public function store(Request $request)
    {
        $fieldId = $request->field_id;


        $templateField   =   TemplateField::updateOrCreate(['id' => $fieldId],
            ['template_id'  => $request->template_id,
                'label_ar' => $request->label_ar,
                'label_en' => $request->label_en,
                'mandatory'  =>  $request->has('mandatory'),
                'min_char' => $request->min_char,
                'max_char' => $request->max_char,
                'field_type_id' => $request->field_type,
                'field_order' => $request->field_order
            ]);
        return Response::json($templateField);
    }

    public function edit($fieldId)
    {
        $where = array('id' => $fieldId);
        $templateField = TemplateField::where($where)->first();
        return Response::json($templateField);
    }

    public function destroy($field_id)
    {
        $field = TemplateField::where('id', $field_id)->delete();

        return Response::json($field);
    }
}
