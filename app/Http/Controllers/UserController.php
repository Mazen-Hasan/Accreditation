<?php

namespace App\Http\Controllers;

use App\Models\SelectOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {

            $users = DB::select('select * from users_view');
            return datatables()->of($users)
                ->addColumn('action', function ($data) {
                	$button = '&nbsp;&nbsp;';
                	if($data->role_name != 'Company Admin' && $data->role_name != 'Data Entry'){
                    	$button .= '<a href="' . route('userEdit', $data->user_id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->user_id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    	$button .= '&nbsp;&nbsp;';
                    }
                    $button .= '<a href="javascript:void(0);" id="reset_password" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->user_id . '"  title="Reset password"><i class="fas fa-retweet"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Users.users');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $companyId = $request->post_id;
        if ($companyId == null) {
            $company = User::updateOrCreate(['id' => $companyId],
                ['name' => $request->name,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                ]);
        } else {
            $company = User::updateOrCreate(['id' => $companyId],
                ['name' => $request->name,
                    'email' => $request->email,
                ]);
        }
        if ($companyId == null) {
            DB::table('users_roles')->insert(
                array(
                    'user_id' => $company->id,
                    'role_id' => $request->role
                )
            );
        } else {
            DB::table('users_roles')->where('user_id', $companyId)->update(array(
                'role_id' => $request->role,
            ));
        }

        return Response::json($company);
    }

    public function userAdd()
    {
        $roles = DB::select('select * from roles');
        $roleSelectOptions = array();
        foreach ($roles as $role) {
        	if($role->slug != 'data-entry' && $role->slug != 'company-admin'){
            	$roleSelectOption = new SelectOption($role->id, $role->name);
            	$roleSelectOptions[] = $roleSelectOption;
            }
        }
        return view('pages.Users.user-add')->with('roles', $roleSelectOptions);

    }

    public function userEdit($id)
    {
        $users = DB::select('select * from users_view where user_id = ?', [$id]);
        foreach ($users as $row) {
            $user = $row;
        }
        $roles = DB::select('select * from roles');
        $roleSelectOptions = array();
        foreach ($roles as $role) {
        	if($role->slug != 'data-entry' && $role->slug != 'company-admin'){
            	$roleSelectOption = new SelectOption($role->id, $role->name);
            	$roleSelectOptions[] = $roleSelectOption;
            }
        }
        return view('pages.Users.user-edit')->with('user', $user)->with('roles', $roleSelectOptions);
    }

    public function resetPassword($id, $password)
    {
        $user = User::updateOrCreate(['id' => $id],
            ['password' => Hash::make($password),
            ]);
        return Response::json($user);
    }
}
