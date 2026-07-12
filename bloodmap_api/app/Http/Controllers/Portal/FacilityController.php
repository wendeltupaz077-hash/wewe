<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\View\View;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FacilityController extends Controller
{
    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        if (! $user || ! $user->isAdminUser()) {
            abort(403);
        }
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $facilityTypes = [
            'hospital' => 'Hospital',
            'clinic' => 'Clinic',
            'blood_center' => 'Blood Center',
            'mobile' => 'Mobile Unit',
        ];

        $heads = User::whereIn('role', ['facility_head', 'facility_staff'])->orderBy('name')->get();

        $facilities = Facility::with(['head'])
            ->withCount('inventory')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%')
                    ->orWhere('province', 'like', '%'.$request->search.'%');
            }))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->has('locked') && in_array($request->locked, ['0', '1']), fn ($q) => $q->where('is_locked', $request->locked))
            ->when($request->filled('head_id'), fn ($q) => $q->where('head_user_id', $request->head_id))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('portal.facilities', compact('facilities', 'facilityTypes', 'heads'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        $heads = User::where('role', 'facility_head')->orWhere('role', 'facility_staff')->orderBy('name')->get();
        return view('portal.facilities.create', compact('heads'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'accepts_donations' => 'sometimes|boolean',
            'is_locked' => 'sometimes|boolean',
            'head_user_id' => 'nullable|exists:users,id',
        ]);

        $facility = Facility::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'accepts_donations' => $request->boolean('accepts_donations'),
            'is_locked' => $request->boolean('is_locked'),
            'head_user_id' => $data['head_user_id'] ?? null,
        ]);

        if (! empty($data['head_user_id'])) {
            $headUser = User::find($data['head_user_id']);
            if ($headUser) {
                $headUser->update(['facility_id' => $facility->id]);
            }
        }

        return redirect()->route('portal.facilities.index')->with('success', 'Facility created successfully!');
    }

    public function edit(Facility $facility): View
    {
        $this->authorizeAdmin();

        $heads = User::whereIn('role', ['facility_head', 'facility_staff'])->orderBy('name')->get();
        return view('portal.facilities.edit', compact('facility', 'heads'));
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'accepts_donations' => 'sometimes|boolean',
            'is_locked' => 'sometimes|boolean',
            'head_user_id' => 'nullable|exists:users,id',
        ]);

        if ($facility->head_user_id && $facility->head_user_id !== ($data['head_user_id'] ?? null)) {
            User::where('id', $facility->head_user_id)->update(['facility_id' => null]);
        }

        $facility->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_email' => $data['contact_email'] ?? null,
            'accepts_donations' => $request->boolean('accepts_donations'),
            'is_locked' => $request->boolean('is_locked'),
            'head_user_id' => $data['head_user_id'] ?? null,
        ]);

        if (! empty($data['head_user_id'])) {
            User::where('id', $data['head_user_id'])->update(['facility_id' => $facility->id]);
        }

        return redirect()->route('portal.facilities.index')->with('success', 'Facility updated successfully!');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($facility->head_user_id) {
            User::where('id', $facility->head_user_id)->update(['facility_id' => null]);
        }

        $facility->delete();

        return redirect()->route('portal.facilities.index')->with('success', 'Facility deleted successfully!');
    }

    public function toggleLock(Facility $facility): RedirectResponse
    {
        $this->authorizeAdmin();

        $facility->update(['is_locked' => ! $facility->is_locked]);
        $message = $facility->is_locked ? 'Facility locked.' : 'Facility unlocked.';

        return redirect()->route('portal.facilities.index')->with('success', $message);
    }
}
