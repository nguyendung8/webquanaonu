<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    protected $table = 'cats';

    protected $fillable = [
        'name',
        'gender',
        'age',
        'personality',
        'image',
        'price',
        'availability',
    ];

    protected $casts = [
        'availability' => 'boolean',
        'price' => 'decimal:2',
    ];
}

