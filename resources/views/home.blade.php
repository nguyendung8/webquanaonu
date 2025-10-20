<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cafe Meo - Trang chủ</title>
    <style>
        body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#fff7f2;color:#2b231e}
        header{display:flex;justify-content:space-between;align-items:center;padding:16px 22px;background:#6f4e37;color:#fff}
        a{color:#fff;text-decoration:none}
        .brand{display:flex;gap:10px;align-items:center}
        .logo{width:28px;height:28px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#8b5e3c,#6f4e37)}
        .wrap{max-width:1100px;margin:0 auto;padding:24px}
        .hero{background:#ffffff;border-radius:18px;padding:24px;box-shadow:0 10px 30px rgba(0,0,0,.05)}
        .btn{appearance:none;border:none;background:#6f4e37;color:#fff;padding:10px 14px;border-radius:10px;cursor:pointer}
        .muted{color:#6c5a52}
    </style>
</head>
<body>
<header>
    <div class="brand"><div class="logo"></div><strong>Cafe Meo</strong></div>
    <nav>
        @auth
            <span class="muted" style="margin-right:8px;">Xin chào, {{ auth()->user()->username }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button class="btn" type="submit">Đăng xuất</button>
            </form>
        @else
            <a href="{{ route('login') }}">Đăng nhập</a>
            <span style="opacity:.5"> | </span>
            <a href="{{ route('register') }}">Đăng ký</a>
        @endauth
    </nav>
</header>

<div class="wrap">
    <div class="hero">
        <h1>Chào mừng đến Cafe Meo</h1>
        <p class="muted">Gọi món, mua sản phẩm, đặt lịch chơi cùng mèo ngay hôm nay.</p>
        @auth
            @if (auth()->user()->role === 'admin')
                <p><a class="btn" href="{{ route('admin.dashboard') }}">Vào trang quản trị</a></p>
            @endif
        @endauth
    </div>
</div>
</body>
</html>

