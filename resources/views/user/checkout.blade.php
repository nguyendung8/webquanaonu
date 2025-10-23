@extends('layouts.app', ['title' => 'Thanh toán'])

@section('content')
<div class="container">
    <h1 class="section-title">Thanh toán</h1>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
        <div>
            <div class="card" style="padding:20px;margin-bottom:16px">
                <h3 style="margin-bottom:12px;color:var(--coffee)">Thông tin giao hàng</h3>
                
                @if(auth()->user()->address)
                    <div style="background:#f8f9fa;padding:16px;border-radius:8px;margin-bottom:16px">
                        <h4 style="margin-bottom:8px;color:var(--coffee)">Địa chỉ giao hàng</h4>
                        <p style="margin:4px 0;color:#666"><strong>Tên:</strong> {{ auth()->user()->username }}</p>
                        @if(auth()->user()->phone)
                            <p style="margin:4px 0;color:#666"><strong>SĐT:</strong> {{ auth()->user()->phone }}</p>
                        @endif
                        <p style="margin:4px 0;color:#666"><strong>Địa chỉ:</strong> {{ auth()->user()->address }}</p>
                    </div>
                    <div style="text-align:center">
                        <a href="{{ route('user.profile') }}" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:8px">
                            <i class="fas fa-edit"></i> Chỉnh sửa địa chỉ
                        </a>
                    </div>
                @else
                    <div style="background:#fff3cd;padding:16px;border-radius:8px;margin-bottom:16px;border-left:4px solid #ffc107">
                        <h4 style="margin-bottom:8px;color:#856404">Chưa có địa chỉ giao hàng</h4>
                        <p style="margin:0;color:#856404">Vui lòng cập nhật địa chỉ giao hàng để tiếp tục đặt hàng.</p>
                    </div>
                    <div style="text-align:center">
                        <a href="{{ route('user.profile') }}" class="btn" style="display:inline-flex;align-items:center;gap:8px">
                            <i class="fas fa-plus"></i> Thêm địa chỉ giao hàng
                        </a>
                    </div>
                @endif
            </div>

            <div class="card" style="padding:20px">
                <h3 style="margin-bottom:12px;color:var(--coffee)">Phương thức thanh toán</h3>

                <form action="{{ route('user.checkout.place') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                            <input type="radio" name="payment_method" value="COD" checked onclick="toggleTransfer(false)">
                            <span><i class="fas fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:10px">
                            <input type="radio" name="payment_method" value="TRANSFER" onclick="toggleTransfer(true)">
                            <span><i class="fas fa-building-columns"></i> Chuyển khoản ngân hàng</span>
                        </label>
                    </div>

                    <div id="transferBox" style="display:none;margin-top:16px">
                        <div class="card" style="padding:16px;background:#fffdf9">
                            <h4 style="margin-bottom:10px">Thông tin chuyển khoản</h4>
                            <ul style="list-style:none;padding:0;margin:0;color:#4a3a33">
                                <li><strong>Ngân hàng:</strong>Vietcombank</li>
                                <li><strong>Chủ TK:</strong> HA THI ANH NGOC</li>
                                <li><strong>Số TK:</strong> 1023198407</li>
                                <li><strong>Nội dung:</strong> Shop-{{ auth()->id() }}-{{ now()->format('ymdHis') }}</li>
                            </ul>
                            <div style="display:flex;gap:16px;align-items:center;margin-top:12px">
                                <div>
                                    <img src="{{ asset('images/qr_code.png') }}" alt="QR" style="width:160px;height:160px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
                                </div>
                                <div style="flex:1">
                                    <label for="payment_img" style="font-weight:600;margin-bottom:8px;display:block">Upload ảnh chuyển khoản</label>
                                    <input type="file" name="payment_img" id="payment_img" accept="image/*">
                                    <small style="display:block;color:#666;margin-top:6px">Chấp nhận: jpg, jpeg, png, webp (tối đa 4MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert" style="margin-top:12px">{{ $errors->first() }}</div>
                    @endif

                    <button type="submit" class="btn" style="margin-top:16px">
                        <i class="fas fa-check"></i> Đặt hàng
                    </button>
                </form>
            </div>

            <div class="card" style="padding:20px;margin-top:16px">
                <h3 style="margin-bottom:12px;color:var(--coffee)">Sản phẩm</h3>
                @foreach($cartItems as $item)
                    <div style="display:flex;gap:12px;align-items:center;margin-bottom:12px">
                        <img src="{{ $item->product->image ? asset($item->product->image) : 'https://via.placeholder.com/64' }}" style="width:64px;height:64px;object-fit:cover;border-radius:8px">
                        <div style="flex:1">
                            <div style="font-weight:600">{{ $item->product->name }}</div>
                            <div style="color:#666;font-size:13px">x{{ $item->quantity }}</div>
                        </div>
                        <div style="font-weight:700;color:var(--coffee)">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <div class="card" style="padding:20px;position:sticky;top:80px">
                <h3 style="margin-bottom:12px;color:var(--coffee)">Tổng đơn</h3>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span>Tạm tính</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                    <span>Vận chuyển</span>
                    <span>Miễn phí</span>
                </div>
                <div style="border-top:2px solid var(--border);padding-top:10px;margin-top:10px;display:flex;justify-content:space-between;font-weight:700;color:var(--coffee);font-size:18px">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTransfer(show) {
    var box = document.getElementById('transferBox');
    var file = document.getElementById('payment_img');
    box.style.display = show ? 'block' : 'none';
    if (show) {
        file.setAttribute('required', 'required');
    } else {
        file.removeAttribute('required');
        file.value = '';
    }
}

// ensure correct state on load (in case of back/validation error)
document.addEventListener('DOMContentLoaded', function() {
    var selected = document.querySelector('input[name="payment_method"]:checked');
    toggleTransfer(selected && selected.value === 'TRANSFER');
});
</script>
@endpush


