@extends('layouts.auth', ['title' => 'Đăng ký'])

@section('content')
    <h2>Tạo tài khoản</h2>
    <p class="sub">Tham gia cộng đồng thời trang nữ tại Pamela Shop.</p>

    @if ($errors->any())
        <div class="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="row">
            <div>
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>
        </div>
        <div class="row">
            <div>
                <label for="password">Mật khẩu</label>
                <div style="position:relative">
                    <input id="password" name="password" type="password" required style="padding-right:40px">
                    <i class="fas fa-eye" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999" onclick="togglePassword('password')"></i>
                </div>
            </div>
            <div>
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <div style="position:relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required style="padding-right:40px">
                    <i class="fas fa-eye" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999" onclick="togglePassword('password_confirmation')"></i>
                </div>
            </div>
        </div>
        <button class="btn" type="submit">Đăng ký</button>
    </form>

    <p class="muted" style="margin-top:12px;">
        Đã có tài khoản? <a class="link" href="{{ route('login') }}">Đăng nhập</a>
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

