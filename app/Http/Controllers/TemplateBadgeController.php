<?php

namespace App\Http\Controllers;

use App\Models\TemplateBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateBadgeController extends Controller
{
    public function index($template_id)
    {

        if (request()->ajax()) {

            $templateBadge = DB::select('select * from template_badges tb where tb.template_id = ?',[$template_id]);

            return datatables()->of($templateBadge)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-badge">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . route('templateBadgeFields', $data->id) . '" id="template-badge-fields" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="fields">Fields<i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.Template.template-badge')->with('template_id',$template_id);
    }

    public function store(Request $request)
    {
        $badge_id = $request->badge_id;

        $templateBadge   =   TemplateBadge::updateOrCreate(['id' => $badge_id],
            ['template_id' => $request->template_id,
                'width' => $request->width,
                'high' => $request->high,
                'bg_color' => $request->bg_color,
                'creator' => Auth::user()->id
            ]);
        return Response::json($templateBadge);
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $templateBadge = TemplateBadge::where($where)->first();
        return Response::json($templateBadge);
    }
}
