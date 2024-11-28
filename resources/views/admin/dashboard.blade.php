@extends('admin.layouts.app')

@section('content')
@if (Auth::user()->role == 2)
<section class="content-header">					
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">  
            <div class="col-lg-2 col-6">							
                <div class="small-box card bg-info">
                    <div class="inner">
                        <h3>{{ $totalOrders }}</h3>
                        <p>Tổng đơn hàng</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ route('orders.index') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-6">							
                <div class="small-box card bg-info">
                    <div class="inner">
                        <h3>{{ $totalProducts }}</h3>
                        <p>Tổng sản phẩm</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ route('productStatistics.productSoldChart') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalRevenue) }}đ</h3>
                        <p>Tổng doanh thu</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="{{ route('admin.getMonthlyRevenue') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                    {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                </div>
            </div>

            <div class="col-lg-4 col-6">							
                <div class="small-box card bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalImport) }}đ</h3>
                        <p>Tổng nhập kho</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-calendar"></i>
                    </div>
                    <a href="{{ route('admin.getMonthlyReceipt') }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>

                    {{-- <a href="javascript:void(0);" class="small-box-footer">&nbsp;</a> --}}
                </div>
            </div>

            

            

            

            
        </div>
        
            <form method="GET" action="{{ route('admin.dashboard') }}" >
                
            <div class="row">
                <h4 class="col-4 d-flex align-items-end">Doanh Thu Tháng {{ $month }}/{{ $year }}</h4>
           
                <div class="col-3">
                    <label for="month">Tháng:</label>
                    <select name="month" id="month" class="form-control">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                 {{ $m }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-3">
                    <label for="year">Năm:</label>
                    <select name="year" id="year" class="form-control">
                        @for ($y = 2020; $y <= Carbon\Carbon::now()->year; $y++)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                 {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Hiển thị</button>
                </div>
            </div>
            </form>
            <br>
        
        
        <div class="row">
            
            
            <!-- Doanh thu tháng -->
            <div class="col-lg-3 col-6">							
                <div class="small-box card bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalRevenueOfMonth) }}đ</h3>
                        <p>Doanh thu tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                </div>
            </div>

            <!-- Nhập hàng tháng -->
            <div class="col-lg-3 col-6">							
                <div class="small-box card bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($totalImportOfMonth) }}đ</h3>
                        <p>Nhập kho tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-calendar"></i>
                    </div>
                </div>
            </div>

             <!-- Đơn hàng tháng -->
             <div class="col-lg-3 col-6">
                <div class="small-box card bg-secondary">
                    <div class="inner">
                        <h3>{{ $totalOrdersOfMonth }}</h3>
                        <p>Đơn hàng tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>

             <!-- Đơn hàng bị hủy trong tháng -->
             <div class="col-lg-3 col-6">
                <div class="small-box card bg-danger">
                    <div class="inner">
                        <h3>{{ $cancelledOrdersOfMonth }}</h3>
                        <p>Đơn hàng bị hủy tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-close"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>	
        </div>
    </div>					
</section>
@else
{{ abort(403) }} <!-- Trả về lỗi 403 nếu user không phải admin -->
@endif
@endsection

@section('customJs')
@endsection
