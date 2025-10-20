@extends('layouts.admin', ['title' => 'Chi tiết đơn hàng'])

@section('content')
<div class="card">
    <div style="margin-bottom:20px">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Quay lại</a>
    </div>

    <h1 class="page-title">Đơn hàng #{{ $order->id }}</h1>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:30px">
        <div>
            <h3 style="margin-bottom:12px">Thông tin khách hàng</h3>
            <p><strong>Tên:</strong> {{ $order->user->username }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <h3 style="margin-bottom:12px">Thông tin đơn hàng</h3>
            <p><strong>Trạng thái:</strong>
                @if($order->status === 'pending')
                    <span class="badge badge-warning">Chờ xử lý</span>
                @elseif($order->status === 'completed')
                    <span class="badge badge-success">Hoàn thành</span>
                @else
                    <span class="badge badge-danger">Đã hủy</span>
                @endif
            </p>
            <p><strong>Tổng tiền:</strong> <span style="font-size:20px;color:var(--coffee)">{{ number_format($order->total, 0, ',', '.') }}đ</span></p>
        </div>
    </div>

    <h3 style="margin-bottom:16px">Chi tiết sản phẩm</h3>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach
            <tr style="font-weight:700;background:var(--cream)">
                <td colspan="3">Tổng cộng</td>
                <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

