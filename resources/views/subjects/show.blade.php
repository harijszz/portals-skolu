@extends('layouts.app')

@section('title', $subject->name)

@section('content')
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('subjects.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
        <h1 class="text-2xl font-bold">{{ $subject->name }}</h1>
        <span class="text-sm text-gray-400">{{ $subject->teacher }}</span>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="bg-white rounded shadow p-6">
            <h2 class="font-semibold mb-3">Informācija</h2>
            <dl class="text-sm">
                <dt class="text-gray-500">Ieskaites slieksnis</dt>
                <dd class="font-medium mb-2">{{ number_format($subject->passing_grade, 1) }}</dd>
                <dt class="text-gray-500">Vidējā atzīme</dt>
                <dd class="font-medium mb-2">{{ $subject->average() !== null ? number_format($subject->average(), 2) : 'Nav datu' }}</dd>
                <dt class="text-gray-500">Statuss</dt>
                <dd class="font-medium">
                    @php $w = $subject->willPass(); @endphp
                    @if ($w === null)
                        <span class="text-gray-400">Nav datu</span>
                    @elseif ($w)
                        <span class="text-green-600">Ieskaitīts</span>
                    @else
                        <span class="text-red-600">Nav ieskaitīts</span>
                    @endif
                </dd>
            </dl>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('subjects.edit', $subject) }}" class="text-sm text-indigo-600 hover:underline">Labot</a>
                <a href="{{ route('grades.create', $subject) }}" class="text-sm text-indigo-600 hover:underline">Pievienot atzīmi</a>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="font-semibold mb-3">Atzīmes</h2>
            @if ($subject->grades->isEmpty())
                <p class="text-sm text-gray-400 italic">Nav atzīmju</p>
            @else
                <div class="space-y-2">
                    @foreach ($subject->grades as $grade)
                        <div class="flex items-center justify-between py-1 border-b last:border-0 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                    {{ $grade->value >= $subject->passing_grade ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($grade->value, 1) }}
                                </span>
                                <span class="text-gray-500">(svars: {{ number_format($grade->weight, 1) }})</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-gray-400 text-xs">{{ $grade->date?->format('d.m.Y') }}</span>
                                <form method="POST" action="{{ route('grades.destroy', $grade) }}" onsubmit="return confirm('Dzēst?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Dzēst</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
