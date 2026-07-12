<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonorApiController extends Controller
{
    public function apply(Request $request): JsonResponse
    {
        $data = $request->validate([
            'blood_type' => 'nullable|string|max:5',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        /** @var User $user */
        $user = $request->user();

        $donor = Donor::updateOrCreate(
            ['user_id' => $user->id],
            [
                'blood_type' => $data['blood_type'] ?? null,
                'verification_status' => 'unverified',
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'donor_status' => 'available',
            ]
        );

        return response()->json($donor);
    }
}
