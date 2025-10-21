@extends('layouts.admin', ['title' => 'Quản lý đơn hàng'])

@section('content')
<div class="card">
    <h1 class="page-title">Quản lý đơn hàng</h1>

    <form method="GET" style="display:grid;grid-template-columns:1fr auto;gap:12px;margin-bottom:20px">
        <select name="status" style="padding:10px 12px;border:1px solid #e6dbd3;border-radius:8px">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
        </select>
        <button type="submit" class="btn">Lọc</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Ngày đặt</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td><strong>#{{ $order->id }}</strong></td>
                <td>{{ $order->user->username }}</td>
                <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                <td>
                    @if($order->status === 'pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                    @elseif($order->status === 'completed')
                        <span class="badge badge-success">Xác nhận</span>
                    @else
                        <span class="badge badge-danger">Đã hủy</span>
                    @endif
                </td>
                <td>
                    @if($order->payment_method === 'TRANSFER')
                        <span class="badge" style="background:#e7f5ff;color:#1c7ed6">Chuyển khoản</span>
                    @else
                        <span class="badge" style="background:#fff3bf;color:#a68000">COD</span>
                    @endif
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary">Chi tiết</a>
                        @if($order->status === 'pending')
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-sm" onclick="return confirm('Xác nhận hoàn thành đơn hàng?')">Hoàn thành</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline" onsubmit="return confirm('Xóa đơn hàng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#999">Chưa có đơn hàng nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $orders->links('pagination::simple-default') }}
    </div>
</div>
@endsection

