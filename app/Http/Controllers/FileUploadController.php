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
            $path = public_path('/docs');
            $file = $request->file->store($path);

            return Response()->json([
                "success" => true,
                "file" => $file
            ]);
        }

        return Response()->json([
            "success" => false,
            "file" => ''
        ]);
    }
}
