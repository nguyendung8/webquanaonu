@extends('layouts.app', ['title' => 'Sản phẩm'])

@section('content')
<div class="container">
    <h1 class="section-title">Thời trang nữ cao cấp</h1>

    <div class="card" style="padding:20px;margin-bottom:32px">
        <form method="GET" style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:12px;align-items:end">
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
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Size</label>
                <select name="size">
                    <option value="">Tất cả</option>
                    <option value="XS" {{ request('size') === 'XS' ? 'selected' : '' }}>XS</option>
                    <option value="S" {{ request('size') === 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ request('size') === 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ request('size') === 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ request('size') === 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="XXL" {{ request('size') === 'XXL' ? 'selected' : '' }}>XXL</option>
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Màu sắc</label>
                <input type="text" name="color" placeholder="Tên màu..." value="{{ request('color') }}">
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

                    @if($product->sizes->count() > 0)
                        <div style="margin:8px 0">
                            <small style="color:#666">Size: {{ $product->sizes->pluck('size')->implode(', ') }}</small>
                        </div>
                    @endif

                    @if($product->colors->count() > 0)
                        <div style="margin:8px 0">
                            <small style="color:#666">Màu:
                                @foreach($product->colors as $color)
                                    @php
                                        // Always use mapping logic, ignore color_code from database
                                        $colorMap = [
                                            'pink' => '#ffc0cb', 'white' => '#ffffff', 'blue' => '#0000ff',
                                            'red' => '#ff0000', 'black' => '#000000', 'yellow' => '#ffff00',
                                            'green' => '#008000', 'purple' => '#800080', 'orange' => '#ffa500',
                                            'gray' => '#808080', 'grey' => '#808080', 'brown' => '#a52a2a',
                                            'navy' => '#000080', 'maroon' => '#800000', 'teal' => '#008080',
                                            'lime' => '#00ff00', 'cyan' => '#00ffff', 'magenta' => '#ff00ff',
                                            'silver' => '#c0c0c0', 'gold' => '#ffd700',
                                        ];
                                        // Trim and lowercase the color name for matching
                                        $cleanColor = strtolower(trim($color->color));
                                        $colorCode = $colorMap[$cleanColor] ?? '#cccccc';
                                    @endphp
                                    <span style="display:inline-flex;align-items:center;gap:4px;margin-right:8px">
                                        <span style="width:12px;height:12px;border-radius:50%;background-color:{{ $colorCode }};border:1px solid #ddd;display:inline-block"></span>
                                        {{ $color->color }}
                                    </span>
                                @endforeach
                            </small>
                        </div>
                    @endif

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                        <span class="product-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        <div style="display:flex;align-items:center;gap:4px">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color:{{ $i <= $product->averageRating() ? '#ffc107' : '#ddd' }};font-size:12px"></i>
                            @endfor
                            <small style="color:#666;margin-left:4px">({{ $product->reviewsCount() }})</small>
                        </div>
                    </div>

                    <div style="display:flex;gap:8px">
                        <a href="{{ route('user.products.show', $product) }}" class="btn btn-sm" style="flex:1;text-align:center">Chi tiết</a>
                    </div>
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
@endsection

