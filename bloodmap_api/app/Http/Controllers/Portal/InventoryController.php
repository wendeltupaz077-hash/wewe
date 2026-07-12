<?php


namespace App\Http\Controllers\Portal;


use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $inventory = BloodInventory::with('facility')
            ->when($user->facility_id, fn ($q) => $q->where('facility_id', $user->facility_id))
            ->where('status', 'available')
            ->when($request->filled('search'), fn ($q) => $q->whereHas('facility', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')))
            ->when($request->filled('facility_id'), fn ($q) => $q->where('facility_id', $request->facility_id))
            ->when($request->filled('blood_type'), fn ($q) => $q->where('blood_type', $request->blood_type))
            ->when($request->filled('component_type'), fn ($q) => $q->where('component_type', $request->component_type))
            ->orderBy('expiry_date')
            ->paginate(20)
            ->withQueryString();

        $facilities = Facility::orderBy('name')->get();

        return view('portal.inventory', compact('inventory', 'facilities'));
    }
}
