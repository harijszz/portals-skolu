<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_complete_latvian_school_data(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 10);
        $this->assertDatabaseCount('subjects', 120);
        $this->assertGreaterThanOrEqual(10 * 12, Grade::count());
        $this->assertDatabaseHas('users', ['email' => 'skolens@example.com']);
        $this->assertSame(
            collect(config('curriculum.subjects'))->pluck('name')->sort()->values()->all(),
            Subject::query()->pluck('name')->unique()->sort()->values()->all(),
        );
        $this->assertSame(12, User::query()->where('email', 'skolens@example.com')->first()->subjects()->count());
        $this->assertSame(6, Grade::query()->pluck('date')->map(
            fn ($date) => $date->format('Y-m'),
        )->unique()->count());
    }

    public function test_seeding_twice_does_not_duplicate_demo_data(): void
    {
        $this->seed();

        $this->seed();

        $this->assertDatabaseCount('users', 10);
        $this->assertDatabaseCount('subjects', 120);
        $this->assertSame(10, User::query()->distinct()->count('email'));
        $this->assertSame(120, Subject::query()->get()->unique(
            fn (Subject $subject) => $subject->user_id.':'.$subject->name,
        )->count());
    }
}
