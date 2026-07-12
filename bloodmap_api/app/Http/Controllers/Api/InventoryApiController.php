<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BloodInventory::with('facility');

        if ($request->user()?->facility_id) {
            $query->where('facility_id', $request->user()->facility_id);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'blood_type' => 'required|string',
            'component_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'collection_date' => 'required|date',
            'expiry_date' => 'required|date',
        ]);

        $daysOld = now()->parse($data['collection_date'])->diffInDays(now());
        $data['freshness_flag'] = $daysOld <= 14 ? 'fresh' : 'standard';

        return response()->json(BloodInventory::create($data)->load('facility'), 201);
    }

    public function destroy(BloodInventory $inventory): JsonResponse
    {
        $inventory->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
