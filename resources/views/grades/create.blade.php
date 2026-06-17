@extends('layouts.app')

@section('title', 'Pievienot atzīmi')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Pievienot atzīmi</h1>

    <form method="POST" action="{{ route('grades.store') }}" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Priekšmets</label>
            <select name="subject_id" required
                class="w-full border rounded px-3 py-2 text-sm @error('subject_id') border-red-400 @enderror">
                <option value="">-- Izvēlies --</option>
                @foreach ($subjects as $s)
                    <option value="{{ $s->id }}" {{ old('subject_id', $subject?->id) == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Atzīme (1-10)</label>
            <input type="number" name="value" step="0.1" min="1" max="10" value="{{ old('value') }}" required
                class="w-full border rounded px-3 py-2 text-sm @error('value') border-red-400 @enderror">
            @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Svars</label>
            <input type="number" name="weight" step="0.1" min="0.1" max="10" value="{{ old('weight', 1) }}"
                class="w-full border rounded px-3 py-2 text-sm @error('weight') border-red-400 @enderror">
            @error('weight') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Apraksts (nav obligāts)</label>
            <input type="text" name="description" value="{{ old('description') }}"
                class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Datums</label>
            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                class="w-full border rounded px-3 py-2 text-sm">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">Saglabāt</button>
            <a href="{{ route('grades.index') }}" class="px-4 py-2 rounded text-sm text-gray-600 hover:bg-gray-100">Atcelt</a>
        </div>
    </form>
@endsection
