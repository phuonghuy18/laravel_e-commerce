<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\ProductReceiptItem;
use Illuminate\Support\Facades\Validator;

class ProductReceiptController extends Controller
{
    public function index(Request $request){
        $productReceipts = ProductReceipt::latest('product_receipt.created_at')
                        ->select('product_receipt.*','users.name','users.email');
        $productReceipts = $productReceipts->leftJoin('users','users.id','product_receipt.user_id');

        if ($request->get('keyword') != ""){
            $productReceipts = $productReceipts->where('users.name','like','%'.$request->keyword.'%');
            $productReceipts = $productReceipts->orWhere('users.email','like','%'.$request->keyword.'%');
            $productReceipts = $productReceipts->orWhere('product_receipt.id','like','%'.$request->keyword.'%');

        }

        $productReceipts = $productReceipts->paginate(10);

        return view('admin.product_receipt.index',[
            'productReceipts' => $productReceipts
        ]);
    }

    public function create(Request $request){

        $products = Product::latest('id')->with('product_images');
        if ($request->get('keyword') !=""){
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }
        $products = $products->paginate();
        $data['products'] = $products;
        return view('admin.product_receipt.create',$data);
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'items.*.quantity' => 'required_if:items.*.checked,true|integer|min:1',  // Chỉ yêu cầu khi checked = true
            'items.*.import_price' => 'required_if:items.*.checked,true|numeric|min:1',  // Kiểm tra nếu checked thì phải có import_price
        ]);

        $messages = [
            'items.*.quantity.required_if' => 'Vui lòng nhập số lượng cho các sản phẩm đã chọn.',
            'items.*.quantity.integer' => 'Số lượng phải là một số nguyên.',
            'items.*.quantity.min' => 'Số lượng phải lớn hơn 0.',
        ];
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->messages(),
            ], 422);
        }
    
        // Tạo biên nhận nhập hàng
        $productReceipt = new ProductReceipt;
        $productReceipt->user_id = auth()->id();
    
        // Tính `grand_total`
        $grandTotal = 0;
        foreach ($request->input('items', []) as $itemData) {
            if (isset($itemData['checked']) && $itemData['checked']) {
                $grandTotal += $itemData['import_price'] * $itemData['quantity'];
            }
        }
        $productReceipt->grand_total = $grandTotal;
        $productReceipt->status = 'pending';
        $productReceipt->save();
    
        // Lưu các sản phẩm vào `product_receipt_items` và tăng số lượng trong kho
        foreach ($request->input('items', []) as $productId => $itemData) {
            if (isset($itemData['checked']) && $itemData['checked']) {
                ProductReceiptItem::create([
                    'product_receipt_id' => $productReceipt->id,
                    'product_id' => $productId,
                    'quantity' => $itemData['quantity'],
                    'product_name' => $itemData['title'],
                    'price' => $itemData['import_price'] ?? 0,
                    'total' => $itemData['quantity']*$itemData['import_price']
                ]);
    
            }
        }
        session()->flash('success','Thêm thành công phiếu nhập');
        return response()->json([
            'status' => true,
            'message' => 'Products added successfully',
        ]);
    }
    
public function detail($productReceiptId){
    $productReceipt = ProductReceipt::select('product_receipt.*','users.name','users.email')
                ->where('product_receipt.id',$productReceiptId)
                
                ->leftJoin('users','users.id','product_receipt.user_id')
                ->first();

      

    $productReceiptItems = ProductReceiptItem::where('product_receipt_id',$productReceiptId)->get();

    return view('admin.product_receipt.detail',[
        'productReceipt' => $productReceipt,
        'productReceiptItems' => $productReceiptItems,
    ]);
}

public function changeProductReceiptStatus(Request $request, $productReceiptId){
    $productReceipt = ProductReceipt::find($productReceiptId);
    if (!$productReceipt) {
        session()->flash('error','Phiếu nhập không tồn tại');
        return response()->json([
            'status' => false,
            'message' => 'Product receipt not found'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'accepts_at' => 'date',
    ]);
    if ($validator->fails()) {
        session()->flash('error','Ngày xác nhận sai định dạng');
        return response()->json([
            'status' => false,
            
        ]);
    }

    $productReceipt->status = $request->status;
    if($productReceipt->status == 'pending'){
        $productReceipt->accepts_at = null;
    } else{
        $productReceipt->accepts_at = $request->accepts_at;
    }

        //cập nhật số lượng
        if($productReceipt->status == 'accepted'){
        foreach ($productReceipt->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->qty += $item->quantity;
                $product->save();
            }
        }
        }
   
    $productReceipt->save();

    session()->flash('success','Thay đổi thành công trạng thái phiếu nhập');

    return response()->json([
        'status' => true,
        'message' => 'Product receipt status changed successfully'
    ]);
}
   
}
