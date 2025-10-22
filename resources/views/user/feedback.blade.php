@extends('layouts.app', ['title' => 'Phản hồi'])

@section('content')
<div class="container">
    <h1 class="section-title">Gửi phản hồi</h1>
    <p style="text-align:center;color:#666;margin-bottom:40px">Chúng tôi rất mong nhận được ý kiến đóng góp từ bạn</p>

    <div class="card" style="max-width:700px;margin:0 auto;padding:32px">
        @auth
            <form action="{{ route('user.feedback.store') }}" method="POST">
                @csrf

                <div style="margin-bottom:20px">
                    <label style="display:block;margin-bottom:8px;font-weight:600">Đánh giá *</label>
                    <div style="display:flex;gap:8px">
                        @for($i = 1; $i <= 5; $i++)
                            <label style="cursor:pointer">
                                <input type="radio" name="rating" value="{{ $i }}" style="display:none" class="rating-input" {{ $i == 5 ? 'checked' : '' }}>
                                <i class="far fa-star rating-star" data-value="{{ $i }}" style="font-size:24px;color:#fbbf24"></i>
                            </label>
                        @endfor
                    </div>
                    @error('rating')
                        <span style="color:#e63946;font-size:14px">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom:20px">
                    <label style="display:block;margin-bottom:8px;font-weight:600">Nội dung phản hồi *</label>
                    <textarea name="content" rows="6" required placeholder="Chia sẻ trải nghiệm của bạn về Pamela Shop..."></textarea>
                    @error('content')
                        <span style="color:#e63946;font-size:14px">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn" style="width:100%">Gửi phản hồi</button>
            </form>
        @else
            <div style="text-align:center;padding:40px">
                <i class="fas fa-lock" style="font-size:48px;color:var(--pink);margin-bottom:16px"></i>
                <h3 style="margin-bottom:16px">Vui lòng đăng nhập</h3>
                <p style="color:#666;margin-bottom:24px">Bạn cần đăng nhập để gửi phản hồi</p>
                <a href="{{ route('login') }}" class="btn">Đăng nhập ngay</a>
            </div>
        @endauth
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.dataset.value;
        document.querySelector(`input[name="rating"][value="${value}"]`).checked = true;

        document.querySelectorAll('.rating-star').forEach(s => {
            if (s.dataset.value <= value) {
                s.classList.remove('far');
                s.classList.add('fas');
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
            }
        });
    });
});

// Set default rating to 5
document.querySelector('input[name="rating"][value="5"]').checked = true;
document.querySelectorAll('.rating-star').forEach(s => {
    if (s.dataset.value <= 5) {
        s.classList.remove('far');
        s.classList.add('fas');
    }
});
</script>
@endsection

