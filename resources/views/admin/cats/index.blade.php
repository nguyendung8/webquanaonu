@extends('layouts.admin', ['title' => 'Quản lý mèo'])

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <h1 class="page-title" style="margin:0">Quản lý mèo</h1>
        <button onclick="openModal()" class="btn">+ Thêm mèo</button>
    </div>

    <form method="GET" style="display:grid;grid-template-columns:1fr auto;gap:12px;margin-bottom:20px">
        <input type="text" name="search" placeholder="Tìm theo tên mèo..." value="{{ request('search') }}" style="padding:10px 12px;border:1px solid #e6dbd3;border-radius:8px">
        <button type="submit" class="btn" style="white-space:nowrap">Lọc</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Giới tính</th>
                <th>Tuổi</th>
                <th>Giá/giờ</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cats as $cat)
            <tr>
                <td>
                    @if($cat->image)
                        <img src="{{ asset($cat->image) }}" class="preview" alt="{{ $cat->name }}">
                    @else
                        <span style="color:#999">—</span>
                    @endif
                </td>
                <td><strong>{{ $cat->name }}</strong></td>
                <td>{{ $cat->gender === 'male' ? 'Đực' : 'Cái' }}</td>
                <td>{{ $cat->age }} tuổi</td>
                <td>{{ number_format($cat->price, 0, ',', '.') }}đ</td>
                <td>
                    @if($cat->availability)
                        <span class="badge badge-success">Có sẵn</span>
                    @else
                        <span class="badge badge-danger">Không có sẵn</span>
                    @endif
                </td>
                <td>
                    <div class="actions">
                        <button onclick='editCat(@json($cat))' class="btn btn-sm btn-secondary">Sửa</button>
                        <form action="{{ route('admin.cats.destroy', $cat) }}" method="POST" onsubmit="return confirm('Xóa mèo này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#999">Chưa có mèo nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $cats->links('pagination::simple-default') }}
    </div>
</div>

<div id="catModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Thêm mèo</h2>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <form id="catForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="method" name="_method" value="POST">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label for="name">Tên mèo *</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="gender">Giới tính *</label>
                    <select id="gender" name="gender" required>
                        <option value="">-- Chọn --</option>
                        <option value="male">Đực</option>
                        <option value="female">Cái</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="age">Tuổi *</label>
                    <input type="number" id="age" name="age" min="0" required>
                </div>

                <div class="form-group">
                    <label for="price">Giá/giờ (VNĐ) *</label>
                    <input type="number" id="price" name="price" min="0" step="1000" required>
                </div>
            </div>

            <div class="form-group">
                <label for="personality">Tính cách</label>
                <textarea id="personality" name="personality"></textarea>
            </div>

            <div class="form-group">
                <label for="image">Ảnh mèo</label>
                <input type="file" id="image" name="image" accept="image/*">
                <div id="currentImage"></div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px">
                    <input type="checkbox" id="availability" name="availability" value="1" checked>
                    <span>Có sẵn</span>
                </label>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn">Lưu</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Thêm mèo';
    document.getElementById('catForm').action = '{{ route("admin.cats.store") }}';
    document.getElementById('method').value = 'POST';
    document.getElementById('name').value = '';
    document.getElementById('gender').value = '';
    document.getElementById('age').value = '';
    document.getElementById('price').value = '';
    document.getElementById('personality').value = '';
    document.getElementById('availability').checked = true;
    document.getElementById('currentImage').innerHTML = '';
    document.getElementById('catModal').style.display = 'flex';
}

function editCat(cat) {
    document.getElementById('modalTitle').textContent = 'Sửa mèo';
    document.getElementById('catForm').action = '/admin/cats/' + cat.id;
    document.getElementById('method').value = 'PUT';
    document.getElementById('name').value = cat.name;
    document.getElementById('gender').value = cat.gender;
    document.getElementById('age').value = cat.age;
    document.getElementById('price').value = cat.price;
    document.getElementById('personality').value = cat.personality || '';
    document.getElementById('availability').checked = cat.availability;
    if(cat.image) {
        document.getElementById('currentImage').innerHTML = '<img src="/'+cat.image+'" class="preview" style="margin-top:8px">';
    } else {
        document.getElementById('currentImage').innerHTML = '';
    }
    document.getElementById('catModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('catModal').style.display = 'none';
}

window.onclick = function(e) {
    const modal = document.getElementById('catModal');
    if (e.target === modal) closeModal();
}
</script>
@endsection
