<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null){

        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];


        $categories = Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        $products = Product::where('status',1);
        // Apply filters here
        if (!empty($categorySlug)){
            $category = Category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }
        
        if (!empty($subCategorySlug)){
            $subCategory = SubCategory::where('slug',$subCategorySlug)->first();
            $products = $products->where('sub_category_id',$subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if (!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandsArray);
        }

        if ($request->get('price_max') != '' && $request->get('price_min') != ''){
            if ($request->get('price_max') == 1000){
                $products = $products->whereBetween('price',[intval($request->get('price_min')),100000]);
            } else {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
            }
            
        }

        if (!empty($request->get('search'))){
            $products = $products->where('title','like','%'.$request->get('search').'%');

        }
        

        
        if ($request->get('sort') != ''){
            if ($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            } else if ($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price','ASC');
            } else {
                $products = $products->orderBy('price','DESC');
            }
        }else {
            $products = $products->orderBy('id','DESC');
        }
        
        $products = $products->paginate(8);

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;
        $data['priceMax'] =  (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $data['priceMin'] =  intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');
 
   
    

        return view('front.shop',$data);
    }

    public function product($id){
        //echo $slug;
        $product = Product::where('id',$id)
            ->withCount('product_ratings')
            ->withSum('product_ratings','rating')
            ->with(['product_images','product_ratings'])->first();
       
        if ($product == null){
            abort(404);
        }

        $relatedProducts = [];
        //fetch related products
        if ($product->related_products != ''){
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->where('status',1)->get();
        }

        //rating
        //"product_ratings_count" => 0
        //"product_ratings_sum_rating" => null
        $avgRating = '0.00';
        $avgRatingPer = 0;
        if ($product->product_ratings_count > 0){
            $avgRating = number_format(($product->product_ratings_sum_rating/$product->product_ratings_count),2) ;
            $avgRatingPer = ($avgRating*100)/5;
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;
        $data['avgRating'] = $avgRating;
        $data['avgRatingPer'] = $avgRatingPer;

        return view('front.product',$data);
    }

    public function saveRating($id, Request $request)
{
    // Lấy `user_id` từ người dùng đã đăng nhập
    $userId = auth()->id();

    // Xác thực dữ liệu đầu vào
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
        'comment' => 'required',
        'rating' => 'required|integer|min:1|max:5'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

// Kiểm tra nếu người dùng đã từng mua sản phẩm này
$hasPurchased = DB::table('order_items')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.user_id', $userId)
        ->where('order_items.product_id', $id)
        ->exists();

    if (!$hasPurchased) {
        session()->flash('error', 'Bạn cần mua để đánh giá sản phẩm này');
        return response()->json([
            'status' => true,
            'message' => 'Bạn cần mua sản phẩm trước khi có thể đánh giá.'
        ]);
    }

    // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
    $existingRating = ProductRating::where('user_id', $userId)
        ->where('product_id', $id)
        ->first();

    if ($existingRating) {
        // Nếu đã tồn tại đánh giá, hiển thị thông báo lỗi
        session()->flash('error', 'Bạn đã đánh giá sản phẩm này');
        return response()->json([
            'status' => true,
            'message' => 'Bạn đã đánh giá sản phẩm này'
        ]);
    }

    // Tạo đánh giá mới nếu người dùng chưa đánh giá sản phẩm này
    $productRating = new ProductRating;
    $productRating->product_id = $id;
    $productRating->user_id = $userId;
    $productRating->username = $request->name;
    $productRating->email = $request->email;
    $productRating->comment = $request->comment;
    $productRating->rating = $request->rating;
    $productRating->status = 0;
    $productRating->save();

    session()->flash('success', 'Cảm ơn vì đánh giá của bạn');
    return response()->json([
        'status' => true,
        'message' => 'Cảm ơn vì đánh giá của bạn'
    ]);
}


}
