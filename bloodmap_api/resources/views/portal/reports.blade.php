@extends('layouts.portal')

@section('page-title', 'Reports')

@push('styles')
<style>
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .reports-grid .portal-card--wide {
        grid-column: 1 / -1;
    }

    .chart-wrap {
        position: relative;
        height: 280px;
    }

    .chart-wrap--tall {
        height: 320px;
    }

    @media (max-width: 900px) {
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="reports-grid">
    <div class="portal-card reveal">
        <h2>Blood Type Distribution</h2>
        @if($inventoryByType->isEmpty())
            <p class="empty-text">No inventory data.</p>
        @else
            <div class="chart-wrap">
                <canvas id="bloodTypeChart" aria-label="Blood type distribution chart"></canvas>
            </div>
        @endif
    </div>

    <div class="portal-card reveal">
        <h2>Monthly Donations (6 months)</h2>
        @if($monthlyDonations->sum() === 0)
            <p class="empty-text">No donation data for the last 6 months.</p>
        @else
            <div class="chart-wrap">
                <canvas id="monthlyDonationsChart" aria-label="Monthly donations chart"></canvas>
            </div>
        @endif
    </div>

    <div class="portal-card portal-card--wide reveal">
        <h2>Inventory by Component Type</h2>
        @if($inventoryByComponent->isEmpty())
            <p class="empty-text">No component inventory data.</p>
        @else
            <div class="chart-wrap chart-wrap--tall">
                <canvas id="componentChart" aria-label="Inventory by component type chart"></canvas>
            </div>
        @endif
    </div>

    <div class="portal-card portal-card--wide reveal">
        <h2>Requests by Status</h2>
        @if($requestsByStatus->isEmpty())
            <p class="empty-text">No request data.</p>
        @else
            <div class="chart-wrap">
                <canvas id="requestsChart" aria-label="Emergency requests by status chart"></canvas>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    const bloodRed = '#c41e3a';
    const bloodDark = '#8b0000';
    const palette = ['#3b82f6', '#f97316', '#ef4444', '#6b7280', '#ec4899', '#14b8a6', '#22c55e', '#a855f7'];

    const tooltipDefaults = {
        enabled: true,
        backgroundColor: 'rgba(255, 255, 255, 0.96)',
        titleColor: '#111827',
        bodyColor: bloodRed,
        borderColor: 'rgba(0, 0, 0, 0.08)',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 8,
        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '700' },
        bodyFont: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
        displayColors: true,
        boxPadding: 6,
    };

    const inventoryByType = @json($inventoryByType);
    const monthlyDonations = @json($monthlyDonations);
    const inventoryByComponent = @json($inventoryByComponent);
    const requestsByStatus = @json($requestsByStatus);

    if (Object.keys(inventoryByType).length) {
        new Chart(document.getElementById('bloodTypeChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(inventoryByType),
                datasets: [{
                    data: Object.values(inventoryByType),
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 12 } },
                    },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => ` units: ${item.raw}`,
                        },
                    },
                },
            },
        });
    }

    if (Object.values(monthlyDonations).some((v) => v > 0)) {
        new Chart(document.getElementById('monthlyDonationsChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyDonations),
                datasets: [{
                    label: 'Donations',
                    data: Object.values(monthlyDonations),
                    backgroundColor: bloodRed,
                    borderRadius: 6,
                    hoverBackgroundColor: bloodDark,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => ` donations: ${item.raw}`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans' } },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                    x: {
                        ticks: { font: { family: 'Plus Jakarta Sans' } },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    if (Object.keys(inventoryByComponent).length) {
        new Chart(document.getElementById('componentChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(inventoryByComponent),
                datasets: [{
                    label: 'Units',
                    data: Object.values(inventoryByComponent),
                    backgroundColor: '#f97316',
                    borderRadius: 6,
                    hoverBackgroundColor: '#ea580c',
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => ` units: ${item.raw}`,
                        },
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans' } },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                    },
                    y: {
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 12 } },
                        grid: { display: false },
                    },
                },
            },
        });
    }

    if (Object.keys(requestsByStatus).length) {
        const statusLabels = Object.keys(requestsByStatus).map((s) =>
            s.charAt(0).toUpperCase() + s.slice(1)
        );

        new Chart(document.getElementById('requestsChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: Object.values(requestsByStatus),
                    backgroundColor: ['#f59e0b', '#3b82f6', '#22c55e', '#ef4444', '#6b7280'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 8,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 12 }, padding: 16 },
                    },
                    tooltip: {
                        ...tooltipDefaults,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => ` requests: ${item.raw}`,
                        },
                    },
                },
            },
        });
    }
})();
</script>
@endpush
