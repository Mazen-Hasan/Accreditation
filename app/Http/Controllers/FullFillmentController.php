<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\PreDefinedFieldElement;
use App\Models\SelectOption;
use App\Models\TemplateField;
use App\Models\TemplateFieldElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;

class FullFillmentController extends Controller
{
    public function index()
    {
        $eventsSelectOptions = array();
        $companySelectOptions = array();
        $accrediationCategorySelectOptions = array();
        $events = DB::select('select * from event_admins_view e where e.event_admin=?',[Auth::user()->id]);

        $count = 0;
        foreach ($events as $event) {
            if ($count == 0) {
                $companies = DB::select('select * from companies_view e where e.event_id=? and e.status=?',[$event->id, 3]);
                $subcount = 0;
                foreach ($companies as $company) {
                    if ($subcount == 0) {
                        $compnaySelectOption = new SelectOption(0, 'All');
                        $companySelectOptions[] = $compnaySelectOption;
                        $subcount = 1;
                    }
                    $compnaySelectOption = new SelectOption($company->id, $company->name);
                    $companySelectOptions[] = $compnaySelectOption;
                }
                $count = 1;
            }
            $eventSelectOption = new SelectOption($event->id, $event->name);
            $eventsSelectOptions[] = $eventSelectOption;
        }

        $where = array('predefined_field_id' => 14);
        $acrrediationCategories = PreDefinedFieldElement::where($where)->get()->all();
        $mycount = 0;
        foreach ($acrrediationCategories as $acrrediationCategory) {
            if ($mycount == 0) {
                $accrediationCategorySelectOption = new SelectOption(0, 'All');
                $accrediationCategorySelectOptions[] = $accrediationCategorySelectOption;
                $mycount = 1;
            }
            $accrediationCategorySelectOption = new SelectOption($acrrediationCategory->value_id, $acrrediationCategory->value_en);
            $accrediationCategorySelectOptions[] = $accrediationCategorySelectOption;
        }
        return view('pages.FullFillment.selections')->with('eventsSelectOptions', $eventsSelectOptions)->with('companySelectOptions', $companySelectOptions)->with('accrediationCategorySelectOptions', $accrediationCategorySelectOptions);
    }

    public function getCompanies($eventId)
    {
        $companies = DB::select('select * from companies_view e where e.event_id=? and e.status=?',[$eventId, 3]);

        $subcount = 0;
        $companySelectOptions = array();
        foreach ($companies as $company) {
            if ($subcount == 0) {
                $compnaySelectOption = new SelectOption(0, 'All');
                $companySelectOptions[] = $compnaySelectOption;
                $subcount = 1;
            }
            $compnaySelectOption = new SelectOption($company->id, $company->name);
            $companySelectOptions[] = $compnaySelectOption;
        }
        return Response::json($companySelectOptions);
    }

