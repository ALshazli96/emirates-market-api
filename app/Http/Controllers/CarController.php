<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\CarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    // عرض كل السيارات
    public function index(Request $request)
    {
        $cars = Listing::with('carDetail')
            ->where('type', 'car')
            ->where('status', 'active')
            ->when($request->city, fn($q, $c) => $q->where('city', $c))
            ->when($request->min_price, fn($q, $p) => $q->where('price', '>=', $p))
            ->when($request->max_price, fn($q, $p) => $q->where('price', '<=', $p))
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $cars,
        ]);
    }

    // إضافة إعلان سيارة
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar'     => 'required|string',
            'price'        => 'required|numeric',
            'city'         => 'required|string',
            'contact_phone'=> 'required|string',
            'make'         => 'required|string',
            'model'        => 'required|string',
            'year'         => 'required|integer',
            'mileage'      => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $listing = Listing::create([
            'user_id'       => auth('api')->id(),
            'type'          => 'car',
            'title_ar'      => $request->title_ar,
            'title_en'      => $request->title_en,
            'price'         => $request->price,
            'city'          => $request->city,
            'location'      => $request->location,
            'contact_phone' => $request->contact_phone,
            'status'        => 'active',
        ]);

        $listing->carDetail()->create([
            'make'         => $request->make,
            'model'        => $request->model,
            'year'         => $request->year,
            'mileage'      => $request->mileage,
            'color'        => $request->color,
            'fuel_type'    => $request->fuel_type ?? 'petrol',
            'transmission' => $request->transmission ?? 'automatic',
            'condition'    => $request->condition ?? 'used',
            'specs'        => $request->specs ?? 'gcc',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الإعلان بنجاح',
            'data'    => $listing->load('carDetail'),
        ], 201);
    }

    // عرض إعلان واحد
    public function show($id)
    {
        $listing = Listing::with(['carDetail', 'user'])
            ->where('type', 'car')
            ->findOrFail($id);

        $listing->increment('views_count');

        return response()->json([
            'success' => true,
            'data'    => $listing,
        ]);
    }
}