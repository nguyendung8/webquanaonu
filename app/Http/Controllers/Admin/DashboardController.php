<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cat;
use App\Models\Order;
use App\Models\Booking;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'cats' => Cat::count(),
            'orders' => Order::count(),
            'bookings' => Booking::count(),
            'customers' => User::where('role', 'user')->count(),
            'revenue_orders' => Order::where('status', 'completed')->sum('total'),
            'revenue_bookings' => Booking::where('status', 'confirmed')->sum('amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentBookings = Booking::with('user', 'cat')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentBookings'));
    }

    public function stats()
    {
        $currentYear = now()->year;

        $ordersMonthly = Order::where('status', 'completed')
            ->whereYear('created_at', $currentYear)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $bookingsMonthly = Booking::where('status', 'confirmed')
            ->whereYear('created_at', $currentYear)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = [
                'month' => $m,
                'month_name' => date('M', mktime(0, 0, 0, $m, 1)),
                'orders_revenue' => $ordersMonthly->get($m)?->revenue ?? 0,
                'orders_count' => $ordersMonthly->get($m)?->count ?? 0,
                'bookings_revenue' => $bookingsMonthly->get($m)?->revenue ?? 0,
                'bookings_count' => $bookingsMonthly->get($m)?->count ?? 0,
            ];
        }

        return view('admin.stats', compact('monthlyData', 'currentYear'));
    }
}

