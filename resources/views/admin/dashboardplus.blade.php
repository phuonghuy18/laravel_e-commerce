@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Doanh Thu Tháng {{ $month }}/{{ $year }}</h2>

    <!-- Form chọn tháng -->
    <form method="GET" action="{{ route('getMonthlyRevenueDashboard') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="month">Chọn tháng:</label>
                <select name="month" id="month" class="form-control">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ $m }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label for="year">Chọn năm:</label>
                <select name="year" id="year" class="form-control">
                    @for ($y = 2020; $y <= Carbon\Carbon::now()->year; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Hiển thị</button>
            </div>
        </div>
    </form>

    <!-- Hiển thị doanh thu -->
    <div class="row">
        <!-- Tổng doanh thu -->
        <div class="col-lg-3 col-6">							
            <div class="small-box card bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalRevenueOfMonth) }} VNĐ</h3>
                    <p>Total Revenue Of This Month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
            </div>
        </div>

        <!-- Tổng giá trị nhập hàng -->
        <div class="col-lg-3 col-6">							
            <div class="small-box card bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalImportOfMonth) }} VNĐ</h3>
                    <p>Total Import Of This Month</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection