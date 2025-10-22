@extends('layouts.app', ['title' => 'Trang chủ'])

@section('styles')
<style>
    .slider{position:relative;height:500px;overflow:hidden;background:var(--light)}
    .slide{position:absolute;width:100%;height:100%;opacity:0;transition:opacity 1s}
    .slide.active{opacity:1}
    .slide img{width:100%;height:100%;object-fit:cover}
    .slide-content{position:absolute;top:50%;left:10%;transform:translateY(-50%);color:#fff;text-shadow:2px 2px 4px rgba(0,0,0,.7)}
    .slide-content h2{font-size:48px;margin-bottom:16px;color:#fff}
    .slide-content p{font-size:20px;margin-bottom:24px;color:#fff}
    .slide-content .btn{background:#fff;color:var(--pink);border:none;padding:12px 24px;border-radius:25px;text-decoration:none;font-weight:600;transition:all .3s}
    .slide-content .btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,0.15)}

    .intro{background:var(--light);padding:60px 5%;text-align:center}
    .intro h2{font-size:36px;color:var(--pink);margin-bottom:16px}
    .intro p{font-size:18px;color:#666;max-width:800px;margin:0 auto}
</style>
@endsection

@section('content')
<div class="slider">
    <div class="slide active">
        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&h=500&fit=crop" alt="Slide 1">
        <div class="slide-content">
            <h2>Pamela Shop</h2>
            <p>Thời trang nữ cao cấp - Phong cách độc đáo cho phụ nữ hiện đại</p>
            <a href="{{ route('user.products') }}" class="btn">Khám phá ngay</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=1600&h=500&fit=crop" alt="Slide 2">
        <div class="slide-content">
            <h2>Bộ sưu tập mới</h2>
            <p>Những mẫu thiết kế mới nhất, phù hợp với mọi phong cách</p>
            <a href="{{ route('user.products') }}" class="btn">Xem bộ sưu tập</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&h=500&fit=crop" alt="Slide 3">
        <div class="slide-content">
            <h2>Chất lượng cao</h2>
            <p>Chất liệu cao cấp và thiết kế tinh tế, đảm bảo sự thoải mái và tự tin</p>
            <a href="{{ route('user.products') }}" class="btn">Mua sắm ngay</a>
        </div>
    </div>
</div>

<div class="intro">
    <h2>Chào mừng đến Pamela Shop</h2>
    <p>Chúng tôi mang đến những bộ trang phục thời trang cao cấp dành riêng cho phụ nữ. Với chất liệu tốt, thiết kế tinh tế và đa dạng về màu sắc, kích thước, Pamela Shop sẽ giúp bạn thể hiện phong cách cá nhân một cách tự tin và quyến rũ.</p>
</div>

<div class="container">
    <div class="section">
        <h2 class="section-title">Sản phẩm mới nhất</h2>
        <div class="product-grid">
            @forelse($latestProducts as $product)
                <div class="card product-card">
                    <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/250x200' }}" alt="{{ $product->name }}">
                    <div class="product-info">
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
                <p style="text-align:center;color:#999;grid-column:1/-1">Chưa có sản phẩm nào</p>
            @endforelse
        </div>
        <div style="text-align:center;margin-top:32px">
            <a href="{{ route('user.products') }}" class="btn btn-outline">Xem tất cả sản phẩm</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(n) {
    slides.forEach(slide => slide.classList.remove('active'));
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
}

setInterval(() => showSlide(currentSlide + 1), 5000);
</script>
@endsection

