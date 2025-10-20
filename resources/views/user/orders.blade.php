@extends('layouts.app', ['title' => 'Đơn hàng của tôi'])

@section('content')
<div class="container">
    <h1 class="section-title">Đơn hàng của tôi</h1>

    @forelse($orders as $order)
        <div class="card" style="margin-bottom:20px;padding:24px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;padding-bottom:16px;border-bottom:2px solid var(--border)">
                <div>
                    <h3 style="color:var(--coffee)">Đơn hàng #{{ $order->id }}</h3>
                    <p style="color:#666;font-size:14px">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div style="text-align:right">
                    @if($order->status === 'pending')
                        <span class="badge" style="background:#fff3cd;color:#856404;padding:6px 12px;border-radius:999px;font-size:14px">Chờ xử lý</span>
                    @elseif($order->status === 'completed')
                        <span class="badge" style="background:#d4edda;color:#155724;padding:6px 12px;border-radius:999px;font-size:14px">Hoàn thành</span>
                    @else
                        <span class="badge" style="background:#f8d7da;color:#721c24;padding:6px 12px;border-radius:999px;font-size:14px">Đã hủy</span>
                    @endif
                    <div style="font-size:24px;color:var(--coffee);font-weight:700;margin-top:8px">
                        {{ number_format($order->total, 0, ',', '.') }}đ
                    </div>
                </div>
            </div>

            <div style="margin-bottom:16px">
                <strong style="display:block;margin-bottom:12px">Chi tiết sản phẩm:</strong>
                @foreach($order->items as $item)
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0f0">
                        <span>{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }} x{{ $item->quantity }}</span>
                        <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="card" style="padding:60px;text-align:center">
            <i class="fas fa-shopping-bag" style="font-size:64px;color:#ddd;margin-bottom:16px"></i>
            <h3 style="margin-bottom:12px">Chưa có đơn hàng nào</h3>
            <p style="color:#666;margin-bottom:24px">Hãy khám phá và đặt hàng ngay!</p>
            <a href="{{ route('user.products') }}" class="btn">Mua sắm ngay</a>
        </div>
    @endforelse

    @if($orders->count() > 0)
        <div style="margin-top:32px;display:flex;justify-content:center">
            {{ $orders->links('pagination::simple-default') }}
        </div>
    @endif
</div>
@endsection

