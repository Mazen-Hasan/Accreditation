<?php

namespace App\Http\Controllers;

use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use Redirect;
use Response;

class CompanyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(CompanyCategory::latest()->get())
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-category">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    if ($data->status == 1) {
                        $button .= '<a href="javascript:void(0);" id="deActivate-category" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">  Deactivate</a>';
                    } else {
                        $button .= '<a href="javascript:void(0);" id="activate-category" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.CompanyCategory.companyCategories');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $categoryId = $request->category_id;
        $category = CompanyCategory::updateOrCreate(['id' => $categoryId],
            ['name' => $request->name,
                'status' => $request->status
            ]);
        return Response::json($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */


    public function edit($id)
    {
        $where = array('id' => $id);
        $category = CompanyCategory::where($where)->first();
        return Response::json($category);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $category = CompanyCategory::where('id', $id)->delete();

        return Response::json($category);
    }

    public function changeStatus($id, $status)
    {
        $category = CompanyCategory::updateOrCreate(['id' => $id],
            [
                'status' => $status
            ]);
        return Response::json($category);
    }

}
