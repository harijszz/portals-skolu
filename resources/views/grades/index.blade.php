@extends('layouts.app')

@section('title', 'Atzīmes')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Atzīmes</h1>
        <a href="{{ route('grades.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">+ Pievienot</a>
    </div>

    <div class="mb-4 flex gap-2">
        <a href="{{ route('grades.index') }}"
            class="px-3 py-1.5 rounded text-sm {{ !request('subject') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Visi
        </a>
        @foreach ($subjects as $s)
            <a href="{{ route('grades.index', ['subject' => $s->id]) }}"
                class="px-3 py-1.5 rounded text-sm {{ request('subject') == $s->id ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $s->name }}
            </a>
        @endforeach
    </div>

    @if ($grades->isEmpty())
        <div class="bg-white rounded shadow p-6 text-center text-gray-500">Nav atzīmju.</div>
    @else
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-gray-500">
                        <th class="p-3">Priekšmets</th>
                        <th class="p-3">Atzīme</th>
                        <th class="p-3">Svars</th>
                        <th class="p-3">Apraksts</th>
                        <th class="p-3">Datums</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        <tr class="border-b last:border-0 hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $grade->subject->name }}</td>
                            <td class="p-3">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                    {{ $grade->value >= $grade->subject->passing_grade ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($grade->value, 1) }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-500">{{ number_format($grade->weight, 1) }}</td>
                            <td class="p-3 text-gray-500">{{ $grade->description ?? '-' }}</td>
                            <td class="p-3 text-gray-500">{{ $grade->date?->format('d.m.Y') ?? '-' }}</td>
                            <td class="p-3 flex gap-2">
                                <a href="{{ route('grades.edit', $grade) }}" class="text-indigo-600 hover:underline">Labot</a>
                                <form method="POST" action="{{ route('grades.destroy', $grade) }}" onsubmit="return confirm('Dzēst?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Dzēst</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
