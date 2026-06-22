<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = Auth::user()->subjects()->with('grades')->orderBy('name')->get();
        return view('subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher' => 'nullable|string|max:255',
            'semester' => 'required|in:whole_year,1,2',
            'credits' => 'nullable|integer|min:1|max:30',
            'passing_grade' => 'required|numeric|min:1|max:10',
        ]);

        Auth::user()->subjects()->create($validated);

        return redirect()->route('subjects.index')->with('success', 'Priekšmets pievienots!');
    }

    public function show(Subject $subject): View
    {
        $subject->load('grades');
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject): View
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher' => 'nullable|string|max:255',
            'semester' => 'required|in:whole_year,1,2',
            'credits' => 'nullable|integer|min:1|max:30',
            'passing_grade' => 'required|numeric|min:1|max:10',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')->with('success', 'Priekšmets atjaunināts!');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Priekšmets dzēsts!');
    }
}
