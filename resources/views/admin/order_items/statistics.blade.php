@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products Summary</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a>
            </div>
            
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick= "window.location.href='{{ Route("productStatistics.index") }}'" class="btn btn-default btn-sm">Reset    </button>
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
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Sale Price</th>
                            <th>Total Quantity Sold</th>
                            <th>Total Revenue</th>
                            <th>Import Quantity</th>
                            <th>Total Import Price</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statistics as $stat)
                    <tr>
                        <td>{{ $stat->product_id }}</td>
                        <td>{{ $stat->name }}</td>
                        <td>{{ number_format($stat->sale_price ?? 0) }}</td>
                        <td>{{ $stat->total_qty ?? 0 }}</td>
                        <td>{{ number_format($stat->total_revenue ?? 0) }}</td>
                        
                        <td>{{ $stat->import_qty ?? 0 }}</td>
                        <td>{{ number_format($stat->total_import_price ?? 0) }}</td>
                    </tr>
                         @endforeach    
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{ $statistics->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
@endif
@endsection