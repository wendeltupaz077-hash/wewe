<?php


namespace App\Http\Controllers\Portal;


use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class RequestController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $requests = EmergencyRequest::with('facility')
            ->when($user->facility_id, fn ($q) => $q->where('facility_id', $user->facility_id))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->whereHas('facility', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
                    ->orWhere('blood_type', 'like', '%'.$request->search.'%')
                    ->orWhere('notes', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('blood_type'), fn ($q) => $q->where('blood_type', $request->blood_type))
            ->when($request->filled('component_type'), fn ($q) => $q->where('component_type', $request->component_type))
            ->when($request->filled('urgency'), fn ($q) => $q->where('urgency', $request->urgency))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('portal.requests', compact('requests'));
    }
}
