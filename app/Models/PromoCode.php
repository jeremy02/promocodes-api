<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'description',
        'discount_amount',
        'radius',
        'radius_unit',
        'start_at',
        'end_at',
        'is_used',
        'is_active',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_active' => 'boolean',
    ];
}
