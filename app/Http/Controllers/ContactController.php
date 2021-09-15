<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactTitle;
use App\Models\SelectOption;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use function Sodium\add;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            return datatables()->of(Contact::latest()->get())
                ->addColumn('name', function($row){
                    return $row->name.' '.$row->middle_name.' '.$row->last_name;
                })
                ->addColumn('titleNames', function($data){
                    $result = '';
                    $titleNames = array();
                    $where = array('contact_id' => $data->id);
                    $titleIds = ContactTitle::where($where)->get()->all();
                    foreach ($titleIds as $titleId){
                        //$result = $result.$titleId->title_id;
                        $where = array('id' => $titleId->title_id);
                        $titles = Title::where($where)->first();
                        $titleNames[] = $titles->title_label;
                    }
                    foreach ($titleNames as $titleName){
                        $result = $result.'<p class="btn btn-facebook" style="margin-bottom: 0px; cursor: auto">'.$titleName.'</p>';
                        $result .= '&nbsp;&nbsp;';
                    }
                    return $result;
                    // getContactTitles($data->id);
                })
                ->addColumn('action', function($data){
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button = '<a href="'.route('contactEdit', $data->id).'" data-toggle="tooltip"  id="edit-event" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    //$button .= '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">   Delete</a>';
                    return $button;
                })
                ->rawColumns(['titleNames','action'])
                ->make(true);
        }
        return view('pages.Contact.contacts');
    }

    public function getContactTitles($id){
        $result = 'koko';
        $titleNames = array();
        $where = array('contact_id' => $id);
        $titleIds = ContactTitle::where($where)->get()->all();
        foreach ($titleIds as $titleId){
            $result = $result.$titleId->title_id;
            $where = array('id' => $titleId->title_id);
           $titles = Title::where($where)->first();
           $titleNames[] = $titles->title_label;
        }
       foreach ($titleNames as $titleName){
           $result = $result.'<p>'.$titleName.'</p>';
       }
        return $result;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //xdebug_break();
        $postId = $request->post_id;
        $post   =   Contact::updateOrCreate(['id' => $postId],
            ['name' => $request->name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'mobile' => $request->mobile,
                'status' => $request->status,
                'creation_date' => $request->creation_date,
                'creator' => $request->creator
            ]);
        if($postId == null) {
            foreach ($request->titles as $title) {
                $help = ContactTitle::updateOrCreate(['id' => $postId],
                    ['contact_id' => $post->id,
                        'title_id' => $title,
                        'status' => 1,
                        'creation_date' => $request->creation_date,
                        'creator' => $request->creator
                    ]);
            }
        }
        return Response::json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function contactAdd()
    {
        $where = array('status' => 1);
        $titlesSelectOptions = array();
        $titles = Title::where($where)->get()->where('status','=','1');
        foreach($titles as $title)
        {
            $titlesSelectOption = new SelectOption($title->id, $title->title_label);
            $titlesSelectOptions[] = $titlesSelectOption;
        }

        $contactStatus1 = new SelectOption(1,'Active');
        $contactStatus2 = new SelectOption(0,'InActive');
        $contactStatuss = [$contactStatus1,$contactStatus2];

        return view('pages.Contact.contact-add')->with('titles',$titlesSelectOptions)->with('contactStatuss',$contactStatuss);
    }



    public function edit($id)
    {
        $where = array('id' => $id);
        $post  = Contact::where($where)->first();

        $contactStatus1 = new SelectOption(1,'Active');
        $contactStatus2 = new SelectOption(0,'InActive');
        $contactStatuss = [$contactStatus1,$contactStatus2];


        $where = array('status' => 1);
        $titlesSelectOptions = array();
        $titles = Title::where($where)->get()->where('status','=','1');

        foreach($titles as $title)
        {
            $titlesSelectOption = new SelectOption($title->id, $title->title_label);
            $titlesSelectOptions[] = $titlesSelectOption;
        }

        if(request()->ajax())
        {
            $where = array('contact_id' => $id);
            return datatables()->of(ContactTitle::where($where)->get()->all())
                ->addColumn('title_label', function($data){
                    $result = '';
                    $where = array('id' => $data->title_id,'status' => 1);
                    $title = Title::where($where)->first();
                    $result = $title->title_label;
                    return $result;
                })
                ->addColumn('action', function($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post" id="remove-contact_title">Remove</a>';
                    $button .= '&nbsp;&nbsp;';
//                    if ($data->status == 1) {
//                        $button .= '<a href="javascript:void(0);" id="deActivate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger">  Deactivate</a>';
//                    }else{
//                        $button .= '<a href="javascript:void(0);" id="activate-title" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-outline-google">  &nbsp;Activate&nbsp;</a>';
//                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('pages.Contact.contact-edit')->with('post',$post)->with('contactStatuss',$contactStatuss)->with('titlesSelectOptions',$titlesSelectOptions);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = Contact::where('id',$id)->delete();

        return Response::json($post);
    }

    public function removeContactTitle($contact_title_id){

        $where = array('id'=> $contact_title_id);
        $post = ContactTitle::where($where)->delete();
        return Response::json($post);
    }

    public function storeContactTitle($contactId,$titleId)
    {
        //xdebug_break();
//        $contactId = $request->post_id;
//        $titleId = $request->contactTitle;
        $post   =   ContactTitle::updateOrCreate(['id' => 0],
            ['contact_id' => $contactId,
                'title_id' => $titleId,
                'status'=> 1
            ]);
        return Response::json($post);
    }
}
