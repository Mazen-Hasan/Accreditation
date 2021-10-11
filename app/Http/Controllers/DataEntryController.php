<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\DataEntry;
use App\Models\Event;
use App\Models\SelectOption;
use App\Models\StaffData;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class DataEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $focalpoint = DB::select('select * from data_entrys_view where company_admin_id = ?', [Auth::user()->id]);
            return datatables()->of($focalpoint)
                ->addColumn('name', function ($row) {
                    return $row->name . ' ' . $row->middle_name . ' ' . $row->last_name;
                })
                ->addColumn('action', function ($data) {
                    //$button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button = '<a href="' . route('dataentryEdit', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="reset_password" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->account_id . '" class="delete btn btn-google">Reset Password</a>';
                    //$button .= '<a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">   Delete</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.DataEntry.dataentrys');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->first();
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
                    'role_id' => 5
                )
            );
            $post = DataEntry::updateOrCreate(['id' => $postId],
                ['name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'telephone' => $request->telephone,
                    'mobile' => $request->mobile,
                    'company_id' => $company->id,
                    'company_admin_id' => Auth::user()->id,
                    'password' => $request->password,
                    'account_id' => $user->id,
                    'status' => $request->status,
                ]);
        } else {
            $post = DataEntry::updateOrCreate(['id' => $postId],
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

        return view('pages.DataEntry.dataentry-add')->with('contactStatuss', $contactStatuss);
    }


    public function edit($id)
    {
        $where = array('id' => $id);
        $focalpoint = DataEntry::where($where)->first();
        $contactStatus1 = new SelectOption(1, 'Active');
        $contactStatus2 = new SelectOption(0, 'InActive');
        $contactStatuss = [$contactStatus1, $contactStatus2];
        return view('pages.DataEntry.dataentry-edit')->with('focalpoint', $focalpoint)->with('contactStatuss', $contactStatuss);
    }


    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $post = DataEntry::where('id', $id)->delete();

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

    public function dataEntryParticipants()
    {
        $dataTableColumuns = array();

        $where = array('account_id' => Auth::user()->id);
        $dataentry = DataEntry::where($where)->first();

        $where = array('company_admin_id' => $dataentry->company_admin_id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $company->event_id);
        $event = Event::where($where)->get()->first();

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        Schema::dropIfExists('temp_dataentry_' . Auth::user()->id);
        Schema::create('temp_dataentry_' . Auth::user()->id, function ($table) use ($templateFields) {
            $table->string('id');
            foreach ($templateFields as $templateField) {
                $dataTableColumuns[] = $templateField->label_en;
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        // $where = array('company_admin_id' => Auth::user()->id);
        // $company = Company::where($where)->get()->first();

        $where = array('event_id' => $company->event_id, 'company_id' => $company->id);
        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            // $staffDatas = StaffData::where($where)->get()->all();
            $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            $staffDataValues = array();
            $staffDataValues[] = $companyStaff->id;
            foreach ($staffDatas as $staffData) {
                if ($staffData->slug == 'select') {
                    $where = array('template_field_id' => $staffData->template_field_id, 'value_id' => $staffData->value);
                    $value = TemplateFieldElement::where($where)->first();
                    $staffDataValues[] = $value->value_en;
                } else {
                    $staffDataValues[] = $staffData->value;
                }
            }
            $alldata[] = $staffDataValues;
        }
        // var_dump($alldata);
        // exit;
        $query = '';
        foreach ($alldata as $data) {
            $query = 'insert into temp_dataentry_' . Auth::user()->id . ' (id';
            foreach ($templateFields as $templateField) {
                $query = $query . ',' . preg_replace('/\s+/', '_', $templateField->label_en);
            }
            $query = $query . ') values (';
            foreach ($data as $staffDataValue) {
                $query = $query . '"' . $staffDataValue . '",';
            }
            $query = substr($query, 0, strlen($query) - 1);
            $query = $query . ')';
            DB::insert($query);
        }
        //DB::insert($query);
        // var_dump($query);
        // exit;
        if (request()->ajax()) {
            $participants = DB::select('select t.* , c.* from temp_dataentry_' . Auth::user()->id . ' t inner join company_staff c on t.id = c.id');
            return datatables()->of($participants)
                ->addColumn('status', function ($data) {
                    $status_value = "initaited";
                    switch ($data->status) {
                        case 0:
                            $status_value = "Initaited";
                            break;
                        case 1:
                            $status_value = "waiting Security Officer Approval";
                            break;
                        case 2:
                            $status_value = "waiting Event Admin Approval";
                            break;
                        case 3:
                            $status_value = "approved by security officer";
                            break;
                        case 4:
                            $status_value = "rejected by security officer";
                            break;
                        case 5:
                            $status_value = "rejected by event admin";
                            break;
                        case 6:
                            $status_value = "approved by event admin";
                            break;
                        case 7:
                            $status_value = "rejected with correction by security officer";
                            break;
                        case 8:
                            $status_value = "rejected with correction by event admin";
                            break;
                        case 9:
                            $status_value = "badge generated";
                            break;
                        case 10:
                            $status_value = "badge printed";
                            break;
                    }
                    return $status_value;
                    //return $row->first_name.' '.$row->last_name;
                })
                ->addColumn('action', function ($data) {
                    $button = '';
                    // $button .= '<a href="' . route('templateFormDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-facebook edit-post">Details</a>';
                    // $button .= '&nbsp;&nbsp;';
                    switch ($data->status) {

                        case 0:
                            $button .= '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                            $button .= '&nbsp;&nbsp;';
                            //$button .= '<a href="javascript:void(0);" id="send_request" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete btn btn-danger">Send Request</a>';
                            break;
                        // case 7:
                        //     $button .= '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                        //     $button .= '&nbsp;&nbsp;';
                        //     $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" data-reason="'.$data->security_officer_reject_reason.'" class="delete btn btn-danger">Reject Reason</a>';
                        //     break;
                        // case 8:
                        //     $button .= '<a href="' . route('templateForm', $data->id) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" class="edit btn btn-success edit-post">Edit</a>';
                        //     $button .= '&nbsp;&nbsp;';
                        //     $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" data-reason="'.$data->event_admin_reject_reason.'" class="delete btn btn-danger">Reject Reason</a>';
                        //     break;
                    }
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        $subCompany_nav = 1;
        // $where = array('company_admin_id' => Auth::user()->id);
        // $company = Company::where($where)->first();
        if ($company->subCompany_id != null) {
            $subCompany_nav = 0;
        }
        return view('pages.DataEntry.dataentry-participants')->with('dataTableColumns', $dataTableColumuns)->with('subCompany_nav', $subCompany_nav);
    }

    public function participantAdd($participant_id)
    {
        $where = array('account_id' => Auth::user()->id);
        $dataentry = DataEntry::where($where)->first();

        $where = array('company_admin_id' => $dataentry->company_admin_id);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $company->event_id);
        $event = Event::where($where)->first();

        $template_id = $event->event_form;
        if ($participant_id != 0) {
            $templateFields = DB::select('select * from staff_data_template_fields_view v where v.staff_id = ? and template_id = ?', [$participant_id, $event->event_form]);
        } else {
            $templateFields = DB::select('select * from template_fields_view v where v.template_id = ? order by v.field_order', [$template_id]);
        }

        //var_dump($templateFields);
        $fieldsCount = 0;

        $options = array();
        $form = '<div class="row">';
        $attachmentForm = '';
        $attachmentFormHidden = '';
        if ($participant_id == 0) {
            $form .= $this->createHiddenField('participant_id', 'participant_id', '');
        } else {
            $form .= $this->createHiddenField('participant_id', 'participant_id', $participant_id);
        }
        foreach ($templateFields as $templateField) {
            $options = [];
            if ($fieldsCount % 2 == 0) {
                if ($fieldsCount > 0) {
                    $form .= '</div>';
                }
                $form .= '<div class="row">';
            }
            $fieldsCount++;

            switch ($templateField->slug) {
                case 'text':
                    if ($participant_id == 0) {
                        if (strtolower($templateField->label_en) == 'company' or strtolower($templateField->label_en) == 'event') {
                            if (strtolower($templateField->label_en) == 'company') {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $company->name);
                            } else {
                                $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $event->name);
                            }
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        if (strtolower($templateField->label_en) == 'company' or strtolower($templateField->label_en) == 'event') {
                            $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                            break;
                        }
                        $form .= $this->createTextField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'number':
                    if ($participant_id == 0) {
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, '');
                    } else {
                        $form .= $this->createNumberField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                            $templateField->mandatory, $templateField->min_char, $templateField->max_char, $templateField->value);
                    }
                    break;

                case 'textarea':
                    $form .= $this->createTextArea(str_replace(' ', '_', $templateField->label_en), $templateField->label_en,
                        $templateField->mandatory);
                    break;

                case 'date':
                    if ($participant_id == 0) {
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->mandatory, '');
                    } else {
                        $form .= $this->createDate(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->mandatory, $templateField->value);
                    }
                    break;

                case 'select':
                    if ($participant_id == 0) {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, '');
                    } else {
                        $fieldElements = DB::select('select * from template_field_elements f where f.template_field_id = ?', [$templateField->template_field_id]);
                        foreach ($fieldElements as $fieldElement) {
                            $option = new SelectOption($fieldElement->value_id, $fieldElement->value_en);
                            $options [] = $option;
                        }
                        $form .= $this->createSelect(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $options, $templateField->value);
                    }
                    break;

                case 'file':
                    $fieldsCount--;
                    if ($participant_id == 0) {
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, 0, '');
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, '');
                    } else {
                        $attachmentForm .= $this->createAttachment(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, 0, $templateField->value);
                        $form .= $this->createHiddenField(str_replace(' ', '_', $templateField->label_en), $templateField->label_en, $templateField->value);
                    }


                    break;
            }
        }
        if ($fieldsCount % 2 == 1) {
            $form .= '<div class="col-md-6"><div class="form-group col"></div></div>';
        }

        $subCompany_nav = 1;
        // $where = array('company_admin_id' => Auth::user()->id);
        // $company = Company::where($where)->first();
        // if($company->subCompany_id != null){
        //     $subCompany_nav = 0;
        // }
        return view('pages.DataEntry.dataentry-participant-add')->with('form', $form)->with('attachmentForm', $attachmentForm)->with('subCompany_nav', $subCompany_nav);
    }

    public function createHiddenField($id, $label, $value)
    {
        $textfield = '<input type="hidden" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';

        return $textfield;
    }

    public function createTextField($id, $label, $mandatory, $min_char, $max_char, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if ($min_char) {
            $minChar = '" minlength="' . $min_char;
        }
        if ($max_char) {
            $maxChar = '"maxlength="' . $max_char;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="text" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . $minChar . $maxChar . '"' . $required . ' value="' . $value . '" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createNumberField($id, $label, $mandatory, $min_value, $max_value, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }
        $minChar = '';
        $maxChar = '';
        if ($min_value) {
            $minChar = '" min="' . $min_value;
        }
        if ($max_value) {
            $maxChar = '"max="' . $max_value;
        }

        $textfield = '<div class="col-md-6"><div class="form-group col">';
        $textfield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $textfield .= '<input type="number" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . $minChar . $maxChar . '"' . $required . ' value="' . $value . '" />';
        $textfield .= '</div></div></div>';

        return $textfield;
    }

    public function createTextArea($id, $label, $mandatory)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<textarea id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . '></textarea>';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createDate($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $datefield = '<div class="col-md-6"><div class="form-group col">';
        $datefield .= '<label>' . $label . '</label><div class="col-sm-12">';
        $datefield .= '<input type="date" id="' . $id . '" name="' . $id . '" placeholder="enter ' . $label . '"' . $required . ' value="' . $value . '" />';
        $datefield .= '</div></div></div>';

        return $datefield;
    }

    public function createSelect($id, $label, $elements, $value)
    {
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  id="' . $id . '" name="' . $label . '">';
        foreach ($elements as $element) {
            $selectField .= '<option ';
            if ($element->key == $value) {
                $selectField .= ' selected="selected"';
            }
            $selectField .= ' value="' . $element->key . '">' . $element->value . '</option>';
        }

        $selectField .= '</select></div></div></div>';
        return $selectField;
    }

    public function createAttachment($id, $label, $mandatory, $value)
    {
        $required = '';
        if ($mandatory == '1') {
            $required = 'required=""';
        }

        $attachmentField = '<form id=form_' . $id . '" name="badgeForm" class="form-horizontal  img-upload" enctype="multipart/form-data" action="javascript:void(0)">';
        $attachmentField .= '<div class="row"><div class="col-md-5"><label>' . $label . '</label></div>';
        $attachmentField .= '<div class="col-md-4"><div class="col-sm-12"><input type="file" id="file_' . $id . '" name=file_"' . $id . '"></div></div>';
        $attachmentField .= '<div class="col-md-3"><button type="submit" id="btn-upload_' . $id . '" value="Upload">Upload</button></div></div>';
        $attachmentField .= '<div class="row"><div class="col-md-12"><div class="form-group col">';
        $attachmentField .= '<label id="file_type_error_' . $id . '"></label><div style="background-color: #ffffff00!important;" class="progress">';
        $attachmentField .= '<div id="file-progress-bar_' . $id . '" class="progress-bar"></div></div></div></div></div></form>';

        return $attachmentField;
    }

    public function createMultiSelect($id, $label, $elements)
    {
        $selectField = '<div class="col-md-6"><div class="form-group col">';
        $selectField .= '<label>' . $label . '</label><div class="col-sm-12">';
        $selectField .= '<select  multiple id="' . $id . '" name="' . $label . '[]">';
        foreach ($elements as $element) {
            $selectField .= '<option value="' . $element->key . '">' . $element->value . '</option>';
        }

        $selectField .= '</select></div></div></div>';
        return $selectField;
    }

    public function storeParticipant(Request $request)
    {
        //$this->uploadFile($request);
        $where = array('account_id' => Auth::user()->id);
        $dataentry = DataEntry::where($where)->first();
        $where = array('company_admin_id' => $dataentry->company_admin_id);
        $company = Company::where($where)->get()->first();
        $participant_id = $request->participant_id;
        $companyStaff = CompanyStaff::updateOrCreate(['id' => $participant_id],
            ['event_id' => $company->event_id,
                'company_id' => $company->id,
                'security_officer_id' => '0',
                'security_officer_decision' => '0',
                'security_officer_decision_date' => null,
                'security_officer_reject_reason' => '',
                'event_admin_id' => '0',
                'event_admin_decision' => '0',
                'event_admin_decision_date' => null,
                'event_admin_reject_reason' => '',
                'status' => '0'
            ]);
        $data = $request->all();


        foreach ($data as $key => $value) {

            if ($key != 'participant_id') {
                if ($participant_id != null) {
                    $query = 'update staff_data s set s.value = "' . $value . '" where s.staff_id = ' . $companyStaff->id . ' and s.key ="' . $key . '" ';
                    DB::update($query);
                } else {
                    $staffData = StaffData::updateOrCreate(['staff_id' => $companyStaff->id, 'key' => $key],
                        ['staff_id' => $companyStaff->id,
                            'key' => $key,
                            'value' => $value
                        ]);
                }
            }
        }

        return Response::json($companyStaff);
    }
}
