<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\DailyChallenge;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::where('is_active', true)
            ->where('slug', '!=', 'daily-challenge')
            ->withCount('sessions')
            ->get()
            ->map(function ($game) {
                // Assign categories and metadata based on slug
                $meta = $this->getGameMetadata($game->slug);
                $game->category = $meta['category'];
                $game->badge = $meta['badge'];
                $game->color = $meta['color'];
                $game->icon = $meta['icon'];
                return $game;
            });

        $categories = $games->pluck('category')->unique()->values()->all();

        // Check Daily Challenge Status
        $today = Carbon::now()->format('Y-m-d');
        // Check if a record exists for today in DailyChallenge table
        // Note: Logic based on DailyChallengeController::show
        // We need to know IF the user completed it.
        // First, we need to know WHICH game is the daily challenge today, but actually DailyChallenge table records it.
        // If the user has a record in DailyChallenge table for today with 'completed_at', then it's done.
        $dailyChallengeCompleted = DailyChallenge::where('user_id', auth()->id())
            ->whereDate('completed_at', $today)
            ->exists();

        // Recent Games
        $recentGameIds = GameSession::where('user_id', auth()->id())
            ->latest()
            ->limit(50)
            ->pluck('game_id')
            ->unique()
            ->take(3);
            
        $recentGames = $games->whereIn('id', $recentGameIds)->values();

        return view('student.games.index', compact('games', 'categories', 'dailyChallengeCompleted', 'recentGames'));
    }

    private function getGameMetadata($slug)
    {
        return match ($slug) {
            'pemdas-rush' => [
                'category' => 'Operations',
                'badge' => 'Order of Operations',
                'color' => 'from-green-500 to-emerald-600',
                'icon' => '🧩'
            ],
            'integer-battle' => [
                'category' => 'Operations',
                'badge' => 'Integer Operations',
                'color' => 'from-red-500 to-rose-600',
                'icon' => '⚔️'
            ],
            'spin-wheel' => [
                'category' => 'Mixed',
                'badge' => 'Mixed Operations',
                'color' => 'from-orange-500 to-amber-600',
                'icon' => '🎡'
            ],
            'drag-drop-sort' => [
                'category' => 'Logic',
                'badge' => 'Sorting',
                'color' => 'from-indigo-500 to-violet-600',
                'icon' => '↔️'
            ],
            'puzzle-match' => [
                'category' => 'Logic',
                'badge' => 'Matching',
                'color' => 'from-blue-500 to-cyan-600',
                'icon' => '🧩'
            ],
            'multiplayer-sim' => [
                'category' => 'Competitive',
                'badge' => 'Multiplayer',
                'color' => 'from-purple-500 to-fuchsia-600',
                'icon' => '👥'
            ],
            default => [
                'category' => 'Challenge',
                'badge' => 'General',
                'color' => 'from-slate-500 to-slate-600',
                'icon' => '🎮'
            ]
        };
    }

    public function play(Game $game)
    {
        if (!$game->is_active) {
            return redirect()->route('student.games.index')->with('error', 'Game is not active.');
        }
        
        $isDailyChallenge = false;
        $data = [
            'game' => $game,
            'isDailyChallenge' => $isDailyChallenge
        ];

        // Route to appropriate game view
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
        
        if ($game->slug === 'daily-challenge') {
            return view('student.games.daily-challenge', $data);
        }
        
        
        
        if ($game->slug === 'multiplayer-sim') {
            return view('student.games.play.multiplayer-sim', $data);
        }
        
        return view('student.games.play', $data);
    }

    public function getQuestions(Game $game)
    {
        $topicsInteger = [
            'Integer Addition',
            'Integer Subtraction',
            'Integer Multiplication',
            'Integer Division',
        ];
        $topicsPemdas = [
            'Order of Operations',
            'Mixed Integer Operations',
        ];
        $count = (int)($game->config['questions'] ?? 10);

        $query = \App\Models\Question::with('mnemonic');

        switch ($game->slug) {
            case 'pemdas-rush':
                $query->whereIn('topic', $topicsPemdas);
                break;
            case 'integer-battle':
            case 'drag-drop-sort':
                $query->whereIn('topic', $topicsInteger);
                break;
            case 'spin-wheel':
                $query->whereIn('topic', array_merge($topicsInteger, $topicsPemdas));
                break;
            case 'multiplayer-sim':
                $query->whereIn('topic', array_merge($topicsInteger, $topicsPemdas))
                      ->where('difficulty', 'medium');
                break;
            case 'daily-challenge':
                // Mix of difficulties: 4 easy, 3 medium, 3 hard (if available)
                $easy = (clone $query)->where('difficulty', 'easy')->inRandomOrder()->take(4)->get();
                $medium = (clone $query)->where('difficulty', 'medium')->inRandomOrder()->take(3)->get();
                $hard = (clone $query)->where('difficulty', 'hard')->inRandomOrder()->take(3)->get();
                $questions = $easy->merge($medium)->merge($hard)->shuffle()->take($count)->values();
                return response()->json($questions);
            default:
                // Unknown slug: default random selection
                break;
        }

        $questions = $query->inRandomOrder()->take($count)->get();

        return response()->json($questions);
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'score' => 'required|integer|min:0|max:10000',
            'duration' => 'required|integer|min:0|max:3600',
            'details' => 'nullable|array'
        ]);

        // Additional validation for details if provided
        if ($request->has('details') && is_array($request->details)) {
            $details = $request->details;
            
            // Validate accuracy is between 0-100
            if (isset($details['accuracy'])) {
                $details['accuracy'] = max(0, min(100, intval($details['accuracy'])));
            }
            
            // Validate wave/round counts
            if (isset($details['waves'])) {
                $details['waves'] = max(0, intval($details['waves']));
            }
            if (isset($details['rounds_completed'])) {
                $details['rounds_completed'] = max(0, intval($details['rounds_completed']));
            }
        }

        GameSession::create([
            'user_id' => auth()->id(),
            'game_id' => $request->game_id,
            'score' => min(10000, max(0, $request->score)), // Cap score between 0-10000
            'duration' => min(3600, max(0, $request->duration)), // Cap duration between 0-3600 seconds
            'details' => $request->details ?? [],
            'completed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
