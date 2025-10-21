@extends('layouts.admin', ['title' => 'Quản lý sản phẩm'])

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <h1 class="page-title" style="margin:0">Quản lý sản phẩm</h1>
        <button onclick="openModal()" class="btn">+ Thêm sản phẩm</button>
    </div>

    <form method="GET" style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;margin-bottom:20px">
        <input type="text" name="search" placeholder="Tìm theo tên sản phẩm..." value="{{ request('search') }}" style="padding:10px 12px;border:1px solid #e6dbd3;border-radius:8px">
        <select name="category_id" style="padding:10px 12px;border:1px solid #e6dbd3;border-radius:8px">
            <option value="">-- Tất cả danh mục --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn" style="white-space:nowrap">Lọc</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="preview" alt="{{ $product->name }}">
                    @else
                        <span style="color:#999">—</span>
                    @endif
                </td>
                <td><strong>{{ $product->name }}</strong></td>
                <td>{{ $product->category->name }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px">
                        <label class="toggle-switch">
                            <input type="checkbox" {{ $product->availability ? 'checked' : '' }}
                                   onchange="toggleAvailability({{ $product->id }}, this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </td>
                <td>
                    <div class="actions">
                        <button onclick='editProduct(@json($product))' class="btn btn-sm btn-secondary">Sửa</button>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#999">Chưa có sản phẩm nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $products->links('pagination::simple-default') }}
    </div>
</div>

<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Thêm sản phẩm</h2>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <form id="productForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="method" name="_method" value="POST">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group">
                    <label for="name">Tên sản phẩm *</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Giá (VNĐ) *</label>
                    <input type="number" id="price" name="price" min="0" step="1000" required>
                </div>

                <div class="form-group">
                    <label for="image">Ảnh sản phẩm</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div id="currentImage"></div>
                </div>
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
    document.getElementById('modalTitle').textContent = 'Thêm sản phẩm';
    document.getElementById('productForm').action = '{{ route("admin.products.store") }}';
    document.getElementById('method').value = 'POST';
    document.getElementById('name').value = '';
    document.getElementById('category_id').value = '';
    document.getElementById('price').value = '';
    document.getElementById('description').value = '';
    document.getElementById('currentImage').innerHTML = '';
    document.getElementById('productModal').style.display = 'flex';
}

function editProduct(prod) {
    document.getElementById('modalTitle').textContent = 'Sửa sản phẩm';
    document.getElementById('productForm').action = '/admin/products/' + prod.id;
    document.getElementById('method').value = 'PUT';
    document.getElementById('name').value = prod.name;
    document.getElementById('category_id').value = prod.category_id;
    document.getElementById('price').value = prod.price;
    document.getElementById('description').value = prod.description || '';
    if(prod.image) {
        document.getElementById('currentImage').innerHTML = '<img src="/'+prod.image+'" class="preview" style="margin-top:8px">';
    } else {
        document.getElementById('currentImage').innerHTML = '';
    }
    document.getElementById('productModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

window.onclick = function(e) {
    const modal = document.getElementById('productModal');
    if (e.target === modal) closeModal();
}

function toggleAvailability(productId, isAvailable) {
    fetch(`/admin/products/${productId}/toggle-availability`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            availability: isAvailable
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to refresh the product list
            window.location.reload();
        } else {
            // Revert toggle if failed
            event.target.checked = !isAvailable;
            alert('Có lỗi xảy ra khi cập nhật trạng thái');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        event.target.checked = !isAvailable;
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    });
}
</script>

<style>
/* Toggle Switch Styles */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
input:checked + .toggle-slider {
    background-color: #4CAF50;
}
input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
</style>
@endsection

