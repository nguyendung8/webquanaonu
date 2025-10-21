<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatController extends Controller
{
    public function index(Request $request)
    {
        $query = Cat::query();

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by age range
        if ($request->filled('min_age')) {
            $query->where('age', '>=', $request->min_age);
        }
        if ($request->filled('max_age')) {
            $query->where('age', '<=', $request->max_age);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $cats = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Check real-time availability for each cat
        $cats->getCollection()->transform(function ($cat) {
            $now = now();
            $today = $now->format('Y-m-d');
            $currentTime = $now->format('H:i:s');

            // Check if cat is currently booked (only confirmed bookings)
            $isCurrentlyBooked = Booking::where('cat_id', $cat->id)
                ->where('booking_date', $today)
                ->where('status', 'confirmed')
                ->where(function($query) use ($currentTime) {
                    $query->where('booking_time', '<=', $currentTime)
                          ->whereRaw('ADDTIME(booking_time, CONCAT(duration_hours, ":00:00")) > ?', [$currentTime]);
                })
                ->exists();

            // Check if cat has any future confirmed bookings
            $hasFutureBookings = Booking::where('cat_id', $cat->id)
                ->where('status', 'confirmed')
                ->where(function($query) use ($today, $currentTime) {
                    $query->where('booking_date', '>', $today)
                          ->orWhere(function($subQuery) use ($today, $currentTime) {
                              $subQuery->where('booking_date', $today)
                                       ->where('booking_time', '>', $currentTime);
                          });
                })
                ->exists();

            $cat->is_available = !$isCurrentlyBooked && !$hasFutureBookings;
            return $cat;
        });

        return view('user.cats', compact('cats'));
    }

    public function showBookingForm(Cat $cat)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt lịch.');
        }

        if (!$cat->availability) {
            return redirect()->route('user.cats')->with('error', 'Mèo này hiện không khả dụng.');
        }

        return view('user.cat-booking', compact('cat'));
    }

    public function book(Request $request, Cat $cat)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'duration_hours' => 'required|integer|min:1|max:8',
            'payment_img' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'booking_date.after_or_equal' => 'Ngày đặt lịch phải từ hôm nay trở đi.',
            'payment_img.required' => 'Vui lòng tải ảnh đặt cọc.',
            'payment_img.image' => 'File phải là hình ảnh hợp lệ.',
            'payment_img.mimes' => 'Chỉ chấp nhận jpg, jpeg, png, webp.',
            'payment_img.max' => 'Kích thước ảnh tối đa 4MB.',
        ]);

        // Check if cat is currently available (real-time check)
        $now = now();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        $isCurrentlyBooked = Booking::where('cat_id', $cat->id)
            ->where('booking_date', $today)
            ->where('status', 'confirmed')
            ->where(function($query) use ($currentTime) {
                $query->where('booking_time', '<=', $currentTime)
                      ->whereRaw('ADDTIME(booking_time, CONCAT(duration_hours, ":00:00")) > ?', [$currentTime]);
            })
            ->exists();

        if ($isCurrentlyBooked) {
            return back()->withErrors(['cat' => 'Mèo này hiện đang được đặt.'])->withInput();
        }

        // Check for time conflicts
        $conflict = Booking::where('cat_id', $cat->id)
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($validated) {
                $startTime = $validated['booking_time'];
                $endTime = date('H:i:s', strtotime($startTime . ' +' . $validated['duration_hours'] . ' hours'));

                $query->where(function($q) use ($startTime, $endTime) {
                    // Check if new booking overlaps with existing bookings
                    $q->where(function($subQ) use ($startTime, $endTime) {
                        // New booking starts during existing booking
                        $subQ->where('booking_time', '<=', $startTime)
                             ->whereRaw('ADDTIME(booking_time, CONCAT(duration_hours, ":00:00")) > ?', [$startTime]);
                    })->orWhere(function($subQ) use ($startTime, $endTime) {
                        // New booking ends during existing booking
                        $subQ->where('booking_time', '<', $endTime)
                             ->whereRaw('ADDTIME(booking_time, CONCAT(duration_hours, ":00:00")) >= ?', [$endTime]);
                    })->orWhere(function($subQ) use ($startTime, $endTime) {
                        // New booking completely contains existing booking
                        $subQ->where('booking_time', '>=', $startTime)
                             ->whereRaw('ADDTIME(booking_time, CONCAT(duration_hours, ":00:00")) <= ?', [$endTime]);
                    });
                });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['time' => 'Khung giờ này đã được đặt. Vui lòng chọn thời gian khác.'])->withInput();
        }

        // Calculate amounts
        $totalAmount = $cat->price * $validated['duration_hours'];
        $depositAmount = $totalAmount / 2; // 50% deposit

        // Handle payment image upload
        $paymentImagePath = null;
        if ($request->hasFile('payment_img')) {
            $file = $request->file('payment_img');
            $filename = uniqid('deposit_') . '.' . $file->getClientOriginalExtension();
            $targetDir = public_path('storage/bookings');
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $file->move($targetDir, $filename);
            $paymentImagePath = 'storage/bookings/' . $filename;
        }

        // Create booking
        Booking::create([
            'user_id' => auth()->id(),
            'cat_id' => $cat->id,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'duration_hours' => $validated['duration_hours'],
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_img' => $paymentImagePath,
        ]);

        return redirect()->route('user.bookings')->with('success', 'Đặt lịch thành công! Vui lòng chờ xác nhận từ admin.');
    }
}
