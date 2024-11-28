@extends('admin.layouts.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Biểu đồ doanh thu năm: {{ $year }}</h1>
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
        <div class="row">

        
        <div class="col-10">
            <canvas style="max-width: 100%; height: 400px;" id="revenueChart"></canvas>
        </div>
        <div class="col-2">
            <form method="GET" action="{{ route('admin.getMonthlyRevenue') }}">
                <label for="year">Năm:</label>
                <select name="year" id="year">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                     @endfor
                </select>
                <button class="btn btn-primary" type="submit">Xem Doanh Thu</button>
            </form>
        </div>
    </div>
        
    </div>
   
    <!-- /.card -->
</section>
    

   
</div>
@endsection

@section('customJs')
<script>
    // Dữ liệu từ Controller
    const monthlyRevenue = @json($monthlyRevenue);

    // Thiết lập đồ thị với Chart.js
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: `Revenue for {{ $year }}`,
                data: monthlyRevenue,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });
</script>
@endsection