@extends('layouts.app')

@section('title', 'Reģistrācija')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h1 class="text-2xl font-bold mb-6">Reģistrācija</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Vārds</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-pasts</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Parole</label>
                <input type="password" name="password" id="password"
                    class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Parole atkārtoti</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border rounded px-3 py-2">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Reģistrēties
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-gray-600">
            Jau ir konts? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Autorizēties</a>
        </p>
    </div>
</div>
@endsection
