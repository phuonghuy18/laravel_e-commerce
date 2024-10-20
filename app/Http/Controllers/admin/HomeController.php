<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(){
        $totalOrders = Order::where('status','!=','cancelled')->count();
        $totalProducts = Product::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role',1)->count();


        $revenueCOD = Order::where('status','delivered')->sum('grand_total');
        $revenueATM = Order::Where('payment_status','paid')->sum('grand_total');
        $totalRevenue = $revenueCOD + $revenueATM;

        //total month
        $thisMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $revenueCODofMonth = Order::where('status','delivered')
                            ->whereDate('created_at','>=',$startOfMonth)
                            ->whereDate('created_at','<=',$currentDate)
                            ->sum('grand_total');

        $revenueATMofMonth = Order::Where('payment_status','paid')
                            ->whereDate('created_at','>=',$startOfMonth)
                            ->whereDate('created_at','<=',$currentDate)                 
                            ->sum('grand_total');

        $totalRevenueOfMonth = $revenueCODofMonth + $revenueATMofMonth;

        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'revenueCOD' => $revenueCOD,
            'revenueATM' => $revenueATM,
            'totalRevenue' => $totalRevenue,
            'totalRevenueOfMonth' => $totalRevenueOfMonth,
            'thisMonthName' => $thisMonthName
        ]);
        // $admin = Auth::guard('admin')->user();
        // echo 'Welcome ' .$admin->name.' <a href="'.route('admin.logout').'">Đăng xuất</a>';
    }

    public function logout(){
        $admin = Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
