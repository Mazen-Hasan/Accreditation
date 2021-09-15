<?php

namespace App\Http\Controllers;

use App\Models\FieldType;
use App\Models\SelectOption;
use App\Models\Template;
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
        return view('pages.Template.templates');
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function templateAdd()
    {

        $fieldTypes = FieldType::get()->all();

//        var_dump($fieldTypes);
//        exit;
        $fieldTypesArray = array();
        foreach($fieldTypes as $fieldType)
        {
            $fieldTypesSelectOption = new SelectOption($fieldType->id, $fieldType->name);
            $fieldTypesArray[] = $fieldTypesSelectOption;
        }

        return view('pages.Template.template-add')->with('filedTypes',$fieldTypesArray);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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

        $query =  'select p.label_ar, p.label_en, p.mandatory, p.min_char, p.max_char, p.field_type_id  from pre_defined_fields  p';
        $pre_defined_fields_res = DB::select($query);

//        foreach($pre_defined_fields_res as $row){
//            echo $row['label_en'];
//        }
//
//        exit();
//
//        DB::insert('insert into template_fields(label_ar, label_en, mandatory, min_char,  max_char, field_type_id)');
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
