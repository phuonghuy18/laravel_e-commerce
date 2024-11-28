@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">TRANG CHỦ</a></li>
                <li class="breadcrumb-item active">CỬA HÀNG</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">            
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>DANH MỤC</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">
                            {{-- @if ($categories->isNotEmpty())
                            @foreach ($categories as $key => $category)
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key }}" aria-expanded="false" aria-controls="collapseOne-{{ $key }}">
                                {{ $category->name }}
                                </button>
                            <div class="accordion-item">
                                @if ($category->sub_category->isNotEmpty())
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key }}" aria-expanded="false" aria-controls="collapseOne-{{ $key }}">
                                        {{ $category->name }}
                                    </button>
                                </h2>
                                @else
                                <a href="{{ route("front.shop",$category->slug) }}" class="nav-item nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                @endif

                                @if ($category->sub_category->isNotEmpty())
                                <div id="collapseOne-{{ $key }}" class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">
                                    @foreach ($category->sub_category as $subCategory)
                                        <a href="{{ route("front.shop",[$category->slug,$subCategory->slug]) }}" class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">{{ $subCategory->name }}</a>
                                    @endforeach
                                                                                     
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach  
                            @endif               --}}
                                             
                            @if ($categories->isNotEmpty())
    @foreach ($categories as $key => $category)
        <div class="accordion-item">
            <!-- Phần tiêu đề của Accordion -->
            <h5 class="accordion-header" id="headingOne-{{ $key }}">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Hiển thị tên danh mục -->
                    <a href="{{ route("front.shop", $category->slug) }}" 
                       class="nav-item nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : '' }}">
                       {{ $category->name }}
                    </a>

                    <!-- Nút dropdown bên cạnh thẻ a -->
                    @if ($category->sub_category->isNotEmpty())
                        <button style="width: 40px;height: 40px; padding: 5px;" class="accordion-button collapsed" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapseOne-{{ $key }}" 
                                aria-expanded="false" 
                                aria-controls="collapseOne-{{ $key }}">
                        </button>
                    @endif
                </div>
            </h5>

            <!-- Nội dung dropdown cho danh mục con -->
            @if ($category->sub_category->isNotEmpty())
                <div id="collapseOne-{{ $key }}" 
                     class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : '' }}" 
                     aria-labelledby="headingOne-{{ $key }}" 
                     data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="navbar-nav">
                            @foreach ($category->sub_category as $subCategory)
                                <a href="{{ route("front.shop", [$category->slug, $subCategory->slug]) }}" 
                                   class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">
                                   {{ $subCategory->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach  
@endif 



                        

                           

                        </div>
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>NHÃN HIỆU</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        @if ($brands->isNotEmpty())
                        @foreach ($brands as $brand)
                        <div class="form-check mb-2">
                            <input {{ (in_array($brand->id, $brandsArray)) ? 'checked' : '' }} class="form-check-input brand-label" type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                {{ $brand->name }}
                            </label>
                        </div>
                        @endforeach
                        
                        @endif          
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>GIÁ</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <input type="text" class="js-range-slider" name="my_range" value="" />
                                         
                                        
                    </div>
                </div>
                <div class="sub-title mt-5">
                    <button class="btn btn-warning" id="clear-filters" type="button">Xóa bộ lọc</button>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                {{-- <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Latest</a>
                                        <a class="dropdown-item" href="#">Price High</a>
                                        <a class="dropdown-item" href="#">Price Low</a>
                                    </div>
                                </div> --}} 
                                <select name="sort" id="sort" class="form-control">
                                    <option value="latest" {{ ($sort == 'latest') ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Thấp dần</option>
                                    <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Cao dần</option>
                                </select>                                   
                            </div>
                        </div>
                    </div>

                    @if ($products->isNotEmpty())
                    @foreach ($products as $product )
                    @php
                        $productImage = $product->product_images->first();
                    @endphp
                    <div class="col-md-3">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                
                                
                                <a href="{{ route("front.product",$product->id) }}" class="product-img">
                                @if (!empty($productImage->img))
                                    <img class="card-img-top" src="{{ asset('uploads/product/'.$productImage->img) }}" width="50" >
                                @else
                                    <img class="card-img-top" src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="">
                                @endif
                                </a>
                                <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            
                         

                                <div class="product-action">
                                    @if ($product->track_qty == 'Yes')
                                    @if ($product->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                    </a>
                                    @else
                                    <a class="btn btn-dark" href="javascript:void(0);">
                                        Hết hàng
                                    </a>
                                    @endif
                                    @else
                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                                    </a>
                                    @endif
                                </div>
                            </div>                        
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="{{ route("front.product",$product->id) }}">{{ $product->title }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>{{ number_format($product->price) }}</strong></span>
                                    @if($product->compare_price > 0)
                                    <span class="h6 text-underline"><del>{{ number_format($product->compare_price) }}</del></span>
                                    @endif
                                </div>
                            </div>                        
                        </div>                                               
                    </div> 
                    @endforeach
                    @else
                    <div class="col-md-3" style="font-style:italic; margin-left: 50px">
                    <p>Không có sản phẩm phù hợp</p>
                    </div>
                    @endif 
                     

                    

                    <div class="col-md-12 pt-5">
                        {{ $products->withQueryString()->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>

    // Khai báo giá trị mặc định để so sánh khi trang tải
    const defaultPriceMin = 100000;
    const defaultPriceMax = 10000000;

    // Thiết lập biến để kiểm tra xem người dùng có thay đổi thanh trượt hay không
    let isSliderChanged = false;

    const rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: defaultPriceMin,
        max: defaultPriceMax,
        from: {{ $priceMin }},
        step: 5000,
        to: {{ $priceMax }},
        skin: "round",
        max_postfix: "+",
        prefix: "đ",
        onFinish: function() {
            isSliderChanged = true;  // Đánh dấu thanh trượt đã được thay đổi
            apply_filters();
        }
    });

    // Lưu biến slider
    const slider = $(".js-range-slider").data("ionRangeSlider");

    $(".brand-label").change(function() {
        apply_filters();
    });

    $("#sort").change(function() {
        apply_filters();
    });

    // Chức năng nút Xóa Bộ Lọc
    $("#clear-filters").click(function() {
        // Đặt lại slider về giá trị min và max
        slider.update({
            from: defaultPriceMin,
            to: defaultPriceMax
        });

        // Bỏ chọn tất cả các ô kiểm thương hiệu
        $(".brand-label").prop("checked", false);

        // Xóa nội dung tìm kiếm
        $("#search").val("");

        // Đặt lại dropdown sắp xếp về mặc định
        $("#sort").val("");

        // Tải lại trang không có tham số truy vấn
        window.location.href = '{{ url()->current() }}';
    });

    function apply_filters() {
        const brands = [];
        $(".brand-label:checked").each(function() {
            brands.push($(this).val());
        });

        // Bắt đầu xây dựng URL với URL hiện tại
        let url = '{{ url()->current() }}?';

        // Thêm bộ lọc thương hiệu
        if (brands.length > 0) {
            url += 'brand=' + brands.join(',') + '&';
        }

        // Chỉ thêm bộ lọc giá nếu người dùng đã thay đổi giá trị thanh trượt
        if (isSliderChanged) {
            const priceMin = slider.result.from;
            const priceMax = slider.result.to;

            if (priceMin !== defaultPriceMin || priceMax !== defaultPriceMax) {
                url += 'price_min=' + priceMin + '&price_max=' + priceMax + '&';
            }
        }

        // Thêm từ khóa tìm kiếm nếu có
        const keyword = $("#search").val();
        if (keyword.length > 0) {
            url += 'search=' + encodeURIComponent(keyword) + '&';
        }

        // Thêm bộ lọc sắp xếp nếu có
        const sortValue = $("#sort").val();
        if (sortValue) {
            url += 'sort=' + encodeURIComponent(sortValue);
        }

        // Xóa ký tự `&` hoặc `?` ở cuối nếu có
        url = url.replace(/[&?]$/, '');

        // Điều hướng đến URL đã cập nhật
        window.location.href = url;
    }

</script>
@endsection

