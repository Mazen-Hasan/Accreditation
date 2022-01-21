<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        if ($files = $request->allFiles()) {
            foreach ($files as $file){

                $extension = $file->extension();

//            $fileName = 'badge-'.$request->template_id;
                $fileName = now();
                $fileName = str_replace(':','_',$fileName);
                $fileName = str_replace(' ', '_', $fileName) . '.' . $extension;

                 //$path = public_path() . '/images';
            $path = 'badges/';

                $stored_file = $file->move(
                    $path, $fileName);

            chmod($stored_file, 0777);
                return Response()->json([
                    "success" => true,
                    "fileName" =>$fileName,
                    "filePath" => $stored_file
                ]);
            }
        }

        return Response()->json([
            "success" => false,
            "fileName" =>'',
            "file" => ''
        ]);
    }

    public function eventLogoUpload(Request $request)
    {
        if ($files = $request->allFiles()) {
            foreach ($files as $file){

                $extension = $file->extension();

                $fileName = now();
                $fileName = str_replace(':','_',$fileName);
                $fileName = str_replace(' ', '_', $fileName) . '.' . $extension;

                $path = 'logo/';

                $stored_file = $file->move(
                    $path, $fileName);

                chmod($stored_file, 0777);
                return Response()->json([
                    "code" => 1,
                    "message" => "Success",
                    "data" => [
                        "fileName" =>$fileName,
                        "filePath" => $stored_file
                    ],
                ]);
            }
        }

        return Response()->json([
            "code" => -1,
            "message" => "Error",
            "data" => "",
        ]);
    }
}
