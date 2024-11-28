@extends('front.layouts.appwithoutsearch')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">TÀI KHOẢN</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.orders') }}">ĐƠN HÀNG</a></li>
                <li class="breadcrumb-item"><span class="white-text" href="#">CHI TIẾT ĐƠN HÀNG</span></li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! Session::get('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div> 
            @endif

            @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div> 
            @endif
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Orders: {{ $order->id }}</h2>
                    </div>

                    <div class="card-body pb-0">
                        <!-- Info -->
                        <div class="card card-sm">
                            <div class="card-body bg-light mb-3">
                                <div class="row">
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Đơn hàng số:</h6>
                                        <!-- Text -->
                                        <p class="mb-lg-0 fs-sm fw-bold">
                                            {{ $order->id }}
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Ngày giao dự kiến:</h6>
                                        <!-- Text -->
                                        <p class="mb-lg-0 fs-sm fw-bold">
                                            <time datetime="2019-10-01">
                                                @if (!empty($order->shipped_date))
                                                    {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                                @else
                                                    n/a
                                                @endif
                                            </time>
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Hoạt động:</h6>
                                        <!-- Text -->
                                        {{-- <p class="mb-0 fs-sm fw-bold">
                                            @if ($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif ($order->status == 'shipping')
                                            <span class="badge bg-info">Shipping</span>
                                            @elseif ($order->status == 'delivered')
                                            <span class="badge bg-success">Delivered</span>
                                            @else
                                            <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </p> --}}
                                        
                                        @if ($order->status == 'cancelled')
                                            <p>Bạn đã hủy đơn hàng này</p>
                                        @elseif ($order->status == 'delivered')
                                           <p>Đã nhận hàng</p>
                                        @else
                                        <button onclick="cancelOrder({{ $order->id }})" class="btn btn-danger btn-sm">Hủy đơn</button>
                                        @endif
                                        
                                        
                                        
                                    </div>
                                    
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Thành tiền:</h6>
                                        <!-- Text -->
                                        <p class="mb-0 fs-sm fw-bold">
                                        {{ number_format($order->grand_total) }}đ
                                        </p>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>
                    <div class="card-body pb-0">
                    <div class="card card-sm">
                        <div class="card-body bg-light mb-3">
                            <div class="row" style="height: 130px">
                                <div class="col-6 col-lg-3">
                                    <!-- Heading -->
                                    <h6 class="heading-xxxs text-muted">Trạng thái đơn hàng:</h6>
                                    <!-- Text -->
                                </div>
                                <div>
                                    <div class="order-status">
                                        <!-- Bước 1 -->
                                        <div class="step {{ $order->status == 'pending' || $order->status == 'shipping' || $order->status == 'delivered' ? 'active' : '' }} {{ $order->status == 'shipping' || $order->status == 'delivered' ? 'shipping-active' : '' }}">
                                            <div class="icon"><i class="fas fa-file-alt"></i></div>
                                            <div class="label">Đơn Hàng Đã Đặt</div>
                                        </div>
                    
                                        <!-- Bước 2 -->
                                        <div class="step {{ $order->status == 'shipping' || $order->status == 'delivered' ? 'active' : '' }} {{ $order->status == 'delivered' ? 'delivered-active' : '' }}">
                                            <div class="icon"><i class="fas fa-truck"></i></div>
                                            <div class="label">Đã Giao Cho ĐVVC</div>
                                        </div>
                    
                                        <!-- Bước 3 -->
                                        <div class="step {{ $order->status == 'delivered' ? 'active' : '' }}">
                                            <div class="icon"><i class="fas fa-box-open"></i></div>
                                            <div class="label">Đã Nhận Được Hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                   

                    </div>

                    <div class="card-footer p-3">

                        <!-- Heading -->
                        <h6 class="mb-7 h5 mt-4">Order Items ({{ $orderItemsCount }})</h6>

                        <!-- Divider -->
                        <hr class="my-3">

                        <!-- List group -->
                        <ul>
                            @foreach ($orderItems as $item)
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-4 col-md-3 col-xl-2">
                                        <!-- Image -->
{{--                                         <a href="product.html"><img src="images/product-1.jpg" alt="..." class="img-fluid"></a>
 --}}                                    
                                        @php
                                            $productImage = getProductImage($item->product_id);
                                        @endphp

                                        @if (!empty($productImage->img))
                                        <img class="img-fluid" src="{{ asset('uploads/product/'.$productImage->img) }}" width="50" >
                                        @else
                                        <img class="img-fluid" src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="">
                                        @endif
                                    
                                    </div>
                                    <div class="col">
                                        <!-- Title -->
                                        <p class="mb-4 fs-sm fw-bold">
                                            <a class="text-body" href="#">{{ $item->name }} x {{ $item->qty }}</a> 
                                            <a href="{{ route('front.product',$item->product_id) }}" style="padding: 2px 10px" class="btn btn-warning btn-sm active" role="button" aria-pressed="true">Mua lại</a>
                                            <br>
                                            {{--  --}}
                                            <span class="text-muted">{{ number_format($item->total) }}đ</span>
                                        </p>
                                    </div>
                                   
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card card-lg mb-5 mt-3">
                        <div class="card-body">
                            <!-- Heading -->
                            <h6 class="mt-0 mb-3 h5">Order Total</h6>
    
                            <!-- List group -->
                            <ul>
                                <li class="list-group-item d-flex">
                                    <span>Subtotal</span>
                                    <span class="ms-auto">{{ number_format($order->subtotal) }}đ</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Discount {{ (!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : '' }}đ</span>
                                    <span class="ms-auto">{{ number_format($order->discount) }}</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Shipping</span>
                                    <span class="ms-auto">{{ number_format($order->shipping) }}đ</span>
                                </li>
                                <li class="list-group-item d-flex fs-lg fw-bold">
                                    <span>Grand Total</span>
                                    <span class="ms-auto">{{ number_format($order->grand_total) }}đ</span>
                                </li>
                                <li class="list-group-item d-flex">
                                    <span>Phương thức thanh toán</span>
                                    @if ($order->payment_status == 'paid')
                                    <span class="ms-auto">Đã thanh toán</span> 
                                    @elseif ($order->status == 'delivered')
                                    <span class="ms-auto">Đã thanh toán và nhận hàng</span>
                                    @else
                                    <span class="ms-auto">Thanh toán khi nhận hàng</span>
                                    @endif
                                    
                                </li>
                            </ul>
                        </div>
                    </div>                      
                </div>
                
                
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    function cancelOrder(orderId) {
        if (confirm('Bạn có chắc muốn hủy đơn hàng này không?')) {
            fetch(`/cancel-order/${orderId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ orderId: orderId })
            })
            .then(response => response.json())  // Chuyển đổi phản hồi thành JSON
            .then(data => {
                if (data.status == true) {
                    // Chuyển hướng khi hủy đơn hàng thành công
                    window.location.href = "{{ route('account.orderDetail',$order->id) }}";
                } else {
                    window.location.href = "{{ route('account.orders') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi hủy đơn hàng.');
            });
        }
    }
</script>

@endsection