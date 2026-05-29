<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title_ar', 'title_en',
        'description_ar', 'description_en', 'price',
        'currency', 'city', 'location', 'latitude',
        'longitude', 'contact_phone', 'is_featured',
        'status', 'views_count', 'expires_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price'       => 'decimal:2',
        'latitude'    => 'decimal:8',
        'longitude'   => 'decimal:8',
    ];

    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع تفاصيل السيارة
    public function carDetail()
    {
        return $this->hasOne(CarDetail::class);
    }

    // علاقة مع تفاصيل العقار
    public function propertyDetail()
    {
        return $this->hasOne(PropertyDetail::class);
    }

    // scope للإعلانات النشطة
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}