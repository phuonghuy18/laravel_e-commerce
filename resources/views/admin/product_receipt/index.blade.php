@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2 || Auth::user()->role == 4)
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products Receipt</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products-receipt.create') }}" class="btn btn-primary">New Receipt</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
            <div class="card-header">
                <div class="card-title">
                    <button type="button" onclick= "window.location.href='{{ Route("products-receipt.index") }}'" class="btn btn-default btn-sm">Reset    </button>
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
                        <th width="60">#</th>
                        
                        <th>User Name</th>
                        <th>Email</th>
                        
                        <th>Amount</th>
                        
                        <th>Status</th>
                        <th>Create at</th>
                        <th>Accept at</th>
                    </tr>
                </thead>

                <tbody>

                    @if ($productReceipts->isNotEmpty())
                        @foreach ($productReceipts as $productReceipt)
                        <tr>
                            <td><a href="{{ route('products-receipt.detail',[$productReceipt->id]) }}"> {{ $productReceipt->id }}</a></td>
                            
                            <td>{{ $productReceipt->name }}</td>
                            <td>{{  $productReceipt->email }}</td>
                            
                            <td>{{ number_format($productReceipt->grand_total) }}</td>
                            <td>
                                @if ($productReceipt->status == 'pending')
                                <span class="badge bg-warning ">Chờ xác nhận</span>
                                @elseif ($productReceipt->status == 'denied')
                                <span class="badge bg-danger ">Bị hủy</span>
                                @else 
                                <span class="badge bg-success">Đã xác nhận</span>
                                 
                                @endif
                            </td>
                           
                            <td>
                                {{ \Carbon\Carbon::parse($productReceipt->created_at)->format('d M, Y') }}
                            </td>
                            <td>
                                @if ($productReceipt->accepts_at)
                                    {{ \Carbon\Carbon::parse($productReceipt->accepts_at)->format('d M, Y') }}
                                @else
                                    Trống
                                @endif
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
</section>
@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
@endif
@endsection

