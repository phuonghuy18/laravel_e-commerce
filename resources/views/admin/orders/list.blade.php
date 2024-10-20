@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Orders</h1>
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
            <div class="card-header">
                <div class="card-title">
                    <button type="button" onclick= "window.location.href='{{ Route("orders.index") }}'" class="btn btn-default btn-sm">Reset    </button>
                </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
        
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                          </div>
                    </div>
                
                
            </div>
        </form>
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">Order#</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date Purchase</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($orders->isNotEmpty())
                            @foreach ($orders as $order)
                            <tr>
                                <td><a href="{{ route('orders.detail',[$order->id]) }}"> {{ $order->id }}</a></td>
                                <td>{{ $order->name }}</td>
                                <td>{{  $order->email }}</td>
                                <td>{{  $order->mobile }}</td>
                                <td>{{ number_format($order->grand_total,3) }}</td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        Paid
                                    @else 
                                       Not Paid
                                    @endif
                                </td>
                                <td>
                                    @if ($order->status == 'pending')
                                    <span class="badge bg-warning ">Pending</span>
                                    @elseif ($order->status == 'shipping')
                                    <span class="badge bg-info">Shipping</span>
                                    @elseif ($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @else
                                    <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                
                                <td>
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}
                                </td>
                            </tr>
                            @endforeach
                        @else 
                        <tr>
                                <td colspan="5">Records not found</td>
                        </tr>
                        @endif 
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
@endif
@endsection

@section('customJs')

@endsection