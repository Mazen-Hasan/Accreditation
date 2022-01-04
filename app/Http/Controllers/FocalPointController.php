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
    	$role = Auth::user()->roles->first()->slug;
        $title_name = 'Focal Points';
        if($role == 'company-admin'){
           	$title_name = 'Subsidiaries Accounts';
        }
        if (request()->ajax()) {
            //$focalpoint = DB::select('select * from focal_points_view');
            $role = Auth::user()->roles->first()->slug;
            if($role == 'event-admin'){
                $focalpoint = DB::select('SELECT f.* from focal_points_view f where f.id in (select distinct(id) from event_companies_focal_points_view where user_id = ?)',[Auth::user()->id]);
            }
            if($role == 'company-admin'){
                $focalpoint = DB::select('SELECT fff.* FROM focal_points_view fff WHERE fff.id IN( SELECT DISTINCT (ff.id) FROM event_companies ee INNER JOIN focal_points_view ff ON ff.id = ee.focal_point_id WHERE ee.parent_id IN( SELECT e.company_id FROM event_companies e INNER JOIN focal_points_view f ON e.focal_point_id = f.id where f.account_id = ? ) )',[Auth::user()->id]);
            }
            return datatables()->of($focalpoint)
                ->addColumn('name', function ($row) {
                    return $row->name . ' ' . $row->last_name;
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
        return view('pages.FocalPoint.focalpoints')->with('title_name',$title_name);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

	// public function store(Request $request)
	// {
	// $postId = $request->post_id;
	// if ($postId == null) {
	// $users = User::where(['email' => $request->account_email])->first();
	// if($users == null){
	// $user = User::updateOrCreate(['id' => $postId],
	// ['name' => $request->account_name,
	// 'password' => Hash::make($request->password),
	// 'email' => $request->account_email,
	// ]);
	// DB::table('users_roles')->insert(
	// array(
	// 'user_id' => $user->id,
	// 'role_id' => 3
	// )
	// );
	// $post = FocalPoint::updateOrCreate(['id' => $postId],
	// ['name' => $request->name,
	// 'middle_name' => $request->middle_name,
	// 'last_name' => $request->last_name,
	// 'email' => $request->email,
	// 'telephone' => $request->telephone,
	// 'mobile' => $request->mobile,
	// 'password' => $request->password,
	// 'account_id' => $user->id,
	// 'status' => $request->status,
	// ]);
	// }else{
	// return Response()->json([
	// "success" => true,
	// "id" => 0,
	// "code" => 401,
	// "message" => 'Entered focal point account email already existed in the system'
	// ]);
	// }
	// } else {
	// $post = FocalPoint::updateOrCreate(['id' => $postId],
	// ['name' => $request->name,
	// 'middle_name' => $request->middle_name,
	// 'last_name' => $request->last_name,
	// 'email' => $request->email,
	// 'telephone' => $request->telephone,
	// 'mobile' => $request->mobile,
	// 'status' => $request->status,
	// ]);
	// }
	// return Response::json($post);
	// }

	public function store(Request $request)
    {
        if($request->entry_type == 'instant'){
                $postId = $request->focal_point_id;
                if ($postId == null) {
                    $users = User::where(['email' => $request->account_email])->first();
                    if($users == null){
                        $user = User::updateOrCreate(['id' => $postId],
                            ['name' => $request->account_name,
                                'password' => Hash::make($request->password),
                                'email' => $request->account_email,
                             	'is_active' => $request->status
                            ]);
                        DB::table('users_roles')->insert(
                            array(
                                'user_id' => $user->id,
                                'role_id' => 3
                            )
                        );
                        $post = FocalPoint::updateOrCreate(['id' => $postId],
                            ['name' => $request->name,
                               // 'middle_name' => $request->middle_name,
                                'last_name' => $request->last_name,
                                //'email' => $request->email,
                                'telephone' => $request->telephone,
                                'mobile' => $request->mobile,
                                'password' => $request->password,
                                'account_id' => $user->id,
                                'status' => $request->status,
                            ]);
                    }else{
                        $focalpoint = FocalPoint::where(['account_id' => $users->id])->first();
                        if($focalpoint != null){
                            return Response()->json([
                                "success" => true,
                                "id" => $focalpoint->id,
                                "name" => $focalpoint->name.' '.$focalpoint->middle_name.' '.$focalpoint->last_name,
                                "code" => 401,
                                "message" => 'Entered focal point account email already existed in the system with the name '.$focalpoint->name.' '.$focalpoint->middle_name.' '.$focalpoint->last_name .', Do you want to add him/her to the event?'
                            ]);
                        }else{
                            return Response()->json([
                                "success" => true,
                                "id" => 0,
                                "code" => 402,
                                "message" => 'Sorry, Entered focal point account email already existed in the system with different role'
                            ]);
                        }
                    }
                } else {
                    $post = FocalPoint::updateOrCreate(['id' => $postId],
                        ['name' => $request->name,
                           // 'middle_name' => $request->middle_name,
                            'last_name' => $request->last_name,
                          //  'email' => $request->email,
                            'telephone' => $request->telephone,
                            'mobile' => $request->mobile,
                            'status' => $request->status,
                        ]);
                   $user = User::updateOrCreate(['id' => $post->account_id],
                            [
                             	'is_active' => $request->status
                            ]);
                }
            return Response::json($post);
        }else{
            $postId = $request->post_id;
            if ($postId == null) {
                $users = User::where(['email' => $request->account_email])->first();
                if($users == null){
                    $user = User::updateOrCreate(['id' => $postId],
                        ['name' => $request->account_name,
                            'password' => Hash::make($request->password),
                            'email' => $request->account_email,
                         	'is_active' => $request->status,
                        ]);
                    DB::table('users_roles')->insert(
                        array(
                            'user_id' => $user->id,
                            'role_id' => 3
                        )
                    );
                    $post = FocalPoint::updateOrCreate(['id' => $postId],
                        ['name' => $request->name,
                          //  'middle_name' => $request->middle_name,
                            'last_name' => $request->last_name,
                          //  'email' => $request->email,
                            'telephone' => $request->telephone,
                            'mobile' => $request->mobile,
                            'password' => $request->password,
                            'account_id' => $user->id,
                            'status' => $request->status,
                        ]);
                }else{
                    return Response()->json([
                        "success" => true,
                        "id" => 0,
                        "code" => 401,
                        "message" => 'Entered focal point account email already existed in the system'
                    ]);
                }
            } else {
                $post = FocalPoint::updateOrCreate(['id' => $postId],
                    ['name' => $request->name,
                      //  'middle_name' => $request->middle_name,
                        'last_name' => $request->last_name,
                     //   'email' => $request->email,
                        'telephone' => $request->telephone,
                        'mobile' => $request->mobile,
                        'status' => $request->status,
                    ]);
               $user = User::updateOrCreate(['id' => $post->account_id],
                            [
                             	'is_active' => $request->status
                            ]);
            }
        return Response::json($post);
        }
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

