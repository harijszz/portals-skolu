@extends('layouts.app')

@section('title', 'Autorizēties')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h1 class="text-2xl font-bold mb-6">Autorizēties</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

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

            <div class="mb-4 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm text-gray-600">Atcerēties mani</label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Ienākt
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-gray-600">
            Nav konta? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Reģistrēties</a>
        </p>
    </div>
</div>
@endsection
