@extends('layouts.app')

@section('title', 'Priekšmeti')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Priekšmeti</h1>
        <a href="{{ route('subjects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">+ Pievienot</a>
    </div>

    @if ($subjects->isEmpty())
        <div class="bg-white rounded shadow p-6 text-center text-gray-500">
            Nav pievienotu priekšmetu.
        </div>
    @else
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-gray-500">
                        <th class="p-3">Nosaukums</th>
                        <th class="p-3">Skolotājs</th>
                        <th class="p-3">Slieksnis</th>
                        <th class="p-3">Atzīmes</th>
                        <th class="p-3">Vidējais</th>
                        <th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr class="border-b last:border-0 hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $subject->name }}</td>
                            <td class="p-3 text-gray-500">{{ $subject->teacher ?? '-' }}</td>
                            <td class="p-3">{{ number_format($subject->passing_grade, 1) }}</td>
                            <td class="p-3">{{ $subject->grades->count() }}</td>
                            <td class="p-3">{{ $subject->average() !== null ? number_format($subject->average(), 2) : '-' }}</td>
                            <td class="p-3 flex gap-2">
                                <a href="{{ route('subjects.show', $subject) }}" class="text-indigo-600 hover:underline">Skatīt</a>
                                <a href="{{ route('subjects.edit', $subject) }}" class="text-gray-500 hover:underline">Labot</a>
                                <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Dzēst?')">
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
