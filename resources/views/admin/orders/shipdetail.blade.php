@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>SOrder: #{{ $order->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('orders.shipperIndex') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-9">
                @include('admin.message')
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                            <h1 class="h5 mb-3">Shipping Address</h1>
                            <address>
                                <strong>{{ $order->first_name.' '.$order->last_name }}</strong><br>
                                {{ $order->address }}
                                Phone: {{ $order->mobile }}<br>
                                Email: {{ $order->email }}
                            </address>
                            
                            </div>
                            
                            
                            
                            <div class="col-sm-4 invoice-col">
                                <br><br>
                                <b>Order ID:</b> {{ $order->id }}<br>
                                <b>Total:</b> {{ number_format($order->grand_total) }}<br>
                                <b>Status:</b> 
                                @if ($order->status == 'pending')
                                    <span class="text-warning  ">Pending</span>
                                    @elseif ($order->status == 'shipping')
                                    <span class="text-info">Shipping</span>
                                    @elseif ($order->status == 'delivered')
                                    <span class="text-success">Delivered</span>
                                    @else
                                    <span class="text-danger">Cancelled</span>
                                    @endif
                                <br>
                                <strong>Shipped Date</strong><br>
                            @if (!empty($order->shipped_date))
                                {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                            @else
                                n/a
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">								
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>                                        
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->price) }}</td>                                        
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ number_format($item->total) }}</td>
                                </tr>
                                @endforeach
                                
                                
                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td>{{ number_format($order->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Discount:{{ (!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : '' }}</th>
                                    <td>{{ number_format($order->discount) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Shipping:</th>
                                    <td>{{ number_format($order->shipping) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Grand Total:</th>
                                    <td>{{ number_format($order->grand_total) }}</td>
                                </tr>
                            </tbody>
                        </table>								
                    </div>                            
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Order Status</h2>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-control">
                                
                                <option value="shipping" {{ ($order->status == 'shipping') ? 'selected' : '' }}>Shipping</option>
                                <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Shipped Date</label>
                            <input placeholder="Shipped Date" value="{{ $order->shipped_date }}" type="text" name="shipped_date" id="shipped_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection

@section('customJs')
    <script>
        $(document).ready(function(){
    $('#shipped_date').datetimepicker({
        // options here
        format:'Y-m-d H:i:s',
    });
});

    $("#changeOrderStatusForm").submit(function(event){
        event.preventDefault();
        if(confirm("Bạn có chắc muốn đổi trạng thái đơn hàng?")){
            $.ajax({
            url :'{{ route("orders.changeOrderStatus", $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                window.location.href='{{ route('orders.detail',$order->id) }}';
            }

        });
        }
        
    });

    
    </script>
@endsection