<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cafe Meo' }}</title>
    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--coffee:#6f4e37;--cream:#f7efe7;--dark:#2b231e;--accent:#c08552;--leaf:#3a5a40;}
        *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:linear-gradient(135deg,var(--cream),#fff)}
        .wrap{min-height:100vh;display:grid;place-items:center;padding:24px}
        .card{width:100%;max-width:900px;display:grid;grid-template-columns:1.2fr 1fr;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.08)}
        .hero{position:relative;background:linear-gradient(160deg,rgba(47,33,27,.8),rgba(47,33,27,.6)),url('https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?q=80&w=1600&auto=format&fit=crop') center/cover;color:#fff;padding:48px}
        .hero h1{margin:0 0 8px;font-size:38px;letter-spacing:.5px}
        .hero p{margin:0;opacity:.9}
        .badge{display:inline-block;margin-top:16px;background:var(--accent);color:#fff;padding:6px 12px;border-radius:999px;font-size:12px}
        .panel{padding:36px 32px}
        .brand{display:flex;align-items:center;gap:10px;color:var(--coffee);margin-bottom:18px}
        .logo{width:36px;height:36px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#8b5e3c, var(--coffee));box-shadow:inset 0 0 0 4px rgba(0,0,0,.05)}
        h2{margin:0 0 6px;font-size:26px;color:var(--dark)}
        .sub{margin:0 0 22px;color:#5b4a42;font-size:14px}
        form{display:grid;gap:14px}
        label{display:block;margin:0 0 6px;color:#4a3a33;font-weight:600;font-size:13px}
        input{width:100%;padding:12px 14px;border:1px solid #e6dbd3;border-radius:12px;background:#fff;font-size:14px;outline:none;transition:border .2s, box-shadow .2s}
        input:focus{border-color:var(--accent);box-shadow:0 0 0 4px rgba(192,133,82,.12)}
        .row{display:grid;gap:14px}
        .btn{appearance:none;border:none;background:var(--coffee);color:#fff;padding:12px 16px;border-radius:12px;font-weight:700;cursor:pointer;font-size:14px;transition:transform .04s, background .2s}
        .btn:hover{background:#5d3f2e}
        .btn:active{transform:translateY(1px)}
        .muted{font-size:13px;color:#6c5a52}
        a.link{color:var(--leaf);text-decoration:none;font-weight:600}
        .alert{padding:10px 12px;border-radius:10px;background:#fff3f0;color:#9b3e1d;border:1px solid #ffd7cc;font-size:13px}
        .success{background:#eefaf2;color:#1f6f43;border-color:#cdeedd}
        @media (max-width:900px){.card{grid-template-columns:1fr}.hero{min-height:200px}}
    </style>
    @yield('head')
    @yield('styles')
    @yield('scripts-head')
    @yield('meta')
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="hero">
            <h1>Cafe Meo</h1>
            <p>Hương vị đậm đà – Không gian ấm áp.</p>
            <span class="badge">Welcome</span>
        </div>
        <div class="panel">
            <div class="brand">
                <div class="logo"></div>
                <strong>Cafe Meo</strong>
            </div>
            @if (session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
@yield('scripts')
</body>
</html>


