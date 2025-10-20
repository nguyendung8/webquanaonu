@extends('layouts.admin', ['title' => 'Quản lý khách hàng'])

@section('content')
<div class="card">
    <h1 class="page-title">Quản lý khách hàng</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Ngày đăng ký</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td><strong>{{ $customer->username }}</strong></td>
                <td>{{ $customer->email }}</td>
                <td>
                    @if($customer->is_active)
                        <span class="badge badge-success">Hoạt động</span>
                    @else
                        <span class="badge badge-danger">Bị khóa</span>
                    @endif
                </td>
                <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="actions">
                        <form action="{{ route('admin.customers.toggleActive', $customer) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $customer->is_active ? 'btn-danger' : 'btn-secondary' }}">
                                {{ $customer->is_active ? 'Khóa' : 'Mở khóa' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" style="display:inline" onsubmit="return confirm('Xóa khách hàng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#999">Chưa có khách hàng nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $customers->links('pagination::simple-default') }}
    </div>
</div>
@endsection

