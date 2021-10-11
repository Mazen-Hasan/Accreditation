<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\TemplateBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateBadgeController extends Controller
{
    public function index()
    {

        if (request()->ajax()) {

            $templateBadge = DB::select('select *  from template_badges_view tb');


            return datatables()->of($templateBadge)
                ->addColumn('action', function ($data) {
                    $button ='';
                    if ($data->is_locked == 0) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-templateId="' . $data->template_id . '" data-original-title="Edit" class="edit btn btn-success edit-badge">Edit</a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    $button .= '<a href="' . route('templateBadgeFields', $data->id) . '" id="template-badge-fields" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-dark" title="fields">Fields<i class="mdi mdi-grid-large menu-items"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->is_locked == 1) {
                        $button .= '<a href="javascript:void(0);" id="unLock-badge" data-toggle="tooltip" data-original-title="Unlock" data-id="' . $data->id . '" class="delete btn btn-facebook">Unlock</a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="lock-badge" data-toggle="tooltip" data-original-title="Lock" data-id="' . $data->id . '" class="delete btn btn-outline-facebook"> &nbsp;Lock&nbsp;</a>';
                    }
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-templateId="' . $data->template_id . '" data-original-title="Preview" class="edit btn btn-facebook preview-badge">Preview</a>';
                    $button .= '&nbsp;&nbsp;';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

//        $templates = DB::select('select * from templates where id not in (select template_id  from template_badges)');
        $templates = DB::select('select * from templates ');
        return view('pages.Template.template-badge')->with('templates', $templates);
    }

    public function store(Request $request)
    {
        $badge_id = $request->badge_id;

//        if($badge_id ==  0){
//            $existTemplate = DB::select('select count(*) from template_badges tb where templatte_id=?',[$request->template_id]);
//            if($existTemplate <> 0){
//                return Response::json('false');
//            }
//        }

        $templateBadge   =   TemplateBadge::updateOrCreate(['id' => $badge_id],
            ['template_id' => $request->template_id,
                'width' => $request->width,
                'high' => $request->high,
                'bg_color' => $request->bg_color,
                'bg_image' => $request->bg_image,
                'is_locked'  =>  $request->has('locked'),
                'creator' => Auth::user()->id
            ]);
        return Response::json($templateBadge);
    }

    public function edit($id)
    {
        $templateBadge = DB::select('select * from template_badges_view where id = ?', [$id]);
        return Response::json($templateBadge[0]);
    }

    public function changeLock($id, $is_locked)
    {
        $post = TemplateBadge::updateOrCreate(['id' => $id],
            [
                'is_locked' => $is_locked
            ]);
        return Response::json($post);
    }
}
