<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use Illuminate\Http\Request;
use Redirect, Response;


class AccreditationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(AccreditationCategory::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="javascript:void(0);" id="deActivate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">  Deactivate</a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="activate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.AccreditationCategory.accreditationCategory');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $postId = $request->post_id;
        $post = AccreditationCategory::updateOrCreate(['id' => $postId],
            ['name' => $request->name,
                'status' => $request->status
            ]);
        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */


    public function edit($id)
    {
        $where = array('id' => $id);
        $post = AccreditationCategory::where($where)->first();
        return Response::json($post);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = AccreditationCategory::where('id', $id)->delete();

        return Response::json($post);
    }

    public function changeStatus($id, $status)
    {
        $post = AccreditationCategory::updateOrCreate(['id' => $id],
            [
                'status' => $status
            ]);
        return Response::json($post);
    }


}


