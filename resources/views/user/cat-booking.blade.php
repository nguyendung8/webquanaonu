@extends('layouts.app', ['title' => 'Đặt lịch mèo'])

@section('content')
<div class="container">
    <h1 class="section-title">Đặt lịch chơi với {{ $cat->name }}</h1>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px">
        <!-- Cat Info -->
        <div class="card" style="padding:24px">
            <h3 style="margin-bottom:16px;color:var(--coffee)">Thông tin mèo</h3>
            <img src="{{ $cat->image ? asset($cat->image) : 'https://via.placeholder.com/300x200' }}"
                 alt="{{ $cat->name }}"
                 style="width:100%;height:200px;object-fit:cover;border-radius:8px;margin-bottom:16px">
            <h4 style="margin-bottom:8px">{{ $cat->name }}</h4>
            <p style="color:#666;margin-bottom:8px">
                {{ $cat->gender === 'male' ? '♂ Đực' : '♀ Cái' }} • {{ $cat->age }} tuổi
            </p>
            <p style="color:#666;margin-bottom:12px">{{ $cat->personality }}</p>
            <div style="font-size:20px;font-weight:700;color:var(--coffee)">
                {{ number_format($cat->price, 0, ',', '.') }}đ/giờ
            </div>
        </div>

        <!-- Booking Form -->
        <div class="card" style="padding:24px">
            <h3 style="margin-bottom:16px;color:var(--coffee)">Thông tin đặt lịch</h3>

            <form action="{{ route('user.cats.book.store', $cat) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                    <div>
                        <label style="display:block;margin-bottom:6px;font-weight:600">Ngày đặt lịch *</label>
                        <input type="date" name="booking_date" required min="{{ date('Y-m-d') }}" value="{{ old('booking_date') }}"
                               style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px;background:#fff">
                    </div>
                    <div>
                        <label style="display:block;margin-bottom:6px;font-weight:600">Giờ bắt đầu *</label>
                        <select name="booking_time" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px;background:#fff">
                            <option value="">-- Chọn giờ --</option>
                            @for($hour = 8; $hour <= 20; $hour++)
                                <option value="{{ sprintf('%02d:00:00', $hour) }}" {{ old('booking_time') == sprintf('%02d:00:00', $hour) ? 'selected' : '' }}>
                                    {{ sprintf('%02d:00', $hour) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:16px">
                    <label style="display:block;margin-bottom:6px;font-weight:600">Số giờ chơi *</label>
                    <select name="duration_hours" required onchange="calculatePrice()" style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px;background:#fff">
                        <option value="">-- Chọn số giờ --</option>
                        @for($hours = 1; $hours <= 8; $hours++)
                            <option value="{{ $hours }}" {{ old('duration_hours') == $hours ? 'selected' : '' }}>{{ $hours }} giờ</option>
                        @endfor
                    </select>
                </div>

                <div style="background:#f8f9fa;padding:16px;border-radius:8px;margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                        <span>Giá/giờ:</span>
                        <span>{{ number_format($cat->price, 0, ',', '.') }}đ</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                        <span>Số giờ:</span>
                        <span id="hours-display">0 giờ</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                        <span>Tổng tiền:</span>
                        <span id="total-amount">0đ</span>
                    </div>
                    <div style="border-top:1px solid #dee2e6;padding-top:8px;display:flex;justify-content:space-between;font-weight:700;color:var(--coffee)">
                        <span>Đặt cọc (50%):</span>
                        <span id="deposit-amount">0đ</span>
                    </div>
                </div>

                <div style="margin-bottom:16px">
                    <label style="display:block;margin-bottom:6px;font-weight:600">Ảnh đặt cọc *</label>
                    <input type="file" name="payment_img" required accept="image/*" style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px;background:#fff">
                    <small style="color:#666;display:block;margin-top:4px">Chụp ảnh chuyển khoản đặt cọc (jpg, png, webp - tối đa 4MB)</small>
                </div>

                @if ($errors->any())
                    <div class="alert" style="margin-bottom:16px">{{ $errors->first() }}</div>
                @endif

                <button type="submit" class="btn" style="width:100%">
                    <i class="fas fa-calendar-check"></i> Đặt lịch
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const catPrice = {{ $cat->price }};

function calculatePrice() {
    const hours = parseInt(document.querySelector('select[name="duration_hours"]').value) || 0;
    const total = catPrice * hours;
    const deposit = total / 2;

    document.getElementById('hours-display').textContent = hours + ' giờ';
    document.getElementById('total-amount').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    document.getElementById('deposit-amount').textContent = new Intl.NumberFormat('vi-VN').format(deposit) + 'đ';
}

// Calculate on page load
document.addEventListener('DOMContentLoaded', calculatePrice);
</script>
@endpush
