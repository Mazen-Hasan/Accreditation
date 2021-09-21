<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TemplateBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GenerateBadgeController extends Controller
{

    public function generate($staff_id){

        $staffs = DB::select('select * from staff_badges_view where staff_id = ?', [$staff_id]);
        $template_id = $staffs[0]->template_id;

        $where = array('template_id' => $template_id);
        $badge = TemplateBadge::where($where)->first();

        $where = array('event_form' => $staffs[0]->template_id);
        $event = Event::where($where)->first();


        $badgeImg =  $this->imgGenerate($badge->width, $badge->high, $badge->bg_color);

        $fontPath = public_path('fonts/poppins/Poppins-Regular.tff');

        foreach ($staffs as $staff){
                $this->addTextTooImg($badgeImg, $staff->position_x, $staff->position_x, $staff->size, $staff->text_color, $staff->value, $fontPath);
            }

        $path = public_path('Badges/');
        $path .= $event->id . '_'. $template_id . '_' . $staff_id . '.png';

        $res = imagepng($badgeImg, $path );
        if($res){
            DB::update('update company_staff set badge_path = ?, print_status = ? where id = ?',[$path,'1', $staff_id]);
        }

        return Response::json($path);

    }

    private function imgGenerate($width, $high, $bg_color){
        // Create the image
        $img = imagecreatetruecolor($width, $high);

        list($r, $g, $b) = sscanf($bg_color, "#%02x%02x%02x");

        $color = imagecolorallocate($img, $r, $g, $b);

        imagefilledrectangle($img, 0, 0, $width, $high, $color);

        return $img;
    }

    private function addTextTooImg($img, $position_x, $position_y, $text_size, $text_color, $text, $fontPath ){
        // Create some colors
        list($r, $g, $b) = sscanf($text_color, "#%02x%02x%02x");

        $text_color = imagecolorallocate($img, $r, $g, $b);

//        imagefttext($img, $text_size, 0, $position_x, $position_y, $text_color, $fontPath, $text);
    }

}
