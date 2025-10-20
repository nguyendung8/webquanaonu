@extends('layouts.admin', ['title' => 'Danh mục sản phẩm'])

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <h1 class="page-title" style="margin:0">Danh mục sản phẩm</h1>
        <button onclick="openModal()" class="btn">+ Thêm danh mục</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Số sản phẩm</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td><strong>{{ $category->name }}</strong></td>
                <td>{{ $category->description ?? '—' }}</td>
                <td>{{ $category->products_count }}</td>
                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="actions">
                        <button onclick='editCategory(@json($category))' class="btn btn-sm btn-secondary">Sửa</button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Xóa danh mục này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#999">Chưa có danh mục nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $categories->links('pagination::simple-default') }}
    </div>
</div>

<div id="categoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Thêm danh mục</h2>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <form id="categoryForm" method="POST">
            @csrf
            <input type="hidden" id="method" name="_method" value="POST">

            <div class="form-group">
                <label for="name">Tên danh mục *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description"></textarea>
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
    document.getElementById('modalTitle').textContent = 'Thêm danh mục';
    document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
    document.getElementById('method').value = 'POST';
    document.getElementById('name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('categoryModal').style.display = 'flex';
}

function editCategory(cat) {
    document.getElementById('modalTitle').textContent = 'Sửa danh mục';
    document.getElementById('categoryForm').action = '/admin/categories/' + cat.id;
    document.getElementById('method').value = 'PUT';
    document.getElementById('name').value = cat.name;
    document.getElementById('description').value = cat.description || '';
    document.getElementById('categoryModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

window.onclick = function(e) {
    const modal = document.getElementById('categoryModal');
    if (e.target === modal) closeModal();
}
</script>
@endsection
