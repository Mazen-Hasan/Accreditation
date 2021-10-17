<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Response;


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
                    $button = '<a href="javascript:void(0)" id="edit-category" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="javascript:void(0);" id="deActivate-category" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Deactivate"><i class="fas fa-ban"></i></a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="activate-category" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Activate"><i class="fas fa-check-circle"></i></a>';
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
        try{
        $postId = $request->post_id;
        $post = AccreditationCategory::updateOrCreate(['id' => $postId],
            ['name' => $request->name,
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            return Response::json(array(
                'code' => 400,
                'message' => $e->getMessage()
            ), 400);
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


