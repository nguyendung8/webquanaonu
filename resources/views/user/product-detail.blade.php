@extends('layouts.app', ['title' => $product->name])

@section('content')
<div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:60px">
        <div>
            <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/600x600' }}" alt="{{ $product->name }}" style="width:100%;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.1)">
        </div>
        <div>
            <p style="color:#999;margin-bottom:8px">{{ $product->category->name }}</p>
            <h1 style="font-size:36px;color:var(--coffee);margin-bottom:16px">{{ $product->name }}</h1>
            <div style="font-size:32px;color:var(--coffee);font-weight:700;margin-bottom:24px">
                {{ number_format($product->price, 0, ',', '.') }}đ
            </div>

            @if($product->availability)
                <span style="display:inline-block;padding:6px 12px;background:#d4edda;color:#155724;border-radius:999px;font-size:14px;margin-bottom:24px">Còn hàng</span>
            @else
                <span style="display:inline-block;padding:6px 12px;background:#f8d7da;color:#721c24;border-radius:999px;font-size:14px;margin-bottom:24px">Hết hàng</span>
            @endif

            <div style="margin-bottom:32px">
                <h3 style="margin-bottom:12px;color:var(--dark)">Mô tả sản phẩm</h3>
                <p style="color:#666;line-height:1.8">{{ $product->description ?? 'Chưa có mô tả' }}</p>
            </div>

            @if($product->availability)
                <form action="{{ route('user.cart.add', $product) }}" method="POST" style="display:flex;gap:12px;align-items:center">
                    @csrf
                    <label style="font-weight:600">Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1" max="10" style="width:80px">
                    <button type="submit" class="btn">Thêm vào giỏ hàng</button>
                </form>
            @endif
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="section">
            <h2 class="section-title">Sản phẩm liên quan</h2>
            <div class="product-grid">
                @foreach($relatedProducts as $related)
                    <div class="card product-card">
                        <img src="{{ $related->image ? asset($related->image) : 'https://via.placeholder.com/250x200' }}" alt="{{ $related->name }}">
                        <div class="product-info">
                            <h3 class="product-name">{{ $related->name }}</h3>
                            <p class="product-desc">{{ Str::limit($related->description, 60) }}</p>
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span class="product-price">{{ number_format($related->price, 0, ',', '.') }}đ</span>
                                <a href="{{ route('user.products.show', $related) }}" class="btn btn-sm">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

