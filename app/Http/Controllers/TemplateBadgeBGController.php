<?php

namespace App\Http\Controllers;

use App\Models\TemplateBadgeBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class TemplateBadgeBGController extends Controller
{
    public function index($badge_id)
    {
        if (request()->ajax()) {
            $badge_bg = DB::select('select * from template_badge_backgrounds_view v where v.badge_id = ?',[$badge_id]);
            return datatables()->of($badge_bg)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" id="edit-bg" data-toggle="tooltip"  data-id="' . $data->id . '" data-bgId="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.Template.template-badge-bg')->with('badge_id',$badge_id);
    }

    public function edit($badge_bg_id)
    {
        $badge_bg = DB::select('select * from template_badge_backgrounds_view v where v.id = ?',[$badge_bg_id]);
        return Response::json($badge_bg[0]);
    }

    public function store(Request $request)
    {
        $badge_bg_id = $request->badge_bg_id;

        $templateBadgeBG = TemplateBadgeBackground::updateOrCreate(['id' => $badge_bg_id],
            ['bg_image' => $request->bg_image,
                'creator' => Auth::user()->id
            ]);

        return Response::json($templateBadgeBG);
    }

}
