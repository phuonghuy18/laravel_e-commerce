<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Illuminate\Support\Facades\Storage; // Import Storage facade for file operations
//use Image;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            // Save TempImage to database
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            // Move uploaded file to public/temp directory
            $image->move(public_path('temp'), $newName);

            // generate thumbnail
            /* $sourcePath = public_path().'/temp/'.$newName;
            $desPath = public_path().'/temp/thumb/'.$newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($desPath); */

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/' .$newName),
                'message' => 'Tải ảnh thành công'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Không có tệp tin ảnh được tải lên'
            ], 400);
        }
    }
}


