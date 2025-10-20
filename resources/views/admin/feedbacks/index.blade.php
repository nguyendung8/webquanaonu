@extends('layouts.admin', ['title' => 'Quản lý phản hồi'])

@section('content')
<div class="card">
    <h1 class="page-title">Quản lý phản hồi</h1>

    <table>
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Sản phẩm/Mèo</th>
                <th>Đánh giá</th>
                <th>Nội dung</th>
                <th>Ngày gửi</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $feedback)
            <tr>
                <td>{{ $feedback->user->username }}</td>
                <td>
                    @if($feedback->product)
                        <span style="color:var(--coffee)">Sản phẩm:</span> {{ $feedback->product->name }}
                    @elseif($feedback->cat)
                        <span style="color:var(--leaf)">Mèo:</span> {{ $feedback->cat->name }}
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if($feedback->rating)
                        <span style="color:#f59e0b">{{ str_repeat('★', $feedback->rating) }}</span>
                    @else
                        —
                    @endif
                </td>
                <td style="max-width:300px">{{ Str::limit($feedback->content, 100) }}</td>
                <td>{{ $feedback->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.feedbacks.destroy', $feedback) }}" method="POST" onsubmit="return confirm('Xóa phản hồi này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#999">Chưa có phản hồi nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $feedbacks->links('pagination::simple-default') }}
    </div>
</div>
@endsection

