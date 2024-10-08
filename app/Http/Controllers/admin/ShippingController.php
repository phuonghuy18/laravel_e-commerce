<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Province;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Validator;


class ShippingController extends Controller
{
    public function create(){
        $provinces = Province::get();
        $data['provinces'] = $provinces;
        $shippingCharges = ShippingCharge::select('shipping_charges.*','provinces.name')
                            ->leftJoin('provinces','provinces.id','shipping_charges.province_id')->get();
        $data['shippingCharges'] = $shippingCharges;
        
        return view('admin.shipping.create',$data);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'province'=>'required',
            'amount'=>'required|numeric'
        ]);

        if ($validator->passes()){
            $count = ShippingCharge::where('province_id',$request->province)->count();

            if ($count > 0){
                session()->flash('error','Đã tồn tại vị trí');
                return response()->json([
                    'status' => true,
                ]);
            }


            $shipping = new ShippingCharge();
            $shipping->province_id = $request->province;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Đã thêm thành công phí vận chuyển');
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

    public function edit($id){

        $shippingCharge = ShippingCharge::find($id);

        $provinces = Province::get();
        $data['provinces'] = $provinces;
        $data['shippingCharge'] = $shippingCharge;
        return view('admin.shipping.edit',$data);
    }

    public function update($id, Request $request){
        $shipping = ShippingCharge::find($id);

        $validator = Validator::make($request->all(),[
            'province'=>'required',
            'amount'=>'required|numeric'
        ]);

        if ($validator->passes()){
            if ($shipping == null){
                session()->flash('error','Vị trí không tồn tại');
                return response()->json([
                    'status' => true,
                ]);
            }

            
            $shipping->province_id = $request->province;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success','Cập nhật thành công phí vận chuyển');
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

    public function destroy($id){

        $shippingCharge = ShippingCharge::find($id);

        if ($shippingCharge == null){
            session()->flash('error','Vị trí không tồn tại');
            return response()->json([
                'status' => true,
            ]);
        }

        $shippingCharge->delete();
        session()->flash('success','Xóa thành công vị trí vận chuyển');
            return response()->json([
                'status' => true,
            ]);
    }
}
