@extends('layouts.app', ['title' => $product->name])

@section('content')
<div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:60px">
        <!-- Product Images Gallery -->
        <div>
            <div id="main-image" style="margin-bottom:16px">
                <img id="main-img" src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/600x600' }}" alt="{{ $product->name }}" style="width:100%;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.1);cursor:pointer">
            </div>

            @if($product->images->count() > 0)
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(80px,1fr));gap:8px">
                    <div class="thumbnail {{ !$product->image ? 'active' : '' }}" onclick="changeMainImage('{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/600x600' }}')" style="cursor:pointer;border:2px solid {{ !$product->image ? 'var(--pink)' : 'transparent' }};border-radius:8px;overflow:hidden">
                        <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/600x600' }}" style="width:100%;height:80px;object-fit:cover">
                    </div>
                    @foreach($product->images as $image)
                        <div class="thumbnail" onclick="changeMainImage('{{ asset($image->image_path) }}')" style="cursor:pointer;border:2px solid transparent;border-radius:8px;overflow:hidden">
                            <img src="{{ asset($image->image_path) }}" style="width:100%;height:80px;object-fit:cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <p style="color:#999;margin-bottom:8px">{{ $product->category->name }}</p>
            <h1 style="font-size:36px;color:var(--pink);margin-bottom:16px">{{ $product->name }}</h1>

            <!-- Rating -->
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
                <div style="display:flex;gap:2px">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="color:{{ $i <= $product->averageRating() ? '#ffc107' : '#ddd' }};font-size:16px"></i>
                    @endfor
                </div>
                <span style="color:#666;font-size:14px">({{ $product->reviewsCount() }} đánh giá)</span>
            </div>

            <div style="font-size:32px;color:var(--pink);font-weight:700;margin-bottom:24px">
                {{ number_format($product->price, 0, ',', '.') }}đ
            </div>

            @if($product->availability)
                <span style="display:inline-block;padding:6px 12px;background:#d4edda;color:#155724;border-radius:999px;font-size:14px;margin-bottom:24px">Còn hàng</span>
            @else
                <span style="display:inline-block;padding:6px 12px;background:#f8d7da;color:#721c24;border-radius:999px;font-size:14px;margin-bottom:24px">Hết hàng</span>
            @endif

            <!-- Sizes -->
            @if($product->sizes->count() > 0)
                <div style="margin-bottom:20px">
                    <h3 style="margin-bottom:8px;color:var(--dark)">Size có sẵn:</h3>
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        @foreach($product->sizes as $size)
                            <span style="padding:8px 16px;border:1px solid var(--border);border-radius:20px;background:#fff;color:var(--dark)">{{ $size->size }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Colors -->
            @if($product->colors->count() > 0)
                <div style="margin-bottom:20px">
                    <h3 style="margin-bottom:8px;color:var(--dark)">Màu sắc:</h3>
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        @foreach($product->colors as $color)
                            <div style="display:flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid var(--border);border-radius:20px;background:#fff">
                                @php
                                    // Always use mapping logic, ignore color_code from database
                                    $colorMap = [
                                        'pink' => '#ffc0cb',
                                        'white' => '#ffffff',
                                        'blue' => '#0000ff',
                                        'red' => '#ff0000',
                                        'black' => '#000000',
                                        'yellow' => '#ffff00',
                                        'green' => '#008000',
                                        'purple' => '#800080',
                                        'orange' => '#ffa500',
                                        'gray' => '#808080',
                                        'grey' => '#808080',
                                        'brown' => '#a52a2a',
                                        'navy' => '#000080',
                                        'maroon' => '#800000',
                                        'teal' => '#008080',
                                        'lime' => '#00ff00',
                                        'cyan' => '#00ffff',
                                        'magenta' => '#ff00ff',
                                        'silver' => '#c0c0c0',
                                        'gold' => '#ffd700',
                                    ];
                                    // Trim and lowercase the color name for matching
                                    $cleanColor = strtolower(trim($color->color));
                                    $colorCode = $colorMap[$cleanColor] ?? '#cccccc';
                                @endphp
                                <div style="width:16px;height:16px;border-radius:50%;background-color:{{ $colorCode }};border:1px solid #ddd"></div>
                                <span style="color:var(--dark)">{{ $color->color }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Product Details -->
            @if($product->material || $product->brand)
                <div style="margin-bottom:20px">
                    @if($product->material)
                        <p style="margin:4px 0;color:#666"><strong>Chất liệu:</strong> {{ $product->material }}</p>
                    @endif
                    @if($product->brand)
                        <p style="margin:4px 0;color:#666"><strong>Thương hiệu:</strong> {{ $product->brand }}</p>
                    @endif
                </div>
            @endif

            <div style="margin-bottom:32px">
                <h3 style="margin-bottom:12px;color:var(--dark)">Mô tả sản phẩm</h3>
                <p style="color:#666;line-height:1.8">{{ $product->description ?? 'Chưa có mô tả' }}</p>
            </div>

            @if($product->availability)
                <form action="{{ route('user.cart.add', $product) }}" method="POST" style="display:flex;flex-direction:column;gap:16px">
                    @csrf

                    <!-- Size Selection -->
                    @if($product->sizes->count() > 0)
                        <div>
                            <label style="display:block;margin-bottom:8px;font-weight:600">Chọn size:</label>
                            <select name="size" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px">
                                <option value="">-- Chọn size --</option>
                                @foreach($product->sizes as $size)
                                    <option value="{{ $size->size }}">{{ $size->size }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Color Selection -->
                    @if($product->colors->count() > 0)
                        <div>
                            <label style="display:block;margin-bottom:8px;font-weight:600">Chọn màu:</label>
                            <select name="color" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px">
                                <option value="">-- Chọn màu --</option>
                                @foreach($product->colors as $color)
                                    <option value="{{ $color->color }}">{{ $color->color }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div style="display:flex;gap:12px;align-items:center">
                        <label style="font-weight:600">Số lượng:</label>
                        <input type="number" name="quantity" value="1" min="1" max="10" style="width:80px;padding:8px;border:1px solid var(--border);border-radius:8px">
                        <button type="submit" class="btn" style="flex:1">Thêm vào giỏ hàng</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="section" style="margin-bottom:60px">
        <h2 class="section-title">Đánh giá sản phẩm</h2>

        @auth
            <div class="card" style="margin-bottom:32px; padding: 20px;">
                <h3 style="margin-bottom:16px">Viết đánh giá của bạn</h3>
                <form action="{{ route('user.reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div style="margin-bottom:16px">
                        <label style="display:block;margin-bottom:8px;font-weight:600">Đánh giá:</label>
                        <div style="display:flex;gap:4px">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star rating-star" data-rating="{{ $i }}" style="font-size:24px;color:#ddd;cursor:pointer;transition:color .2s"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="5">
                    </div>

                    <div style="margin-bottom:16px">
                        <label for="title" style="display:block;margin-bottom:8px;font-weight:600">Tiêu đề:</label>
                        <input type="text" id="title" name="title" placeholder="Tiêu đề đánh giá..." style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px">
                    </div>

                    <div style="margin-bottom:16px">
                        <label for="content" style="display:block;margin-bottom:8px;font-weight:600">Nội dung:</label>
                        <textarea id="content" name="content" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;resize:vertical"></textarea>
                    </div>

                    <button type="submit" class="btn">Gửi đánh giá</button>
                </form>
            </div>
        @else
            <div class="card" style="text-align:center;padding:32px;margin-bottom:32px">
                <p style="color:#666;margin-bottom:16px">Đăng nhập để viết đánh giá</p>
                <a href="{{ route('login') }}" class="btn">Đăng nhập</a>
            </div>
        @endauth

        <!-- Reviews List -->
        <div class="reviews-list">
            @forelse($product->reviews as $review)
                <div class="card" style="margin-bottom:16px; padding: 20px;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px">
                        <div>
                            <strong>{{ $review->user->username }}</strong>
                            <div style="display:flex;gap:2px;margin-top:4px">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color:{{ $i <= $review->rating ? '#ffc107' : '#ddd' }};font-size:14px"></i>
                                @endfor
                            </div>
                        </div>
                        <small style="color:#999">{{ $review->created_at->format('d/m/Y') }}</small>
                    </div>

                    @if($review->title)
                        <h4 style="margin-bottom:8px;color:var(--dark)">{{ $review->title }}</h4>
                    @endif

                    <p style="color:#666;line-height:1.6">{{ $review->content }}</p>
                </div>
            @empty
                <div class="card" style="text-align:center;padding:32px">
                    <p style="color:#999">Chưa có đánh giá nào</p>
                </div>
            @endforelse
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

@section('scripts')
<script>
function changeMainImage(src) {
    document.getElementById('main-img').src = src;

    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.style.borderColor = 'transparent';
    });
    event.currentTarget.style.borderColor = 'var(--pink)';
}

// Rating stars
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.getElementById('rating-input').value = rating;

        document.querySelectorAll('.rating-star').forEach((s, index) => {
            s.style.color = index < rating ? '#ffc107' : '#ddd';
        });
    });

    star.addEventListener('mouseenter', function() {
        const rating = this.dataset.rating;
        document.querySelectorAll('.rating-star').forEach((s, index) => {
            s.style.color = index < rating ? '#ffc107' : '#ddd';
        });
    });
});

document.querySelector('.rating-star').addEventListener('mouseleave', function() {
    const currentRating = document.getElementById('rating-input').value;
    document.querySelectorAll('.rating-star').forEach((s, index) => {
        s.style.color = index < currentRating ? '#ffc107' : '#ddd';
    });
});
</script>
@endsection