    public function getParticipants($eventId, $companyId, $accreditId)
    {
        $returnedParticipnats = array();
        $dataTableColumuns = array();
        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();

        if ($companyId != 0) {
            $where = array('id' => $companyId);
            $company = Company::where($where)->get()->first();
        }

        $company_admin_id = '_superAdmin_' . Auth::user()->id;

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }
        Schema::dropIfExists('temp' . $company_admin_id);
        Schema::create('temp' . $company_admin_id, function ($table) use ($templateFields, $companyId) {
            $table->string('id');
            foreach ($templateFields as $templateField) {
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        if ($companyId == 0) {
            $where = array('event_id' => $eventId,'status' ,'>', 8);
            $companyStaffs = DB::select('select * from company_staff c where c.event_id = ? and c.status>8',[$eventId]);

        } else {
//            $where = array('event_id' => $eventId, 'company_id' => $company->id,'status' , '>', 8);
            $companyStaffs = DB::select('select * from company_staff c where c.event_id = ? and c.company_id=? and c.status>8',[$eventId, $companyId]);

        }

        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            if ($companyId != 0) {
                $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            } else {
                $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            }
            $staffDataValues = array();
            $staffDataValues[] = $companyStaff->id;
            $count = 0;
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
        $query = '';
        foreach ($alldata as $data) {
            $query = '';
            if ($companyId == 0) {
                $query = $query . 'insert into temp' . $company_admin_id . ' (id';
            } else {
                $query = $query . 'insert into temp' . $company_admin_id . ' (id';
            }
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
        if ($accreditId == 'All') {
            $participants = DB::select("select t.* , c.* from temp" . $company_admin_id . " t inner join company_staff c on t.id = c.id");
        } else {
            $participants = DB::select("select t.* , c.* from temp" . $company_admin_id . " t inner join company_staff c on t.id = c.id where t.Accreditation_category ='" . $accreditId . "'");
        }
        foreach ($participants as $participant) {
            $returnedParticipnats[] = $participant->id;
        }
        return Response::json($returnedParticipnats);
    }

    public function fullFillment(Request $request)
    {
        $staffIDs = $request->get('staff');
        $updateProduct = CompanyStaff::whereIn('id', $staffIDs)
            ->update(['print_status' => '2', 'status' => '10']);
        return Response::json($updateProduct);
    }

    public function allParticipants($eventId, $companyId, $accreditId, $checked)
    {
        $dataTableColumuns = array();
        $where = array('id' => $eventId);
        $event = Event::where($where)->get()->first();

        if ($companyId != 0) {
            $where = array('id' => $companyId);
            $company = Company::where($where)->get()->first();
        }

        $company_admin_id = '_superAdmin_' . Auth::user()->id;

        $where = array('template_id' => $event->event_form);
        $templateFields = TemplateField::where($where)->orderBy('field_order', 'ASC')->get()->all();

        foreach ($templateFields as $templateField) {
            $dataTableColumuns[] = $templateField->label_en;
        }

        Schema::dropIfExists('temp' . $company_admin_id);
        Schema::create('temp' . $company_admin_id, function ($table) use ($templateFields, $companyId) {
            $table->string('id');
            foreach ($templateFields as $templateField) {
                $table->string(preg_replace('/\s+/', '_', $templateField->label_en));
            }
        });
        if ($companyId == 0) {
            $where = array('event_id' => $eventId);
        } else {
            $where = array('event_id' => $eventId, 'company_id' => $company->id);
        }
        $companyStaffs = CompanyStaff::where($where)->get()->all();
        $alldata = array();
        foreach ($companyStaffs as $companyStaff) {
            $where = array('staff_id' => $companyStaff->id);
            if ($companyId != 0) {
                $staffDatas = DB::select('select * from staff_data_template_fields_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            } else {
                $staffDatas = DB::select('select * from event_staff_data_view where staff_id = ? and template_id = ?', [$companyStaff->id, $event->event_form]);
            }
            $staffDataValues = array();
            $staffDataValues[] = $companyStaff->id;
            $count = 0;
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
        $query = '';
        foreach ($alldata as $data) {
            $query = '';
            if ($companyId == 0) {
                $query = $query . 'insert into temp' . $company_admin_id . ' (id';
            } else {
                $query = $query . 'insert into temp' . $company_admin_id . ' (id';
            }
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
        if (request()->ajax()) {
            if ($accreditId == 'All') {
                $participants = DB::select("select t.* , c.* from temp" . $company_admin_id . " t inner join company_staff c on t.id = c.id");
            } else {
                $participants = DB::select("select t.* , c.* from temp" . $company_admin_id . " t inner join company_staff c on t.id = c.id where t.Accreditation_category ='" . $accreditId . "'");
            }
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
                            $status_value = "Rejected with correction by security officer";
                            break;
                        case 8:
                            $status_value = "Rejected with correction by event admin";
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
                ->addColumn('action', function ($data) use ($checked) {
                    $button = '';
                    if ($checked == 1) {
                        $button .= '<input type="checkbox" class="select" data-id="' . $data->id . '" checked />';
                    } else {
                        $button .= '<input type="checkbox" class="select" data-id="' . $data->id . '" />';
                    }
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('pages.FullFillment.all-participants')->with('dataTableColumns', $dataTableColumuns)->with('company_id', $companyId)->with('event_id', $eventId)->with('accredit', $accreditId)->with('checked', $checked);
    }

}
