@extends('layouts.app')

@section('title', 'Atzīmju kalkulators')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Atzīmju kalkulators</h1>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="font-semibold mb-3">What-if kalkulators</h2>
        <p class="text-sm text-gray-500 mb-4">Aprēķini, kāda būtu tava vidējā atzīme, ja saņemtu vēl vienu atzīmi.</p>

        <form method="GET" action="{{ route('calculator') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Priekšmets</label>
                <select name="subject_id" class="border rounded px-3 py-2 text-sm w-48">
                    <option value="">-- Izvēlies --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">What-if atzīme</label>
                <input type="number" name="what_if_value" step="0.1" min="1" max="10"
                    value="{{ request('what_if_value') }}"
                    class="border rounded px-3 py-2 text-sm w-24">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Svars</label>
                <input type="number" name="what_if_weight" step="0.1" min="0.1" max="10"
                    value="{{ request('what_if_weight', 1) }}"
                    class="border rounded px-3 py-2 text-sm w-20">
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">Aprēķināt</button>
        </form>

        @if ($whatIfAverage !== null)
            <div class="mt-4 p-4 bg-indigo-50 rounded">
                <p class="text-sm text-gray-600">
                    What-if vidējā atzīme:
                    <strong class="text-lg text-indigo-700">{{ number_format($whatIfAverage, 2) }}</strong>
                </p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded shadow p-6">
        <h2 class="font-semibold mb-3">Visi priekšmeti un to vidējās atzīmes</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="pb-2">Priekšmets</th>
                        <th class="pb-2">Atzīmes</th>
                        <th class="pb-2">Vidējais</th>
                        <th class="pb-2">Nepieciešams</th>
                        <th class="pb-2">Slieksnis</th>
                        <th class="pb-2">Statuss</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr class="border-b last:border-0">
                            <td class="py-2 font-medium">{{ $subject->name }}</td>
                            <td class="py-2">
                                @forelse ($subject->grades as $grade)
                                    <span class="inline-block px-1.5 py-0.5 rounded text-xs font-medium {{ $grade->value >= $subject->passing_grade ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ number_format($grade->value, 1) }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 italic">-</span>
                                @endforelse
                            </td>
                            <td class="py-2 font-medium">{{ $subject->average() !== null ? number_format($subject->average(), 2) : '-' }}</td>
                            <td class="py-2">
                                @php
                                    $avg = $subject->average();
                                @endphp
                                @if ($avg !== null && $avg < $subject->passing_grade)
                                    <span class="text-red-600 font-medium">vēl {{ number_format($subject->passing_grade - $avg, 2) }}</span>
                                @elseif ($avg !== null)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-2">{{ number_format($subject->passing_grade, 1) }}</td>
                            <td class="py-2">
                                @php
                                    $willPass = $subject->willPass();
                                @endphp
                                @if ($willPass === null)
                                    <span class="text-gray-400">-</span>
                                @elseif ($willPass)
                                    <span class="text-green-600 font-semibold">Ieskaitīts</span>
                                @else
                                    <span class="text-red-600 font-semibold">Nav</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
