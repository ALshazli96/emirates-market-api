<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDetail extends Model
{
    protected $fillable = [
        'listing_id', 'make', 'model', 'year',
        'mileage', 'color', 'fuel_type', 'transmission',
        'condition', 'specs', 'body_type', 'cylinders',
        'finance_available', 'is_documented',
    ];

    protected $casts = [
        'finance_available' => 'boolean',
        'is_documented'     => 'boolean',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}