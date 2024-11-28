@extends('admin.layouts.app')

@section('content')

<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Biểu đồ nhập kho năm: {{ $year }}</h1>
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
            <canvas style="max-width: 100%; height: 400px;" id="receiptChart"></canvas>
        </div>
        <div class="col-2">
            <form method="GET" action="{{ route('admin.getMonthlyReceipt') }}">
                <label for="year">Năm:</label>
                <select name="year" id="year">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                     @endfor
                </select>
                <button class="btn btn-primary" type="submit">Xem Phiếu nhập</button>
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
    const monthlyReceipt = @json($monthlyReceipt);

const ctx = document.getElementById('receiptChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: `Receipt for {{ $year }}`,
            data: monthlyReceipt,
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: false,
            tension: 0.3,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Monthly Receipt Overview'
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return `Receipt: ${tooltipItem.raw.toLocaleString()} VND`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Receipts (VND)'
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