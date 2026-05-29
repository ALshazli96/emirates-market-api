<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyDetail extends Model
{
    protected $fillable = [
        'listing_id', 'property_type', 'operation_type',
        'area_sqft', 'bedrooms', 'bathrooms', 'floor',
        'total_floors', 'furnished', 'agent_name',
        'permit_number', 'yearly_price', 'roi_percentage',
    ];

    protected $casts = [
        'area_sqft'       => 'decimal:2',
        'yearly_price'    => 'decimal:2',
        'roi_percentage'  => 'decimal:2',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}