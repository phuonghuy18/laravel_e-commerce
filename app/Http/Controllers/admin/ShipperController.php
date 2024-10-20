<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class ShipperController extends Controller
{
    public function shipperIndex(Request $request){
        // Lấy danh sách đơn hàng có trạng thái 'shipping' hoặc 'delivered'
        $orders = Order::latest('orders.created_at')
                    ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                    ->select('orders.*', 'users.name', 'users.email')
                    ->whereIn('orders.status', ['shipping', 'delivered']); // Điều kiện lấy trạng thái
    
        // Kiểm tra từ khóa tìm kiếm
        if ($request->get('keyword') != ""){
            $keyword = $request->keyword;
            $orders = $orders->where(function($query) use ($keyword) {
                $query->where('users.name', 'like', '%'.$keyword.'%')
                      ->orWhere('users.email', 'like', '%'.$keyword.'%')
                      ->orWhere('orders.id', 'like', '%'.$keyword.'%');
            });
        }
    
        // Phân trang kết quả
        $orders = $orders->paginate(10);
    
        // Trả về view với danh sách đơn hàng
        return view('admin.orders.shiplist', [
            'orders' => $orders
        ]);
    }

    public function shipperDetail($orderId){

        $order = Order::select('orders.*','countries.name as countryName')
                ->where('orders.id',$orderId)
                ->leftJoin('countries','countries.id','orders.country_id')
                ->first();

        $orderItems = OrderItem::where('order_id',$orderId)->get();

        return view('admin.orders.shipdetail',[
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function shipperChangeOrderStatus(Request $request, $orderId){
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        session()->flash('success','Thay đổi thành công trạng thái đơn hàng ');

        return response()->json([
            'status' => true,
            'message' => 'Order status changed successfully'
        ]);
    }
    
}
