@extends('layouts.admin', ['title' => 'Chi tiết đơn hàng'])

@section('content')
<div class="card">
    <div style="margin-bottom:20px">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Quay lại</a>
        <a href="{{ route('admin.orders.pdf', $order) }}" class="btn" style="margin-left:12px;display:inline-flex;align-items:center;gap:8px">
            <i class="fas fa-file-pdf"></i>
            Xuất PDF
        </a>
    </div>

    <h1 class="page-title">Đơn hàng #{{ $order->id }}</h1>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:30px">
        <div>
            <h3 style="margin-bottom:12px">Thông tin khách hàng</h3>
            <p><strong>Tên:</strong> {{ $order->user->username }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            @if($order->user->phone)
                <p><strong>Số điện thoại:</strong> {{ $order->user->phone }}</p>
            @endif
            @if($order->user->address)
                <p><strong>Địa chỉ giao hàng:</strong> {{ $order->user->address }}</p>
            @endif
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

            <p>
                <strong>Phương thức thanh toán:</strong>
                @if($order->payment_method === 'TRANSFER')
                    <span class="badge" style="background:#e7f5ff;color:#1c7ed6">Chuyển khoản</span>
                @else
                    <span class="badge" style="background:#fff3bf;color:#a68000">COD</span>
                @endif
            </p>

            @if($order->payment_method === 'TRANSFER')
                <div style="margin-top:8px">
                    <strong>Ảnh chuyển khoản:</strong>
                    @if(!empty($order->payment_img))
                        <div style="margin-top:8px">
                            <a href="{{ asset($order->payment_img) }}" target="_blank" title="Xem ảnh gốc">
                                <img src="{{ asset($order->payment_img) }}" alt="Ảnh chuyển khoản" style="max-width:260px;border-radius:8px;border:1px solid var(--border)">
                            </a>
                        </div>
                    @else
                        <div class="alert alert-error" style="margin-top:8px">Chưa có ảnh xác nhận chuyển khoản.</div>
                    @endif
                </div>
            @endif

            <p style="margin-top:10px"><strong>Tổng tiền:</strong> <span style="font-size:20px;color:var(--coffee)">{{ number_format($order->total, 0, ',', '.') }}đ</span></p>
        </div>
    </div>

    <h3 style="margin-bottom:16px">Chi tiết sản phẩm</h3>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Size/Màu</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}</td>
                <td>
                    @if($item->size || $item->color)
                        <div style="font-size:12px;color:#666">
                            @if($item->size)
                                <span>Size: {{ $item->size }}</span>
                            @endif
                            @if($item->size && $item->color)
                                <br>
                            @endif
                            @if($item->color)
                                <span>Màu: {{ $item->color }}</span>
                            @endif
                        </div>
                    @else
                        <span style="color:#999">-</span>
                    @endif
                </td>
                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
            </tr>
            @endforeach
            <tr style="font-weight:700;background:var(--cream)">
                <td colspan="4">Tổng cộng</td>
                <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

