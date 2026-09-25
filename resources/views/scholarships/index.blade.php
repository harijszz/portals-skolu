@extends('layouts.app')

@section('title', 'Stipendijas kalkulators')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Stipendijas kalkulators</h1>
            <p class="mt-1 text-sm text-gray-500">Vidējais tiek aprēķināts no visām mēneša atzīmēm bez svara ieskaites.</p>
        </div>

        <form method="GET" action="{{ route('scholarships.index') }}" class="flex items-end gap-2">
            <div>
                <label for="month" class="mb-1 block text-xs text-gray-500">Mēnesis</label>
                <input id="month" name="month" type="month" value="{{ $selectedMonth->format('Y-m') }}"
                    class="rounded border px-3 py-2 text-sm @error('month') border-red-400 @enderror">
            </div>
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-700">
                Aprēķināt
            </button>
        </form>
    </div>

    @error('month')
        <div class="mb-4 rounded border border-red-300 bg-red-100 px-4 py-3 text-sm text-red-800">
            {{ $message }}
        </div>
    @enderror

    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <div class="rounded bg-white p-5 shadow">
            <div class="text-sm text-gray-500">Mēneša vidējais</div>
            <div class="mt-1 text-3xl font-bold text-indigo-600">
                {{ $metrics->average !== null ? number_format($metrics->average, 2, ',', '.') : '—' }}
            </div>
        </div>
        <div class="rounded bg-white p-5 shadow">
            <div class="text-sm text-gray-500">Stipendija mēnesī</div>
            <div class="mt-1 text-3xl font-bold text-green-600">
                {{ number_format($metrics->scholarship->amount, 2, ',', '.') }} €
            </div>
            @if ($metrics->scholarship->minimumAmount !== null)
                <div class="mt-1 text-xs text-gray-500">
                    Diapazons:
                    {{ number_format($metrics->scholarship->minimumAmount, 0) }}–{{ number_format($metrics->scholarship->maximumAmount, 0) }} €
                </div>
            @endif
        </div>
        <div class="rounded bg-white p-5 shadow">
            <div class="text-sm text-gray-500">Ieskaitītās atzīmes</div>
            <div class="mt-1 text-3xl font-bold text-gray-700">{{ $metrics->grades->count() }}</div>
            <div class="mt-1 text-xs text-gray-500">{{ $metrics->subjectAverages->count() }} priekšmeti</div>
        </div>
    </div>

    @if ($metrics->grades->isEmpty())
        <div class="mb-6 rounded bg-white p-6 text-center text-gray-500 shadow">
            Šajā mēnesī nav pievienotu atzīmju ar datumu.
        </div>
    @else
        <div class="mb-6 grid gap-6 lg:grid-cols-3">
            <section class="rounded bg-white p-5 shadow lg:col-span-2">
                <h2 class="mb-4 font-semibold">Atzīmes {{ $selectedMonth->format('m.Y') }}</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-gray-500">
                                <th class="pb-2">Datums</th>
                                <th class="pb-2">Priekšmets</th>
                                <th class="pb-2">Atzīme</th>
                                <th class="pb-2">Svars</th>
                                <th class="pb-2">Darbs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($metrics->grades as $grade)
                                <tr class="border-b last:border-0">
                                    <td class="py-2 whitespace-nowrap text-gray-500">{{ $grade->date->format('d.m.Y') }}</td>
                                    <td class="py-2 font-medium">{{ $grade->subject->name }}</td>
                                    <td class="py-2 font-semibold">{{ number_format((float) $grade->value, 1, ',', '.') }}</td>
                                    <td class="py-2 text-gray-500">{{ number_format((float) $grade->weight, 1, ',', '.') }}</td>
                                    <td class="py-2 text-gray-500">{{ $grade->description ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded bg-white p-5 shadow">
                <h2 class="mb-4 font-semibold">Vidējie pēc priekšmeta</h2>
                <div class="space-y-3">
                    @foreach ($metrics->subjectAverages as $subject)
                        <div class="flex items-center justify-between border-b pb-2 last:border-0 last:pb-0">
                            <div>
                                <div class="text-sm font-medium">{{ $subject['name'] }}</div>
                                <div class="text-xs text-gray-400">{{ $subject['grade_count'] }} atzīmes</div>
                            </div>
                            <div class="font-semibold">{{ number_format($subject['average'], 2, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    @endif

    <section class="rounded bg-white p-5 shadow">
        <h2 class="mb-4 font-semibold">Pēdējo 12 mēnešu stipendijas</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="pb-2">Mēnesis</th>
                        <th class="pb-2">Atzīmes</th>
                        <th class="pb-2">Vidējais</th>
                        <th class="pb-2">Stipendija</th>
                        <th class="pb-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $month)
                        <tr class="border-b last:border-0 {{ $month->month->format('Y-m') === $selectedMonth->format('Y-m') ? 'bg-indigo-50' : '' }}">
                            <td class="py-2 font-medium">{{ $month->month->format('m.Y') }}</td>
                            <td class="py-2 text-gray-500">{{ $month->grades->count() }}</td>
                            <td class="py-2">{{ $month->average !== null ? number_format($month->average, 2, ',', '.') : '—' }}</td>
                            <td class="py-2 font-semibold text-green-600">{{ number_format($month->scholarship->amount, 2, ',', '.') }} €</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('scholarships.index', ['month' => $month->month->format('Y-m')]) }}"
                                    class="text-indigo-600 hover:underline">Skatīt</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
