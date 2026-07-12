<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorController extends Controller
{
    public function index(Request $request): View
    {
        $donors = Donor::with('user')
            ->when($request->filled('search'), fn ($q) => $q->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')))
            ->when($request->filled('blood_type'), fn ($q) => $q->where('blood_type', $request->blood_type))
            ->when($request->filled('verification_status'), fn ($q) => $q->where('verification_status', $request->verification_status))
            ->when($request->filled('donor_status'), fn ($q) => $q->where('donor_status', $request->donor_status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('portal.donors', compact('donors'));
    }
}
