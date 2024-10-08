<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DiscountCoupon;
use Illuminate\Support\Carbon;

class DiscountCodeController extends Controller
{
    public function index(Request $request){
        $discountCoupons = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))){
            $discountCoupons = $discountCoupons->where('name' , 'like' , '%'.$request->get('keyword').'%');
            $discountCoupons = $discountCoupons->orWhere('code' , 'like' , '%'.$request->get('keyword').'%');
        }
        $discountCoupons = $discountCoupons->paginate(10); // =orderBy('created_at','DESC')

        return view('admin.coupon.list', compact('discountCoupons'));
    }

    public function create(){
        return view('admin.coupon.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()){

            if (!empty($request->starts_at)){
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);

                if ($startAt->lte($now)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Ngày bắt đầu phải lớn hơn hôm nay']
                    ]);
                }
            }
            /* if (!empty($request->starts_at) && !empty($request->expires_at)){
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);

                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);

                if ($expiresAt->gt($startAt) == false){
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Ngày kết thúc phải sau ngày bắt đầu']
                    ]);
                }
                
            } */
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
            
                if ($expiresAt->lte($startAt)) {  // Sử dụng 'lte' để kiểm tra nếu 'expiresAt' nhỏ hơn hoặc bằng 'startAt'
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Ngày kết thúc phải sau ngày bắt đầu']
                    ]);
                }
            }
            


            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            session()->flash('success', 'Discount coupon đã thêm');
            return response()->json([
                'status' => true,
                'message' => 'Discount coupon đã thêm'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }
    public function edit(Request $request, $id){
        $coupon = DiscountCoupon::find($id);

        if (empty($coupon)){
            //$request->session()->flash('error','Sản phẩm không tồn tại');
            return redirect()->route('coupons.index')->with('error','Bản ghi không tồn tại');
        }
        $data['coupon'] = $coupon;
        return view('admin.coupon.edit', $data);
    }
    public function update(Request $request, $id){
        $discountCode = DiscountCoupon::find($id);

        if (empty($discountCode)){
            //$request->session()->flash('error','Sản phẩm không tồn tại');
            return redirect()->route('coupons.index')->with('error','Bản ghi không tồn tại');
        }

        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()){

            /* if (!empty($request->starts_at) && !empty($request->expires_at)){
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);

                $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);

                if ($expiresAt->gt($startAt) == false){
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Ngày kết thúc phải sau ngày bắt đầu']
                    ]);
                }
                
            } */
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
            
                if ($expiresAt->lte($startAt)) {  // Sử dụng 'lte' để kiểm tra nếu 'expiresAt' nhỏ hơn hoặc bằng 'startAt'
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Ngày kết thúc phải sau ngày bắt đầu']
                    ]);
                }
            }
            

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            session()->flash('success', 'Discount coupon đã cập nhật');
            return response()->json([
                'status' => true,
                'message' => 'Discount coupon đã cập nhật'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request, $id){
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null){
            session()->flash('error','Bản ghi không tồn tại');
            return response()->json([
                'status' => true
            ]);
        }

        $discountCode->delete();
        session()->flash('success','Đã xóa Discount Coupon Code');
        return response()->json([
            'status' => true
        ]);
    }
}
