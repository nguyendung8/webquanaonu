<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pamela Shop' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--coffee:#2097e3;--cream:#aad5f1;--dark:#2b231e;--accent:#24658f;--leaf:#3a5a40;--border:#e6dbd3;}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#fff;color:var(--dark);line-height:1.6}

        .header{position:sticky;top:0;background:#fff;border-bottom:2px solid var(--border);z-index:100;box-shadow:0 2px 12px rgba(0,0,0,.05)}
        .header-top{display:flex;align-items:center;justify-content:space-between;padding:16px 5%;max-width:1400px;margin:0 auto}
        .logo{display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--coffee);font-size:24px;font-weight:700}
        .logo-img{width:40px;height:40px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#8b5e3c,var(--coffee))}

        .nav{display:flex;gap:32px;align-items:center}
        .nav a{text-decoration:none;color:var(--dark);font-weight:500;transition:color .2s;position:relative}
        .nav a:hover,.nav a.active{color:var(--coffee)}
        .nav a.active::after{content:'';position:absolute;bottom:-18px;left:0;right:0;height:2px;background:var(--coffee)}

        .user-actions{display:flex;gap:16px;align-items:center}
        .user-actions a,.user-actions button{text-decoration:none;color:var(--dark);font-size:14px;font-weight:500;padding:8px 16px;border-radius:8px;transition:all .2s;background:none;border:1px solid var(--border);cursor:pointer}
        .user-actions a:hover,.user-actions button:hover{background:var(--cream);border-color:var(--accent)}
        .cart-icon{position:relative}
        .cart-badge{position:absolute;top:-8px;right:-8px;background:#e63946;color:#fff;border-radius:50%;width:20px;height:20px;font-size:11px;display:flex;align-items:center;justify-content:center;font-weight:700}
        .user-dropdown{position:relative}
        .user-toggle{display:flex;align-items:center;gap:8px;cursor:pointer;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:#fff;transition:all .2s}
        .user-toggle:hover{background:var(--cream);border-color:var(--accent)}
        .dropdown-menu{position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid var(--border);border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.1);min-width:200px;display:none;z-index:1000}
        .dropdown-menu.active{display:block}
        .dropdown-menu a,.dropdown-menu button{display:flex;align-items:center;gap:10px;width:100%;padding:12px 16px;border:none;background:none;text-align:left;color:var(--dark);text-decoration:none;transition:background .2s;font-size:14px}
        .dropdown-menu a:hover,.dropdown-menu button:hover{background:var(--cream)}
        .dropdown-menu hr{border:none;border-top:1px solid var(--border)}

        .container{max-width:1400px;margin:0 auto;padding:40px 5%}
        .section{margin-bottom:60px}
        .section-title{font-size:32px;color:var(--coffee);margin-bottom:24px;text-align:center;font-weight:700}

        .card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.06);transition:transform .3s,box-shadow .3s;border:1px solid var(--border)}
        .card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(0,0,0,.12)}

        .product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:24px}
        .product-card img{width:100%;height:200px;object-fit:cover}
        .product-info{padding:16px}
        .product-name{font-size:16px;font-weight:600;color:var(--dark);margin-bottom:8px}
        .product-price{font-size:20px;color:var(--coffee);font-weight:700}
        .product-desc{font-size:14px;color:#666;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

        .btn{appearance:none;border:none;background:var(--coffee);color:#fff;padding:12px 24px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600;text-decoration:none;display:inline-block;transition:all .2s}
        .btn:hover{background:#24658f;transform:translateY(-1px)}
        .btn-secondary{background:var(--accent)}
        .btn-secondary:hover{background:#a86d3f}
        .btn-outline{background:transparent;border:2px solid var(--coffee);color:var(--coffee)}
        .btn-outline:hover{background:var(--coffee);color:#fff}

        .footer{background:var(--dark);color:#fff;padding:40px 5%;margin-top:80px}
        .footer-content{max-width:1400px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:32px}
        .footer h4{color:var(--accent);margin-bottom:16px}
        .footer a{color:#ccc;text-decoration:none;display:block;margin-bottom:8px}
        .footer a:hover{color:#fff}

        input[type="text"],input[type="email"],input[type="number"],select,textarea{width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px;outline:none;transition:border .2s,box-shadow .2s}
        input:focus,select:focus,textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(192,133,82,.1)}

        .alert{padding:12px 16px;border-radius:8px;margin-bottom:16px}
        .alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
        .alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}

        @media (max-width:768px){
            .nav{display:none}
            .product-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:16px}
        }
    </style>
    @yield('styles')
</head>
<body>
<header class="header">
    <div class="header-top">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-img"></div>
            <span>Pamela Shop</span>
        </a>

        <nav class="nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
            <a href="{{ route('user.products') }}" class="{{ request()->routeIs('user.products*') ? 'active' : '' }}">Sản phẩm</a>
            <a href="{{ route('user.feedback') }}" class="{{ request()->routeIs('user.feedback') ? 'active' : '' }}">Phản hồi</a>
            @auth
                <a href="{{ route('user.orders') }}" class="{{ request()->routeIs('user.orders') ? 'active' : '' }}">Đơn hàng</a>
                <a href="{{ route('user.cart') }}" class="{{ request()->routeIs('user.cart') ? 'active' : '' }}">Giỏ hàng</a>
            @else
                <a href="{{ route('login') }}">Đơn hàng</a>
                <a href="{{ route('login') }}">Giỏ hàng</a>
            @endauth
        </nav>

        <div class="user-actions">
            @auth
                <a href="{{ route('user.cart') }}" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="user-dropdown">
                    <div class="user-toggle" onclick="toggleDropdown()">
                        <i class="fas fa-user-circle" style="font-size:20px;color:var(--coffee)"></i>
                        <span>{{ auth()->user()->username }}</span>
                        <i class="fas fa-chevron-down" style="font-size:12px"></i>
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="{{ route('user.change-password') }}">
                            <i class="fas fa-key"></i>
                            <span>Đổi mật khẩu</span>
                        </a>
                        <hr>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}">Đăng nhập</a>
                <a href="{{ route('register') }}" style="background:var(--coffee);color:#fff;border-color:var(--coffee)">Đăng ký</a>
            @endauth
        </div>
    </div>
</header>

<main>
    @if (session('success'))
        <div class="container">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="container">
            <div class="alert alert-error">{{ session('error') }}</div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="footer">
    <div class="footer-content">
        <div>
            <h4>Về Pamela Shop</h4>
            <p style="color:#ccc">Cửa hàng thời trang nữ cao cấp với những sản phẩm chất lượng và phong cách độc đáo.</p>
        </div>
        <div>
            <h4>Liên kết</h4>
            <a href="{{ route('home') }}">Trang chủ</a>
            <a href="{{ route('user.products') }}">Sản phẩm</a>
            <a href="{{ route('user.feedback') }}">Phản hồi</a>
        </div>
        <div>
            <h4>Liên hệ</h4>
            <p style="color:#ccc">Email: contact@pamelashop.com</p>
            <p style="color:#ccc">Phone: 0123 456 789</p>
        </div>
    </div>
    <div style="text-align:center;margin-top:32px;padding-top:24px;border-top:1px solid #444;color:#888">
        © 2025 Pamela Shop. All rights reserved.
    </div>
</footer>

@yield('scripts')
<script>
function toggleDropdown() {
    document.getElementById('userDropdown').classList.toggle('active');
}

window.addEventListener('click', function(e) {
    if (!e.target.closest('.user-dropdown')) {
        document.getElementById('userDropdown')?.classList.remove('active');
    }
});
</script>
@stack('scripts')
</body>
</html>

