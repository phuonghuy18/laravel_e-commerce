@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Import Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="post" name="importProductForm" id="importProductForm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $product->title }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $product->slug }}">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>	                                                                      
                    </div>
                    
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Import Price</label>
                                        <input type="text" name="import_price" id="import_price" class="form-control" placeholder="Import Price" >
                                        <p class="error"></p>	
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price">Quantity</label>
                                        <input type="number" min="0" name="import_qty" id="import_qty" class="form-control" placeholder="Qty">
                                        <p class="error"></p>		
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price">Inventory</label>
                                        <input type="number" min="0" readonly name="qty" id="qty" class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                        <p class="error"></p>		
                                    </div>
                                </div>
                                {{-- <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Total Import Price</label>
                                        <input type="text" name="total_improt_price" id="total_improt_price" class="form-control" value="{{  }}">
                                        
                                    </div>
                                </div> --}}
                                                                            
                            </div>
                        </div>	                                                                      
                    </div>

                    
                </div>
                {{-- <div class="col-md-4">
                    
                    <div class="card">
                        <div class="card-body">	
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                        <option {{ ($product->category_id == $category->id) ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->name }}</option> 
                                        @endforeach         
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Select a Sub Category</option>
                                    @if ($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory)
                                        <option {{ ($product->sub_category_id == $subCategory->id) ? 'selected' : ''}} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option> 
                                        @endforeach         
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Select a Brand</option>
                                    @if ($brands->isNotEmpty())
                                        @foreach ($brands as $brand)
                                        <option {{ ($product->brand_id == $brand->id) ? 'selected' : ''}} value="{{ $brand->id }}">{{ $brand->name }}</option> 
                                        @endforeach         
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    

                </div> --}}
            </div>
            
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Import</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
        </form>
        <!-- /.card -->
    </section>
    @else
    {{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
    @endif
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        

$("#importProductForm").submit(function(event){
    event.preventDefault();
    var formArray = $(this).serializeArray();
    $("button[type='submit']").prop('disable',true);

    $.ajax({
        url: '{{ route("products.updateStock",$product->id) }}',
        type: 'put',
        data: formArray,
        dataType: 'json',
        success: function(response){
            $("button[type='submit']").prop('disable',false);

            if(response['status'] == true) {
                $(".error").removeClass('invalid-feedback').html(''); 
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                window.location.href="{{ route('products.index') }}";
            } else {
                var errors = response['errors'];

              $(".error").removeClass('invalid-feedback').html(''); 
              $("input[type='text'], select, input[type='number']").removeClass('is-invalid'); 

              $.each(errors, function(key,value){
                $(`#${key}`).addClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedback')
                .html(value);
              });
            }
        },
        error: function(){
            console.log("Something went wrong");
        }
    });
});

    



    </script>
@endsection