@extends('layouts.app')

@section('title', 'Pievienot priekšmetu')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Pievienot priekšmetu</h1>

    <form method="POST" action="{{ route('subjects.store') }}" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nosaukums</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full border rounded px-3 py-2 text-sm @error('name') border-red-400 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Skolotājs</label>
            <input type="text" name="teacher" value="{{ old('teacher') }}"
                class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Ieskaites slieksnis (1-10)</label>
            <input type="number" name="passing_grade" step="0.1" min="1" max="10" value="{{ old('passing_grade', 4) }}"
                class="w-full border rounded px-3 py-2 text-sm @error('passing_grade') border-red-400 @enderror">
            @error('passing_grade') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">Saglabāt</button>
            <a href="{{ route('subjects.index') }}" class="px-4 py-2 rounded text-sm text-gray-600 hover:bg-gray-100">Atcelt</a>
        </div>
    </form>
@endsection
