<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request){

        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        $sPath = $image->getPathName();



        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->img = 'NULL';
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        // product_id => 4; product_image => 1
        // 4-1-time.png
        $productImage->img = $imageName;
        $productImage->save();

        $dPath = public_path().'/uploads/product/'.$imageName;
        File::copy($sPath,$dPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/product/'.$productImage->img),
            'message' => 'Image saved successfully'
        ]);
    }

    public function destroy(Request $request){

        $productImage = ProductImage::find($request->id);
        
        if (empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ]);
        }

        
        //delete images from foler
        File::delete(public_path('uploads/product/'.$productImage->img));
        
        $productImage->delete();
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
}
