@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2 || Auth::user()->role == 4)
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Product Receipt</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products-receipt.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<section class="content">
    <form action="" method="get">
        <div class="card-header">
            <div class="card-title">
                <button type="button" onclick= "window.location.href='{{ Route("products-receipt.create") }}'" class="btn btn-default btn-sm">Reset    </button>
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

    <!-- Default box -->
    <form action="" method="post" name="productReceiptForm" id="productReceiptForm">
    <div class="container-fluid">
        <div class="row">
            <div class="card-body table-responsive p-3">								
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th width="60"></th>
                            <th>Product</th>
                            <th width="150">Price Sold</th>
                            <th></th>                                        
                            <th width="100">Quantity</th>
                            <th>Import Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($products->isNotEmpty())
                        @foreach ($products as $product)
                        @php
                        $productImage = $product->product_images->first();
                        @endphp
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if (!empty($productImage->img))
                                <img src="{{ asset('uploads/product/'.$productImage->img) }}" class="img-thumbnail" width="50" >
                                @else
                                <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="">
                                @endif
                            </td>
                            <td>{{ $product->title }}</td>
                            <td><input readonly class="form-control" type="text" name="items[{{ $product->id }}][price]" value="{{ number_format($product->price) }}"></td>
                            <td><input class="form-control" id="checked_{{ $product->id }}" name="items[{{ $product->id }}][checked]" type="checkbox"></td>

                            <td><input class="form-control" id="qty_{{ $product->id }}" name="items[{{ $product->id }}][quantity]" type="number" value="0"></td>
                            <td><input class="form-control" id="import_price_{{ $product->id }}" name="items[{{ $product->id }}][import_price]" type="number"></td>
                            <input type="hidden" name="items[{{ $product->id }}][title]" value="{{ $product->title }}">         
                        </tr>
                        
                        @endforeach
                        @else
                                <tr>
                                    <td>Records Not Found</td>
                                </tr>
                            @endif
                        
                    </tbody>
                </table>
                <div class="card-footer clearfix">
                    {{ $products->links() }}
                </div>								
            </div>  
                            
                                
        
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
    </div>
    </form>
    <!-- /.card -->
</section>
@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin hoặc staff -->
@endif
@endsection

@section('customJs')
<script>
    $("#productReceiptForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route("products-receipt.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status == true) {
                    // Hiển thị thông báo thành công và chuyển hướng
                    alert(response.message);
                    window.location.href = "{{ route('products-receipt.index') }}";
                } else {
                    // Hiển thị thông báo lỗi nếu có
                    alert("Lỗi khi thêm sản phẩm.");
                }
            },
            error: function(jqXHR) {
                $("button[type=submit]").prop('disabled', false);
                if (jqXHR.status === 422) {
                    let errors = jqXHR.responseJSON.errors;
                    let errorMessages = '';
                    $.each(errors, function(key, messages) {
                        errorMessages += messages.join(', ') + '\n';
                    });
                    alert("Lỗi xác thực:\n" + errorMessages);
                } else {
                    console.log("Lỗi không xác định xảy ra");
                }
            }
        });
    });
</script>
@endsection