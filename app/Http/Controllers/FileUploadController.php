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

                $fileName = now();
                $fileName = str_replace(':','_',$fileName);
                $fileName = str_replace(' ', '_', $fileName) . '.' . $extension;

                $stored_file = $file->storeAs(
                    'public/badges', $fileName);

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
}
