<?php

namespace App\Http\Controllers;

use App\Models\TemplateField;
use Illuminate\Http\Request;

class TemplateFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(TemplateField::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="javascript:void(0);" id="deActivate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">  Deactivate</a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="activate-template" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Template.template-fields');
    }
}
