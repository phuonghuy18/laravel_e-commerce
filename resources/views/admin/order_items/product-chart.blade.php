@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Thống kê số lượng bán ra theo danh mục</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="chartTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="male-tab" data-toggle="tab" href="#male" role="tab">Nước hoa nam</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="female-tab" data-toggle="tab" href="#female" role="tab">Nước hoa nữ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="unisex-tab" data-toggle="tab" href="#unisex" role="tab">Unisex</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="chartTabContent">
            <div class="tab-pane fade show active" id="male" role="tabpanel">
                <canvas id="malePerfumeChart" style="max-width: 80%; height: 400px;"></canvas>
            </div>
            <div class="tab-pane fade" id="female" role="tabpanel">
                <canvas id="femalePerfumeChart" style="max-width: 80%; height: 400px;"></canvas>
            </div>
            <div class="tab-pane fade" id="unisex" role="tabpanel">
                <canvas id="unisexPerfumeChart" style="max-width: 80%; height: 400px;"></canvas>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const maleData = {!! json_encode($maleData) !!};
    const femaleData = {!! json_encode($femaleData) !!};
    const unisexData = {!! json_encode($unisexData) !!};

    let maleChartInstance = null;
    let femaleChartInstance = null;
    let unisexChartInstance = null;

    function renderChart(ctx, data, chartInstance) {
        if (chartInstance) {
            chartInstance.destroy(); // Xóa biểu đồ cũ trước khi vẽ biểu đồ mới
        }
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.name),
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: data.map(item => item.total_qty),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : null;
                            }
                        }
                    }
                }
            }
        });
    }

    // Render chart for each tab when clicked
    document.getElementById('male-tab').addEventListener('click', function() {
        const ctx = document.getElementById('malePerfumeChart').getContext('2d');
        maleChartInstance = renderChart(ctx, maleData, maleChartInstance);
    });

    document.getElementById('female-tab').addEventListener('click', function() {
        const ctx = document.getElementById('femalePerfumeChart').getContext('2d');
        femaleChartInstance = renderChart(ctx, femaleData, femaleChartInstance);
    });

    document.getElementById('unisex-tab').addEventListener('click', function() {
        const ctx = document.getElementById('unisexPerfumeChart').getContext('2d');
        unisexChartInstance = renderChart(ctx, unisexData, unisexChartInstance);
    });

    // Khởi tạo biểu đồ cho tab đầu tiên (nếu cần)
    document.getElementById('male-tab').click();
});

</script>
@endsection

