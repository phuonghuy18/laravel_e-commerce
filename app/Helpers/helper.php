<?php

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\Country;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;

function getCategories(){
    return Category::orderBy('name','ASC')
    ->with('sub_category')
    ->where('status',1)
    ->where('showHome','Yes')
    ->orderBy('id','DESC')
    ->get();
}

function getProductImage($productId){
    return ProductImage::where('product_id',$productId)->first();
}

function orderEmail($orderId, $userType="customer"){
    $order = Order::where('id',$orderId)->with('items')->first();

    if ($userType == 'customer'){
        $subject = 'Cảm ơn bạn đã mua hàng';
        $email = $order->email;
    } else {
        $subject = 'Bạn nhận một đơn hàng';
        $email = env('ADMIN_EMAIL ');
    }

    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
    //dd($order);
}

function getCountryInfo($id){
    return Country::where('id',$id)->first();
}
?>