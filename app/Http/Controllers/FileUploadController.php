<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        request()->validate([
            'file'  => 'required|mimes:png|max:2048',
        ]);

        if ($files = $request->file('file')) {

            //store file into document folder
//            $path = public_path('/docs');
//            $file = $request->file->store('docs');
//            $file = $request->file->put($path);

            $extension = $request->file->extension();

            $fileName = now();
            $fileName = str_replace(' ', '_', $fileName) . '.' . $extension;

//            $app_path = storage_path('app');

            $file = $request->file->storeAs(
                'public/badges', $fileName);

            return Response()->json([
                "success" => true,
                "fileName" =>$fileName,
                "filePath" => $file
            ]);
        }

        return Response()->json([
            "success" => false,
            "fileName" =>'',
            "file" => ''
        ]);
    }
}
