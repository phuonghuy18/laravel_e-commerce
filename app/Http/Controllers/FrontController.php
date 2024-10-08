<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index(){

        $products = Product::where('is_featured','Yes')
        ->where('status',1)
        ->orderBy('id','DESC')
        ->get();
        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderBy('id','DESC')
        ->take(8)
        ->where('status',1)
        ->get();
        $data['latestProducts'] = $latestProducts;
        return view('front.home',$data);
    }

    public function addToWishList(Request $request){
        
        if (Auth::check() == false){

            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::where('id',$request->id)->first();

        if ($product == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Sản phẩm hiện không có</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id
            ]
        );

        /* $wishlist = new Wishlist;
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $request->id;
        $wishlist->save(); */

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> đã được thêm  vào danh sách yêu thích</div>'
        ]);
    }
}
