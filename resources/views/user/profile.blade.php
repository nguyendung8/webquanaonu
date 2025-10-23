@extends('layouts.app', ['title' => 'Thông tin cá nhân'])

@section('content')
<div class="container">
    <div class="card" style="max-width:800px;margin:0 auto;padding:32px">
        <h1 class="section-title" style="text-align:left;margin-bottom:24px">Thông tin cá nhân</h1>

        @if (session('success'))
            <div class="alert alert-success" style="margin-bottom:20px">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px">
                <ul style="margin:0;padding-left:20px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
            <div>
                <h3 style="margin-bottom:16px;color:var(--coffee)">Thông tin cơ bản</h3>
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div style="margin-bottom:20px">
                        <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                            <i class="fas fa-user"></i> Tên đăng nhập *
                        </label>
                        <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px">
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                            <i class="fas fa-envelope"></i> Email *
                        </label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px">
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                            <i class="fas fa-phone"></i> Số điện thoại
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px" placeholder="Nhập số điện thoại">
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                            <i class="fas fa-map-marker-alt"></i> Địa chỉ
                        </label>
                        <textarea name="address" rows="3" style="width:100%;padding:12px;border:1px solid var(--border);border-radius:8px;font-size:14px;resize:vertical" placeholder="Nhập địa chỉ giao hàng">{{ old('address', auth()->user()->address) }}</textarea>
                    </div>

                    <button type="submit" class="btn" style="width:100%">
                        <i class="fas fa-save"></i> Cập nhật thông tin
                    </button>
                </form>
            </div>

            <div>
                <h3 style="margin-bottom:16px;color:var(--coffee)">Đổi mật khẩu</h3>
                
                <div style="margin-bottom:16px">
                    <button type="button" class="btn btn-secondary" onclick="togglePasswordForm()" style="width:100%">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </button>
                </div>

                <div id="passwordForm" style="display:none">
                    <form action="{{ route('user.change-password.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div style="margin-bottom:20px">
                            <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--dark)">
                                <i class="fas fa-lock"></i> Mật khẩu hiện tại *
                            </label>
                            <div style="position:relative">
                                <input type="password" id="current_password" name="current_password" required style="width:100%;padding:12px 40px 12px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px">
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
                            <button type="button" class="btn btn-secondary" onclick="togglePasswordForm()" style="flex:1">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                        </div>
                    </form>
                </div>

                <div style="margin-top:24px;padding:16px;background:#f8f9fa;border-radius:8px">
                    <h4 style="margin-bottom:8px;color:var(--coffee)">Thông tin tài khoản</h4>
                    <p style="margin:4px 0;color:#666"><strong>Vai trò:</strong> {{ auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}</p>
                    <p style="margin:4px 0;color:#666"><strong>Trạng thái:</strong> {{ auth()->user()->is_active ? 'Hoạt động' : 'Tạm khóa' }}</p>
                    <p style="margin:4px 0;color:#666"><strong>Ngày tạo:</strong> {{ auth()->user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePasswordForm() {
    const form = document.getElementById('passwordForm');
    const isVisible = form.style.display !== 'none';
    form.style.display = isVisible ? 'none' : 'block';
    
    if (!isVisible) {
        form.scrollIntoView({ behavior: 'smooth' });
    }
}

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
