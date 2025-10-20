@extends('layouts.app', ['title' => 'Giỏ hàng'])

@section('content')
<div class="container">
    <h1 class="section-title">Giỏ hàng của bạn</h1>

    @if($cartItems->count() > 0)
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px">
            <div>
                @foreach($cartItems as $item)
                    <div class="card" style="display:flex;gap:20px;padding:20px;margin-bottom:16px">
                        <img src="{{ $item->product->image ? asset($item->product->image) : 'https://via.placeholder.com/120x120' }}" alt="{{ $item->product->name }}" style="width:120px;height:120px;object-fit:cover;border-radius:8px">
                        <div style="flex:1">
                            <h3 style="margin-bottom:8px">{{ $item->product->name }}</h3>
                            <p style="color:var(--coffee);font-size:20px;font-weight:700;margin-bottom:12px">
                                {{ number_format($item->product->price, 0, ',', '.') }}đ
                            </p>
                            <div style="display:flex;gap:12px;align-items:center">
                                <form action="{{ route('user.cart.update', $item) }}" method="POST" style="display:flex;gap:8px;align-items:center">
                                    @csrf
                                    @method('PATCH')
                                    <label style="font-size:14px">Số lượng:</label>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" style="width:70px;padding:6px" onchange="this.form.submit()">
                                </form>
                                <form action="{{ route('user.cart.destroy', $item) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#e63946;cursor:pointer;font-size:14px">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div style="text-align:right">
                            <strong style="font-size:18px;color:var(--coffee)">
                                {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}đ
                            </strong>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <div class="card" style="padding:24px;position:sticky;top:80px">
                    <h3 style="margin-bottom:20px;color:var(--coffee)">Tổng đơn hàng</h3>
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div style="border-top:2px solid var(--border);padding-top:12px;margin-top:12px">
                        <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:var(--coffee)">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    <button class="btn" style="width:100%;margin-top:24px" onclick="alert('Tính năng thanh toán đang phát triển')">Thanh toán</button>
                    <a href="{{ route('user.products') }}" class="btn btn-outline" style="width:100%;margin-top:12px;text-align:center">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @else
        <div class="card" style="padding:60px;text-align:center">
            <i class="fas fa-shopping-cart" style="font-size:64px;color:#ddd;margin-bottom:16px"></i>
            <h3 style="margin-bottom:12px">Giỏ hàng trống</h3>
            <p style="color:#666;margin-bottom:24px">Hãy thêm sản phẩm vào giỏ hàng!</p>
            <a href="{{ route('user.products') }}" class="btn">Mua sắm ngay</a>
        </div>
    @endif
</div>
@endsection

