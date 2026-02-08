<?php

namespace Tests\Feature\Student;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePlayTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_games_list()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get(route('student.games.index'));

        $response->assertStatus(200);
        $response->assertViewIs('student.games.index');
    }

    public function test_student_can_view_game_play_page()
    {
        $student = User::factory()->create(['role' => 'student']);
        $game = Game::factory()->create(['is_active' => true]);

        $response = $this->actingAs($student)->get(route('student.games.play', $game));

        $response->assertStatus(200);
        $response->assertViewIs('student.games.play');
        $response->assertViewHas('game');
    }

    public function test_student_cannot_play_inactive_game()
    {
        $student = User::factory()->create(['role' => 'student']);
        $game = Game::factory()->create(['is_active' => false]);

        $response = $this->actingAs($student)->get(route('student.games.play', $game));

        $response->assertRedirect(route('student.games.index'));
    }

    public function test_student_can_fetch_questions()
    {
        $student = User::factory()->create(['role' => 'student']);
        $game = Game::factory()->create();

        $response = $this->actingAs($student)->get(route('student.games.questions', $game->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'content', 'choices', 'correct_answer', 'mnemonic']
        ]);
    }

    public function test_student_can_store_game_session()
    {
        $student = User::factory()->create(['role' => 'student']);
        $game = Game::factory()->create();

        $response = $this->actingAs($student)->postJson(route('student.games.session.store'), [
            'game_id' => $game->id,
            'score' => 100,
            'duration' => 60,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('game_sessions', [
            'user_id' => $student->id,
            'game_id' => $game->id,
            'score' => 100,
            'duration' => 60,
        ]);
    }

    public function test_student_can_view_progress()
    {
        $student = User::factory()->create(['role' => 'student']);
        $game = Game::factory()->create();
        GameSession::create([
            'user_id' => $student->id,
            'game_id' => $game->id,
            'score' => 50,
            'duration' => 30,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('student.progress'));

        $response->assertStatus(200);
        $response->assertViewIs('student.progress');
        $response->assertViewHas('sessions');
    }

    public function test_teacher_cannot_access_student_games()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        
        $response = $this->actingAs($teacher)->get(route('student.games.index'));

        $response->assertStatus(403); // Forbidden
    }
}
