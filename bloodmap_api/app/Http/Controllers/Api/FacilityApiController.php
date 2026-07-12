<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;

class FacilityApiController extends Controller
{
    public function index(): JsonResponse
    {
        $facilities = Facility::query()->orderBy('name')->get()->map(fn (Facility $f) => [
            'id' => $f->id,
            'name' => $f->name,
            'type' => $f->type,
            'city' => $f->city,
            'stock_status' => $f->computeStockStatus(),
            'accepts_donations' => $f->accepts_donations,
            'total_units' => $f->availableUnits(),
            'latitude' => $f->latitude,
            'longitude' => $f->longitude,
        ]);

        return response()->json($facilities);
    }

    public function show(Facility $facility): JsonResponse
    {
        return response()->json([
            'facility' => $facility,
            'stock_status' => $facility->computeStockStatus(),
            'total_units' => $facility->availableUnits(),
            'inventory' => $facility->inventory()
                ->where('status', 'available')
                ->where('expiry_date', '>=', now()->toDateString())
                ->get(),
        ]);
    }
}
