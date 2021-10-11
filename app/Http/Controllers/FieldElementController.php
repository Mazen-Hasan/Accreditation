<?php

namespace App\Http\Controllers;

use App\Models\TemplateFieldElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class FieldElementController extends Controller
{
    public function index($field_id)
    {
        if (request()->ajax()) {
            $fieldElements = DB::select('select * from template_field_elements_view f where f.template_field_id = ?', [$field_id]);
            return datatables()->of($fieldElements)
                ->addColumn('action', function ($data) {
                    $button = '';
                    if ($data->is_locked == 0) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-element">Edit</a>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-element"  data-id="' . $data->id . '" data-original-title="Delete" class="delete btn btn-danger delete-element">Delete</a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $template = DB::select('select * from template_fields_view v where v.id = ?', [$field_id]);

        return view('pages.Template.template-field-elements')->with('template', $template[0]);
    }

    public function store(Request $request)
    {
        $elementId = $request->element_id;
        $fieldElement = TemplateFieldElement::updateOrCreate(['id' => $elementId],
            ['template_field_id' => $request->field_id,
                'value_ar' => $request->value_ar,
                'value_en' => $request->value_en,
                'order' => $request->order,
                'value_id' => $request->value_id,
            ]);
        return Response::json($fieldElement);
    }

    public function edit($elementId)
    {
        $where = array('id' => $elementId);
        $fieldElement = TemplateFieldElement::where($where)->first();
        return Response::json($fieldElement);
    }

    public function destroy($element_id)
    {
        $field = TemplateFieldElement::where('id', $element_id)->delete();

        return Response::json($field);
    }
}
