<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\PropertyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    // عرض كل العقارات
    public function index(Request $request)
    {
        $properties = Listing::with('propertyDetail')
            ->where('type', 'property')
            ->where('status', 'active')
            ->when($request->city, fn($q, $c) => $q->where('city', $c))
            ->when($request->min_price, fn($q, $p) => $q->where('price', '>=', $p))
            ->when($request->max_price, fn($q, $p) => $q->where('price', '<=', $p))
            ->when($request->type, fn($q, $t) =>
                $q->whereHas('propertyDetail', fn($q) =>
                    $q->where('property_type', $t)))
            ->when($request->operation, fn($q, $o) =>
                $q->whereHas('propertyDetail', fn($q) =>
                    $q->where('operation_type', $o)))
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $properties,
        ]);
    }

    // إضافة إعلان عقار
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar'       => 'required|string',
            'price'          => 'required|numeric',
            'city'           => 'required|string',
            'contact_phone'  => 'required|string',
            'property_type'  => 'required|in:apartment,villa,studio,land,office,shop',
            'operation_type' => 'required|in:sale,rent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $listing = Listing::create([
            'user_id'       => auth('api')->id(),
            'type'          => 'property',
            'title_ar'      => $request->title_ar,
            'title_en'      => $request->title_en,
            'price'         => $request->price,
            'city'          => $request->city,
            'location'      => $request->location,
            'contact_phone' => $request->contact_phone,
            'status'        => 'active',
        ]);

        $listing->propertyDetail()->create([
            'property_type'  => $request->property_type,
            'operation_type' => $request->operation_type,
            'area_sqft'      => $request->area_sqft,
            'bedrooms'       => $request->bedrooms,
            'bathrooms'      => $request->bathrooms,
            'floor'          => $request->floor,
            'furnished'      => $request->furnished ?? 'unfurnished',
            'agent_name'     => $request->agent_name,
            'yearly_price'   => $request->yearly_price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة إعلان العقار بنجاح',
            'data'    => $listing->load('propertyDetail'),
        ], 201);
    }

    // عرض عقار واحد
    public function show($id)
    {
        $listing = Listing::with(['propertyDetail', 'user'])
            ->where('type', 'property')
            ->findOrFail($id);

        $listing->increment('views_count');

        return response()->json([
            'success' => true,
            'data'    => $listing,
        ]);
    }
}