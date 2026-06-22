<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $subjects = Auth::user()->subjects()->with('grades')->orderBy('name')->get();

        $summary = $subjects->map(function ($subject) {
            $average = $subject->average();
            $willPass = $subject->willPass();

            return (object) [
                'subject' => $subject,
                'grades' => $subject->grades,
                'average' => $average,
                'willPass' => $willPass,
                'needs' => $this->calculateNeeded($subject),
            ];
        });

        $stats = (object) [
            'subjectCount' => $subjects->count(),
            'gradeCount' => $subjects->sum(fn($s) => $s->grades->count()),
            'overallAverage' => $this->overallAverage($subjects),
            'passingCount' => $summary->filter(fn($s) => $s->willPass === true)->count(),
            'failingCount' => $summary->filter(fn($s) => $s->willPass === false)->count(),
        ];

        return view('dashboard', compact('summary', 'subjects', 'stats'));
    }

    private function overallAverage($subjects): ?float
    {
        $allGrades = $subjects->flatMap(fn($s) => $s->grades);
        if ($allGrades->isEmpty()) return null;

        $totalWeight = $allGrades->sum('weight');
        if ($totalWeight == 0) return null;

        $weightedSum = $allGrades->sum(fn($g) => $g->value * $g->weight);
        return round($weightedSum / $totalWeight, 2);
    }

    public function calculator(Request $request): View
    {
        $subjects = Auth::user()->subjects()->with('grades')->orderBy('name')->get();

        $whatIfGrades = [];
        $whatIfAverage = null;

        if ($request->has('subject_id') && $request->has('what_if_value')) {
            $subject = Subject::with('grades')->findOrFail($request->subject_id);
            $whatIfGrades = $subject->grades->toArray();
            $whatIfGrades[] = [
                'value' => $request->what_if_value,
                'weight' => $request->what_if_weight ?? 1,
                'description' => '(what-if)',
            ];

            $totalWeight = collect($whatIfGrades)->sum('weight');
            $weightedSum = collect($whatIfGrades)->sum(fn($g) => $g['value'] * $g['weight']);
            $whatIfAverage = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : null;
        }

        return view('calculator', compact('subjects', 'whatIfGrades', 'whatIfAverage'));
    }

    private function calculateNeeded(Subject $subject): ?float
    {
        if ($subject->grades->isEmpty()) return null;

        $totalWeight = $subject->grades->sum('weight');
        $weightedSum = $subject->grades->sum(fn($g) => $g->value * $g->weight);
        $needed = ($subject->passing_grade * ($totalWeight + 1) - $weightedSum) / 1;

        return round($needed, 2);
    }
}
