@extends('front.layouts.app')

@section('content')
    
<section class="section-1">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <!-- <img src="images/carousel-1.jpg" class="d-block w-100" alt=""> -->

                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/banner1-m.png') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/banner1.png') }}" />
                    <img src="{{ asset('front-assets/images/banner1.png') }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">NƯỚC HOA NAM</h1>
                        <p class="mx-md-5 px-5">Khám phá thế giới mạnh mẽ và quyến rũ, để trải nghiệm bộ sưu tập nước hoa nam đẳng cấp, mang đến sự tự tin và cuốn hút từ những thương hiệu nổi tiếng.</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop', ['categorySlug' => 'nuoc-hoa-nam']) }}">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                
                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/carousel-2-m.jpg') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/carousel-2.jpg') }}" />
                    <img src="{{ asset('front-assets/images/carousel-2.jpg') }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">NƯỚC HOA NỮ</h1>
                            <p class="mx-md-5 px-5">Hãy khám phá thế giới hương thơm tinh tế, nồng nàn và cùng hoà mình vào bộ sưu tập
                            những lọ nước hoa được yêu thích nhất của lâu đài sắc đẹp Lancôme.
                            </p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop', ['categorySlug' => 'nuoc-hoa-nu']) }}">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/carousel-3-m.jpg') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/carousel-3.jpg') }}" />
                    <img src="{{ asset('front-assets/images/carousel-3.jpg') }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-6 text-white mb-3">Mua sắm trực tuyến với giá cả hợp lý</h1>
                        <p class="mx-md-5 px-5">Khám phá các ưu đãi đặc biệt và chương trình giảm giá khi mua sắm trực tuyến, mang lại cho bạn trải nghiệm mua sắm tiết kiệm và tiện lợi.</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<section class="section-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Genuine import</h2>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>                    
            </div>
        </div>
    </div>
</section>
<section class="section-3">
    <div class="container">
        <div class="section-title">
            <h2>Categories</h2>
        </div>           
        <div class="row pb-3">
            @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $category)
            <div class="col-lg-3">
                <div class="cat-card">
                    <div class="left">
                        @if ($category->img !="")
                        <img src="{{ asset('uploads/category/'.$category->img) }}" alt="" class="img-fluid">
                        @endif
                        {{-- <img src="{{ asset('front-assets/images/cat-1.jpg') }}" alt="" class="img-fluid"> --}}
                    </div>
                    <div class="right">
                        <div class="cat-data">
                            <h2>{{ $category->name }}</h2>
                            {{-- <p>100 Products</p> --}}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach   
            @endif
            
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Featured Products</h2>
        </div>    
        <div class="row pb-3">
            @if ($featuredProducts->isNotEmpty())
                @foreach ($featuredProducts as $product )
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
                                @if ($product->compare_price > 0)
                                <span class="h6 text-underline"><del>{{ number_format($product->compare_price) }}</del></span>
                                @endif
                                
                            </div>
                        </div>                        
                    </div>                                               
                </div>
                @endforeach
            @endif       
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Latest Produsts</h2>
        </div>    
        <div class="row pb-3">
            @if ($latestProducts->isNotEmpty())
            @foreach ($latestProducts as $product )
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
                            @if ($product->compare_price > 0)
                            <span class="h6 text-underline"><del>{{ number_format($product->compare_price) }}</del></span>
                            @endif
                            
                        </div>
                    </div>                        
                </div>                                               
            </div>
            @endforeach
        @endif               
        </div>
    </div>
</section>

@endsection