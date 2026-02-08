<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_guest_to_login()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_root_redirects_authenticated_user()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_redirects_student()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_dashboard_redirects_teacher()
    {
        $user = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('teacher.dashboard'));
    }

    public function test_student_routes_accessible()
    {
        $user = User::factory()->create(['role' => 'student']);

        $routes = [
            'student.dashboard',
            'student.mnemonics',
            'student.games.index',
            'student.progress',
            'student.practice',
            'student.leaderboard',
            'student.feedback',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    public function test_teacher_routes_accessible()
    {
        $user = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        $routes = [
            'teacher.dashboard',
            'teacher.students',
            'teacher.reports',
            'teacher.feedback',
            'teacher.questions.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertStatus(200);
        }

        // Test dynamic route
        $response = $this->actingAs($user)->get(route('teacher.students.progress', $student));
        $response->assertStatus(200);
    }
}