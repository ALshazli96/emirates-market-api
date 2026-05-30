<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Listing::with(['carDetail', 'propertyDetail'])
            ->where('status', 'active');

        // البحث بالكلمة
        if ($request->q) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('title_ar', 'like', "%{$q}%")
                      ->orWhere('title_en', 'like', "%{$q}%")
                      ->orWhere('city', 'like', "%{$q}%");
            });
        }

        // فلتر النوع
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // فلتر المدينة
        if ($request->city) {
            $query->where('city', $request->city);
        }

        // فلتر السعر
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // الترتيب
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderByDesc('is_featured')
              ->orderBy($sortBy, $sortOrder);

        $results = $query->paginate(20);

        return response()->json([
            'success' => true,
            'total'   => $results->total(),
            'data'    => $results,
        ]);
    }
}