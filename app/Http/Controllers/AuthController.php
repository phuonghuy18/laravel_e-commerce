<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Province;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Mail\ResetPasswordEmail;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');
    }

    public function register(){
        return view('front.account.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if ($validator->passes()){

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password =Hash::make($request->password);
            $user->save();

            session()->flash('success', 'Đăng ký tài khoản thành công.');

            return response()->json([
                'status' => true,
                
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->passes()){
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                if (session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('account.profile');

            } else {
                //session()->flash('error','Tài khoản/mật khẩu không đúng');
                return redirect()->route('account.login')
                                ->withInput($request->only('email'))
                                ->with('error','Tài khoản/mật khẩu không đúng');
                                
            }

        } else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }

    }

    public function profile(){
        $userId = Auth::user()->id;

        $countries = Country::orderBy('name','ASC')->get();

        $provinces = Province::orderBy('name','ASC')->get();

        $user = User::where('id',$userId)->first();

        $address = CustomerAddress::where('user_id',$userId)->first();

        return view('front.account.profile',[
            'user' => $user,
            'provinces' => $provinces,
            'address' => $address,
            'countries' => $countries
        ]);
    }

    public function updateProfile(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required'
        ]);

        if ($validator->passes()){
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            session()->flash('success','Thông tin cá nhân đã được cập nhật');
            return response()->json([
                'status' => true,
                'message' => 'Thông tin cá nhân đã được cập nhật'
            ]);

        } else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            // 'email' => 'required',
            // 'country_id' => 'required',
            'address' => 'required',
            
            'province_id' => 'required',
            'mobile' => 'required'

        ]);

        if ($validator->passes()){
            /* $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save(); */

            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    // 'email' => $request->email,
                    'mobile' => $request->mobile,
                    // 'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    
                    'province_id' => $request->province_id
                    
                ]
            );

            session()->flash('success','Địa chỉ đã được cập nhật');
            return response()->json([
                'status' => true,
                'message' => 'Địa chỉ đã được cập nhật'
            ]);

        } else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout(){
        \Cart::destroy();
        Auth::logout();
        return redirect()->route('account.login')
                    ->with('success','Đăng xuất thành công');;
    }

    public function orders(){

        $user = Auth::user();

        $orders = Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();

        $data['orders'] = $orders;
        return view('front.account.order',$data);
    }

    public function orderDetail($id){
             $data = [];
            $user = Auth::user();

            $order = Order::where('user_id',$user->id)->where('id',$id)->first();
            $data['order'] = $order;     
    
            $orderItems = OrderItem::where('order_id',$id)->get();
            $data['orderItems'] = $orderItems;
    
            $orderItemsCount = OrderItem::where('order_id',$id)->count();
            $data['orderItemsCount'] = $orderItemsCount;
    
            return view('front.account.order-detail',$data);
    }

    public function wishlist(){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data = [];
        $data['wishlists'] = $wishlists;
        return view('front.account.wishlist',$data);
    }

    public function removeProductFromWishlist(Request $request){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->where('product_id',$request->id)->first();
        if ($wishlists == null){
            session()->flash('error', 'Sản phẩm không có trong danh sách yêu thích');
            return response()->json([
                'status' => true,
                
            ]);
        } else {
            Wishlist::where('user_id', Auth::user()->id)->where('product_id',$request->id)->delete();

            session()->flash('success', 'Sản phẩm đã được xóa khỏi danh sách yêu thích');
            return response()->json([
                'status' => true,
                
            ]);
        }
    }

    public function showChangePasswordForm(){
        return view('front.account.change-password');
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'new_password_confirmation' => 'required|same:new_password'

        ]);

        if($validator->passes()){

            $user = User::select('id','password')->where('id',Auth::user()->id)->first();
            
            if (!Hash::check($request->old_password,$user->password)){
                session()->flash('error','Mật khẩu cũ không đúng, vui lòng thử lại');
                return response()->json([
                    'status' => true,
                    
                ]);
            }

            User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            session()->flash('success','Đổi mật khẩu thành công');
                return response()->json([
                    'status' => true,
                    
                ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function forgotPassword(){
        return view('front.account.forgot-password');
    }
    
    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()){
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }
        
        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        //send email
        $user = User::where('email', $request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'Yêu cầu lấy lại mật khẩu'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
        
        return redirect()->route('front.forgotPassword')->with('success','Quên mật khẩu thành công, vui lòng kiểm tra email của bạn');
    }

    public function resetPassword($token){
        $tokenExist = \DB::table('password_reset_tokens')->where('token',$token)->first();

        if ($tokenExist == null){
            return redirect()->route('front.forgotPassword')->with('error','Yêu cầu thất bại');
        }

        return view('front.account.reset-password',[
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request){
        $token = $request->token;

        $tokenObject = \DB::table('password_reset_tokens')->where('token',$token)->first();

        if ($tokenObject == null){
            return redirect()->route('front.forgotPassword')->with('error','Yêu cầu thất bại');
        }

        $user = User::where('email',$tokenObject->email)->first();

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()){
            return redirect()->route('front.resetPassword',$token)->withErrors($validator);
        }

        User::where('id',$user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        \DB::table('password_reset_tokens')->where('email',$user->email)->delete();


        return redirect()->route('account.login')->with('success','Cập nhật mật khẩu thành công');

    }
}
