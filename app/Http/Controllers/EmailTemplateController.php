<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class EmailTemplateController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $emailTemplates = DB::select('select * from email_templates_view');

            return datatables()->of($emailTemplates)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0)" id="edit-email-template" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.Template.email-templates');
    }

    public function store(Request $request)
    {
        $emailTemplateId = $request->email_template_id;

        $emailTemplate = EmailTemplate::updateOrCreate(['id' => $emailTemplateId],
            ['content' => $request->email_template_content,
                'subject' => $request->subject
            ]);
        return Response::json($emailTemplate);
    }

    public function edit($emailTemplateId)
    {
        $where = array('id' => $emailTemplateId);
        $emailTemplate = EmailTemplate::where($where)->first();
        return Response::json($emailTemplate);
    }

}
