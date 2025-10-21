<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cat_id',
        'booking_date',
        'booking_time',
        'duration_hours',
        'amount',
        'status',
        'payment_img',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'booking_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }
}

