<?php

namespace App\Http\Controllers;

use App\Models\DailyChallengeConfig;
use App\Models\Feedback;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\DailyChallenge;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        // Student Stats
        $totalStudents = User::where('role', 'student')->count();
        $recentStudents = User::where('role', 'student')->latest()->take(5)->get();

        // Game Stats
        $totalGames = Game::count();
        $totalSessions = GameSession::count();
        
        // Analytics: Average Score per Game
        $gamePerformance = GameSession::select('game_id', DB::raw('avg(score) as average_score'))
            ->groupBy('game_id')
            ->with('game')
            ->get()
            ->map(function ($session) {
                return [
                    'game' => $session->game->name,
                    'score' => round($session->average_score, 1),
                ];
            });

        // Analytics: Activity Last 7 Days
        $activityData = GameSession::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Recent Game Sessions
        $recentSessions = GameSession::with(['user', 'game'])
            ->latest()
            ->take(10)
            ->get();

        return view('teacher.dashboard', compact(
            'totalStudents', 
            'recentStudents', 
            'totalGames', 
            'totalSessions',
            'gamePerformance',
            'activityData',
            'recentSessions'
        ));
    }

    public function students(Request $request)
    {
        $query = User::where('role', 'student');
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        $students = $query
            ->withCount('gameSessions')
            ->withAvg('gameSessions', 'score')
            ->paginate(10)
            ->withQueryString();
        return view('teacher.students', compact('students'));
    }

    public function studentsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $student = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'student',
        ]);
        return redirect()->route('teacher.students')->with('status', 'Student created.');
    }

    public function studentsUpdate(Request $request, User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$student->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        $student->name = $validated['name'];
        $student->email = $validated['email'];
        if (!empty($validated['password'])) {
            $student->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        $student->save();
        return redirect()->route('teacher.students')->with('status', 'Student updated.');
    }

    public function studentsDestroy(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }
        // Prevent deleting self accidentally
        if ($student->id === auth()->id()) {
            return redirect()->route('teacher.students')->with('error', 'Cannot delete your own account.');
        }
        $student->delete();
        return redirect()->route('teacher.students')->with('status', 'Student deleted.');
    }

    public function adminStore(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'admin')->withInput()->with('open_admin', true);
        }
        $data = $validator->validated();
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => 'teacher',
        ]);
        return redirect()->route('teacher.dashboard')->with('status', 'Teacher account created.');
    }

    public function studentProgress(User $student)
    {
        // Ensure the user is actually a student
        if (!$student->isStudent()) {
            abort(404);
        }
        $sessions = GameSession::where('user_id', $student->id)
            ->with('game')
            ->orderByDesc('completed_at')
            ->orderByDesc('created_at')
            ->get();

        $totalSessions = $sessions->count();
        $totalDuration = (int) $sessions->sum('duration');
        $avgScore = $totalSessions > 0 ? round($sessions->avg('score'), 1) : 0;
        $bestScore = (int) ($sessions->max('score') ?? 0);

        // Streak calculation (consecutive days with sessions)
        $dates = $sessions->map(function ($s) {
                $dt = $s->completed_at ?? $s->created_at;
                return $dt?->toDateString();
            })
            ->filter()
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

        // Score trend: last 14 days average score
        $byDate = $sessions->groupBy(function ($s) {
            $dt = $s->completed_at ?? $s->created_at;
            return $dt?->toDateString();
        });
        $trendLabels = [];
        $trendData = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $trendLabels[] = \Carbon\Carbon::parse($d)->format('M d');
            $dayScores = $byDate->get($d, collect())->pluck('score');
            $trendData[] = $dayScores->isNotEmpty() ? round($dayScores->avg(), 1) : 0;
        }

        // Sessions per game and average score per game
        $byGame = $sessions->groupBy(fn ($s) => optional($s->game)->name ?? 'Unknown');
        $gameLabels = $byGame->keys()->values();
        $gameSessionCounts = $byGame->map->count()->values();
        $gameAvgScores = $byGame->map(function ($group) {
            return round($group->avg('score'), 1);
        })->values();

        $recentSessions = $sessions->take(10);

        return view('teacher.student-progress', compact(
            'student',
            'totalSessions',
            'totalDuration',
            'avgScore',
            'bestScore',
            'streak',
            'trendLabels',
            'trendData',
            'gameLabels',
            'gameSessionCounts',
            'gameAvgScores',
            'recentSessions'
        ));
    }

    public function admins(Request $request)
    {
        $query = User::where('role', 'teacher');
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        $admins = $query->latest()->paginate(10)->withQueryString();
        return view('teacher.admins', compact('admins'));
    }

    public function adminsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'teacher',
        ]);
        return redirect()->route('teacher.admins.index')->with('status', 'Teacher created.');
    }

    public function adminsUpdate(Request $request, User $admin)
    {
        if ($admin->role !== 'teacher') {
            abort(404);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$admin->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        if (!empty($validated['password'])) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        $admin->save();
        return redirect()->route('teacher.admins.index')->with('status', 'Teacher updated.');
    }

    public function adminsDestroy(User $admin)
    {
        if ($admin->role !== 'teacher') {
            abort(404);
        }
        if ($admin->id === auth()->id()) {
            return redirect()->route('teacher.admins.index')->with('error', 'Cannot delete your own account.');
        }
        $admin->delete();
        return redirect()->route('teacher.admins.index')->with('status', 'Teacher deleted.');
    }

    public function reports(Request $request)
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalSessions = GameSession::count();
        $avgScore = GameSession::avg('score') ?? 0;

        // Success rate: sessions with score > 50% of max (assume max ~100)
        $totalWithScore = GameSession::count();
        $passing = GameSession::where('score', '>=', 50)->count();
        $successRate = $totalWithScore > 0 ? $passing / $totalWithScore : 0;

        $students = collect();
        $games = collect();
        $questions = collect();

        if ($request->get('type') === 'student') {
            $students = User::where('role', 'student')
                ->withCount('gameSessions')
                ->withAvg('gameSessions', 'score')
                ->paginate(15)
                ->withQueryString();
        } elseif ($request->get('type') === 'game') {
            $games = Game::withCount('sessions')
                ->withAvg('sessions', 'score')
                ->get();
        } elseif ($request->get('type') === 'question') {
            $questions = Question::withCount(['practiceAnswers as total_attempts'])
                ->withCount(['practiceAnswers as correct_count' => function ($q) {
                    $q->where('is_correct', true);
                }])
                ->paginate(15)
                ->withQueryString();
        }

        // Activity over last 30 days
        $activityRaw = GameSession::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $activityLabels = [];
        $activityValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $activityLabels[] = \Carbon\Carbon::parse($d)->format('M d');
            $activityValues[] = $activityRaw[$d] ?? 0;
        }

        // Top students by average score
        $topStudents = User::where('role', 'student')
            ->whereHas('gameSessions')
            ->withCount('gameSessions as sessions_count')
            ->withAvg('gameSessions as avg_score', 'score')
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        return view('teacher.reports', compact(
            'totalStudents',
            'totalSessions',
            'avgScore',
            'successRate',
            'students',
            'games',
            'questions',
            'activityLabels',
            'activityValues',
            'topStudents'
        ));
    }

    public function feedback(Request $request)
    {
        $query = Feedback::with('user')->latest();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('message', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQuery) use ($q) {
                        $userQuery->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        // Compute stats from the full dataset (not just the current page)
        $totalFeedback = Feedback::count();
        $recentFeedback = Feedback::where('created_at', '>', now()->subDays(7))->count();
        $resolvedFeedback = Feedback::where('status', 'resolved')->count();

        return view('teacher.feedback', compact('feedbacks', 'totalFeedback', 'recentFeedback', 'resolvedFeedback'));
    }

    /**
     * Mark feedback as resolved
     */
    public function feedbackResolve(Feedback $feedback)
    {
        $feedback->update(['status' => 'resolved']);
        return response()->json(['success' => true]);
    }

    /**
     * Delete feedback
     */
    public function feedbackDestroy(Feedback $feedback)
    {
        $feedback->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('teacher.feedback')->with('status', 'Feedback deleted.');
    }

    /**
     * View all daily challenge results
     */
    public function dailyChallenges(Request $request)
    {
        $query = DailyChallenge::with(['user', 'game']);
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->get('date'));
        } else {
            // Default to today
            $query->whereDate('created_at', now()->toDateString());
        }
        
        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('user_id', $request->get('student_id'));
        }
        
        // Filter by completion status
        if ($request->filled('status')) {
            if ($request->get('status') === 'completed') {
                $query->whereNotNull('completed_at');
            } elseif ($request->get('status') === 'pending') {
                $query->whereNull('completed_at');
            }
        }

        $challenges = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        
        // Get summary stats
        $totalAttempts = DailyChallenge::whereDate('created_at', now()->toDateString())->count();
        $completedToday = DailyChallenge::whereDate('completed_at', now()->toDateString())->count();
        $averageScoreToday = DailyChallenge::whereDate('completed_at', now()->toDateString())->avg('score');
        
        // Get students for filter dropdown
        $students = User::where('role', 'student')->pluck('name', 'id');

        // Get scheduled challenges (configs) for the schedule tab
        $scheduledChallenges = DailyChallengeConfig::with('game')
            ->orderBy('date')
            ->get();

        // Get available games for the schedule form
        $availableGames = Game::where('is_active', true)->get();
        
        return view('teacher.daily-challenges', compact(
            'challenges',
            'totalAttempts',
            'completedToday',
            'averageScoreToday',
            'students',
            'scheduledChallenges',
            'availableGames'
        ));
    }

    /**
     * View daily challenges for a specific student
     */
    public function studentDailyChallenges(User $student, Request $request)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $query = DailyChallenge::where('user_id', $student->id)
            ->select('*')
            ->with(['game', 'question']);
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->get('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->get('to_date'));
        }
        
        $challenges = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        
        // Get stats for this student
        $totalAttempts = DailyChallenge::where('user_id', $student->id)->count();
        $totalCompleted = DailyChallenge::where('user_id', $student->id)->whereNotNull('completed_at')->count();
        $averageScore = DailyChallenge::where('user_id', $student->id)->avg('score');
        $bestScore = DailyChallenge::where('user_id', $student->id)->max('score');
        
        // Get streak (consecutive days with completed challenges)
        $completedDates = DailyChallenge::where('user_id', $student->id)
            ->whereNotNull('completed_at')
            ->selectRaw('DATE(completed_at) as date')
            ->groupBy('date')
            ->orderByDesc('date')
            ->pluck('date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
            ->values();
        
        $streak = 0;
        if ($completedDates->isNotEmpty()) {
            $current = now()->toDateString();
            $index = 0;
            
            if ($completedDates[0] === $current) {
                $streak = 1;
                $index = 1;
            }
            
            for (; $index < $completedDates->count(); $index++) {
                $expected = now()->subDays($index)->toDateString();
                if ($completedDates[$index] === $expected) {
                    $streak++;
                } else {
                    break;
                }
            }
        }

        return view('teacher.student-daily-challenges', compact(
            'student',
            'challenges',
            'totalAttempts',
            'totalCompleted',
            'averageScore',
            'bestScore',
            'streak'
        ));
    }

    /**
     * Return JSON of student's submitted answers for daily challenges
     */
    public function studentDailyAnswers(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $perPage = (int) request()->get('per_page', 15);
        $answers = DailyChallenge::where('user_id', $student->id)
            ->whereNotNull('submitted_answer')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $collection = $answers->getCollection();
        $totalCorrect = $collection->where('is_correct', true)->count();
        $totalIncorrect = $collection->where('is_correct', false)->count();

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

    /**
     * Get question details for a daily challenge
     */
    public function getStudentChallengeQuestion(User $student, DailyChallenge $challenge)
    {
        if (!$student->isStudent() || $challenge->user_id !== $student->id) {
            abort(403, 'Unauthorized');
        }

        $question = $challenge->question;
        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        // Build question performance breakdown
        // This simulates which specific questions in the challenge were correct/wrong
        $questionPerformance = [];
        
        // If the challenge has answer details, build the breakdown
        if ($challenge->submitted_answer !== null) {
            // Check if this is a multi-question challenge or single
            // For now, create a breakdown based on score vs total possible
            $totalQuestions = 5; // Assuming 5 questions per challenge
            $correctQuestions = ceil(($challenge->score / 20) * $totalQuestions); // Estimate based on score
            
            for ($i = 1; $i <= $totalQuestions; $i++) {
                $questionPerformance[] = [
                    'question_number' => $i,
                    'is_correct' => $i <= $correctQuestions,
                ];
            }
        }

        return response()->json([
            'question' => [
                'id' => $question->id,
                'body' => $question->content,
                'answer' => $question->correct_answer,
                'options' => $question->choices,
            ],
            'challenge' => [
                'id' => $challenge->id,
                'submitted_answer' => $challenge->submitted_answer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $challenge->is_correct,
                'score' => $challenge->score,
                'attempt_number' => $challenge->attempt_number,
                'created_at' => $challenge->created_at,
                'question_performance' => $questionPerformance,
            ],
        ]);
    }

    /**
     * Store a new daily challenge config (schedule)
     */
    public function dailyChallengesStore(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'unique:daily_challenge_configs,date'],
            'game_id' => ['required', 'exists:games,id'],
        ]);

        DailyChallengeConfig::create($validated);

        return redirect()->route('teacher.daily-challenges.index')->with('status', 'Challenge scheduled.');
    }

    /**
     * Update a daily challenge config
     */
    public function dailyChallengesUpdate(Request $request, DailyChallengeConfig $config)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'unique:daily_challenge_configs,date,' . $config->id],
            'game_id' => ['required', 'exists:games,id'],
        ]);

        $config->update($validated);

        return redirect()->route('teacher.daily-challenges.index')->with('status', 'Challenge schedule updated.');
    }

    /**
     * Delete a daily challenge config
     */
    public function dailyChallengesDestroy(DailyChallengeConfig $config)
    {
        $config->delete();

        return redirect()->route('teacher.daily-challenges.index')->with('status', 'Scheduled challenge removed.');
    }

    /**
     * Delete a daily challenge attempt
     */
    public function dailyChallengeDestroy(DailyChallenge $challenge)
    {
        $challenge->delete();

        return redirect()->route('teacher.daily-challenges.index')->with('status', 'Challenge attempt deleted.');
    }

    /**
     * Get details of a daily challenge attempt (JSON)
     */
    public function getChallengeDetails(DailyChallenge $challenge)
    {
        $challenge->load(['user', 'game', 'question']);

        return response()->json([
            'id' => $challenge->id,
            'student' => $challenge->user ? [
                'name' => $challenge->user->name,
                'email' => $challenge->user->email,
            ] : null,
            'game' => $challenge->game ? $challenge->game->name : 'Unknown',
            'question' => $challenge->question ? [
                'content' => $challenge->question->content,
                'correct_answer' => $challenge->question->correct_answer,
                'choices' => $challenge->question->choices,
            ] : null,
            'submitted_answer' => $challenge->submitted_answer,
            'is_correct' => $challenge->is_correct,
            'score' => $challenge->score,
            'duration' => $challenge->duration,
            'attempt_number' => $challenge->attempt_number,
            'completed_at' => $challenge->completed_at?->toIso8601String(),
            'created_at' => $challenge->created_at->toIso8601String(),
            'metadata' => $challenge->metadata,
        ]);
    }
}
