<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-[#283B60] to-slate-900">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 pt-6 pb-12 sm:pt-8 sm:pb-16 px-3 sm:px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-1 sm:top-2 left-5 sm:left-10 text-3xl sm:text-5xl">⭐</div>
                <div class="absolute top-12 sm:top-20 right-10 sm:right-20 text-2xl sm:text-4xl">🔥</div>
                <div class="absolute bottom-2 sm:bottom-5 left-1/3 text-4xl sm:text-6xl">🎯</div>
            </div>

            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white mb-1 sm:mb-2 drop-shadow-lg">
                    ⭐ Daily Challenge
                </h1>
                <p class="text-white/90 drop-shadow-lg text-xs sm:text-base md:text-lg">Complete today's featured game and earn bonus points!</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 py-6 sm:py-12 relative">
            <!-- Streak & Stats Bar -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6 sm:mb-8">
                <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 backdrop-blur-md rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-yellow-400/30">
                    <div class="text-2xl sm:text-4xl mb-1 sm:mb-2">🔥</div>
                    <div class="text-xs sm:text-sm text-white/70">Current Streak</div>
                    <div class="text-2xl sm:text-3xl font-bold text-yellow-300">{{ auth()->user()->daily_challenge_streak ?? 0 }}</div>
                </div>

                <div class="bg-gradient-to-br from-[#283B60]/20 to-[#1A3263]/20 backdrop-blur-md rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-[#283B60]/30">
                    <div class="text-2xl sm:text-4xl mb-1 sm:mb-2">🎮</div>
                    <div class="text-xs sm:text-sm text-white/70">Attempts Today</div>
                    <div class="text-2xl sm:text-3xl font-bold text-[#283B60]">{{ $stats['attempts'] }}</div>
                </div>

                <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 backdrop-blur-md rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-blue-400/30">
                    <div class="text-2xl sm:text-4xl mb-1 sm:mb-2">🏆</div>
                    <div class="text-xs sm:text-sm text-white/70">Best Score</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-300">{{ $stats['bestScore'] }}</div>
                </div>

                <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 backdrop-blur-md rounded-xl sm:rounded-2xl p-3 sm:p-6 border border-green-400/30">
                    <div class="text-2xl sm:text-4xl mb-1 sm:mb-2">✨</div>
                    <div class="text-xs sm:text-sm text-white/70">Avg Score</div>
                    <div class="text-2xl sm:text-3xl font-bold text-green-300">{{ $stats['averageScore'] }}</div>
                </div>
            </div>

            <!-- Today's Challenge Card -->
            <div class="bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-2xl sm:rounded-3xl p-6 sm:p-12 border border-white/10 shadow-2xl mb-6 sm:mb-8">
                <div class="max-w-2xl mx-auto">
                    <!-- Game Icon -->
                    <div class="text-center mb-6 sm:mb-8">
                        <div class="inline-block text-5xl sm:text-7xl lg:text-9xl mb-3 sm:mb-6 animate-bounce">
                            @switch($dailyGame->slug)
                                @case('puzzle-match')
                                    🧩
                                    @break
                                @case('drag-drop-sort')
                                    🎴
                                    @break
                                @case('integer-battle')
                                    ⚔️
                                    @break
                                @case('pemdas-rush')
                                    ⚡
                                    @break
                                @case('spin-wheel')
                                    🎡
                                    @break
                                @default
                                    🎮
                            @endswitch
                        </div>
                    </div>

                    <!-- Game Title -->
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white text-center mb-2 sm:mb-4">
                        {{ $dailyGame->name }}
                    </h2>

                    <!-- Game Description -->
                    <p class="text-center text-white/70 text-xs sm:text-base lg:text-lg mb-6 sm:mb-8 px-2">
                        {{ $dailyGame->description ?? 'Complete today\'s challenge!' }}
                    </p>

                    <!-- Challenge Reward Info -->
                    <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-400/30 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8">
                        <div class="flex items-center justify-center gap-2 sm:gap-3 flex-col sm:flex-row">
                            <span class="text-2xl sm:text-3xl">🎁</span>
                            <div class="text-center sm:text-left">
                                <p class="text-white/70 text-xs sm:text-sm">Bonus Reward</p>
                                <p class="text-lg sm:text-2xl font-bold text-yellow-300">+50 XP for Completion</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Message -->
                    @if($stats['completedToday'])
                        <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-400/30 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-center">
                            <div class="flex items-center justify-center gap-2 mb-2 flex-col sm:flex-row">
                                <span class="text-xl sm:text-2xl">✅</span>
                                <span class="text-green-300 font-semibold text-sm sm:text-base">Challenge Completed!</span>
                            </div>
                            <p class="text-white/70 text-xs sm:text-sm">You've already completed today's challenge. Keep up the streak! 🔥</p>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-blue-500/20 to-cyan-500/20 border border-blue-400/30 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-center">
                            <div class="flex items-center justify-center gap-2 mb-2 flex-col sm:flex-row">
                                <span class="text-xl sm:text-2xl">🎯</span>
                                <span class="text-blue-300 font-semibold text-sm sm:text-base">Ready to Play?</span>
                            </div>
                            <p class="text-white/70 text-xs sm:text-sm">Complete the challenge to maintain your streak and earn bonus XP!</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex gap-2 sm:gap-4 justify-center flex-col sm:flex-row px-2">
                        <a href="{{ route('student.daily-challenge.play') }}" class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl text-sm sm:text-lg shadow-lg transition transform hover:scale-105 active:scale-95 touch-manipulation w-full sm:w-auto text-center">
                            ▶️ Play Challenge
                        </a>
                        <a href="{{ route('student.games.index') }}" class="bg-gradient-to-r from-[#283B60] to-[#1A3263] hover:from-[#283B60] hover:to-[#1A3263] text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl text-sm sm:text-lg shadow-lg transition transform hover:scale-105 active:scale-95 touch-manipulation w-full sm:w-auto text-center">
                            🎮 All Games
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-2xl sm:rounded-3xl p-6 sm:p-8 border border-white/10 shadow-2xl">
                <h3 class="text-xl sm:text-2xl font-bold text-white mb-4 sm:mb-6">💡 Daily Challenge Tips</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div class="flex gap-3 sm:gap-4">
                        <div class="text-2xl sm:text-3xl flex-shrink-0">🎯</div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-white mb-1 sm:mb-2 text-sm sm:text-base">Maintain Your Streak</h4>
                            <p class="text-white/70 text-xs sm:text-sm">Complete the daily challenge every day to build a streak and unlock special rewards!</p>
                        </div>
                    </div>
                    <div class="flex gap-3 sm:gap-4">
                        <div class="text-2xl sm:text-3xl flex-shrink-0">⭐</div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-white mb-1 sm:mb-2 text-sm sm:text-base">Improve Your Score</h4>
                            <p class="text-white/70 text-xs sm:text-sm">Try multiple times to beat your best score and earn higher rankings on the leaderboard.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 sm:gap-4">
                        <div class="text-2xl sm:text-3xl flex-shrink-0">🔥</div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-white mb-1 sm:mb-2 text-sm sm:text-base">Bonus XP</h4>
                            <p class="text-white/70 text-xs sm:text-sm">Earn +50 XP just for completing today's challenge, plus points from your game score!</p>
                        </div>
                    </div>
                    <div class="flex gap-3 sm:gap-4">
                        <div class="text-2xl sm:text-3xl flex-shrink-0">📊</div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-white mb-1 sm:mb-2 text-sm sm:text-base">New Challenge Daily</h4>
                            <p class="text-white/70 text-xs sm:text-sm">A new game is featured every day at midnight. Check back tomorrow for a different challenge!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
