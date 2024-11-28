@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2) <!-- Admin -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Phiếu nhập số: #{{ $productReceipt->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products-receipt.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

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
                            <h1 class="h5 mb-3">Chi tiết nhập hàng</h1>
                            <b>Phiếu nhập số:</b> {{ $productReceipt->id }}<br>
                            <b>Tổng cộng:</b> {{ number_format($productReceipt->grand_total) }}<br>
                            <b>Status:</b> 
                            @if ($productReceipt->status == 'pending')
                                <span class="text-warning">Chờ xác nhận</span>
                            @elseif ($productReceipt->status == 'denied')
                                <span class="text-danger">Bị hủy</span>
                            @else
                                <span class="text-success">Đã xác nhận</span>
                            @endif
                            <br>
                            <strong>Shipped Date: </strong>
                        @if (!empty($productReceipt->created_at))
                            {{ \Carbon\Carbon::parse($productReceipt->created_at)->format('d M, Y') }}
                        @else
                            n/a
                        @endif

                            
                            </div>
                            
                            
                            
                            <div class="col-sm-4 invoice-col">
                                <br><br>
                                
                                <b>Người nhập: </b>{{ $productReceipt->name }}<br>
                                {{-- {{ $order->address }}<br>
                                {{ $order->city }}, {{ $order->countryName }}<br>
                                Phone: {{ $order->mobile }}<br> --}}
                                <b>Email: </b>{{ $productReceipt->email }}
                            
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">								
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>                                        
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productReceiptItems as $item)
                                @php
                                // Lấy hình ảnh sản phẩm bằng helper
                                    $productImage = getProductImage($item->product_id);
                                @endphp
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>
                                        @if (!empty($productImage->img))
                                        <img src="{{ asset('uploads/product/'.$productImage->img) }}" class="img-thumbnail" width="50" >
                                        @else
                                        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="">
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->price) }}</td>                                        
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->total) }}</td>
                                </tr>
                                @endforeach
                                
                                
                                <tr>
                                    <th colspan="4" class="text-right">Grand Total:</th>
                                    <td>{{ number_format($productReceipt->grand_total) }}</td>
                                </tr>
                            </tbody>
                        </table>								
                    </div>                            
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <form action="" method="post" name="changeProductReceiptStatusForm" id="changeProductReceiptStatusForm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Status</h2>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-control">
                                <option value="pending" {{ ($productReceipt->status == 'pending') ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="accepted" {{ ($productReceipt->status == 'accepted') ? 'selected' : '' }}>Xác nhận</option>
                                <option value="denied" {{ ($productReceipt->status == 'denied') ? 'selected' : '' }}>Hủy</option>
                                {{-- <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option> --}}
                                
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Accept Date</label>
                            <input placeholder="Accept Date" value="{{ $productReceipt->accepts_at }}" type="text" name="accepts_at" id="accepts_at" class="form-control">
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
@elseif (Auth::user()->role == 4)  <!-- Staff -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Phiếu nhập số: #{{ $productReceipt->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products-receipt.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-12">
                @include('admin.message')
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                            <h1 class="h5 mb-3">Chi tiết nhập hàng</h1>
                            <b>Phiếu nhập số:</b> {{ $productReceipt->id }}<br>
                            <b>Tổng cộng:</b> {{ number_format($productReceipt->grand_total) }}<br>
                            <b>Status:</b> 
                            @if ($productReceipt->status == 'pending')
                                <span class="text-warning">Chờ xác nhận</span>
                                @else
                                <span class="text-success">Đã xác nhận</span>
                                @endif
                            <br>
                            <strong>Shipped Date: </strong>
                        @if (!empty($productReceipt->created_at))
                            {{ \Carbon\Carbon::parse($productReceipt->created_at)->format('d M, Y') }}
                        @else
                            n/a
                        @endif

                            
                            </div>
                            
                            
                            
                            <div class="col-sm-4 invoice-col">
                                <br><br>
                                
                                <b>Người nhập: </b>{{ $productReceipt->name }}<br>
                                {{-- {{ $order->address }}<br>
                                {{ $order->city }}, {{ $order->countryName }}<br>
                                Phone: {{ $order->mobile }}<br> --}}
                                <b>Email: </b>{{ $productReceipt->email }}
                            
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">								
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>                                        
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productReceiptItems as $item)
                                @php
                                // Lấy hình ảnh sản phẩm bằng helper
                                    $productImage = getProductImage($item->product_id);
                                @endphp
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>
                                        @if (!empty($productImage->img))
                                        <img src="{{ asset('uploads/product/'.$productImage->img) }}" class="img-thumbnail" width="50" >
                                        @else
                                        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="">
                                        @endif
                                    </td>
                                    
                                    
                                    <td>{{ number_format($item->price) }}</td>                                        
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->total) }}</td>
                                </tr>
                                @endforeach
                                
                                
                                <tr>
                                    <th colspan="4" class="text-right">Grand Total:</th>
                                    <td>{{ number_format($productReceipt->grand_total) }}</td>
                                </tr>
                            </tbody>
                        </table>								
                    </div>                            
                </div>
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
    <script>
        $(document).ready(function(){
    $('#accepts_at').datetimepicker({
        // options here
        format:'Y-m-d H:i:s',
    });
});

$("#changeProductReceiptStatusForm").submit(function(event){
        event.preventDefault();
        if(confirm("Bạn có chắc muốn đổi trạng thái phiếu nhập?")){
            $.ajax({
            url :'{{ route("products-receipt.changeProductReceiptStatus", $productReceipt->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                window.location.href='{{ route('products-receipt.detail',$productReceipt->id) }}';
            }

        });
        }
        
    });
    </script>
@endsection