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
            // $where = array('event_admin' => Auth::user()->id);
            // $event = Event::where($where)->get()->first();
            //$focalpoint = DB::select('select * from focal_points_view where event_admin_id = ?', [Auth::user()->id]);
            $focalpoint = DB::select('select * from focal_points_view');
            return datatables()->of($focalpoint)
                ->addColumn('name', function ($row) {
                    return $row->name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                // ->addColumn('titleNames', function($data){
                //     $result = '';
                //     $titleNames = array();
                //     $where = array('contact_id' => $data->id);
                //     $titleIds = ContactTitle::where($where)->get()->all();
                //     foreach ($titleIds as $titleId){
                //         //$result = $result.$titleId->title_id;
                //         $where = array('id' => $titleId->title_id);
                //         $titles = Title::where($where)->first();
                //         $titleNames[] = $titles->title_label;
                //     }
                //     foreach ($titleNames as $titleName){
                //         $result = $result.'<p class="btn btn-facebook" style="margin-bottom: 0px; cursor: auto">'.$titleName.'</p>';
                //         $result .= '&nbsp;&nbsp;';
                //     }
                //     return $result;
                //     // getContactTitles($data->id);
                // })
                ->addColumn('action', function ($data) {
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button = '<a href="' . route('focalpointEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="reset_password" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->account_id . '" class="delete btn btn-google">Reset Password</a>';
                    //$button .= '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">   Delete</a>';
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
        // $where = array('event_admin' => Auth::user()->id);
        // $event = Event::where($where)->get()->first();
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
                    //'event_admin_id' => Auth::user()->id,
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
                    // 'event_id' => $event->id,
                    // 'password' => $request->password,
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
        // $titles = Title::where($where)->get()->where('status','=','1');
        // foreach($titles as $title)
        // {
        //     $titlesSelectOption = new SelectOption($title->id, $title->title_label);
        //     $titlesSelectOptions[] = $titlesSelectOption;
        // }

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


        // $where = array('status' => 1);
        // $titlesSelectOptions = array();
        // $titles = Title::where($where)->get()->where('status','=','1');

        // foreach($titles as $title)
        // {
        //     $titlesSelectOption = new SelectOption($title->id, $title->title_label);
        //     $titlesSelectOptions[] = $titlesSelectOption;
        // }

//         if(request()->ajax())
//         {
//             $where = array('contact_id' => $id);
//             return datatables()->of(ContactTitle::where($where)->get()->all())
//                 // ->addColumn('title_label', function($data){
//                 //     $result = '';
//                 //     $where = array('id' => $data->title_id,'status' => 1);
//                 //     $title = Title::where($where)->first();
//                 //     $result = $title->title_label;
//                 //     return $result;
//                 // })
//                 ->addColumn('action', function($data) {
//                     $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post" id="remove-contact_title">Remove</a>';
//                     $button .= '&nbsp;&nbsp;';
// //                    if ($data->status == 1) {
// //                        $button .= '<a href="javascript:void(0);" id="deActivate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">  Deactivate</a>';
// //                    }else{
// //                        $button .= '<a href="javascript:void(0);" id="activate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
// //                    }
//                     return $button;
//                 })
//                 ->rawColumns(['action'])
//                 ->make(true);
//         }


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
        //xdebug_break();
//        $contactId = $request->post_id;
//        $titleId = $request->contactTitle;
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

