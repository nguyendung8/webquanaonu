<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function toggleActive(User $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'mở khóa' : 'khóa';
        return redirect()->route('admin.customers.index')->with('success', "Đã {$status} tài khoản.");
    }

    public function destroy(User $customer)
    {
        if ($customer->role === 'admin') {
            return back()->with('error', 'Không thể xóa tài khoản admin.');
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Đã xóa khách hàng.');
    }
}

