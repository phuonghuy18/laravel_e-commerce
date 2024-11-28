<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

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

    public function page($slug){
        $page = Page::where('slug',$slug)->first();

        if ($page == null){
            abort(404);
        }

        return view('front.page',[
            'page' => $page
        ]);
    }

    public function sendContactEmail(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:10'
        ]);
        if ($validator->passes()){
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'Bạn nhận được một email liên hệ'
            ];
            
            $admin = User::where('id',1)->first();

            Mail::to($admin->email)->send(new ContactEmail($mailData));

            session()->flash('success', 'Cảm ơn bạn đã đóng góp, chúng tôi sẽ sớm liên hệ');

            return response()->json([
                'status' => true
            ]);

        } else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function cancelOrder($orderId){

    $order = Order::find($orderId);
    $orderItems = OrderItem::where('order_id', $orderId)->get();

    if (!$order) {
        session()->flash('error', 'Đơn hàng không tồn tại');
        return response()->json([
            'status' => false,
            'message' => 'Đơn hàng không tồn tại'
        ]);
       
    }

    // Update product stock based on order items (cần sửa)
    foreach ($orderItems as $item) {
        $product = Product::find($item->product_id);
        if ($product && $product->track_qty == 'Yes') {
            $currentQty = $product->qty;
            $updatedQty = $currentQty+$item->qty;
            $product->qty = $updatedQty;
            $product->save();
        }
    }

    // Set order details to null or zero
    $order->subtotal = 0;
    $order->shipping = 0;
    $order->grand_total = 0;
    $order->discount = null;
    $order->coupon_code_id = null;
    $order->coupon_code = null;
    $order->payment_status = 'not paid';
    $order->status = 'cancelled';
    $order->save();

    session()->flash('success','Bạn đã hủy đơn hàng thành công');
    return response()->json([
        'status' => true,
        'message' => 'Bạn đã hủy đơn hàng thành công'
    ]);
}

}
