@extends('layouts.admin', ['title' => 'Chi tiết booking'])

@section('content')
<div class="card">
    <div style="margin-bottom:20px">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">← Quay lại</a>
    </div>

    <h1 class="page-title">Booking #{{ $booking->id }}</h1>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:30px">
        <div>
            <h3 style="margin-bottom:12px">Thông tin khách hàng</h3>
            <p><strong>Tên:</strong> {{ $booking->user->username }}</p>
            <p><strong>Email:</strong> {{ $booking->user->email }}</p>
            <p><strong>Ngày đặt:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <h3 style="margin-bottom:12px">Thông tin mèo</h3>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                @if($booking->cat->image)
                    <img src="{{ asset($booking->cat->image) }}" alt="{{ $booking->cat->name }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                @endif
                <div>
                    <p><strong>Tên:</strong> {{ $booking->cat->name }}</p>
                    <p><strong>Giới tính:</strong> {{ $booking->cat->gender === 'male' ? '♂ Đực' : '♀ Cái' }}</p>
                    <p><strong>Tuổi:</strong> {{ $booking->cat->age }} tuổi</p>
                </div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:30px">
        <div>
            <h3 style="margin-bottom:12px">Thông tin booking</h3>
            <p><strong>Ngày chơi:</strong> {{ $booking->booking_date->format('d/m/Y') }}</p>
            <p><strong>Giờ bắt đầu:</strong> {{ date('H:i', strtotime($booking->booking_time)) }}</p>
            <p><strong>Thời gian:</strong> {{ $booking->duration_hours }} giờ</p>
            <p><strong>Giờ kết thúc:</strong> {{ date('H:i', strtotime($booking->booking_time . ' +' . $booking->duration_hours . ' hours')) }}</p>
        </div>
        <div>
            <h3 style="margin-bottom:12px">Thông tin thanh toán</h3>
            <p><strong>Trạng thái:</strong>
                @if($booking->status === 'pending')
                    <span class="badge badge-warning">Chờ xác nhận</span>
                @elseif($booking->status === 'confirmed')
                    <span class="badge badge-success">Đã xác nhận</span>
                @else
                    <span class="badge badge-danger">Đã hủy</span>
                @endif
            </p>
            <p><strong>Tổng tiền:</strong> <span style="font-size:20px;color:var(--coffee)">{{ number_format($booking->amount, 0, ',', '.') }}đ</span></p>
            <p><strong>Đặt cọc (50%):</strong> <span style="font-size:18px;color:var(--coffee)">{{ number_format($booking->amount / 2, 0, ',', '.') }}đ</span></p>
        </div>
    </div>

    @if($booking->payment_img)
        <div style="margin-bottom:30px">
            <h3 style="margin-bottom:12px">Ảnh đặt cọc</h3>
            <div style="display:flex;gap:16px;align-items:flex-start">
                <div>
                    <a href="{{ asset($booking->payment_img) }}" target="_blank" title="Xem ảnh gốc">
                        <img src="{{ asset($booking->payment_img) }}" alt="Ảnh đặt cọc" style="max-width:300px;border-radius:8px;border:1px solid var(--border);cursor:pointer">
                    </a>
                </div>
                <div style="flex:1">
                    <p style="color:#666;margin-bottom:8px">Ảnh chuyển khoản đặt cọc từ khách hàng</p>
                    <p style="font-size:14px;color:#999">Click vào ảnh để xem kích thước gốc</p>
                </div>
            </div>
        </div>
    @endif

    @if($booking->cat->personality)
        <div style="margin-bottom:30px">
            <h3 style="margin-bottom:12px">Tính cách mèo</h3>
            <p style="color:#666;padding:16px;background:#f8f9fa;border-radius:8px">{{ $booking->cat->personality }}</p>
        </div>
    @endif

    <div style="display:flex;gap:12px;justify-content:flex-end">
        @if($booking->status === 'pending')
            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" style="display:inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="btn" onclick="return confirm('Xác nhận booking này?')">
                    <i class="fas fa-check"></i> Xác nhận
                </button>
            </form>
            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" style="display:inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Hủy booking này?')">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </form>
        @elseif($booking->status === 'confirmed')
            <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" style="display:inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Hủy booking đã xác nhận?')">
                    <i class="fas fa-times"></i> Hủy
                </button>
            </form>
        @endif

        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display:inline" onsubmit="return confirm('Xóa booking này?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Xóa
            </button>
        </form>
    </div>
</div>
@endsection
