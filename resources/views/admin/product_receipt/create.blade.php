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
                            <td>
                                <input type="checkbox" name="items[{{ $product->id }}][checked]" class="product-checkbox" />
                            </td>

                            <td>
                                <input type="number" name="items[{{ $product->id }}][quantity]" class="quantity-input" min="1" disabled />
                            </td>
                            <td>
                                <input type="number" name="items[{{ $product->id }}][import_price]" class="price-input" min="1" disabled>
                            </td>
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

    var errorMessages = '';

    // Lặp qua tất cả các dòng sản phẩm để kiểm tra số lượng và giá
    $('.product-checkbox').each(function() {
        var row = $(this).closest('tr');
        var quantity = row.find('.quantity-input').val();
        var price = row.find('.price-input').val(); // Kiểm tra giá nhập
        var isChecked = $(this).prop('checked');

        if (isChecked) {
            // Kiểm tra số lượng
            if (!quantity || quantity <= 0) {
                errorMessages += 'Vui lòng nhập số lượng cho sản phẩm: ' + row.find('td').eq(1).text() + '\n';
            }
            // Kiểm tra giá nhập
            if (!price || price <= 0) {
                errorMessages += 'Vui lòng nhập giá cho sản phẩm: ' + row.find('td').eq(1).text();
            }
        }
    });

    if (errorMessages) {
        $("button[type=submit]").prop('disabled', false);
        alert("Lỗi:\n" + errorMessages);
        return; // Dừng submit form nếu có lỗi
    }

    // Gửi AJAX nếu không có lỗi
    $.ajax({
        url: '{{ route("products-receipt.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
            $("button[type=submit]").prop('disabled', false);
            if (response.status == true) {
                // Hiển thị thông báo thành công và chuyển hướng
                
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

                // Lặp qua các lỗi và hiển thị thông báo lỗi tùy chỉnh
                $.each(errors, function(key, messages) {
                    if (key.includes('quantity')) {
                        // Nếu lỗi liên quan đến trường quantity
                        errorMessages += "Vui lòng nhập số lượng cho các sản phẩm đã chọn.";
                    } else {
                        errorMessages += messages.join(' ') + '\n';
                    }
                });

                // Hiển thị lỗi tùy chỉnh
                alert("Lỗi:\n" + errorMessages);
            } else {
                console.log("Lỗi không xác định xảy ra");
            }
        }
    });
});

document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        let row = this.closest('tr');
        let quantityInput = row.querySelector('.quantity-input');
        let priceInput = row.querySelector('.price-input');
        
        if (this.checked) {
            quantityInput.disabled = false; // Bật trường số lượng
            priceInput.disabled = false; // Bật trường giá
        } else {
            quantityInput.disabled = true; // Tắt trường số lượng
            priceInput.disabled = true; // Tắt trường giá
        }
    });
});
</script>
@endsection