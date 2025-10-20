@extends('layouts.auth', ['title' => 'Đăng nhập'])

@section('content')
    <h2>Đăng nhập</h2>
    <p class="sub">Vào quầy gọi món và đặt lịch chơi cùng mèo.</p>

    @if ($errors->any())
        <div class="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div>
            <label for="identifier">Email hoặc Username</label>
            <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required autofocus>
        </div>
        <div>
            <label for="password">Mật khẩu</label>
            <div style="position:relative">
                <input id="password" name="password" type="password" required style="padding-right:40px">
                <i class="fas fa-eye" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999" onclick="togglePassword('password')"></i>
            </div>
        </div>
        <button class="btn" type="submit">Đăng nhập</button>
    </form>

    <p class="muted" style="margin-top:12px;">
        Chưa có tài khoản? <a class="link" href="{{ route('register') }}">Đăng ký ngay</a>
    </p>
@endsection

<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const icon = input.nextElementSibling;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

