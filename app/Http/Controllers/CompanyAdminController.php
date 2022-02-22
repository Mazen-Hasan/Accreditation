<?php

namespace App\Http\Controllers;

use App\Models\AccreditationCategory;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyAccreditaionCategory;
use App\Models\CompanyCategory;
use App\Models\CompanyStaff;
use App\Models\Country;
use App\Models\Event;
use App\Models\FocalPoint;
use App\Models\Gender;
use App\Models\NationalityClass;
use App\Models\Participant;
use App\Models\Religion;
use App\Models\EventCompany;
use App\Models\SelectOption;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class CompanyAdminController extends Controller
{
    public function index()
    {
        //$events = DB::select('select * from company_admins_view cc where cc.account_id = ? and cc.status = ? and cc.event_end_date >= CURRENT_DATE()', [Auth::user()->id, 3]);
        $events = DB::select('select * from company_admins_view cc where cc.account_id = ? and cc.status = ? and cc.event_status < ?', [Auth::user()->id, 3,4]);
        $subCompany_nav = 1;
        return view('pages.CompanyAdmin.company-admin')->with('events', $events)->with('subCompany_nav', $subCompany_nav);
    }

    public function companyParticipants($companyId, $eventId)
    {

        // $addable = 1;
        // $companyAccrediationCategories = CompanyAccreditaionCategory::where(['company_id'=>$companyId,'event_id'=>$eventId])->get()->all();
        // if($companyAccrediationCategories == null){
        //     $addable = 0;
        // }else{
        //     $size = 0;
        //     $inserted = 0;
        //     $status = 0;
        //     $count = 0;
        //     foreach($companyAccrediationCategories as $companyAccrediationCategory){
        //         $size = $size + $companyAccrediationCategory->size;
        //         $inserted = $inserted + $companyAccrediationCategory->inserted;
        //         $status = $status + $companyAccrediationCategory->status;
        //         $count = $count + 1;
        //     }
        //     if($size == $inserted){
        //         $addable = 0;
        //     }
        //     if($status > 0){
        //         if($status/2 != $count){
        //             $addable = 0;
        //         }
        //     }else{
        //         $addable = 0;
        //     }
        // }

    	$addable = 1;
        $companyAccrediationCategories = CompanyAccreditaionCategory::where(['company_id'=>$companyId,'event_id'=>$eventId])->get()->all();
        if($companyAccrediationCategories == null){
            $addable = 2;
        }else{
            $size = 0;
            $inserted = 0;
            $status = 0;
            $count = 0;
            foreach($companyAccrediationCategories as $companyAccrediationCategory){
                $size = $size + $companyAccrediationCategory->size;
                $inserted = $inserted + $companyAccrediationCategory->inserted;
                $status = $status + $companyAccrediationCategory->status;
                $count = $count + 1;
            }
            if($size == $inserted){
                if($size == 0){
                    $addable = 2;
                }else{
                    $addable = 0;
                }
            }
            if($status > 0){
                if($status/2 != $count){
                    $addable = 3;
                }
            }else{
                $addable = 3;
            }
        }
        $dataTableColumuns = array();

        $where = array('id' => $companyId);
        $company = Company::where($where)->get()->first();

        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        // Schema::dropIfExists('temp_' . $companyId);
        // Schema::create('temp_' . $companyId, function ($table) use ($templateFields) {
        //     $table->string('id');
        //     foreach ($templateFields as $templateField) {
        //         $dataTableColumuns[] = $templateField->label_en;
        //         $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
        //     }
        // });
        if(!Schema::hasTable('temp_' . $eventId)){
            Schema::create('temp_' . $eventId, function ($table) use ($templateFields) {
                $table->string('id');
                foreach ($templateFields as $templateField) {
                    $dataTableColumuns[] = $templateField->label_en;
                    $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
                }
            });
         }
    	// $where = array('event_id' => $eventId, 'company_id' => $companyId);
    	// //$companyStaffs = CompanyStaff::where($where)->get()->all();
    	// $companyStaffs = DB::select('select * from company_staff_view where event_id = ? and company_id = ? or parent_id = ?',[$eventId,$companyId,$companyId]);
    	// $alldata = array();
    	// foreach ($companyStaffs as $companyStaff) {
    	// $where = array('staff_id' => $companyStaff->id);
    	// $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
    	// $staffDataValues = array();
    	// $staffDataValues[] = $companyStaff->id;
    	// foreach ($staffDatas as $staffData) {
    	// if ($staffData->slug == 'select') {
    	// $where = array('template_field_id' => $staffData->template_field_id, 'value_id' => $staffData->value);
    	// $value = TemplateFieldElement::where($where)->first();
    	// $staffDataValues[] = $value->value_en;
    	// } else {
    	// $staffDataValues[] = $staffData->value;
    	// }
    	// }
    	// $alldata[] = $staffDataValues;
    	// }
    	// $query = '';
    	// foreach ($alldata as $data) {
    	// $query = 'insert into temp_' . $companyId . ' (id';
    	// foreach ($templateFields as $templateField) {
    	// $query = $query . ',' . preg_replace('/\s+/', '_', $templateField->label_en);
    	// }
    	// $query = $query . ') values (';
    	// foreach ($data as $staffDataValue) {
    	// $query = $query . '"' . $staffDataValue . '",';
    	// }
    	// $query = substr($query, 0, strlen($query) - 1);
    	// $query = $query . ')';
    	// DB::insert($query);
    	// }
        if (request()->ajax()) {
            $eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
            $companies = "'".$companyId."'";
            if($eventcompanies != null){
                foreach($eventcompanies as $eventcompnay){
                    $companies = $companies.",'".$eventcompnay->company_id."'";
                }
            }
            $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '`' . ' t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')');
        //$participants = DB::select('select t.* , c.* from temp_' . $eventId . ' t inner join company_staff c on t.id = c.id where c.company_id = ?',[$companyId]);
            return datatables()->of($participants)
                ->addColumn('status', function ($data) {
                    $status_value = "initaited";
                    switch ($data->status) {
                        case 0:
                            $status_value = "Initiated";
                            break;
                        case 1:
                            $status_value = "Waiting Security Officer Approval";
                            break;
                        case 2:
                            $status_value = "Waiting Event Admin Approval";
                            break;
                        case 3:
                            $status_value = "Approved by security officer";
                            break;
                        case 4:
                            $status_value = "Rejected by security officer";
                            break;
                        case 5:
                            $status_value = "Rejected by event admin";
                            break;
                        case 6:
                            $status_value = "Approved by event admin";
                            break;
                        case 7:
                            $status_value = "Needs review and correction by security officer";
                            break;
                        case 8:
                            $status_value = "Needs review and correction by event admin";
                            break;
                        case 9:
                            $status_value = "Badge generated";
                            break;
                        case 10:
                            $status_value = "Badge printed";
                            break;
                    }
                    return $status_value;
                })
                ->addColumn('action', function ($data) use ($event) {
                    $button = '';
                    $button .= '<a href="' . route('templateFormDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    if($event->status < 3){
                        switch ($data->status) {

                            case 0:
                                $button .= '<a href="' . route('templateForm', [$data->id,$data->company_id,$data->event_id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                $button .= '<a href="javascript:void(0);" id="send_request" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Send request"><i class="far fa-paper-plane"></i></a>';
                                break;
                            case 7:
                                $button .= '<a href="' . route('templateForm', [$data->id,$data->company_id,$data->event_id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->security_officer_reject_reason . '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                                break;
                            case 8:
                                $button .= '<a href="' . route('templateForm', [$data->id,$data->company_id,$data->event_id]) . '" data-toggle="tooltip"  id="edit-event" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                                $button .= '&nbsp;&nbsp;';
                                $button .= '<a href="javascript:void(0);" id="show_reason" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-reason="' . $data->event_admin_reject_reason . '" title="Reject reason"><i class="far fa-comment-alt"></i></a>';
                                break;
                        }
                    }
                    return $button;
                })
            	->addColumn('image', function ($data) {
                    $image = '';
                    //$image .= '<a href="' . route('templateFormDetails', $data->id) . '" data-toggle="tooltip"  id="participant-details" data-id="' . $data->id . '" data-original-title="Edit" title="Details"><i class="far fa-list-alt"></i></a>';
                    $image .= '<img src="'. asset('badges/'.$data->Personal_Image).'" alt="Personal" class="pic-img" style="margin-left:40px">';
                    return $image;
                })
                ->addColumn('identifier', function ($data) {
                    return $data->identifier;
                })
                ->rawColumns(['identifier','image','status', 'action'])
                ->make(true);
        }
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.CompanyAdmin.company-participants')->with('dataTableColumns', $dataTableColumuns)->with('subCompany_nav', $subCompany_nav)->with('companyId',$companyId)
            ->with('eventId',$eventId)->with('event_name', $event->name)->with('company_name', $company->name)->with('addable',$addable)->with('event_status',$event->status);
    }

    public function getPaticipantsData($companyId,$eventId,$values){
        $eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
        $companies = "'".$companyId."'";
        if($eventcompanies != null){
            foreach($eventcompanies as $eventcompnay){
                $companies = $companies.",'".$eventcompnay->company_id."'";
            }
        }
        $totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '`' . ' t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')');
        $size = 10;
        $whereCondition = "";
        if($values != null){
            if(str_contains($values,",")){
                $comands = explode(",",$values);
                $skip = $size * $comands[0];
                $c_size = sizeof($comands);
                $i = 1;
                while($i < sizeof($comands)){
                    $token = $comands[$i];
                    $i = $i + 1;
                    $complexityType = $comands[$i];
                    if($complexityType == "C"){
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $i = $i + 1;
                        $operator = $comands[$i];
                        $i = $i + 1;
                        $condition2 = $comands[$i];
                        $i = $i + 1;
                        $condition2token = $comands[$i];
                        $whereCondition =  $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token) . " ".$operator ." ". TemplateController::getConditionPart($token,$condition2,$condition2token);
                    }else{
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $whereCondition = $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token);
                    }
                    $i = $i + 1;
                }
                $companies = "'".$companyId."'";
                if($eventcompanies != null){
                    foreach($eventcompanies as $eventcompnay){
                        $companies = $companies.",'".$eventcompnay->company_id."'";
                    }
                }
                $totalSize = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')'. $whereCondition);
                $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.')'. $whereCondition." LIMIT ". $size. " OFFSET ". $skip);
            }else{
                $skip = $size * $values;
                $eventcompanies = EventCompany::where(['event_id'=>$eventId,'parent_id'=>$companyId])->get()->all();
                $companies = "'".$companyId."'";
                if($eventcompanies != null){
                    foreach($eventcompanies as $eventcompnay){
                        $companies = $companies.",'".$eventcompnay->company_id."'";
                    }
                }
                $participants = DB::select('select t.* , c.* from `temp_' . $eventId . '` t inner join company_staff c on t.id = c.id where c.company_id in ('.$companies.') LIMIT '. $size. " OFFSET ". $skip);
            }
        }
        return Response::json(array(
            'success' =>true,
            'code' => 1,
            'size' => round(sizeof($totalSize)/2),
            'templates' => $participants,
            'message' => 'hi'
        ));
        //return Response::json($templates);
    }


