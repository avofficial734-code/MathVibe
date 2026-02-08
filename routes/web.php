<?php
use App\Http\Controllers\GameController;
use App\Http\Controllers\DailyChallengeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Redirect /dashboard based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student Routes
Route::middleware(['auth', 'verified', 'isStudent'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/mnemonics', [StudentController::class, 'mnemonics'])->name('mnemonics');
    
    // Games
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/play/{game:slug}', [GameController::class, 'play'])->name('games.play');
    Route::get('/games/{game}/questions', [GameController::class, 'getQuestions'])->name('games.questions');
    Route::post('/games/session', [GameController::class, 'storeSession'])->name('games.session.store');
    
    Route::get('/progress', [StudentController::class, 'progress'])->name('progress');
    Route::get('/practice', [StudentController::class, 'practice'])->name('practice');
    Route::get('/leaderboard', [StudentController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/feedback', [StudentController::class, 'feedback'])->name('feedback');
    
    // Daily Challenge Routes
    Route::get('/daily-challenge', [DailyChallengeController::class, 'show'])->name('daily-challenge.show');
    Route::get('/daily-challenge/play', [DailyChallengeController::class, 'play'])->name('daily-challenge.play');
    Route::post('/daily-challenge/record', [DailyChallengeController::class, 'storeSession'])->name('daily-challenge.record');
    Route::get('/daily-challenge/answers', [DailyChallengeController::class, 'studentAnswers'])->name('daily-challenge.answers');
    
    
});

// Teacher Routes
Route::middleware(['auth', 'verified', 'isTeacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [TeacherController::class, 'students'])->name('students');
    Route::get('/students/{student}/progress', [TeacherController::class, 'studentProgress'])->name('students.progress');
    Route::post('/students', [TeacherController::class, 'studentsStore'])->name('students.store');
    Route::patch('/students/{student}', [TeacherController::class, 'studentsUpdate'])->name('students.update');
    Route::delete('/students/{student}', [TeacherController::class, 'studentsDestroy'])->name('students.destroy');
    Route::post('/admin', [TeacherController::class, 'adminStore'])->name('admin.store');
    
    // Daily Challenge Management
    Route::get('/daily-challenges', [TeacherController::class, 'dailyChallenges'])->name('daily-challenges.index');
    Route::post('/daily-challenges/config', [TeacherController::class, 'dailyChallengesStore'])->name('daily-challenges.config.store');
    Route::patch('/daily-challenges/config/{config}', [TeacherController::class, 'dailyChallengesUpdate'])->name('daily-challenges.config.update');
    Route::delete('/daily-challenges/config/{config}', [TeacherController::class, 'dailyChallengesDestroy'])->name('daily-challenges.config.destroy');
    Route::delete('/daily-challenges/attempt/{challenge}', [TeacherController::class, 'dailyChallengeDestroy'])->name('daily-challenges.destroy');
    Route::get('/daily-challenges/attempt/{challenge}/details', [TeacherController::class, 'getChallengeDetails'])->name('daily-challenges.details');
    Route::get('/daily-challenges/{student}', [TeacherController::class, 'studentDailyChallenges'])->name('daily-challenges.student');
    Route::get('/daily-challenges/{student}/answers', [TeacherController::class, 'studentDailyAnswers'])->name('daily-challenges.answers');
    Route::get('/daily-challenges/{student}/challenge/{challenge}/question', [TeacherController::class, 'getStudentChallengeQuestion'])->name('daily-challenges.question');
    
    Route::get('/admins', [TeacherController::class, 'admins'])->name('admins.index');
    Route::post('/admins', [TeacherController::class, 'adminsStore'])->name('admins.store');
    Route::patch('/admins/{admin}', [TeacherController::class, 'adminsUpdate'])->name('admins.update');
    Route::delete('/admins/{admin}', [TeacherController::class, 'adminsDestroy'])->name('admins.destroy');
    
    // Resource Routes
    Route::resource('questions', QuestionController::class);
    // Route::resource('games', TeacherGameController::class); // Future: Game management
    
    Route::get('/reports', [TeacherController::class, 'reports'])->name('reports');
    Route::get('/feedback', [TeacherController::class, 'feedback'])->name('feedback');
    Route::patch('/feedback/{feedback}/resolve', [TeacherController::class, 'feedbackResolve'])->name('feedback.resolve');
    Route::delete('/feedback/{feedback}', [TeacherController::class, 'feedbackDestroy'])->name('feedback.destroy');
});

require __DIR__.'/auth.php';
