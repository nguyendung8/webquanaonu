<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Pamela Shop - Thời trang nữ cao cấp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--pink:#e91e63;--rose:#f8bbd9;--dark:#2b231e;--accent:#c2185b;--light:#fce4ec;--border:#f8bbd9;}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:linear-gradient(135deg,#fce4ec,#fff);color:var(--dark);line-height:1.6}

        .hero{background:linear-gradient(135deg,var(--pink),var(--accent));color:#fff;padding:80px 0;text-align:center;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1600&auto=format&fit=crop') center/cover;opacity:0.1}
        .hero-content{position:relative;z-index:2;max-width:800px;margin:0 auto;padding:0 20px}
        .hero h1{font-size:48px;font-weight:700;margin-bottom:16px;text-shadow:2px 2px 4px rgba(0,0,0,0.3)}
        .hero p{font-size:20px;margin-bottom:32px;opacity:0.9}
        .btn{appearance:none;border:none;background:#fff;color:var(--pink);padding:16px 32px;border-radius:50px;cursor:pointer;font-size:16px;font-weight:600;text-decoration:none;display:inline-block;transition:all .3s;box-shadow:0 4px 15px rgba(0,0,0,0.1)}
        .btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,0.15)}

        .features{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:32px;padding:80px 20px;max-width:1200px;margin:0 auto}
        .feature{text-align:center;padding:32px;background:#fff;border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.05);transition:transform .3s}
        .feature:hover{transform:translateY(-5px)}
        .feature i{font-size:48px;color:var(--pink);margin-bottom:16px}
        .feature h3{font-size:24px;margin-bottom:12px;color:var(--dark)}
        .feature p{color:#666;line-height:1.6}

        .cta{background:var(--light);padding:80px 20px;text-align:center}
        .cta h2{font-size:36px;margin-bottom:16px;color:var(--dark)}
        .cta p{font-size:18px;color:#666;margin-bottom:32px}

        @media (max-width:768px){
            .hero h1{font-size:32px}
            .hero p{font-size:16px}
            .features{grid-template-columns:1fr;padding:40px 20px}
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <h1>Pamela Shop</h1>
            <p>Thời trang nữ cao cấp - Phong cách độc đáo cho phụ nữ hiện đại</p>
            <a href="{{ route('user.products') }}" class="btn">
                <i class="fas fa-shopping-bag"></i> Khám phá ngay
            </a>
        </div>
    </div>

    <div class="features">
        <div class="feature">
            <i class="fas fa-tshirt"></i>
            <h3>Thời trang đa dạng</h3>
            <p>Bộ sưu tập quần áo nữ phong phú với nhiều phong cách từ thanh lịch đến trẻ trung</p>
        </div>
        <div class="feature">
            <i class="fas fa-palette"></i>
            <h3>Nhiều màu sắc</h3>
            <p>Đa dạng màu sắc và kích thước để bạn lựa chọn phù hợp với phong cách cá nhân</p>
        </div>
        <div class="feature">
            <i class="fas fa-star"></i>
            <h3>Chất lượng cao</h3>
            <p>Chất liệu cao cấp và thiết kế tinh tế, đảm bảo sự thoải mái và tự tin cho bạn</p>
        </div>
        <div class="feature">
            <i class="fas fa-shipping-fast"></i>
            <h3>Giao hàng nhanh</h3>
            <p>Dịch vụ giao hàng tận nơi nhanh chóng và an toàn trên toàn quốc</p>
        </div>
    </div>

    <div class="cta">
        <h2>Sẵn sàng làm mới tủ quần áo?</h2>
        <p>Khám phá bộ sưu tập mới nhất và tìm cho mình những bộ trang phục hoàn hảo</p>
        <a href="{{ route('user.products') }}" class="btn">
            <i class="fas fa-shopping-cart"></i> Mua sắm ngay
        </a>
    </div>
</body>
</html>

