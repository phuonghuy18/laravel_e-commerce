<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ImportProduct;
use App\Models\ProductReceipt;

class HomeController extends Controller
{
    public function index(Request $request)
{
    $totalOrders = Order::where('status', '!=', 'cancelled')->count();
    $totalProducts = Product::count();
    $totalCustomers = User::where('role', 1)->count();

    // Tổng doanh thu (COD + ATM)
    $revenueCOD = Order::where('status', 'delivered')
                        ->where('payment_status', '!=' ,'paid')    
                        ->sum('grand_total');
    $revenueATM = Order::where('payment_status', 'paid')->sum('grand_total');
    $totalRevenue = $revenueCOD + $revenueATM;

    // Tổng nhập hàng
    $totalImport = ProductReceipt::where('status', 'accepted')->sum('grand_total');

    // Tính doanh thu và số đơn hàng theo tháng
    $month = $request->input('month', Carbon::now()->month);
    $year = $request->input('year', Carbon::now()->year);

    // Lấy ngày đầu tháng và cuối tháng
    $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

    // Lấy tất cả các đơn hàng trong tháng
    $ordersOfMonth = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth]);

    // Đếm số đơn hàng trong tháng
    $totalOrdersOfMonth = $ordersOfMonth->count();

    // Đếm số đơn hàng bị hủy trong tháng
    $cancelledOrdersOfMonth = $ordersOfMonth->clone()
        ->where('status', 'cancelled')
        ->count();

    // Doanh thu từ COD trong tháng
    $revenueCODofMonth = $ordersOfMonth->clone()
        ->where('status', 'delivered')
        ->where('payment_status', '!=', 'paid')
        ->sum('grand_total');

    // Doanh thu từ ATM trong tháng
    $revenueATMofMonth = $ordersOfMonth->clone()
        ->where('payment_status', 'paid')
        ->sum('grand_total');

    // Tổng doanh thu tháng
    $totalRevenueOfMonth = $revenueCODofMonth + $revenueATMofMonth;

    // Tổng nhập hàng trong tháng
    $totalImportOfMonth = ProductReceipt::where('status', 'accepted')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum('grand_total');

    // Xóa ảnh tạm thời
    $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');
    $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();
    foreach ($tempImages as $tempImage) {
        $path = public_path('/temp/' . $tempImage->name);
        if (File::exists($path)) {
            File::delete($path);
        }
        $tempImage->delete();
    }

    // Trả về view
    return view('admin.dashboard', [
        'totalOrders' => $totalOrders,
        'totalProducts' => $totalProducts,
        'totalCustomers' => $totalCustomers,
        'revenueCOD' => $revenueCOD,
        'revenueATM' => $revenueATM,
        'totalOrdersOfMonth' => $totalOrdersOfMonth,
        'cancelledOrdersOfMonth' => $cancelledOrdersOfMonth,
        'totalRevenue' => $totalRevenue,
        'totalImport' => $totalImport,
        'totalRevenueOfMonth' => $totalRevenueOfMonth,
        'totalImportOfMonth' => $totalImportOfMonth,
        'month' => $month,
        'year' => $year,
    ]);
}


    public function getMonthlyRevenue(Request $request)
{
    $year = $request->input('year', 2024); // Năm mặc định là 2024 nếu không có trong request
    $monthlyRevenue = [];

    for ($month = 1; $month <= 12; $month++) {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        // Lấy tất cả đơn hàng trong khoảng thời gian của tháng
        $ordersOfMonth = Order::whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth);

            $totalRevenue = $ordersOfMonth->clone()
                ->where('status', 'delivered')
                ->sum('grand_total');

        // Tính doanh thu từ các đơn hàng đã giao nhưng chưa thanh toán
        $revenueCOD = $ordersOfMonth->clone()
            ->where('status', 'delivered')
            ->where(function($query) {
                $query->where('payment_status', '!=', 'paid')
                      ->orWhereNull('payment_status');
            })
            ->sum('grand_total');

        // Tính doanh thu từ các đơn hàng đã thanh toán (không quan tâm trạng thái giao hàng)
        $revenueATM = $ordersOfMonth->clone()
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        // Tổng doanh thu không trùng lặp
        $totalRevenue = $revenueCOD + $revenueATM;

        // Thêm tổng doanh thu của tháng vào mảng
        $monthlyRevenue[] = $totalRevenue;
    }

    return view('admin.getMonthlyRevenue', compact('monthlyRevenue', 'year'));
}

public function getMonthlyReceipt(Request $request)
{
    $year = $request->input('year', 2024); // Năm mặc định là 2024 nếu không có trong request
    $monthlyReceipt = [];

    for ($month = 1; $month <= 12; $month++) {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        // Lấy tất cả đơn hàng trong khoảng thời gian của tháng
        $totalReceipt = ProductReceipt::where('status', 'accepted')
        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        ->sum('grand_total');

        // Thêm tổng của tháng vào mảng
        $monthlyReceipt[] = $totalReceipt;
    }

    return view('admin.getMonthlyReceipt', compact('monthlyReceipt', 'year'));
}

    public function logout(){
        $admin = Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function getMonthlyRevenueDashboard(Request $request)
{
    // Lấy tháng và năm từ request hoặc mặc định là tháng hiện tại
    $month = $request->input('month', Carbon::now()->month);
    $year = $request->input('year', Carbon::now()->year);

    // Lấy ngày đầu tháng và cuối tháng
    $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
    $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');

    // Lấy tất cả các đơn hàng trong tháng đã chọn
    $ordersOfMonth = Order::whereDate('created_at', '>=', $startOfMonth)
        ->whereDate('created_at', '<=', $endOfMonth);

        $totalRevenueOfMonth = $ordersOfMonth->clone()
        ->where('status', 'delivered')
        ->sum('grand_total');
    // Doanh thu COD
    $revenueCODofMonth = $ordersOfMonth->clone()
        ->where('status', 'delivered')
        ->where('payment_status', '!=', 'paid')
        ->sum('grand_total');

    // Doanh thu đã thanh toán (ATM)
    $revenueATMofMonth = $ordersOfMonth->clone()
        ->where('payment_status', 'paid')
        ->sum('grand_total');

    // Tổng doanh thu
    $totalRevenueOfMonth = $revenueCODofMonth + $revenueATMofMonth;

    // Tổng giá trị nhập hàng trong tháng
    $totalImportOfMonth = ProductReceipt::where('status', 'accepted')
        ->whereDate('created_at', '>=', $startOfMonth)
        ->whereDate('created_at', '<=', $endOfMonth)
        ->sum('grand_total');

    return view('admin.dashboardplus', compact(
        'month',
        'year',
        'revenueCODofMonth',
        'revenueATMofMonth',
        'totalRevenueOfMonth',
        'totalImportOfMonth'
    ));
}
}
