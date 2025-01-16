<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Country;
use App\Models\Province;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use App\Models\ShippingCharge;
use Illuminate\Support\Carbon;
use App\Models\DiscountCoupon;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::with('product_images')->find($request->id);

        if ($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }

        if (Cart::count() > 0){
           // echo "Product already in cart";
           // sản phẩm trong giỏ hàng
           // check nếu sản phẩm already trong giỏ hàng
           // return a message là sản phẩm trong giỏ hàng
           // nếu sp ko trong giỏ hàng => thêm vào giỏ hàng

           $cartContent = Cart::content();
           $productAlreadyExist = false;

           foreach ($cartContent as $item){
            if ($item->id == $product->id){
                $productAlreadyExist = true;
            }
           }

           if ($productAlreadyExist == false){
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
           
            $status = true;
            $message = '<strong>'.$product->title.'</strong> được thêm vào giỏ hàng của bạn.';
            session()->flash('success', $message);
        
        } else {
            $status = false;
            $message = '<strong>'.$product->title.'</strong> đã có trong giỏ hàng của bạn.'; 
           }

        } else {
            
            // Giỏ hàng trống
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> được thêm vào giỏ hàng của bạn.';
            session()->flash('success', $message);
        }
        
        return response()->json([
            'status' => $status,
            'message' =>  $message
        ]);

        //Cart::add('293ad', 'Product 1', 1, 9.99);
    }

    public function cart(){
        $cartContent = Cart::content();
       // dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart',$data);
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        
        $product = Product::find($itemInfo->id);
        //check qty avaiable in stock
        if ($product->track_qty == 'Yes'){
            if ($qty <= $product->qty){
                Cart::update($rowId, $qty);
                $message = 'Giỏ hàng được cập nhật';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Số lượng ('.$qty.') sản phẩm vượt quá hiện có trong kho';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Giỏ hàng được cập nhật';
            $status = true;
            session()->flash('success', $message);
        }

 
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request){

        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null){
            session()->flash('error', 'Sản phẩm không có trong giỏ hàng');

            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng'
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Sản phẩm đã được xóa khỏi giỏ hàng';  
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout(){

        $discount = 0;
        
        // empty cart
        if (Cart::count() == 0){
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false){
            if (session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');
        }

        
        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first();


        session()->forget('url.intended');

        // $countries = Country::orderBy('name','ASC')->get();

        $provinces = Province::orderBy('name','ASC')->get();

        $subTotal = Cart::subtotal(2,'.','');
        // apply discount
        if (session()->has('code')){
            $code = session()->get('code');

            if ($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // + shipping
        if ($customerAddress != ''){
            $userProvince = $customerAddress->province_id;
            $shippingInfo = ShippingCharge::where('province_id', $userProvince)->first();
    
            //echo $shippingInfo->amount;
            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item){
                $totalQty += $item->qty;
            }
    
            $grandTotal = ($subTotal-$discount)+$totalShippingCharge;
            
        } else {
            $grandTotal = ($subTotal-$discount);
            $totalShippingCharge = 0;
        }
        

        return view('front.checkout',[
            // 'countries' => $countries,
            'provinces' => $provinces,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal
        ]);
    }

    public function processCheckout(Request $request){
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            // 'country' => 'required',
            'address' => 'required',
            
            'province' => 'required',
            
            'mobile' => 'required'

        ]);
        if ($validator->fails()){
            return response()->json([
                'message' => 'Please fix error',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //save user address

        $user = Auth::user();


        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                // 'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                // 'city' => $request->city,
                'province_id' => $request->province
                
            ]
        );

        // store data in orders table

        if ($request->payment_method == 'cod'){
            
           // $discountCodeId = '';
            $promoCode = '';
            $discountCodeId=NULL;
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
            
            // apply discount
        if (session()->has('code')){
            $code = session()->get('code');

            if ($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountCodeId = $code->id;
            $promoCode = $code->code;
        }

            // calculate shipping
            $shippingInfo = ShippingCharge::where('province_id',$request->province)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){
                $shipping = $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

        } else {
            $shippingInfo = ShippingCharge::where('province_id','other')->first();
            $shipping = $shippingInfo->amount;
            $grandTotal = ($subTotal-$discount)+$shipping;

        }

        
            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;

            $order->payment_status = 'not paid';
            $order->status = 'pending';
            
            $order->user_id = $user->id;

            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            
            // $order->city = $request->city;
            //$order->zip = $request->zip;
            $order->notes = $request->order_notes;
            // $order->country_id = $request->country;
            $order->province_id = $request->province;

            $order->save();

            // store order item in order items table
            foreach(Cart::content() as $item){
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                // update product stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes'){
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty-$item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
            }



            // send order email
            orderEmail($order->id, 'customer');

            session()->flash('success','bạn đã đặt hàng thành công');
            Cart::destroy();

            session()->forget('code');
            
            if ($request->payment_method == 'atm'){
                return response()->json([
                    'message' => 'Order saved successfully',
                    'orderId' =>$order->id,
                    'status' => true,
                    'atm' => true
                ]);;
            }
            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' =>$order->id,
                'status' => true 
            ]);
            


        } else {
            $promoCode = '';
            $discountCodeId=NULL;
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
            
            // apply discount
        if (session()->has('code')){
            $code = session()->get('code');

            if ($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountCodeId = $code->id;
            $promoCode = $code->code;
        }

            // calculate shipping
            $shippingInfo = ShippingCharge::where('province_id',$request->province)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){
                $shipping = $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

        } else {
            $shippingInfo = ShippingCharge::where('province_id','other')->first();
            $shipping = $shippingInfo->amount;
            $grandTotal = ($subTotal-$discount)+$shipping;

        }

        
            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;

            $order->payment_status = 'paid';
            $order->status = 'pending';
            
            $order->user_id = $user->id;

            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            
            // $order->city = $request->city;
            //$order->zip = $request->zip;
            $order->notes = $request->order_notes;
            // $order->country_id = $request->country;
            $order->province_id = $request->province;

            $order->save();

            // store order item in order items table
            foreach(Cart::content() as $item){
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                // update product stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes'){
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty-$item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
                
            }



            // send order email
            orderEmail($order->id, 'customer');

            session()->flash('success','Bạn đã đặt hàng thành công');
            Cart::destroy();

            session()->forget('code');
            
            if ($request->payment_method == 'atm'){
                return response()->json([
                    'message' => 'Order saved successfully',
                    'orderId' =>$order->id,
                    'status' => true,
                    'atm' => true
                ]);;
            }
            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' =>$order->id,
                'status' => true 
            ]);
        }

    }


    public function paymentATM($id){
        $order = Order::find($id);
        session()->flash('success','Bạn đã đặt hàng thành công');
        function execPostRequest($url, $data)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            return $result;
        }


        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $order->grand_total;
        $orderId = $orderId = $order->id . '_' . time();
        $redirectUrl = "http://127.0.0.1:8000/thanks/".$orderId;
        $ipnUrl = "http://127.0.0.1:8000/ticketPaid/".$orderId;
        $extraData = "";
        

        $requestId = time() . "";
        $requestType = "payWithATM";
        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json
        //   dd($jsonResult);
        //Just a example, please check more in there
        
        return redirect($jsonResult['payUrl']);
    }




    public function thankyou($id){
        return view('front.thanks',[
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request){

        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString = '';

        // apply discount
        if (session()->has('code')){
            $code = session()->get('code');

            if ($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = 
                '<div class="mt-4" id="discount-response">
                '.session()->get('code')->code.'
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                </div> ';
        }

        

        if ($request->province_id > 0){
            
            $shippingInfo = ShippingCharge::where('province_id',$request->province_id)->first();
            
            $totalQty = 0;
            foreach (Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){

                $shippingCharge = $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal),
                    'discount' => number_format($discount),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge)
                ]); 

        } else {
            $shippingInfo = ShippingCharge::where('province_id','other')->first();

            $shippingCharge =  $shippingInfo->amount;
            $grandTotal = ($subTotal-$discount)+$shippingCharge;

            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal),
                'discount' => number_format($discount),
                'discountString' => $discountString,
                'shippingCharge' => number_format($shippingCharge)
            ]);
        }
    } else {

        return response()->json([
            'status' => true,
            'grandTotal' => number_format($subTotal-$discount),
            'discount' => number_format($discount),
            'discountString' => $discountString,
            'shippingCharge' => number_format(0)
        ]);
        }
    }

    public function applyDiscount(Request $request){
        //dd($request->code);
        $code = DiscountCoupon::where('code',$request->code)->first();

        if ($code == null){
            return response()->json([
                'status' => false,
                'message' => 'Coupon Code không hợp lệ'
            ]);
        }
        // check coupon date
        $now = Carbon::now();
       // echo $now->format('Y-m-d H:i:s');

        if ($code->starts_at != ""){
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            if ($now->lt($startDate)){
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon Code không hợp lệ',
                ]);
            }
        }

        if ($code->expires_at != ""){
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)){
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon Code không hợp lệ',
                ]);
            }
        }

        // max uses check
        if ($code->max_uses > 0){
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if ($couponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon Code không hợp lệ',
                ]);
            }
        }
        
        //max uses user check
        if ($code->max_uses_user > 0){
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();

            if ($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn đã dùng Coupon này lần trước',
                ]);
            }
        }
        
        $subTotal = Cart::subtotal(2,'.','');

        //min amount check
        if ($code->min_amount > 0){
            if ($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'Số tiền tối thiểu của đơn hàng là $'.$code->min_amount.'.',
                ]);
            }
        }

            session()->put('code',$code);
            return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request){
        session()->forget('code');
        return $this->getOrderSummery($request);
    }

}
