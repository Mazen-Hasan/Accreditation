<?php

namespace App\Http\Controllers;

use App\Models\CompanyStaff;
use App\Models\Event;
use App\Models\TemplateBadge;
use App\Models\TemplateFieldElement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GenerateBadgeController extends Controller
{

    public function getBadgePath($staff_id)
    {

        $where = array('id' => $staff_id);
        $badge = CompanyStaff::where($where)->first();

        $badgePath = $badge->badge_path;

        return Response::json($badgePath);
    }

    public function printBadge($staff_id)
    {
        DB::update('update company_staff set print_status = ?, status =? where id = ?', ['2', '10', $staff_id]);

        return Response::json(true);
    }

    public function generate($staff_id)
    {

        $where = array('id' => $staff_id);
        $eventID = CompanyStaff::where($where)->first()->event_id;

        $where = array('id' => $eventID);
        $template_id = Event::where($where)->first()->event_form;


        $staffInfo = DB::select('select * from staff_badges_view where staff_id = ? and template_id = ?', [$staff_id, $template_id]);
//        $template_id = $staffInfo[0]->template_id;

        $where = array('template_id' => $template_id);
        $badge = TemplateBadge::where($where)->first();

        $where = array('event_form' => $staffInfo[0]->template_id);
        $event = Event::where($where)->first();


        $bg_image_path = public_path('storage/badges/' . $badge->bg_image);

        $badgeImg = $this->imgGenerate($badge->width, $badge->high, $badge->bg_color, $bg_image_path);

        $fontPath = public_path('fonts/poppins/Poppins-Regular');

        foreach ($staffInfo as $staff) {
            if (str_contains($staff->value, '.png')) {
                $image_path = public_path('storage/badges/' . $staff->value);
                $this->addImageTooImg($badgeImg, $staff->position_x, $staff->position_y, $staff->size, $staff->size, $image_path);
            } else {
                if ($staff->slug == 'select') {
                    $where = array('template_field_id' => $staff->template_field_id, 'value_id' => $staff->value);
                    $value = TemplateFieldElement::where($where)->first();
                    $this->addTextTooImg($badgeImg, $staff->position_x, $staff->position_y, $staff->size, $staff->text_color, $value->value_en, $fontPath);
                } else {
                    $this->addTextTooImg($badgeImg, $staff->position_x, $staff->position_y, $staff->size, $staff->text_color, $staff->value, $fontPath);
                }
            }
        }

        $path = public_path('badges');
        $path .= '/' . $event->id . '_' . $template_id . '_' . $staff_id . '.png';

        $res = imagepng($badgeImg, $path);

        $path = $event->id . '_' . $template_id . '_' . $staff_id . '.png';
        if ($res) {
            DB::update('update company_staff set badge_path = ?, print_status = ?, status =? where id = ?', [$path, '1', '9', $staff_id]);
        }

        imagedestroy($badgeImg);
        return Response::json($path);
    }

    private function imgGenerate($width, $high, $bg_color, $bg_image_path)
    {
        // Create the image
        $img = imagecreatetruecolor($width, $high);

        list($r, $g, $b) = sscanf($bg_color, "#%02x%02x%02x");

        $color = imagecolorallocate($img, $r, $g, $b);

        imagefilledrectangle($img, 0, 0, $width, $high, $color);

        if ($bg_image_path) {
            $bg_img = $this->loadImage($bg_image_path);

            if ($bg_img) {
                $bg_img = imagescale($bg_img, $width, $high);

                imagecopymerge($img, $bg_img, 0, 0, 0, 0, $width, $high, 100);
            } else {
                var_dump('false');
                exit;
            }
        }

        return $img;
    }

    private function loadImage($img_path)
    {
        $im = @imagecreatefrompng($img_path);
        return $im;
    }

    private function addImageTooImg($img, $position_x, $position_y, $width, $high, $img_path)
    {

        $bg_img = $this->loadImage($img_path);

        if ($bg_img) {
            $bg_img = imagescale($bg_img, $width, $high);

            imagecopymerge($img, $bg_img, $position_x, $position_y, 0, 0, $width, $high, 100);
        }
    }

    private function addTextTooImg($img, $position_x, $position_y, $text_size, $text_color, $text, $fontPath)
    {
        // Create some colors
        list($r, $g, $b) = sscanf($text_color, "#%02x%02x%02x");

        $text_color = imagecolorallocate($img, $r, $g, $b);

        //imagefttext($img, $text_size, 0, $position_x, $position_y, $text_color, $fontPath, $text);
    }

    public function generatePreview($badge_id)
    {

        $where = array('id' => $badge_id);
        $badge = TemplateBadge::where($where)->first();

        $bg_image_path = public_path('storage/badges/' . $badge->bg_image);

        $badgeImg = $this->imgGenerate($badge->width, $badge->high, $badge->bg_color, $bg_image_path);

        $fontPath = public_path('fonts/poppins/Poppins-Regular');

        $template_badge_fields = DB::select('select * from badge_design_view where badge_id = ?', [$badge_id]);

        $image_place_holder_path = public_path('preview');
        $image_place_holder_path .= '/img.png';

        foreach ($template_badge_fields as $template_badge_field) {
            if ($template_badge_field->slug == 'file') {
                $i = $template_badge_field->slug;
                $this->addImageTooImg($badgeImg, $template_badge_field->position_x, $template_badge_field->position_y,
                    $template_badge_field->size, $template_badge_field->size, $image_place_holder_path);

            } else {
                $i = 'text';
                $this->addTextTooImg($badgeImg, $template_badge_field->position_x, $template_badge_field->position_y,
                    $template_badge_field->size, $template_badge_field->text_color, $template_badge_field->label_en, $fontPath);
            }
        }

        $path = public_path('preview');
        $path .= '/' . $badge_id . '.png';

        $res = imagepng($badgeImg, $path);

        imagedestroy($badgeImg);

        $path = '/' . $badge_id . '.png';
        return Response::json($path);
    }
}
