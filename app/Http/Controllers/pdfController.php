<?php

namespace App\Http\Controllers;

require(app_path('includes/fpdf.php'));

use App\Models\CompanyStaff;
use FPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class pdfController extends Controller
{
    public function generate(Request $request)
    {

        $staffIDs = $request->get('staff');

        $one_staff_id = array_values($staffIDs)[0];

        //get badge width and high
        $badge_size = DB::select('SELECT tb.width, tb.high FROM company_staff cs join events e on cs.event_id = e.id
                                        join template_badges tb on e.event_form = tb.template_id where cs.id = ?',[$one_staff_id]);

        if(!empty($staffIDs)){

            //create pdf
            //calculate badge width &high
            $pixel_to_mm = 0.2645833333;

            $badge_width = $badge_size[0]->width;
            $badge_high = $badge_size[0]->high;

            $page_width =  $badge_width * $pixel_to_mm;
            $badge_high =  $badge_high * $pixel_to_mm;

            $pdf = new FPDF('P','mm',array($page_width, $badge_high));
            $pdf->AliasNbPages();

            //loop on Staffs
            $staffs = DB::table('company_staff')->whereIn('id', $staffIDs)->get();

            foreach ($staffs as $staff){
                $path = public_path('badges/') . $staff->badge_path;
                $pdf->AddPage();
                $pdf->Image($path,0,0);
            }

            //save pdf
            $pdf->Output('download/badges.pdf','F');

            $updateProduct = CompanyStaff::whereIn('id',$staffIDs)
                ->update(['print_status' => '2','status' => '10']);

            return Response()->json([
                "errMsg" => 'Success',
                "errCode" => '1',
                "file" => asset('download/badges.pdf')
            ]);
        }
        else{
            return Response()->json([
                "errMsg" => 'No staff selected',
                "errCode" => '-100'
            ]);
        }
    }
}
