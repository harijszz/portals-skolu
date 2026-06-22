@extends('layouts.app')

@section('title', 'Panākumu pārskats')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Panākumu pārskats</h1>

    @if ($stats->subjectCount > 0)
        <div class="grid gap-4 mb-8 md:grid-cols-4">
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats->subjectCount }}</div>
                <div class="text-sm text-gray-500">Priekšmeti</div>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats->gradeCount }}</div>
                <div class="text-sm text-gray-500">Atzīmes</div>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-2xl font-bold {{ $stats->overallAverage !== null && $stats->overallAverage >= 4 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $stats->overallAverage !== null ? number_format($stats->overallAverage, 2) : '—' }}
                </div>
                <div class="text-sm text-gray-500">Kopējais vidējais</div>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-2xl font-bold {{ $stats->failingCount > 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $stats->passingCount }}/{{ $stats->subjectCount }}
                </div>
                <div class="text-sm text-gray-500">Ieskaitīti</div>
            </div>
        </div>
    @endif

    @if ($summary->isEmpty())
        <div class="bg-white rounded shadow p-6 text-center text-gray-500">
            Vēl nav pievienots neviens priekšmets.
            <a href="{{ route('subjects.create') }}" class="text-indigo-600 hover:underline">Pievienot pirmo</a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($summary as $item)
                <div class="bg-white rounded shadow p-5 {{ $item->willPass === null ? '' : ($item->willPass ? 'ring-2 ring-green-400' : 'ring-2 ring-red-400') }}">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="font-semibold text-lg">{{ $item->subject->name }}</h2>
                        <span class="text-xs text-gray-400">{{ $item->subject->teacher }}</span>
                    </div>

                    <div class="text-sm text-gray-500 mb-3">
                        Ieskaites slieksnis: <strong>{{ number_format($item->subject->passing_grade, 1) }}</strong>
                    </div>

                    @if ($item->grades->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach ($item->grades as $grade)
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                    {{ $grade->value >= $item->subject->passing_grade ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($grade->value, 1) }}
                                </span>
                            @endforeach
                        </div>

                        <div class="text-sm">
                            <span class="text-gray-500">Vidējais:</span>
                            <strong>{{ number_format($item->average, 2) }}</strong>
                        </div>

                        @if ($item->willPass !== null)
                            <div class="mt-2 font-semibold text-sm {{ $item->willPass ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->willPass ? 'Ieskaitīts ✓' : 'Nav ieskaitīts ✗' }}
                            </div>
                        @endif
                    @else
                        <div class="text-sm text-gray-400 italic">Nav atzīmju</div>
                    @endif

                    <div class="mt-3 pt-3 border-t flex gap-2 text-xs">
                        <a href="{{ route('subjects.show', $item->subject) }}" class="text-indigo-600 hover:underline">Skatīt</a>
                        <a href="{{ route('grades.create', $item->subject) }}" class="text-indigo-600 hover:underline">Pievienot atzīmi</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
