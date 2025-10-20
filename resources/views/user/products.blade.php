@extends('layouts.app', ['title' => 'Sản phẩm'])

@section('content')
<div class="container">
    <h1 class="section-title">Sản phẩm của chúng tôi</h1>

    <div class="card" style="padding:20px;margin-bottom:32px">
        <form method="GET" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:12px;align-items:end">
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Tìm kiếm</label>
                <input type="text" name="search" placeholder="Tên sản phẩm..." value="{{ request('search') }}">
            </div>
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Danh mục</label>
                <select name="category_id">
                    <option value="">Tất cả</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Giá từ</label>
                <input type="number" name="price_min" placeholder="0" value="{{ request('price_min') }}" step="1000">
            </div>
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Đến</label>
                <input type="number" name="price_max" placeholder="1000000" value="{{ request('price_max') }}" step="1000">
            </div>
            <button type="submit" class="btn">Lọc</button>
        </form>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="card product-card">
                <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/250x200' }}" alt="{{ $product->name }}">
                <div class="product-info">
                    <span style="font-size:12px;color:#999">{{ $product->category->name }}</span>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-desc">{{ Str::limit($product->description, 60) }}</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                        <span class="product-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        <a href="{{ route('user.products.show', $product) }}" class="btn btn-sm">Chi tiết</a>
                    </div>
                    <form action="{{ route('user.cart.add', $product) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn" style="width:100%;padding:8px;font-size:13px">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p style="text-align:center;color:#999;grid-column:1/-1">Không tìm thấy sản phẩm nào</p>
        @endforelse
    </div>

    <div style="margin-top:32px;display:flex;justify-content:center">
        {{ $products->links('pagination::simple-default') }}
    </div>
</div>

<div style="background:var(--cream);padding:60px 0;margin-top:80px">
    <div class="container">
        <h2 class="section-title">Dịch vụ chơi cùng mèo</h2>
        <p style="text-align:center;color:#666;margin-bottom:32px">Đặt lịch và trải nghiệm thời gian vui vẻ cùng những chú mèo đáng yêu</p>

        <div class="card" style="padding:20px;margin-bottom:32px">
            <form method="GET" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;gap:12px;align-items:end">
                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Giới tính</label>
                    <select name="cat_gender">
                        <option value="">Tất cả</option>
                        <option value="male" {{ request('cat_gender') === 'male' ? 'selected' : '' }}>Đực</option>
                        <option value="female" {{ request('cat_gender') === 'female' ? 'selected' : '' }}>Cái</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Tuổi từ</label>
                    <input type="number" name="cat_age_min" placeholder="0" value="{{ request('cat_age_min') }}">
                </div>
                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Đến</label>
                    <input type="number" name="cat_age_max" placeholder="10" value="{{ request('cat_age_max') }}">
                </div>
                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Giá/giờ</label>
                    <input type="number" name="cat_price_min" placeholder="0" value="{{ request('cat_price_min') }}" step="10000">
                </div>
                <button type="submit" class="btn">Lọc</button>
            </form>
        </div>

        <div class="product-grid">
            @forelse($cats as $cat)
                <div class="card">
                    <img src="{{ $cat->image ? asset($cat->image) : 'https://via.placeholder.com/250x200' }}" alt="{{ $cat->name }}" style="width:100%;height:200px;object-fit:cover">
                    <div class="product-info">
                        <h3 class="product-name">{{ $cat->name }}</h3>
                        <p style="font-size:14px;color:#666;margin-bottom:8px">
                            {{ $cat->gender === 'male' ? '♂ Đực' : '♀ Cái' }} • {{ $cat->age }} tuổi
                        </p>
                        <p class="product-desc">{{ $cat->personality }}</p>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span class="product-price">{{ number_format($cat->price, 0, ',', '.') }}đ/giờ</span>
                            <a href="{{ auth()->check() ? '#' : route('login') }}" class="btn btn-sm" onclick="{{ auth()->check() ? "alert('Tính năng đặt lịch đang phát triển');return false" : '' }}">Đặt lịch</a>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align:center;color:#999;grid-column:1/-1">Hiện chưa có mèo nào</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

