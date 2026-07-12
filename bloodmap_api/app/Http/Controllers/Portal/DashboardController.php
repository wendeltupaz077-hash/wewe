<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $facilityQuery = Facility::query();
        if ($user->facility_id) {
            $facilityQuery->where('id', $user->facility_id);
        }

        $stats = [
            'facilities' => $facilityQuery->count(),
            'inventory_units' => BloodInventory::when($user->facility_id, fn ($q) => $q->where('facility_id', $user->facility_id))
                ->where('status', 'available')->sum('quantity'),
            'active_requests' => EmergencyRequest::when($user->facility_id, fn ($q) => $q->where('facility_id', $user->facility_id))
                ->where('status', '!=', 'resolved')->count(),
            'registered_users' => $user->isAdminUser()
                ? User::where('role', 'user')
                    ->whereNotNull('email')
                    ->where('is_registered', true)
                    ->count()
                : 0,
            'donors' => Donor::count(),
            'near_expiry' => BloodInventory::when($user->facility_id, fn ($q) => $q->where('facility_id', $user->facility_id))
                ->where('status', 'available')
                ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
                ->count(),
        ];

        $recentRequests = EmergencyRequest::with('facility')
            ->when($user->facility_id, fn ($q) => $q->where('facility_id', $user->facility_id))
            ->latest()->take(5)->get();

        return view('portal.dashboard', compact('stats', 'recentRequests'));
    }
}
