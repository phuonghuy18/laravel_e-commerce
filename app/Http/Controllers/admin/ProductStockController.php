<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ImportProduct;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;

class ProductStockController extends Controller
{
    public function import($id, Request $request){
        $product = Product::find($id);


        if (empty($product)){
            //$request->session()->flash('error','Sản phẩm không tồn tại');
            return redirect()->route('products.index')->with('error','Bản ghi không tồn tại');
        }

        //Fetch Product Imgages
        /* $productImage = ProductImage::where('product_id',$product->id)->get();


        $subCategories = SubCategory::where('category_id',$product->category_id)->get(); */

        
        //fetch related products
        

        $data = []; 
        $data['product'] = $product;
        
        

        return view('admin.products.import', $data);
    }


    public function updateStock($id, Request $request){
        $product = Product::find($id);

        $validator = Validator::make($request->all(),[
            'import_price' => 'required|numeric',
            'import_qty' => 'required|numeric'
        ]);
        
        

        

        if($validator->passes()){
            $importProduct = new ImportProduct();
            $importProduct->product_id = $product->id;
           
            $importProduct-> import_price = $request->import_price;
            
            
            
            $importProduct-> import_qty = $request->import_qty;
            $importProduct-> total_import_price = $request->import_price*$request->import_qty;
            
            $importProduct->save();

            $product->qty += $importProduct->import_qty;
            $product->save();

            //save gallery
            

            $request->session()->flash('success','Sản phẩm đã được nhập');

            return response()->json([
                'status' => true,
                'message' => 'Product updateddd successfully'
            ]);

            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /* public function index(Request $request){
        //$products = Product::latest('id')->with('product_images');
        $importProducts = ImportProduct::latest('id');

       // $productImage = ProductImage::where('product_id',$product->id)->get();

        if ($request->get('keyword') !=""){
            $importProducts = $importProducts->where('title','like','%'.$request->keyword.'%');
        }
        //$importProducts = $importProducts->paginate();


        $data = [];
        $data['importProducts'] = $importProducts;
       // $data['productImage'] = $productImage;
      //  $data['products'] = $products;
        //dd($products);
        return view('admin.import.list',$data);
    } */




    public function index1()
    {
        // Lấy tất cả dữ liệu từ bảng imports
        $importProducts = ImportProduct::with('product')->get();

        // Trả về view và truyền dữ liệu qua
        return view('admin.import.list', compact('importProducts'));
    }


    public function index(Request $request){
        $importProducts = ImportProduct::select('import_products.*', 'products.title')
                    ->latest('import_products.id')
                   ->leftJoin('products', 'products.id', '=', 'import_products.product_id');
                   
                   
                   if ($request->get('keyword') !=""){
                    $importProducts = $importProducts->where('title','like','%'.$request->keyword.'%');
                }
       
        $importProducts = $importProducts->paginate(10);
        
        return view('admin.import.list',[
            'importProducts' => $importProducts
        ]);
    }


}
