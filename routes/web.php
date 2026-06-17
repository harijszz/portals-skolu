<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/calculator', [DashboardController::class, 'calculator'])->name('calculator');

Route::resource('subjects', SubjectController::class);

Route::resource('grades', GradeController::class);

Route::get('/grades/subject/{subject}', [GradeController::class, 'index'])->name('grades.subject');
