<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;
use App\Models\TempImage;
use App\Models\ProductRating;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    
    public function index(Request $request){
        $products = Product::latest('id')->with('product_images');
        if ($request->get('keyword') !=""){
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }
        $products = $products->paginate();
        $data['products'] = $products;
        //dd($products);
        
        return view('admin.products.list',$data);
    }



    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request){
        /* dd($request->image_array);
        exit(); */
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){

            $product = new Product();
            $product-> title = $request->title;
            $product-> slug = $request->slug;
            $product-> description = $request->description;
            $product-> price = $request->price;
            $product-> compare_price = $request->compare_price;
            
            
            $product-> track_qty = $request->track_qty;
            $product-> qty = $request->qty;
            $product-> status = $request->status;
            $product-> category_id = $request->category;
            $product-> sub_category_id = $request->sub_category;
            $product-> brand_id = $request->brand;
            $product-> is_featured = $request->is_featured;
            
            $product-> short_description = $request->short_description;
            $product-> related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();


            //save gallery
            if (!empty($request->image_array)){
                foreach ($request->image_array as $temp_image_id){

                    $tempImageInfo =TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); //png,gif,jpg

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->img = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    // product_id => 4; product_image => 1
                    // 4-1-time.png
                    $productImage->img = $imageName;
                    $productImage->save();

                    $sPath = public_path().'/temp/'.$tempImageInfo->name;
                    $dPath = public_path().'/uploads/product/'.$imageName;
                    File::copy($sPath,$dPath);
                    
                }
            }


            $request->session()->flash('success','Sản phẩm đã được thêm');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function edit($id, Request $request){
        $product = Product::find($id);


        if (empty($product)){
            //$request->session()->flash('error','Sản phẩm không tồn tại');
            return redirect()->route('products.index')->with('error','Bản ghi không tồn tại');
        }

        //Fetch Product Imgages
        $productImage = ProductImage::where('product_id',$product->id)->get();


        $subCategories = SubCategory::where('category_id',$product->category_id)->get();

        $relatedProducts = [];
        //fetch related products
        if ($product->related_products != ''){
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->with('product_images')->get();
        }

        $data = []; 
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImage'] = $productImage;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.products.edit', $data);
    }


    public function update($id, Request $request){
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){

            $product-> title = $request->title;
            $product-> slug = $request->slug;
            $product-> description = $request->description;
            $product-> price = $request->price;
            $product-> compare_price = $request->compare_price;
            
            
            $product-> track_qty = $request->track_qty;
            $product-> qty = $request->qty;
            $product-> status = $request->status;
            $product-> category_id = $request->category;
            $product-> sub_category_id = $request->sub_category;
            $product-> brand_id = $request->brand;
            $product-> is_featured = $request->is_featured;
            
            $product-> short_description = $request->short_description;
            $product-> related_products = (!empty($request->related_products)) ? implode(',',$request->related_products) : '';
            $product->save();


            //save gallery
            

            $request->session()->flash('success','Sản phẩm đã được cập nhật');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);

            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request){
        $product = Product::find($id);

        if (empty($product)){
            $request->session()->flash('error', 'Sản phẩm không tồn tại');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }


        $productImages = ProductImage::where('product_id',$id)->get();
        
        if(!empty($productImages)){
           foreach ($productImages as $productImage){
            File::delete(public_path('uploads/product/'.$productImage->img));
            }
            ProductImage::where('product_id',$id)->delete();
        }
        
        $product->delete();
        $request->session()->flash('success', 'Sản phẩm đã được xóa');
        
            return response()->json([
                'status' => true,
                'message' => 'Product deleted succesfully'
            ]);
        
    }

    public function getProducts(Request $request){
        $tempProduct = [];
        
        if ($request->term != ""){
            $products = Product::where('title','like','%'.$request->term.'%')->get();
        
            if ($products != null){
                foreach ($products as $product){
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        
            return response()->json([
                'tags' => $tempProduct,
                'status' => true
            ]);
    }

    public function productRatings(Request $request){
        $ratings = ProductRating::select('product_rating.*','products.title as productTitle')
                            ->orderBy('product_rating.created_at','DESC');
        $ratings = $ratings->leftJoin('products','products.id','product_rating.product_id');
        if ($request->get('keyword') !=""){
            $ratings = $ratings->orWhere('products.title','like','%'.$request->keyword.'%');
            $ratings = $ratings->orWhere('product_rating.username','like','%'.$request->keyword.'%');
        }
        $ratings = $ratings->paginate(10);
        return view('admin.products.ratings',[
            'ratings' => $ratings
        ]);
    }

    public function changRatingStatus(Request $request){
        $productRating = ProductRating::find($request->id);
        $productRating->status = $request->status;
        $productRating -> save();
        session()->flash('success', 'Đổi trạng thái thành công');
        return response()->json([
            'status' => true
        ]);
    }


}
