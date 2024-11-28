<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function index(Request $request)
    {
        // Lấy dữ liệu thống kê số lượng bán ra theo từng product_id
        $statistics = DB::table('products')
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
        ->leftJoin('import_products', 'products.id', '=', 'import_products.product_id')
        ->select(
            'products.id as product_id',
            'products.title as name',
            'products.price as sale_price',  // Giá bán từ bảng products
            DB::raw('SUM(CASE WHEN orders.payment_status = "paid" OR orders.status = "delivered" THEN order_items.qty ELSE 0 END) as total_qty'),  // Chỉ tính qty khi thỏa điều kiện
            DB::raw('SUM(CASE WHEN orders.payment_status = "paid" OR orders.status = "delivered" THEN order_items.total ELSE 0 END) as total_revenue'),  // Chỉ tính total khi thỏa điều kiện
            DB::raw('SUM(import_products.import_qty) as import_qty'),  // Tổng số lượng nhập từ bảng import_products
            DB::raw('SUM(import_products.total_import_price) as total_import_price')  // Tổng giá nhập từ bảng import_products
        )
        ->groupBy('products.id', 'products.title', 'products.price')
        ->orderBy('products.id', 'DESC');
        if ($request->get('keyword') != "") {
            $statistics = $statistics->where('products.title', 'like', '%' . $request->keyword . '%');
        }
            $statistics= $statistics->paginate(10); 
            
        
        return view('admin.order_items.statistics', compact('statistics'));
    }

    public function productSoldChart(Request $request)
{
    // Hàm phụ trợ để lấy dữ liệu theo danh mục
    function getDataByCategory($categoryName) {
        return DB::table('products')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.title as name',
                'categories.name as category_name',
                DB::raw('SUM(CASE WHEN orders.payment_status = "paid" OR orders.status = "delivered" THEN order_items.qty ELSE 0 END) as total_qty')
            )
            ->where('categories.name', '=', $categoryName)
            ->groupBy('products.id', 'products.title', 'categories.name')
            ->get();
    }

    // Lấy dữ liệu theo từng danh mục
    $maleData = getDataByCategory('nước hoa nam');
    $femaleData = getDataByCategory('nước hoa nữ');
    $unisexData = getDataByCategory('unisex');

    //dd($femaleData);
    return view('admin.order_items.product-chart', compact('maleData', 'femaleData', 'unisexData'));
}




}



