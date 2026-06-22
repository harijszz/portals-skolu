<?php

namespace App\Providers;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::bind('subject', function ($value) {
            return Auth::user()->subjects()->findOrFail($value);
        });

        Route::bind('grade', function ($value) {
            return Grade::whereIn('subject_id', Auth::user()->subjects()->pluck('id'))
                ->findOrFail($value);
        });
    }
}
