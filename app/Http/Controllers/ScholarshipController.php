<?php

namespace App\Http\Controllers;

use App\Services\StudentMonthlyMetricsService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScholarshipController extends Controller
{
    public function index(Request $request, StudentMonthlyMetricsService $metricsService): View
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $selectedMonth = CarbonImmutable::createFromFormat(
            'Y-m-d',
            ($validated['month'] ?? now()->format('Y-m')).'-01',
        )->startOfMonth();
        $metrics = $metricsService->forMonth($request->user(), $selectedMonth);
        $history = $metricsService->history($request->user(), $selectedMonth);

        return view('scholarships.index', compact('selectedMonth', 'metrics', 'history'));
    }
}
