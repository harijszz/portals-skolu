<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarshipPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('scholarships.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_student_sees_only_their_monthly_scholarship(): void
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->for($user)->create(['name' => 'Matematika']);
        $otherUser = User::factory()->create();
        $otherSubject = Subject::factory()->for($otherUser)->create(['name' => 'Fizika']);

        Grade::factory()->for($subject)->create([
            'value' => 8,
            'date' => '2026-09-10',
            'description' => 'Kontroldarba: procenti',
        ]);
        Grade::factory()->for($subject)->create([
            'value' => 6,
            'date' => '2026-09-20',
            'description' => 'Mājas darbs',
        ]);
        Grade::factory()->for($otherSubject)->create([
            'value' => 1,
            'date' => '2026-09-11',
            'description' => 'Citas skolēna atzīme',
        ]);

        $response = $this->actingAs($user)->get(route('scholarships.index', [
            'month' => '2026-09',
        ]));

        $response->assertOk()
            ->assertSee('Stipendijas kalkulators')
            ->assertSee('7,00')
            ->assertSee('65,57')
            ->assertSee('Kontroldarba: procenti')
            ->assertDontSee('Citas skolēna atzīme');
    }

    public function test_invalid_month_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('scholarships.index', ['month' => '2026-13']))
            ->assertSessionHasErrors('month');
    }
}
