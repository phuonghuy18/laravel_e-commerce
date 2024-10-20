@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Import Products</h1>
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
                            <button type="button" onclick= "window.location.href='{{ Route("import-product.index") }}'" class="btn btn-default btn-sm">Reset    </button>
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
                                <th width="60">ID</th>
                                
                                <th>Product</th>
                                <th>Price</th>
                                <th>Import Qty</th>
                                <th>Price Total</th>
                                <th>Time</th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if ($importProducts->isNotEmpty())
                                @foreach ($importProducts  as $importProduct)
                                {{-- @php
                                    $productImage = $product->product_images->first();
                                @endphp --}}
                                <tr>
                                    {{-- <td>{{ $importProduct->product_id }}</td> --}}
                                    {{-- <td>
                                        @if (!empty($productImage->img))
                                        <img src="{{ asset('uploads/product/'.$productImage->img) }}" class="img-thumbnail" width="50" >
                                        @else
                                        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="">
                                        @endif
                                        
                                    
                                    </td> --}}
                                    
                                    <td>{{ $importProduct->id }}</td>
                                    <td>{{ $importProduct->title }}</td>
                                    <td>{{ $importProduct->import_price }}</td>
                                    <td>{{ $importProduct->import_qty }} </td>
                                    <td>{{ $importProduct->total_import_price }} </td>   
                                    <td>{{ $importProduct->created_at }}</td>
                                    
                                    											
                                    
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
                    {{ $importProducts->links() }}
                </div>
                
            </div>
        </div>

        <!-- /.card -->
    </section>
@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
@endif
@endsection


