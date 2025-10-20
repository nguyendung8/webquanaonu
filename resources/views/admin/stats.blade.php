@extends('layouts.admin', ['title' => 'Thống kê doanh thu'])

@section('content')
<div class="card">
    <h1 class="page-title">Thống kê doanh thu năm {{ $currentYear }}</h1>

    <div style="margin-bottom:30px">
        <canvas id="revenueChart" style="max-height:400px"></canvas>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Đơn hàng (số lượng)</th>
                <th>Doanh thu đơn hàng</th>
                <th>Booking (số lượng)</th>
                <th>Doanh thu booking</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRevenue = 0; @endphp
            @foreach($monthlyData as $data)
                @php
                    $monthTotal = $data['orders_revenue'] + $data['bookings_revenue'];
                    $totalRevenue += $monthTotal;
                @endphp
                <tr>
                    <td><strong>{{ $data['month_name'] }}</strong></td>
                    <td>{{ $data['orders_count'] }}</td>
                    <td>{{ number_format($data['orders_revenue'], 0, ',', '.') }}đ</td>
                    <td>{{ $data['bookings_count'] }}</td>
                    <td>{{ number_format($data['bookings_revenue'], 0, ',', '.') }}đ</td>
                    <td><strong>{{ number_format($monthTotal, 0, ',', '.') }}đ</strong></td>
                </tr>
            @endforeach
            <tr style="background:var(--cream);font-weight:700">
                <td>Tổng cộng</td>
                <td>{{ collect($monthlyData)->sum('orders_count') }}</td>
                <td>{{ number_format(collect($monthlyData)->sum('orders_revenue'), 0, ',', '.') }}đ</td>
                <td>{{ collect($monthlyData)->sum('bookings_count') }}</td>
                <td>{{ number_format(collect($monthlyData)->sum('bookings_revenue'), 0, ',', '.') }}đ</td>
                <td>{{ number_format($totalRevenue, 0, ',', '.') }}đ</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthlyData, 'month_name')),
        datasets: [
            {
                label: 'Doanh thu đơn hàng',
                data: @json(array_column($monthlyData, 'orders_revenue')),
                borderColor: '#6f4e37',
                backgroundColor: 'rgba(111, 78, 55, 0.1)',
                tension: 0.3
            },
            {
                label: 'Doanh thu booking mèo',
                data: @json(array_column($monthlyData, 'bookings_revenue')),
                borderColor: '#3a5a40',
                backgroundColor: 'rgba(58, 90, 64, 0.1)',
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Biểu đồ doanh thu theo tháng' }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});
</script>
@endsection

