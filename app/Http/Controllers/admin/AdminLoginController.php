<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{


    public function index(){
        return view('admin.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request ->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password'=>$request->password],$request->get('remember'))){

                $admin = Auth::guard('admin')->user();

                if($admin->role == 2){
                    return redirect()->route('admin.dashboard');
                } elseif ($admin->role == 3){
                    return redirect()->route('orders.shipperIndex');
                } elseif ($admin->role == 4){
                    return redirect()->route('products.productRatings');
                }
                 else
                {
                    $admin = Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'Bạn không có quyền đăng nhập admin');
                }

                return redirect()->route('admin.dashboard');
            } else{
                return redirect()->route('admin.login')->with('error', 'Email hoặc mật khẩu không đúng');
            }

        } else{
            return \redirect()->route('admin.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

}
