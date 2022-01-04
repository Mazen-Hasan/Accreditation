<?php

namespace App\Http\Controllers;

use App\Models\contactTitle;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ContactTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($contact_id)
    {
        if (request()->ajax()) {
            $contactTitles = DB::select('select * from contact_titles_view v where v.contact_id = ?', [$contact_id]);
            return datatables()->of($contactTitles)
                ->addColumn('action', function ($data) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip" id="delete-title"  data-id="' . $data->id . '" data-original-title="Delete" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $titles = Title::get()->all();

        $contact = DB::select('select c.id, concat(coalesce(c.name,"")," ",coalesce(c.middle_name,"")," ",coalesce(c.last_name,"")) AS "name" from contacts c where c.id = ?', [$contact_id]);

        return view('pages.Contact.contact-titles')->with('titles', $titles)->with('contact', $contact[0]);
    }

    public function destroy($title_id)
    {
        $where = array('id' => $title_id);
        $post = ContactTitle::where($where)->delete();
        return Response::json($post);
    }

    public function store(Request $request)
    {
        $where = array('contact_id' => $request->contact_id, 'title_id' => $request->title_id);
        $contactTitle = ContactTitle::where($where)->first();

        if($contactTitle){
            return Response::json(array(
                'code' => -1,
                'message' => 'Title is already exit for this contact'
            ), 400);
        }

        $post = ContactTitle::updateOrCreate(['id' => 0],
            ['contact_id' => $request->contact_id,
                'title_id' => $request->title_id,
                'status' => 1,
                'creator' => Auth::user()->id
            ]);
        return Response::json($post);
    }
}
