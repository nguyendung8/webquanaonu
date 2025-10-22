<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Quản trị' }} - Pamela Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--coffee:#6f4e37;--cream:#f7efe7;--dark:#2b231e;--accent:#c08552;--leaf:#3a5a40;--border:#e6dbd3;}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f9f6f2;color:var(--dark)}
        .header{position:fixed;top:0;left:0;right:0;height:60px;background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 24px;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,.03)}
        .brand{display:flex;align-items:center;gap:10px;color:var(--coffee);font-weight:700;font-size:18px}
        .logo{width:32px;height:32px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#8b5e3c,var(--coffee))}
        .user-dropdown{position:relative}
        .user-toggle{display:flex;align-items:center;gap:8px;cursor:pointer;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:#fff;transition:all .2s;color:var(--coffee);font-weight:600;font-size:14px}
        .user-toggle:hover{background:var(--cream)}
        .user-toggle i{font-size:20px}
        .dropdown-menu{position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid var(--border);border-radius:12px;min-width:180px;box-shadow:0 8px 24px rgba(0,0,0,.12);opacity:0;visibility:hidden;transform:translateY(-10px);transition:all .2s}
        .user-dropdown.active .dropdown-menu{opacity:1;visibility:visible;transform:translateY(0)}
        .dropdown-menu button{display:flex;align-items:center;gap:10px;width:100%;padding:12px 16px;border:none;background:none;text-align:left;color:var(--dark);text-decoration:none;transition:background .2s;font-size:14px;cursor:pointer;border-radius:12px}
        .dropdown-menu button:hover{background:var(--cream)}
        .sidebar{position:fixed;top:60px;left:0;bottom:0;width:240px;background:#fff;border-right:1px solid var(--border);padding:20px 0;overflow-y:auto}
        .menu-item{display:flex;align-items:center;gap:12px;padding:12px 24px;color:var(--dark);text-decoration:none;font-size:14px;font-weight:500;transition:all .2s;border-left:3px solid transparent}
        .menu-item i{width:18px;text-align:center;font-size:16px}
        .menu-item:hover{background:var(--cream);border-left-color:var(--accent)}
        .menu-item.active{background:var(--cream);border-left-color:var(--coffee);color:var(--coffee)}
        .content{margin-left:240px;margin-top:60px;padding:24px;min-height:calc(100vh - 60px)}
        .card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.04);border:1px solid var(--border)}
        .page-title{font-size:24px;color:var(--coffee);margin-bottom:20px;font-weight:700}
        .btn{appearance:none;border:none;background:var(--coffee);color:#fff;padding:10px 16px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600;text-decoration:none;display:inline-block;transition:all .2s}
        .btn:hover{background:#5d3f2e;transform:translateY(-1px)}
        .btn-sm{padding:6px 12px;font-size:13px}
        .btn-secondary{background:var(--accent)}
        .btn-secondary:hover{background:#a86d3f}
        .btn-danger{background:#c53030}
        .btn-danger:hover{background:#9b2c2c}
        table{width:100%;border-collapse:collapse;margin-top:16px}
        th,td{padding:12px;text-align:left;border-bottom:1px solid var(--border)}
        th{background:var(--cream);color:var(--coffee);font-weight:600;font-size:13px;text-transform:uppercase;letter-spacing:.5px}
        tr:hover{background:#fefdfb}
        .badge{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600}
        .badge-success{background:#d4edda;color:#155724}
        .badge-warning{background:#fff3cd;color:#856404}
        .badge-danger{background:#f8d7da;color:#721c24}
        .form-group{margin-bottom:16px}
        label{display:block;margin-bottom:6px;color:var(--dark);font-weight:600;font-size:14px}
        input[type="text"],input[type="email"],input[type="password"],input[type="number"],input[type="date"],input[type="time"],input[type="file"],select,textarea{width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px;background:#fff;outline:none;transition:border .2s,box-shadow .2s}
        input:focus,select:focus,textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(192,133,82,.1)}
        textarea{resize:vertical;min-height:100px}
        .alert{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px}
        .alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
        .alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
        .pagination{display:flex;gap:8px;margin-top:20px;justify-content:center}
        .pagination a,.pagination span{padding:8px 12px;border:1px solid var(--border);border-radius:6px;text-decoration:none;color:var(--dark);font-size:14px}
        .pagination a:hover{background:var(--cream)}
        .pagination .active{background:var(--coffee);color:#fff;border-color:var(--coffee)}
        .actions{display:flex;gap:8px}
        img.preview{max-width:100px;max-height:100px;object-fit:cover;border-radius:8px}
        .modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
        .modal-content{background:#fff;border-radius:16px;padding:0;width:90%;max-width:700px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.3)}
        .modal-header{display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-bottom:1px solid var(--border);background:var(--cream)}
        .modal-header h2{margin:0;font-size:20px;color:var(--coffee)}
        .modal-close{appearance:none;border:none;background:none;font-size:32px;color:var(--dark);cursor:pointer;padding:0;line-height:1}
        .modal-content form{padding:24px}
    </style>
    @yield('styles')
</head>
<body>
<header class="header">
    <div class="brand">
        <div class="logo"></div>
        <span>Pamela Shop Admin</span>
    </div>
    <div class="user-dropdown">
        <div class="user-toggle" onclick="toggleDropdown()">
            <i class="fas fa-user-circle"></i>
            <span>{{ auth()->user()->username }}</span>
            <i class="fas fa-chevron-down" style="font-size:12px"></i>
        </div>
        <div class="dropdown-menu" id="userDropdown">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </button>
            </form>
        </div>
    </div>
</header>

<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="fas fa-tshirt"></i>
        <span>Sản phẩm</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="fas fa-folder"></i>
        <span>Danh mục</span>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="fas fa-shopping-cart"></i>
        <span>Đơn hàng</span>
    </a>
    <a href="{{ route('admin.feedbacks.index') }}" class="menu-item {{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
        <i class="fas fa-comments"></i>
        <span>Phản hồi</span>
    </a>
    <a href="{{ route('admin.customers.index') }}" class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Khách hàng</span>
    </a>
    <a href="{{ route('admin.stats') }}" class="menu-item {{ request()->routeIs('admin.stats') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Thống kê</span>
    </a>
</aside>

<main class="content">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

@yield('scripts')
<script>
function toggleDropdown() {
    document.getElementById('userDropdown').parentElement.classList.toggle('active');
}

window.addEventListener('click', function(e) {
    if (!e.target.closest('.user-dropdown')) {
        document.querySelector('.user-dropdown')?.classList.remove('active');
    }
});
</script>
</body>
</html>

