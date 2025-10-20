@extends('layouts.admin', ['title' => 'Quản lý booking mèo'])

@section('content')
<div class="card">
    <h1 class="page-title">Quản lý booking mèo</h1>

    <form method="GET" style="display:grid;grid-template-columns:1fr auto;gap:12px;margin-bottom:20px">
        <select name="status" style="padding:10px 12px;border:1px solid #e6dbd3;border-radius:8px">
            <option value="">-- Tất cả trạng thái --</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
        </select>
        <button type="submit" class="btn">Lọc</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Mã</th>
                <th>Khách hàng</th>
                <th>Mèo</th>
                <th>Ngày</th>
                <th>Giờ</th>
                <th>Thời gian</th>
                <th>Số tiền</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td><strong>#{{ $booking->id }}</strong></td>
                <td>{{ $booking->user->username }}</td>
                <td>{{ $booking->cat->name }}</td>
                <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                <td>{{ $booking->booking_time }}</td>
                <td>{{ $booking->duration_hours }}h</td>
                <td>{{ number_format($booking->amount, 0, ',', '.') }}đ</td>
                <td>
                    @if($booking->status === 'pending')
                        <span class="badge badge-warning">Chờ xác nhận</span>
                    @elseif($booking->status === 'confirmed')
                        <span class="badge badge-success">Đã xác nhận</span>
                    @else
                        <span class="badge badge-danger">Đã hủy</span>
                    @endif
                </td>
                <td>
                    <div class="actions">
                        @if($booking->status === 'pending')
                            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-sm">Xác nhận</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display:inline" onsubmit="return confirm('Xóa booking này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;color:#999">Chưa có booking nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $bookings->links('pagination::simple-default') }}
    </div>
</div>
@endsection

