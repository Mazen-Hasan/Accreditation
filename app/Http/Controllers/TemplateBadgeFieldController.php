<?php

namespace App\Http\Controllers;

use App\Models\TemplateBadge;
use App\Models\TemplateBadgeFields;
use App\Models\TemplateField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateBadgeFieldController extends Controller
{
    public function index($badge_id)
    {

//        var_dump($badge_id);
        $where = array('id' => $badge_id);
        $templateBadge = TemplateBadge::where($where)->get()->first();

//        var_dump($templateBadge->template_id);
        $templateFields = DB::select('select * from template_fields_view v where v.template_id = ?',[$templateBadge->template_id]);

//        var_dump($templateFields);
        if (request()->ajax()) {
            $templaeBadgeFileds = DB::select('select * from template_badge_fields where  badge_id = ?', [$badge_id]);
            return datatables()->of($templaeBadgeFileds)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-field">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-field"  data-id="' . $data->id . '" data-original-title="Delete" class="delete btn btn-danger delete-field">Delete</a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Template.template-badge-fields')->with('badge_id',$badge_id)->with('templateFields',$templateFields);
    }

    public function store(Request $request)
    {
        $templateFieldId = $request->template_field_id;

        $where = array('id' => $templateFieldId);
        $templateFiled = TemplateField::where($where)->first();

        $templateBadgeField   =   TemplateBadgeFields::updateOrCreate(['id' => $request->field_id],
            [   'badge_id'  => $request->badge_id,
                'template_field_id'  => $request->template_field_id,
                'template_field_name' => $templateFiled->label_en,
                'position_x' => $request->position_x,
                'position_y'  =>  $request->position_y,
                'size' => $request->size,
                'text_color' => $request->text_color,
                'bg_color' => $request->bg_color,
            ]);
        return Response::json($templateBadgeField);
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $post = TemplateBadgeFields::where($where)->first();
        return Response::json($post);
    }

    public function destroy($field_id)
    {
        $field = TemplateBadgeFields::where('id', $field_id)->delete();

        return Response::json($field);
    }
}
