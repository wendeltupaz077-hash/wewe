<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\EmergencyRequest;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $inventoryByType = BloodInventory::query()
            ->selectRaw('blood_type, SUM(quantity) as total')
            ->where('status', 'available')
            ->groupBy('blood_type')
            ->orderByDesc('total')
            ->pluck('total', 'blood_type');

        $monthlyDonations = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->startOfMonth();
            $total = BloodInventory::query()
                ->whereBetween('collection_date', [
                    $month->toDateString(),
                    $month->copy()->endOfMonth()->toDateString(),
                ])
                ->sum('quantity');

            $monthlyDonations->put($month->format('M'), (int) $total);
        }

        $inventoryByComponent = BloodInventory::query()
            ->selectRaw('component_type, SUM(quantity) as total')
            ->where('status', 'available')
            ->groupBy('component_type')
            ->orderByDesc('total')
            ->get()
            ->mapWithKeys(fn ($row) => [$this->componentLabel($row->component_type) => (int) $row->total]);

        $requestsByStatus = EmergencyRequest::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('portal.reports', compact(
            'inventoryByType',
            'monthlyDonations',
            'inventoryByComponent',
            'requestsByStatus',
        ));
    }

    private function componentLabel(string $type): string
    {
        return match ($type) {
            'whole_blood' => 'Whole Blood',
            'packed_rbc' => 'PRBC',
            'platelets' => 'Platelets',
            'plasma' => 'Plasma',
            'irradiated' => 'Irradiated',
            default => ucwords(str_replace('_', ' ', $type)),
        };
    }
}
