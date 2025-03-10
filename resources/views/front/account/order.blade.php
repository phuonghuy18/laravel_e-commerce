@extends('front.layouts.appwithoutsearch')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">TÀI KHOẢN</a></li>
                <li class="breadcrumb-item">ĐƠN HÀNG</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.account.common.sidebar')
                @include(('front.account.common.message'))
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Đơn hàng của tôi</h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table">
                                <thead> 
                                    <tr>
                                        <th style="width: 100px">Mã đơn hàng #</th>
                                        <th>Ngày mua</th>
                                        <th>Trạng thái</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($orders->isNotEmpty())
                                        @foreach ($orders as $order )
                                        <tr>
                                            <td>
                                                <a href="{{ route('account.orderDetail',$order->id) }}">{{ $order->id }}</a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
                                            <td>
                                                @if ($order->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @elseif ($order->status == 'shipping')
                                                <span class="badge bg-info">Shipping</span>
                                                @elseif ($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                                @else
                                                <span class="badge bg-danger">Cancelled</span>
                                                @endif

                                            </td>
                                            <td>{{ number_format($order->grand_total) }}đ</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">Đơn hàng không có</td>
                                        </tr>
                                    @endif
                                    
                                                                            
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
