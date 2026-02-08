<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\DailyChallenge;
use App\Models\DailyChallengeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyChallengeController extends Controller
{
    /**
     * Get today's daily challenge
     */
    public function show()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('Y-m-d');
        
        // Get or create today's challenge (deterministic based on date)
        $dailyGame = $this->getDailyChallenge();
        
        // Check if user completed today's challenge
        $completedToday = DailyChallenge::where('user_id', $user->id)
            ->where('game_id', $dailyGame->id)
            ->whereDate('completed_at', $today)
            ->exists();
        
        // Get user's best score for this game
        $bestScore = DailyChallenge::where('user_id', $user->id)
            ->where('game_id', $dailyGame->id)
            ->max('score') ?? 0;
        
        // Get user's sessions for this game today
        $todaySessions = DailyChallenge::where('user_id', $user->id)
            ->where('game_id', $dailyGame->id)
            ->whereDate('created_at', $today)
            ->get();
        
        $stats = [
            'attempts' => $todaySessions->count(),
            'bestScore' => $bestScore,
            'completedToday' => $completedToday,
            'averageScore' => $todaySessions->count() > 0 ? round($todaySessions->avg('score')) : 0
        ];
        
        return view('student.daily-challenge.show', compact('dailyGame', 'stats'));
    }
    
    /**
     * Get the daily challenge game for today
     * Uses a deterministic hash based on the date so everyone gets the same game
     */
    private function getDailyChallenge()
    {
        $today = Carbon::now()->format('Y-m-d');

        // Check for manual config first
        $config = DailyChallengeConfig::where('date', $today)->with('game')->first();
        if ($config && $config->game && $config->game->is_active) {
            return $config->game;
        }

        // Return the specific daily challenge game if available
        $dailyChallengeGame = Game::where('slug', 'daily-challenge')->where('is_active', true)->first();
        if ($dailyChallengeGame) {
            return $dailyChallengeGame;
        }

        $games = Game::where('is_active', true)
            ->where('slug', '!=', 'daily-challenge') // Exclude daily-challenge game itself
            ->get();
        
        if ($games->isEmpty()) {
            // Fallback to any active game
            $games = Game::where('is_active', true)->get();
        }
        
        // Use date-based seed for deterministic randomization
        $seed = crc32($today) % $games->count();
        
        return $games[$seed];
    }
    
    /**
     * Play today's daily challenge
     */
    public function play()
    {
        $dailyGame = $this->getDailyChallenge();
        $game = $dailyGame;
        
        // Route to appropriate game view with flag
        return $this->routeToGameView($game, true);
    }
    
    /**
     * Route to the correct game view based on game slug
     */
    private function routeToGameView($game, $isDailyChallenge = false)
    {
        $data = compact('game', 'isDailyChallenge');

        if ($game->slug === 'daily-challenge') {
            return view('student.daily-challenge', $data);
        }

        if ($game->slug === 'puzzle-match') {
            return view('student.games.puzzle-match', $data);
        }
        
        if ($game->slug === 'drag-drop-sort') {
            return view('student.games.drag-drop-sort', $data);
        }
        
        if ($game->slug === 'integer-battle') {
            return view('student.games.integer-battle', $data);
        }
        
        if ($game->slug === 'pemdas-rush') {
            return view('student.games.play.pemdas-rush', $data);
        }
        
        if ($game->slug === 'spin-wheel') {
            return view('student.games.play.spin-wheel', $data);
        }
        
        if ($game->slug === 'multiplayer-sim') {
            return view('student.games.play.multiplayer-sim', $data);
        }
        
        // Default fallback to generic game view
        return view('student.games.play', $data);
    }
    
    /**
     * Store a daily challenge session
     */
    public function storeSession(Request $request)
    {
        $user = Auth::user();
        $dailyGame = $this->getDailyChallenge();

        $validated = $request->validate([
            'question_id' => 'nullable|exists:questions,id',
            'submitted_answer' => 'nullable|string',
            'is_correct' => 'boolean',
            'score' => 'required|integer|min:0',
            'duration' => 'nullable|integer', // in seconds
            'metadata' => 'nullable|array',
        ]);

        // Record the daily challenge attempt
        $challenge = DailyChallenge::create([
            'user_id' => $user->id,
            'game_id' => $dailyGame->id,
            'question_id' => $validated['question_id'] ?? null,
            'submitted_answer' => $validated['submitted_answer'] ?? null,
            'is_correct' => $validated['is_correct'] ?? false,
            'score' => $validated['score'],
            'duration' => $validated['duration'] ?? null,
            'attempt_number' => DailyChallenge::where('user_id', $user->id)
                ->where('game_id', $dailyGame->id)
                ->whereDate('created_at', now()->toDateString())
                ->count() + 1,
            'completed_at' => now(),
            'metadata' => $validated['metadata'] ?? [],
        ]);

        // Also store in GameSession for compatibility
        GameSession::create([
            'user_id' => $user->id,
            'game_id' => $dailyGame->id,
            'score' => $validated['score'],
            'duration' => $validated['duration'] ?? null,
            'details' => array_merge(
                $validated['metadata'] ?? [],
                [
                    'daily_challenge_id' => $challenge->id,
                    'submitted_answer' => $validated['submitted_answer'] ?? null,
                    'is_correct' => $validated['is_correct'] ?? false,
                ]
            ),
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Daily challenge recorded successfully',
            'data' => $challenge,
        ]);
    }

    /**
     * Return current student's submitted answers (JSON)
     */
    public function studentAnswers()
    {
        $user = Auth::user();

        $perPage = (int) request()->get('per_page', 15);
        $answers = DailyChallenge::where('user_id', $user->id)
            ->whereNotNull('submitted_answer')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $collection = $answers->getCollection();
        
        // Calculate global stats for the user
        $totalCorrect = DailyChallenge::where('user_id', $user->id)
            ->whereNotNull('submitted_answer')
            ->where('is_correct', true)
            ->count();
            
        $totalIncorrect = DailyChallenge::where('user_id', $user->id)
            ->whereNotNull('submitted_answer')
            ->where('is_correct', false)
            ->count();

        return response()->json([
            'total' => $answers->total(),
            'total_correct' => $totalCorrect,
            'total_incorrect' => $totalIncorrect,
            'current_page' => $answers->currentPage(),
            'last_page' => $answers->lastPage(),
            'per_page' => $answers->perPage(),
            'answers' => $answers->items(),
        ]);
    }
}
