@extends('layouts.app', ['title' => 'Lịch sử đặt lịch'])

@section('content')
<div class="container">
    <h1 class="section-title">Lịch sử đặt lịch</h1>

    @if($bookings->count() > 0)
        <div class="card" style="padding:0;overflow:hidden">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:var(--cream)">
                        <th style="padding:16px;text-align:left;border-bottom:1px solid var(--border)">Mèo</th>
                        <th style="padding:16px;text-align:left;border-bottom:1px solid var(--border)">Ngày & Giờ</th>
                        <th style="padding:16px;text-align:left;border-bottom:1px solid var(--border)">Thời gian</th>
                        <th style="padding:16px;text-align:left;border-bottom:1px solid var(--border)">Tổng tiền</th>
                        <th style="padding:16px;text-align:left;border-bottom:1px solid var(--border)">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td style="padding:16px;border-bottom:1px solid var(--border)">
                                <div style="display:flex;align-items:center;gap:12px">
                                    <img src="{{ $booking->cat->image ? asset($booking->cat->image) : 'https://via.placeholder.com/50x50' }}"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                                    <div>
                                        <div style="font-weight:600">{{ $booking->cat->name }}</div>
                                        <div style="font-size:12px;color:#666">
                                            {{ $booking->cat->gender === 'male' ? '♂ Đực' : '♀ Cái' }} • {{ $booking->cat->age }} tuổi
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:16px;border-bottom:1px solid var(--border)">
                                <div style="font-weight:600">{{ $booking->booking_date->format('d/m/Y') }}</div>
                                <div style="font-size:14px;color:#666">{{ date('H:i', strtotime($booking->booking_time)) }}</div>
                            </td>
                            <td style="padding:16px;border-bottom:1px solid var(--border)">
                                {{ $booking->duration_hours }} giờ
                            </td>
                            <td style="padding:16px;border-bottom:1px solid var(--border)">
                                <div style="font-weight:600;color:var(--coffee)">
                                    {{ number_format($booking->amount, 0, ',', '.') }}đ
                                </div>
                                <div style="font-size:12px;color:#666">
                                    Đặt cọc: {{ number_format($booking->amount / 2, 0, ',', '.') }}đ
                                </div>
                            </td>
                            <td style="padding:16px;border-bottom:1px solid var(--border)">
                                @if($booking->status === 'pending')
                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge badge-success">Đã xác nhận</span>
                                @else
                                    <span class="badge badge-danger">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:24px;display:flex;justify-content:center">
            {{ $bookings->links('pagination::simple-default') }}
        </div>
    @else
        <div class="card" style="padding:60px;text-align:center">
            <i class="fas fa-calendar-times" style="font-size:64px;color:#ddd;margin-bottom:16px"></i>
            <h3 style="margin-bottom:12px">Chưa có lịch đặt nào</h3>
            <p style="color:#666;margin-bottom:24px">Hãy đặt lịch chơi với mèo yêu thích!</p>
            <a href="{{ route('user.products') }}" class="btn">Xem danh sách mèo</a>
        </div>
    @endif
</div>
@endsection
