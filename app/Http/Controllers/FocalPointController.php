<?php

namespace App\Http\Controllers;

use App\Models\FocalPoint;
use App\Models\SelectOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class FocalPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $focalpoint = DB::select('select * from focal_points_view');
            return datatables()->of($focalpoint)
                ->addColumn('name', function ($row) {
                    return $row->name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('focalpointEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="reset_password" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->account_id . '" title="Reset password"><i class="fas fa-retweet"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.FocalPoint.focalpoints');
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
        if ($postId == null) {
            $user = User::updateOrCreate(['id' => $postId],
                ['name' => $request->account_name,
                    'password' => Hash::make($request->password),
                    'email' => $request->account_email,
                ]);
            DB::table('users_roles')->insert(
                array(
                    'user_id' => $user->id,
                    'role_id' => 3
                )
            );
            $post = FocalPoint::updateOrCreate(['id' => $postId],
                ['name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'telephone' => $request->telephone,
                    'mobile' => $request->mobile,
                    'password' => $request->password,
                    'account_id' => $user->id,
                    'status' => $request->status,
                ]);
        } else {
            $post = FocalPoint::updateOrCreate(['id' => $postId],
                ['name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'telephone' => $request->telephone,
                    'mobile' => $request->mobile,
                    'status' => $request->status,
                ]);
        }


        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function focalpointAdd()
    {
        $where = array('status' => 1);
        $titlesSelectOptions = array();

        $contactStatus1 = new SelectOption(1, 'Active');
        $contactStatus2 = new SelectOption(0, 'InActive');
        $contactStatuss = [$contactStatus1, $contactStatus2];

        return view('pages.FocalPoint.focalpoint-add')->with('contactStatuss', $contactStatuss);
    }


    public function edit($id)
    {
        $where = array('id' => $id);
        $focalpoint = FocalPoint::where($where)->first();
        $contactStatus1 = new SelectOption(1, 'Active');
        $contactStatus2 = new SelectOption(0, 'InActive');
        $contactStatuss = [$contactStatus1, $contactStatus2];
        return view('pages.FocalPoint.focalpoint-edit')->with('focalpoint', $focalpoint)->with('contactStatuss', $contactStatuss);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = FocalPoint::where('id', $id)->delete();

        return Response::json($post);
    }


    public function storeContactTitle($contactId, $titleId)
    {
        $post = User::updateOrCreate(['id' => 0],
            ['contact_id' => $contactId,
                'title_id' => $titleId,
                'status' => 1
            ]);
        return Response::json($post);
    }

    public function resetPassword($id, $password)
    {
        $user = User::updateOrCreate(['id' => $id],
            ['password' => Hash::make($password),
            ]);
        return Response::json($user);
    }


}

