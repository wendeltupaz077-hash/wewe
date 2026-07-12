<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::query()
            ->with(['inventory' => fn ($q) => $q->where('status', 'available')
                ->where('expiry_date', '>=', now()->toDateString())])
            ->orderBy('name')
            ->get()
            ->map(function (Facility $f) {
                $f->computed_status = $f->computeStockStatus();
                $f->total_units = $f->availableUnits();

                return $f;
            });

        return view('portal.stock', compact('facilities'));
    }
}
