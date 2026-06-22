<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(?Subject $subject = null): View
    {
        $subjectIds = Auth::user()->subjects()->pluck('id');

        if ($subject && $subject->exists) {
            $grades = Grade::where('subject_id', $subject->id)
                ->whereIn('subject_id', $subjectIds)
                ->with('subject')->latest()->get();
        } else {
            $grades = Grade::whereIn('subject_id', $subjectIds)
                ->with('subject')->latest()->get();
        }

        $subjects = Auth::user()->subjects()->orderBy('name')->get();
        return view('grades.index', compact('grades', 'subjects', 'subject'));
    }

    public function create(?Subject $subject = null): View
    {
        $subjects = Auth::user()->subjects()->orderBy('name')->get();
        return view('grades.create', compact('subjects', 'subject'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'value' => 'required|numeric|min:1|max:10',
            'weight' => 'required|numeric|min:0.1|max:10',
            'description' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $subject = Auth::user()->subjects()->findOrFail($validated['subject_id']);
        $subject->grades()->create($validated);

        return redirect()->route('grades.index')->with('success', 'Atzīme pievienota!');
    }

    public function edit(Grade $grade): View
    {
        $subjects = Auth::user()->subjects()->orderBy('name')->get();
        return view('grades.edit', compact('grade', 'subjects'));
    }

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'value' => 'required|numeric|min:1|max:10',
            'weight' => 'required|numeric|min:0.1|max:10',
            'description' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        $grade->update($validated);

        return redirect()->route('grades.index')->with('success', 'Atzīme atjaunināta!');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Atzīme dzēsta!');
    }
}
