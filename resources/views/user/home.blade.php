@extends('layouts.app', ['title' => 'Trang chủ'])

@section('styles')
<style>
    .slider{position:relative;height:500px;overflow:hidden;background:var(--cream)}
    .slide{position:absolute;width:100%;height:100%;opacity:0;transition:opacity 1s}
    .slide.active{opacity:1}
    .slide img{width:100%;height:100%;object-fit:cover}
    .slide-content{position:absolute;top:50%;left:10%;transform:translateY(-50%);color:#fff;text-shadow:2px 2px 4px rgba(0,0,0,.7)}
    .slide-content h2{font-size:48px;margin-bottom:16px}
    .slide-content p{font-size:20px;margin-bottom:24px}

    .intro{background:var(--cream);padding:60px 5%;text-align:center}
    .intro h2{font-size:36px;color:var(--coffee);margin-bottom:16px}
    .intro p{font-size:18px;color:#666;max-width:800px;margin:0 auto}
</style>
@endsection

@section('content')
<div class="slider">
    <div class="slide active">
        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1600&h=500&fit=crop" alt="Slide 1">
        <div class="slide-content">
            <h2>Cafe Meo</h2>
            <p>Hương vị đậm đà, không gian ấm áp</p>
            <a href="{{ route('user.products') }}" class="btn">Khám phá ngay</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=1600&h=500&fit=crop" alt="Slide 2">
        <div class="slide-content">
            <h2>Sản phẩm chất lượng</h2>
            <p>Cafe nguyên chất, đồ uống phong phú</p>
            <a href="{{ route('user.products') }}" class="btn">Xem menu</a>
        </div>
    </div>
    <div class="slide">
        <img src="https://images.unsplash.com/photo-1478098711619-5ab0b478d6e6?w=1600&h=500&fit=crop" alt="Slide 3">
        <div class="slide-content">
            <h2>Chơi cùng mèo</h2>
            <p>Đặt lịch và trải nghiệm không gian cùng những chú mèo đáng yêu</p>
            <a href="{{ route('user.products') }}" class="btn">Đặt lịch ngay</a>
        </div>
    </div>
</div>

<div class="intro">
    <h2>Chào mừng đến Cafe Meo</h2>
    <p>Chúng tôi mang đến không gian thư giãn với hương vị cafe đậm đà, đồ uống phong phú và những chú mèo đáng yêu. Đến với Cafe Meo, bạn không chỉ thưởng thức cafe ngon mà còn được tận hưởng khoảng thời gian vui vẻ bên những người bạn bốn chân.</p>
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

