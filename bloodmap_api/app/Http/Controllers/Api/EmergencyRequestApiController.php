<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use App\Services\DonorMatchingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmergencyRequestApiController extends Controller
{
    public function __construct(private DonorMatchingService $matching) {}

    public function index(Request $request): JsonResponse
    {
        $query = EmergencyRequest::with('facility')->latest();
        if ($request->user()?->facility_id) {
            $query->where('facility_id', $request->user()->facility_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'blood_type' => 'required|string',
            'component_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'urgency' => 'required|in:normal,urgent,critical',
            'notes' => 'nullable|string',
        ]);

        $data['requested_by'] = $request->user()?->id;
        $data['status'] = 'pending';

        $emergency = EmergencyRequest::create($data);
        $match = $this->matching->matchForRequest($emergency);
        $emergency->update(['escalation_level' => $match['escalation']]);

        return response()->json([
            'request' => $emergency->load('facility'),
            'matching' => $match,
        ], 201);
    }
}
