@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<h1 class="page-title">Dashboard</h1>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
    <div class="card" style="text-align:center;padding:24px">
        <div style="font-size:32px;font-weight:700;color:var(--coffee)">{{ $stats['products'] }}</div>
        <div style="color:#666;margin-top:8px">Sản phẩm</div>
    </div>
    <div class="card" style="text-align:center;padding:24px">
        <div style="font-size:32px;font-weight:700;color:var(--leaf)">{{ $stats['categories'] }}</div>
        <div style="color:#666;margin-top:8px">Danh mục</div>
    </div>
    <div class="card" style="text-align:center;padding:24px">
        <div style="font-size:32px;font-weight:700;color:var(--accent)">{{ $stats['orders'] }}</div>
        <div style="color:#666;margin-top:8px">Đơn hàng</div>
    </div>
    <div class="card" style="text-align:center;padding:24px">
        <div style="font-size:32px;font-weight:700;color:#8b5cf6">{{ $stats['customers'] }}</div>
        <div style="color:#666;margin-top:8px">Khách hàng</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
    <div class="card">
        <h3 style="margin-bottom:16px;color:var(--coffee)">Doanh thu</h3>
        <p><strong>Đơn hàng:</strong> {{ number_format($stats['revenue_orders'], 0, ',', '.') }}đ</p>
        <p style="font-size:18px;font-weight:700;margin-top:12px;color:var(--coffee)">
            Tổng: {{ number_format($stats['revenue_orders'], 0, ',', '.') }}đ
        </p>
    </div>
    <div class="card">
        <h3 style="margin-bottom:16px;color:#f59e0b">Chờ xử lý</h3>
        <p><strong>Đơn hàng:</strong> {{ $stats['pending_orders'] }}</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:16px">
    <div class="card">
        <h3 style="margin-bottom:16px">Đơn hàng gần đây</h3>
        @forelse($recentOrders as $order)
            <div style="padding:8px 0;border-bottom:1px solid var(--border)">
                <strong>#{{ $order->id }}</strong> - {{ $order->user->username }} - {{ number_format($order->total, 0, ',', '.') }}đ
            </div>
        @empty
            <p style="color:#999">Chưa có đơn hàng nào</p>
        @endforelse
    </div>
</div>
@endsection