//    public function companyParticipantAdd()
//    {
//        $accreditationCategories = AccreditationCategory::get()->all();
//        $accreditationCategoriesSelectOption = array();
//        foreach ($accreditationCategories as $accreditationCategory) {
//            $accreditationCategorySelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
//            $accreditationCategoriesSelectOption[] = $accreditationCategorySelectOption;
//        }
//        $nationalClassess = NationalityClass::get()->all();
//        $classess = array();
//        foreach ($nationalClassess as $nationalClass) {
//            $class = new SelectOption($nationalClass->id, $nationalClass->name);
//            $classess[] = $class;
//        }
//        $gendersItems = Gender::get()->all();
//        $genders = array();
//        foreach ($gendersItems as $gendersItem) {
//            $gender = new SelectOption($gendersItem->id, $gendersItem->name);
//            $genders[] = $gender;
//        }
//        $religionsItems = Religion::get()->all();
//        $religions = array();
//        foreach ($religionsItems as $religionsItem) {
//            $religion = new SelectOption($religionsItem->id, $religionsItem->name);
//            $religions[] = $religion;
//        }
//        return view('pages.CompanyAdmin.company-participant-add')->with('classess', $classess)->with('genders', $genders)->with('accreditationCategoriesSelectOption', $accreditationCategoriesSelectOption)->with('religionsSelectOption', $religions);
//    }

    public function store(Request $request)
    {
        $where = array('company_admin_id' => Auth::user()->id);
        $company = Company::where($where)->get()->first();
        $postId = $request->post_id;
        $post = Participant::updateOrCreate(['id' => $postId],
            ['first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'first_name_ar' => $request->first_name_ar,
                'last_name_ar' => $request->last_name_ar,
                'nationality' => $request->nationality,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'position' => $request->position,
                'religion' => $request->religion,
                'address' => $request->address,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'company' => $company->id,
                'subCompany' => $company->id,
                'passport_number' => $request->passport_number,
                'id_number' => $request->id_number,
                'class' => $request->class,
                'accreditation_category' => $request->accreditation_category,
                'creator' => $request->creator,
            ]);
        return Response::json($post);
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $post = Participant::where($where)->first();

        $accreditationCategories = AccreditationCategory::get()->all();
        $accreditationCategoriesSelectOption = array();
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorySelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategoriesSelectOption[] = $accreditationCategorySelectOption;
        }
        $nationalClassess = NationalityClass::get()->all();
        $classess = array();
        foreach ($nationalClassess as $nationalClass) {
            $class = new SelectOption($nationalClass->id, $nationalClass->name);
            $classess[] = $class;
        }
        $gendersItems = Gender::get()->all();
        $genders = array();
        foreach ($gendersItems as $gendersItem) {
            $gender = new SelectOption($gendersItem->id, $gendersItem->name);
            $genders[] = $gender;
        }
        $religionsItems = Religion::get()->all();
        $religions = array();
        foreach ($religionsItems as $religionsItem) {
            $religion = new SelectOption($religionsItem->id, $religionsItem->name);
            $religions[] = $religion;
        }

        return view('pages.CompanyAdmin.company-participant-edit')->with('post', $post)->with('classess', $classess)->with('genders', $genders)->with('accreditationCategoriesSelectOption', $accreditationCategoriesSelectOption)->with('religionsSelectOption', $religions);;
    }

    public function companyAccreditCategories($eventId, $companyId)
    {
        $companies = DB::select('select * from companies_view where id = ? and event_id = ?', [$companyId,$eventId]);
        foreach($companies as $company1){
            $company = $company1;
        }

        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();
        $companyAccreditationCategories = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$company->id, $eventId]);
        $status = 0;
        $remainingSize = $company->size;
        foreach ($companyAccreditationCategories as $companyAccreditationCategory) {
            $status = $companyAccreditationCategory->status;
            $remainingSize = $remainingSize - $companyAccreditationCategory->size;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
//         $accreditationCategories = AccreditationCategory::where($where)->get()->all();

//         foreach ($accreditationCategories as $accreditationCategory) {
//             $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
//             $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
//         }
        $accreditationCategories = DB::select('select * from event_accreditation_categories_view where event_id = ?',[$eventId]);
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->accreditation_category_id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        if (request()->ajax()) {
            $companyAccreditationCategories = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$companyId, $eventId]);
            $companyAccreditationCategoriesStatuss = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$companyId, $eventId]);
            $status = 0;
            foreach ($companyAccreditationCategoriesStatuss as $companyAccreditationCategoriesStatus) {
                $status = $companyAccreditationCategoriesStatus->status;
            }
            // $status = 1;
            if ($status == 0) {
                return datatables()->of($companyAccreditationCategories)
                    ->addColumn('action', function ($data) use($event) {
                        $button = '';
                        if($event->status < 3){
                            $button .= '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" title="Edit Size"><i class="fas fa-chart-pie"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip"  data-size="' . $data->size . '" data-original-title="Delete" data-id="' . $data->id . '" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        }
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } else {
                if ($status == 1) {
                    return datatables()->of($companyAccreditationCategories)
                        ->addColumn('action', function ($data) {
                            $button = 'Waiting for approval';
                            return $button;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                } else {
                    return datatables()->of($companyAccreditationCategories)
                        ->addColumn('action', function ($data) {
                            $button = 'Approved';
                            return $button;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }
            }
        }
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.CompanyAdmin.company-accreditation-size')->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('companyId', $company->id)->with('eventId', $eventId)->with('status', $status)->with('event_name', $event->name)->with('company_name', $company->name)->with('company_size', $company->size)->with('remaining_size', $remainingSize)->with('subCompany_nav', $subCompany_nav)->with('event_status',$event->status);
    }

    public function editCompanyAccreditSize($id)
    {

        $where = array('id' => $id);
        $post = CompanyAccreditaionCategory::where($where)->first();
        return Response::json($post);
    }

    public function storeCompanyAccrCatSize($id, $accredit_cat_id, $size, $company_id, $event_id)
    {

        $where = array('company_id'=>$company_id, 'event_id' => $event_id);
        $eventcompnay = EventCompany::where($where)->first();
    	$status = 0;
    	if($eventcompnay->parent_id != null){
        	$status = 2;
        }
        try {
            $post = CompanyAccreditaionCategory::updateOrCreate(['id' => $id],
                ['size' => $size,
                 	'event_company_id' => $eventcompnay->id,
                    'accredit_cat_id' => $accredit_cat_id,
                    'company_id' => $company_id,
                    'subcompany_id' => $company_id,
                    'event_id' => $event_id,
                    'status' => $status
                ]);

        } catch (\Exception $e) {
            return Response::json(array(
                'code' => 400,
                'message' => $e->getMessage()
            ), 400);
        }
        return Response::json($post);
    }

    public function destroyCompanyAccreditCat($id)
    {
        $post = CompanyAccreditaionCategory::where('id', $id)->delete();
        return Response::json($post);

    }

    public function sendApproval($companyId, $eventId)
    {
        $event = Event::where(['id'=>$eventId])->first();
        $company = Company::where(['id'=>$companyId])->first();
        $where = array('company_id' => $companyId, 'event_id' => $eventId);
        $companyAccreditCategories = CompanyAccreditaionCategory::where($where)
            ->update(['status' => 1]);
        $event_admins = DB::select('select * from event_admins_view e where e.id=?',[$eventId]);
        foreach ($event_admins as $event_admin){
            NotificationController::sendAlertNotification($event_admin->event_admin, 'sendApproval', $event->name . ': ' . $company->name . ': ' . 'Accreditation Categories Size approval', Route('companyAccreditCat', [$companyId, $eventId]));
        }
        return Response::json($companyAccreditCategories);

    }

    public function sendRequest($staffId)
    {
        $where = array('id' => $staffId);
        $companyStaff = CompanyStaff::where($where)->first();
        $companyId = $companyStaff->company_id;
        $eventId = $companyStaff->event_id;

        $eventWhere = array('id' => $eventId);
        $event = Event::where($eventWhere)->first();

        $companyWhere = array('id' => $companyId);
        $company = Company::where($companyWhere)->first();

        $approval = $event->approval_option;

        $event_admins = DB::select('select * from event_admins_view e where e.id=?',[$eventId]);
        $event_security_officers = DB::select('select * from event_security_officers_view e where e.id=?',[$eventId]);

        if ($approval == 2) {
            foreach ($event_security_officers as $event_security_officer){
//                NotificationController::sendAlertNotification($event_security_officer->security_officer_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', '/security-officer-participant-details/' . $staffId);
                // NotificationController::sendAlertNotification($event_security_officer->security_officer_id, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', Route('securityParticipantDetails' , $staffId));

            	$notification_type = Config::get('enums.notification_types.PAR');
                NotificationController::sendNotification($notification_type, $event->name, $company->name, $event_security_officer->security_officer_id, $staffId,
                    $event->name . ': ' . $company->name . ': ' . 'Participant approval',
                    Route('securityParticipantDetails' , $staffId));
            }

//            $updateQuery = 'update company_staff set security_officer_id = ' . $event->security_officer . ' where id = ' . $staffId;
//            DB::update($updateQuery);
            DB::update('update company_staff set status = ? where id = ?', [1, $staffId]);

        } else {
            foreach ($event_admins as $event_admin){
                NotificationController::sendAlertNotification($event_admin->event_admin, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', Route('participantDetails', $staffId));
            }

            if ($approval == 1) {
//                NotificationController::sendAlertNotification($event->event_admin, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', '/event-participant-details/' . $staffId);
//                DB::update('update company_staff set event_admin_id = ? where id = ?', [$event->event_admin, $staffId]);
                DB::update('update company_staff set status = ? where id = ?', [2, $staffId]);
            } else {
//                NotificationController::sendAlertNotification($event->event_admin, $staffId, $event->name . ': ' . $company->name . ': ' . 'Participant approval', '/event-participant-details/' . $staffId);
//                DB::update('update company_staff set event_admin_id = ? where id = ?', [$event->event_admin, $staffId]);
                DB::update('update company_staff set status = ? where id = ?', [2, $staffId]);
            }
        }
        return Response::json($event);

    }

    public function subCompanies($companyId, $eventId)
    {
        $where = array('id' => $companyId);
        $company = Company::where($where)->first();
        $where = array('id' => $eventId);
        $event = Event::where($where)->first();
        if (request()->ajax()) {
            $companies = DB::select('select * from companies_view where parent_id = ? and event_id = ?', [$company->id,$eventId]);
            return datatables()->of($companies)
                ->addColumn('action', function ($data) use ($event) {
                    $button = "";
                    if($event->status < 3){
                        $button .= '<a href="' . route('subCompanyEdit', [$data->id, $data->event_id]) . '" data-toggle="tooltip"  id="edit-company" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascript:void(0);" id="invite-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" data-name="' . $data->name . '" data-focalpoint="' . $data->focal_point . '" title="Invite"><i class="far fa-share-square"></i></a>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    $button .= '<a href="' . route('subCompanyAccreditCategories', [$data->id, $data->event_id]) . '" id="delete-company" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" title="Accreditation Size"><i class="fas fa-sitemap"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.CompanyAdmin.subCompany')->with('event_name', $event->name)->with('company_name', $company->name)->with('eventId', $event->id)->with('companyId',$companyId)->with('subCompany_nav',$subCompany_nav)->with('event_status',$event->status);
    }

    public function getsubCompaniesData($companyId, $eventId,$values){
        $totalSize = DB::select('select * from companies_view where parent_id = ? and event_id = ?', [$companyId,$eventId]);
        //$totalSize = Template::latest()->get();
        $size = 10;
        $whereCondition = "";
        if($values != null){
            if(str_contains($values,",")){
                $comands = explode(",",$values);
                $skip = $size * $comands[0];
                $c_size = sizeof($comands);
                $i = 1;
                while($i < sizeof($comands)){
                    $token = $comands[$i];
                    $i = $i + 1;
                    $complexityType = $comands[$i];
                    if($complexityType == "C"){
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $i = $i + 1;
                        $operator = $comands[$i];
                        $i = $i + 1;
                        $condition2 = $comands[$i];
                        $i = $i + 1;
                        $condition2token = $comands[$i];
                        $whereCondition =  $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token) . " ".$operator ." ". TemplateController::getConditionPart($token,$condition2,$condition2token);
                    }else{
                        $i = $i + 1;
                        $condition1 = $comands[$i];
                        $i = $i + 1;
                        $condition1token = $comands[$i];
                        $whereCondition = $whereCondition." and ".TemplateController::getConditionPart($token,$condition1,$condition1token);
                    }
                    $i = $i + 1;
                }
                $totalSize = DB::select('select * from companies_view where parent_id = ? and event_id = ? '. $whereCondition, [$companyId,$eventId]);
                $templates = DB::select('select * from companies_view where parent_id = ? and event_id = ? '. $whereCondition." LIMIT ". $size. " OFFSET ". $skip, [$companyId,$eventId]);
            }else{
                $skip = $size * $values;
                $templates = DB::select("select * from companies_view where parent_id = ? and event_id = ? LIMIT ". $size. " OFFSET ". $skip, [$companyId,$eventId]);
            }
        }
        return Response::json(array(
            'success' =>true,
            'code' => 1,
            'size' => round(sizeof($totalSize)/2),
            'templates' => $templates,
            'message' => 'hi'
        ));
        //return Response::json($templates);
    }

    public function storeSubCompnay(Request $request)
    {
        $where = array('id' => $request->focal_point);
        $focalPoint = FocalPoint::where($where)->first();
        $companyId = $request->company_Id;
        if ($companyId == null) {
                $company = Company::updateOrCreate(['id' => $companyId],
                ['name' => $request->company_name,
                    'address' => $request->address,
                    'telephone' => $request->telephone,
                    'website' => $request->website,
                    'country_id' => $request->country,
                    'city_id' => $request->city,
                    'category_id' => $request->category,
                    'parent_id'=> $request->parent_id
                ]);
                $event_company = EventCompany::updateOrCreate(['id' => 0],
                ['event_id' => $request->event_id,
                'company_id' => $company->id,
                'parent_id' => $request->parent_id,
                'status' => $request->company_status,
                'focal_point_id' => $request->focal_point,
                'size' => $request->size,
                'need_management' => 0
            ]);
        } else {

            $where = array('id' => $companyId);
            $company = Company::where($where)->first();
            $status = $company->status;
            if ($request->company_status == 0) {
                $status = 0;
            } else {
                if ($company->status != 3) {
                    $status = $request->company_status;
                }
            }
            $company = Company::updateOrCreate(['id' => $companyId],
                ['name' => $request->company_name,
                    'address' => $request->address,
                    'telephone' => $request->telephone,
                    'website' => $request->website,
                    'country_id' => $request->country,
                    'city_id' => $request->city,
                    'category_id' => $request->category,
                ]);
                $event_company = EventCompany::updateOrCreate(['event_id' => $request->event_id,'company_id' => $companyId],
                [
                'status' => $request->company_status,
                'focal_point_id' => $request->focal_point,
                'size' => $request->size,
                'need_management' => 0
            ]);
        }

        return Response::json($company);
    }

    public function subCompanyEdit($id, $eventid)
    {
        $where = array('id' => $eventid);
        $event = Event::where($where)->first();
        $companies = DB::select('select * from companies_view where id = ? and event_id = ?', [$id,$eventid]);
        foreach($companies as $company){
            $post = $company;
        }
        $eventcompanies = EventCompany::where(['company_id'=>$post->id,'event_id'=>$eventid])->first();
        $where = array('id' => $eventcompanies->focal_point_id);
        //$where = array('status' => 1);
        $contacts = FocalPoint::where($where)->get()->all();
        $focalPointsOption = array();
        foreach ($contacts as $contact) {
            $focalPointSelectOption = new SelectOption($contact->id, $contact->name . ' ' . $contact->last_name);
            $focalPointsOption[] = $focalPointSelectOption;
        }

        $countrysSelectOptions = array();
//         $countries = Country::get()->all();

//         foreach ($countries as $country) {
//             $countrySelectOption = new SelectOption($country->id, $country->name);
//             $countrysSelectOptions[] = $countrySelectOption;
//         }
        $countries = DB::select('select DISTINCT(ccc.country_id), c.country_name from country_cities_view ccc inner join country_cities_view c on ccc.country_id = c.country_id');
        foreach ($countries as $country) {
            $countrySelectOption = new SelectOption($country->country_id, $country->country_name);
            $countrysSelectOptions[] = $countrySelectOption;
        }

        $citysSelectOptions = array();
        //$cities = City::get()->all();
		$cities = City::where(['country_id'=>$post->country_id])->get()->all();
        foreach ($cities as $city) {
            $citySelectOption = new SelectOption($city->id, $city->name);
            $citysSelectOptions[] = $citySelectOption;
        }

        $where = array('status' => 1);
        $categorysSelectOptions = array();
        $categories = CompanyCategory::where($where)->get()->all();

        foreach ($categories as $category) {
            $categorySelectOption = new SelectOption($category->id, $category->name);
            $categorysSelectOptions[] = $categorySelectOption;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        $companyStatus1 = new SelectOption(1, 'Active');
        $companyStatus2 = new SelectOption(0, 'InActive');
        $companyStatuss = [$companyStatus1, $companyStatus2];

        $parentId = $post->parent_id;

        $eventcompanysize = EventCompany::where(['event_id'=> $eventid,'company_id'=> $parentId])->first();
        $allwoedSize = $eventcompanysize->size;

        $eventsubcompanysize = EventCompany::where(['event_id'=> $eventid,'company_id'=> $post->id])->first();
        $allwoedSize = $allwoedSize + $eventsubcompanysize->size;
        $eventcompanies = EventCompany::where(['event_id'=> $eventid,'parent_id'=> $parentId])->get()->all();
        foreach($eventcompanies as $eventcompnay){
            $allwoedSize = $allwoedSize - $eventcompnay->size;
        }
        $participants = CompanyStaff::where(['company_id'=>$parentId])->get()->all();
        foreach($participants as $participant){
            $allwoedSize = $allwoedSize - 1;
        }

        if (request()->ajax()) {
            $companyAccreditationCategories = DB::select('select * from company_accreditaion_categories_view where company_id = ?', [$id]);
            return datatables()->of($companyAccreditationCategories)
                ->addColumn('action', function ($data) {
                    $button = '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" title="Edit"><i class="fas fa-edit"></i></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip" data-original-title="Delete" data-id="' . $data->id . '" class="delete btn btn-danger" title="Delete"><i class="far fa-trash-alt"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.CompanyAdmin.subCompany-edit')->with('company', $post)->with('countrys', $countrysSelectOptions)->with('citys', $citysSelectOptions)->with('focalPoints', $focalPointsOption)
            ->with('categorys', $categorysSelectOptions)->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('eventId', $eventid)->with('event_name', $event->name)->with('company_name', $post->name)->with('statuss', $companyStatuss)->with('subCompany_nav',$subCompany_nav)->with('allowedSize',$allwoedSize);
    }

    public function destroy($id)
    {
        $post = Company::where('id', $id)->delete();

        return Response::json($post);
    }

    public function subCompanyAdd($id,$companyId)
    {
        $where = array('id' => $id);
        $event = Event::where($where)->first();
        $where = array('id' => $companyId);
        $company = Company::where($where)->first();
        $where = array('status' => 1);
        $contacts = FocalPoint::where($where)->get()->all();
        $focalPointsOption = array();
        // foreach ($contacts as $contact) {
        //     $focalPointSelectOption = new SelectOption($contact->id, $contact->name . ' ' . $contact->middle_name . ' ' . $contact->last_name);
        //     $focalPointsOption[] = $focalPointSelectOption;
        // }

        $countrysSelectOptions = array();
//         $countries = Country::get()->all();

//         foreach ($countries as $country) {
//             $countrySelectOption = new SelectOption($country->id, $country->name);
//             $countrysSelectOptions[] = $countrySelectOption;
//         }
        $countries = DB::select('select DISTINCT(ccc.country_id), c.country_name from country_cities_view ccc inner join country_cities_view c on ccc.country_id = c.country_id');
        foreach ($countries as $country) {
            $countrySelectOption = new SelectOption($country->country_id, $country->country_name);
            $countrysSelectOptions[] = $countrySelectOption;
        }

        $citysSelectOptions = array();
        $cities = City::get()->all();

        // foreach ($cities as $city) {
        //     $citySelectOption = new SelectOption($city->id, $city->name);
        //     $citysSelectOptions[] = $citySelectOption;
        // }

        $where = array('status' => 1);
        $categorysSelectOptions = array();
        $categories = CompanyCategory::where($where)->get()->all();

        foreach ($categories as $category) {
            $categorySelectOption = new SelectOption($category->id, $category->name);
            $categorysSelectOptions[] = $categorySelectOption;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
        $accreditationCategories = AccreditationCategory::where($where)->get()->all();

        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        $companyStatus1 = new SelectOption(1, 'Active');
        $companyStatus2 = new SelectOption(0, 'InActive');
        //$companyStatus3 = new SelectOption(3,'Invited');
        $companyStatuss = [$companyStatus1, $companyStatus2];
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        $eventcompanysize = EventCompany::where(['event_id'=> $id,'company_id'=> $companyId])->first();
        $allwoedSize = $eventcompanysize->size;
        $eventcompanies = EventCompany::where(['event_id'=> $id,'parent_id'=> $companyId])->get()->all();
        foreach($eventcompanies as $eventcompnay){
            $allwoedSize = $allwoedSize - $eventcompnay->size;
        }
        $participants = CompanyStaff::where(['event_id'=> $id,'company_id'=>$companyId])->get()->all();
        foreach($participants as $participant){
            $allwoedSize = $allwoedSize - 1;
        }
        return view('pages.CompanyAdmin.subCompany-add')->with('countrys', $countrysSelectOptions)->with('citys', $citysSelectOptions)->with('focalPoints', $focalPointsOption)
            ->with('categorys', $categorysSelectOptions)->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('eventId', $id)->with('event_name', $event->name)->with('statuss', $companyStatuss)->with('company_name', $company->name)
            ->with('companyId',$companyId)->with('subCompany_nav',$subCompany_nav)->with('allowedSize',$allwoedSize);
    }

    public function subCompanyAccreditCategories($companyId, $eventId)
    {
        $addable = 1;
        $companyParents = EventCompany::where(['company_id'=>$companyId,'event_id'=>$eventId])->get()->all();
        foreach($companyParents as $companyParent){
            $parentId = $companyParent->parent_id;
        }
        $parentAcredititationCategories = CompanyAccreditaionCategory::where(['company_id'=> $parentId,'event_id'=>$eventId])->get()->all();
        foreach($parentAcredititationCategories as $parentAcredititationCategory){
            $parentAcredititationCategorystatus = $parentAcredititationCategory->status;
            if($parentAcredititationCategorystatus != 2){
                $addable = 0;
            }
        }

        $companies = DB::select('select * from companies_view where id = ? and event_id = ?', [$companyId,$eventId]);
        foreach($companies as $company1){
            $company = $company1;
        }

        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();
        $companyAccreditationCategories = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$company->id, $eventId]);
        $status = 0;
        $remainingSize = $company->size;
        foreach ($companyAccreditationCategories as $companyAccreditationCategory) {
            $status = $companyAccreditationCategory->status;
            $remainingSize = $remainingSize - $companyAccreditationCategory->size;
        }

        $where = array('status' => 1);
        $accreditationCategorysSelectOptions = array();
//         $accreditationCategories = AccreditationCategory::where($where)->get()->all();

//         foreach ($accreditationCategories as $accreditationCategory) {
//             $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->id, $accreditationCategory->name);
//             $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
//         }
        $accreditationCategories = DB::select('select * from event_accreditation_categories_view where event_id = ?',[$eventId]);
        foreach ($accreditationCategories as $accreditationCategory) {
            $accreditationCategorysSelectOption = new SelectOption($accreditationCategory->accreditation_category_id, $accreditationCategory->name);
            $accreditationCategorysSelectOptions[] = $accreditationCategorysSelectOption;
        }

        if (request()->ajax()) {
            $companyAccreditationCategories = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$companyId, $eventId]);
            $companyAccreditationCategoriesStatuss = DB::select('select * from event_company_accrediation_categories_view where company_id = ? and event_id = ?', [$companyId, $eventId]);
            $status = 1;
            foreach ($companyAccreditationCategoriesStatuss as $companyAccreditationCategoriesStatus) {
                $status = $companyAccreditationCategoriesStatus->status;
            }
            //if ($status == 0) {
                return datatables()->of($companyAccreditationCategories)
                    ->addColumn('action', function ($data) use ($event) {
                        $button = "";
                        if($event->status < 3){
                            $button .= '<a href="javascript:void(0);" data-toggle="tooltip"  id="edit-company-accreditation" data-id="' . $data->id . '" data-original-title="Edit" title="Edit Size"><i class="fas fa-chart-pie"></i></a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="javascript:void(0);" id="delete-company-accreditation" data-toggle="tooltip"  data-size="' . $data->size . '" data-original-title="Delete" data-id="' . $data->id . '" title="Delete"><i class="far fa-trash-alt"></i></a>';
                        }
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            // } else {
            //     if ($status == 1) {
            //         return datatables()->of($companyAccreditationCategories)
            //             ->addColumn('action', function ($data) {
            //                 $button = 'Waiting for approval';
            //                 return $button;
            //             })
            //             ->rawColumns(['action'])
            //             ->make(true);
            //     } else {
            //         return datatables()->of($companyAccreditationCategories)
            //             ->addColumn('action', function ($data) {
            //                 $button = 'Approved';
            //                 return $button;
            //             })
            //             ->rawColumns(['action'])
            //             ->make(true);
            //     }
            // }
        }
        $subCompany_nav = 1;
        if($company->parent_id != null){
            $subCompany_nav = 0;
        }
        return view('pages.CompanyAdmin.subCompany-accreditation-size')->with('accreditationCategorys', $accreditationCategorysSelectOptions)->with('companyId', $company->id)->with('eventId', $eventId)->with('status', $status)->with('event_name', $event->name)->with('company_name', $company->name)->with('company_size', $company->size)->with('remaining_size', $remainingSize)->with('subCompany_nav',$subCompany_nav)->with('company_parent',$company->parent_id)->with('event_status',$event->status)->with('addable',$addable);
    }

    // public function Invite($companyId,$eventId)
    // {
    //     $post = EventCompany::updateOrCreate(['company_id' => $companyId,'event_id'=>$eventId],
    //         [
    //             'status' => 3
    //         ]);
    //         $focal_point = DB::select('select * from focal_points f where f.id = ?', [$post->focal_point_id]);
    //         $event = Event::where(['id'=>$eventId])->first();
    //         $company = Company::where(['id'=>$companyId])->first();
    //         NotificationController::sendAlertNotification($focal_point[0]->account_id, 0, $event->name . ': ' . $company->name . ': ' . 'Event invitation', Route('companyParticipants' , [$companyId, $eventId]));
    //     return Response::json($post);
    // }

	public function Invite($companyId,$eventId)
    {
        $post = EventCompany::updateOrCreate(['company_id' => $companyId,'event_id'=>$eventId],
            [
                'status' => 3
            ]);
            $focal_point = DB::select('select * from focal_points f where f.id = ?', [$post->focal_point_id]);
            $event = Event::where(['id'=>$eventId])->first();
            $company = Company::where(['id'=>$companyId])->first();
//            NotificationController::sendAlertNotification($focal_point[0]->account_id, 0, $event->name . ': ' . $company->name . ': ' . 'Event invitation', Route('companyParticipants' , [$companyId, $eventId]));

            $notification_type = Config::get('enums.notification_types.EIN');
            NotificationController::sendNotification($notification_type, $event->name, '', $focal_point[0]->account_id, 0, $event->name . ':' . 'Event invitation',
            Route('companyParticipants' , [$companyId, $eventId]));

        return Response::json($post);
    }

    public function getSubCompnayCities($countrytId)
    {
        $cities = DB::select('select * from cities c where c.country_id = ? ',[$countrytId]);

        $subcount = 0;
        $citySelectOptions = array();
        foreach ($cities as $city) {
            // if ($subcount == 0) {
            //     $compnaySelectOption = new SelectOption(0, 'All');
            //     $companySelectOptions[] = $compnaySelectOption;
            //     $subcount = 1;
            // }
            $citySelectOption = new SelectOption($city->id, $city->name);
            $citySelectOptions[] = $citySelectOption;
        }
        return Response::json($citySelectOptions);
    }
}
