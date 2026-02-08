<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\DailyChallenge;
use App\Models\Mnemonic;
use App\Models\ProgressTracker;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $recentGames = GameSession::where('user_id', $user->id)
            ->with('game')
            ->latest()
            ->take(5)
            ->get();
        
        // Calculate some stats
        $totalGames = GameSession::where('user_id', $user->id)->count();
        $totalScore = GameSession::where('user_id', $user->id)->sum('score');
        $avgScore = $totalGames > 0 ? round($totalScore / max($totalGames, 1), 1) : 0;

        $activeGames = Game::where('is_active', true)->take(4)->get();
        $mnemonics = Mnemonic::inRandomOrder()->take(3)->get();

        $dates = GameSession::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->pluck('created_at')
            ->map(fn ($d) => $d->toDateString())
            ->unique()
            ->values();

        $streak = 0;
        if ($dates->isNotEmpty()) {
            $current = now()->toDateString();
            $index = 0;
            if ($dates[0] === $current) {
                $streak = 1;
                $index = 1;
            }
            for (; $index < $dates->count(); $index++) {
                $expected = now()->subDays($index)->toDateString();
                if ($dates[$index] === $expected) {
                    $streak++;
                } else {
                    break;
                }
            }
        }

        // Weekly Activity Data
        $weeklyActivity = collect(range(6, 0))->map(function ($daysAgo) use ($user) {
            $date = now()->subDays($daysAgo);
            return [
                'day' => $date->format('D'),
                'full_date' => $date->format('Y-m-d'),
                'count' => GameSession::where('user_id', $user->id)
                    ->whereDate('created_at', $date->toDateString())
                    ->count(),
                'is_today' => $daysAgo === 0,
            ];
        });

        return view('student.dashboard', compact(
            'recentGames',
            'totalGames',
            'totalScore',
            'avgScore',
            'activeGames',
            'mnemonics',
            'streak',
            'weeklyActivity'
        ));
    }

    public function mnemonics()
    {
        $mnemonics = Mnemonic::all();
        $topics = Mnemonic::select('topic')->distinct()->pluck('topic')->sort()->values();
        return view('student.mnemonics', compact('mnemonics', 'topics'));
    }

    public function progress()
    {
        $user = auth()->user();
        
        $sessions = GameSession::where('user_id', $user->id)
            ->with('game')
            ->latest()
            ->paginate(10);

        // Fetch data for charts (last 30 sessions)
        $chartSessions = GameSession::where('user_id', $user->id)
            ->with('game')
            ->latest()
            ->take(30)
            ->get();

        $dailyChallenges = DailyChallenge::where('user_id', $user->id)
            ->with(['game', 'question'])
            ->latest()
            ->paginate(10, ['*'], 'challenges_page');

        // Calculate stats
        $allSessions = GameSession::where('user_id', $user->id)->get();
        $stats = [
            'total_sessions' => $allSessions->count(),
            'avg_score' => $allSessions->count() > 0 ? round($allSessions->avg('score'), 1) : 0,
            'total_minutes' => floor($allSessions->sum('duration') / 60),
        ];
            
        return view('student.progress', compact('sessions', 'dailyChallenges', 'chartSessions', 'stats'));
    }

    public function practice()
    {
        return view('student.practice');
    }

    public function leaderboard()
    {
        return view('student.leaderboard');
    }

    public function feedback()
    {
        return view('student.feedback');
    }
}
