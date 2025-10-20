@extends('layouts.app', ['title' => 'Đổi mật khẩu'])

@section('content')
<div class="container">
    <div class="card" style="max-width:600px;margin:0 auto;padding:32px">
        <h1 class="section-title" style="text-align:left;margin-bottom:24px">Đổi mật khẩu</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:20px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.change-password.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div style="margin-bottom:20px">
                <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                    <i class="fas fa-lock"></i> Mật khẩu hiện tại *
                </label>
                <div style="position:relative">
                    <input type="password" id="current_password" name="current_password" required autofocus style="width:100%;padding:12px 40px 12px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px">
                    <i class="fas fa-eye toggle-password" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999" onclick="togglePassword('current_password')"></i>
                </div>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                    <i class="fas fa-key"></i> Mật khẩu mới *
                </label>
                <div style="position:relative">
                    <input type="password" id="new_password" name="new_password" required style="width:100%;padding:12px 40px 12px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px">
                    <i class="fas fa-eye toggle-password" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999" onclick="togglePassword('new_password')"></i>
                </div>
                <small style="color:#666;font-size:13px;display:block;margin-top:4px">
                    Mật khẩu phải có ít nhất 6 ký tự
                </small>
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                    <i class="fas fa-check-circle"></i> Xác nhận mật khẩu mới *
                </label>
                <div style="position:relative">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required style="width:100%;padding:12px 40px 12px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px">
                    <i class="fas fa-eye toggle-password" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999" onclick="togglePassword('new_password_confirmation')"></i>
                </div>
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn" style="flex:1">
                    <i class="fas fa-save"></i> Đổi mật khẩu
                </button>
                <a href="{{ route('home') }}" class="btn btn-secondary" style="flex:1;text-align:center">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush

