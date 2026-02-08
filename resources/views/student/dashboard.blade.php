<x-app-layout>
    <div class="min-h-screen bg-slate-900 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-[#131c31] to-slate-900">
        <!-- Welcome Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 via-[#2e1065] to-blue-800 pt-16 pb-24 px-4 shadow-xl">
            <!-- Background Decorations -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-10 left-10 text-8xl animate-pulse">🎮</div>
                <div class="absolute top-20 right-20 text-6xl animate-bounce delay-700">⭐</div>
                <div class="absolute bottom-5 left-1/3 text-8xl animate-pulse delay-1000">🎯</div>
                <div class="absolute bottom-10 right-10 text-7xl animate-bounce">🏆</div>
                <!-- Abstract Circles -->
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-indigo-200 mb-3 drop-shadow-sm">
                            Welcome back, <br/><span class="text-yellow-400">{{ auth()->user()->name }}</span>! 👋
                        </h1>
                        <p class="text-xl text-blue-100 font-medium max-w-2xl">
                            ✨ Keep pushing your limits! Practice makes progress.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-sm font-semibold">
                            📅 {{ now()->format('l, F j, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
                    <!-- Games Played -->
                    <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-blue-200 font-medium mb-1">Games Played</div>
                                <div class="text-3xl font-bold text-white">{{ $totalGames }}</div>
                            </div>
                            <div class="p-2 bg-blue-500/20 rounded-lg text-blue-300 group-hover:bg-blue-500/30 transition-colors">
                                🎮
                            </div>
                        </div>
                    </div>

                    <!-- Total Points -->
                    <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-green-200 font-medium mb-1">Total Points</div>
                                <div class="text-3xl font-bold text-emerald-400">{{ $totalScore }}</div>
                            </div>
                            <div class="p-2 bg-emerald-500/20 rounded-lg text-emerald-300 group-hover:bg-emerald-500/30 transition-colors">
                                💎
                            </div>
                        </div>
                    </div>

                    <!-- Day Streak -->
                    <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-orange-200 font-medium mb-1">Day Streak</div>
                                <div class="text-3xl font-bold text-orange-400">{{ $streak }} <span class="text-lg text-orange-300/70">days</span></div>
                            </div>
                            <div class="p-2 bg-orange-500/20 rounded-lg text-orange-300 group-hover:bg-orange-500/30 transition-colors">
                                🔥
                            </div>
                        </div>
                    </div>

                    <!-- Avg Accuracy -->
                    <div class="group bg-white/5 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:bg-white/10 hover:border-white/20 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm text-purple-200 font-medium mb-1">Avg Accuracy</div>
                                <div class="text-3xl font-bold text-purple-400">{{ round($avgScore) }}%</div>
                            </div>
                            <div class="p-2 bg-purple-500/20 rounded-lg text-purple-300 group-hover:bg-purple-500/30 transition-colors">
                                🎯
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4 mt-8">
                    <a href="{{ route('student.games.index') }}" class="px-8 py-4 bg-white text-indigo-900 font-bold rounded-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 text-lg flex items-center gap-2 shadow-blue-900/50">
                        <span class="text-2xl">▶️</span> Play a Game
                    </a>
                    <a href="{{ route('student.progress') }}" class="px-8 py-4 bg-indigo-600/30 border border-indigo-400/30 backdrop-blur-sm text-white font-bold rounded-xl hover:bg-indigo-600/50 transition-all duration-300 text-lg flex items-center gap-2">
                        <span class="text-2xl">📊</span> View Progress
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-10 relative z-20">
            @php
                $avgScore = $totalGames > 0 ? round($totalScore / max($totalGames, 1), 1) : 0;
            @endphp

            <!-- User Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Level Card -->
                <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700 shadow-xl hover:shadow-2xl hover:border-indigo-500/50 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            <span class="p-1.5 bg-indigo-500/20 rounded-lg">🎖️</span> Your Level
                        </h3>
                    </div>
                    <div class="flex items-end gap-2 mb-4">
                        <span class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-300">
                            {{ min(max(round($totalGames / 2), 1), 20) }}
                        </span>
                        <span class="text-slate-400 font-medium mb-2">/ 20</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-3 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-indigo-500 to-cyan-400 h-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(99,102,241,0.5)]" style="width: {{ min(max((($totalGames / 40) * 100), 0), 100) }}%"></div>
                    </div>
                    <p class="text-slate-400 text-sm mt-3 flex justify-between">
                        <span>XP Progress</span>
                        <span class="text-indigo-300">{{ max(0, 2550 - ($totalGames * 100)) }} pts to next</span>
                    </p>
                </div>

                <!-- Badges Card -->
                <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700 shadow-xl hover:shadow-2xl hover:border-blue-500/50 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            <span class="p-1.5 bg-blue-500/20 rounded-lg">🏅</span> Badges Earned
                        </h3>
                    </div>
                    <div class="flex flex-wrap gap-3 mb-4 min-h-[60px]">
                        <div class="p-2 bg-slate-700/50 rounded-lg text-2xl hover:scale-110 hover:bg-slate-600 transition-all cursor-help" title="Speed Demon">⚡</div>
                        <div class="p-2 bg-slate-700/50 rounded-lg text-2xl hover:scale-110 hover:bg-slate-600 transition-all cursor-help" title="Perfect Score">💯</div>
                        <div class="p-2 bg-slate-700/50 rounded-lg text-2xl hover:scale-110 hover:bg-slate-600 transition-all cursor-help" title="Streak Master">🔥</div>
                        <div class="p-2 bg-slate-700/50 rounded-lg text-2xl hover:scale-110 hover:bg-slate-600 transition-all cursor-help" title="Math Wizard">🧙</div>
                        @if($totalGames >= 20)
                            <div class="p-2 bg-yellow-500/20 rounded-lg text-2xl hover:scale-110 transition-all cursor-help ring-1 ring-yellow-500/50" title="Combo King">👑</div>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400">Total Unlocked</span>
                        <span class="text-blue-300 font-bold">{{ min($totalGames > 10 ? 5 : ($totalGames > 5 ? 4 : ($totalGames > 0 ? 3 : 0)), 20) }} / 20</span>
                    </div>
                </div>

                <!-- Streak Card -->
                <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-6 border border-slate-700 shadow-xl hover:shadow-2xl hover:border-green-500/50 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            <span class="p-1.5 bg-green-500/20 rounded-lg">🔥</span> Current Streak
                        </h3>
                    </div>
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-300">{{ $streak }}</span>
                        <span class="text-xl text-slate-400 font-medium">Days</span>
                    </div>
                    <p class="text-green-300/90 text-sm mb-4 font-medium">Keep the fire burning! 🔥</p>
                    <div class="space-y-2 text-slate-300 text-sm bg-slate-700/30 p-3 rounded-xl border border-slate-600/50">
                        <div class="flex items-center gap-2">
                            <span class="text-green-400">✓</span> Played today
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400">★</span> Best streak: <span class="text-white font-bold">30 days</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Game Hub Section -->
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-2 flex items-center gap-3">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Game Hub</span>
                        </h2>
                        <p class="text-slate-400">Choose a game and start learning through play</p>
                    </div>
                    <a href="{{ route('student.games.index') }}" class="group flex items-center gap-2 text-indigo-300 hover:text-indigo-200 font-semibold transition-colors">
                        View All <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>

                <!-- Game Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($activeGames as $game)
                        @php
                            $gameIcons = [
                                'puzzle-match' => '🧩',
                                'drag-drop-sort' => '↔️',
                                'integer-battle' => '⚔️',
                                'spin-wheel' => '🎡',
                                'speed-math' => '⚡',
                                'pemdas-puzzle' => '🧩',
                                'timed-challenge' => '⏱️'
                            ];
                            $gameColors = [
                                'puzzle-match' => 'from-indigo-600 to-blue-500',
                                'drag-drop-sort' => 'from-cyan-600 to-blue-500',
                                'integer-battle' => 'from-red-600 to-orange-500',
                                'spin-wheel' => 'from-purple-600 to-pink-500',
                                'speed-math' => 'from-emerald-600 to-green-500',
                            ];
                            $gradient = $gameColors[$game->slug] ?? 'from-slate-700 to-slate-600';
                        @endphp
                        <div class="group relative overflow-hidden bg-slate-800 rounded-2xl border border-slate-700 hover:border-indigo-500/50 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                            <!-- Gradient Overlay on Hover -->
                            <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }} opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                            
                            <div class="p-6 relative z-10">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-4xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        {{ $gameIcons[$game->slug] ?? '🎮' }}
                                    </div>
                                    @php
                                        $difficulty = $game->config['difficulty'] ?? 'Medium';
                                        $diffColor = match($difficulty) {
                                            'Easy' => 'bg-green-500/20 text-green-300 border-green-500/30',
                                            'Hard' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                            default => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $diffColor }}">
                                        {{ $difficulty }}
                                    </span>
                                </div>

                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors">{{ $game->name }}</h3>
                                <p class="text-slate-400 text-sm mb-6 line-clamp-2 h-10">{{ $game->description }}</p>

                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                        👥 <span class="text-slate-400">Popular</span>
                                    </span>
                                    <a href="{{ route('student.games.play', $game->slug) }}" class="px-4 py-2 bg-slate-700 hover:bg-white hover:text-indigo-900 text-white text-sm font-bold rounded-lg transition-all duration-300 shadow-md">
                                        Play Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center bg-slate-800/50 rounded-2xl border border-slate-700 border-dashed">
                            <div class="text-6xl mb-4 opacity-50">😴</div>
                            <h3 class="text-xl font-bold text-white mb-2">No Games Available</h3>
                            <p class="text-slate-400">Check back later for new challenges!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Progress & Stats Section -->
            <div class="mb-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Weekly Activity -->
                    <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-8 border border-slate-700 shadow-xl">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-white font-bold text-xl flex items-center gap-2">
                                <span class="p-1.5 bg-indigo-500/20 rounded-lg">📊</span> Weekly Activity
                            </h3>
                            <select class="bg-slate-700 border-none text-white text-sm rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option>This Week</option>
                                <option>Last Week</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end gap-3 h-48 px-2">
                            @php
                                $maxCount = $weeklyActivity->max('count');
                            @endphp
                            @foreach($weeklyActivity as $activity)
                                @php
                                    $height = $maxCount > 0 ? ($activity['count'] / $maxCount) * 100 : 0;
                                    // Ensure visible bar even for small values if not 0
                                    $displayHeight = $activity['count'] > 0 ? max($height, 5) : 2;
                                @endphp
                                <div class="flex-1 flex flex-col justify-end gap-2 group cursor-pointer h-full">
                                    <div class="relative w-full flex-1 flex items-end">
                                        <div class="w-full rounded-t-lg transition-all duration-500 group-hover:brightness-125 {{ $activity['is_today'] ? 'bg-gradient-to-t from-indigo-600 to-cyan-400 shadow-[0_0_15px_rgba(99,102,241,0.5)]' : 'bg-slate-700 group-hover:bg-slate-600' }}" 
                                             style="height: {{ $displayHeight }}%">
                                            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-black text-white text-xs py-1 px-2 rounded pointer-events-none transition-opacity whitespace-nowrap z-10">
                                                {{ $activity['count'] }} games
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-center {{ $activity['is_today'] ? 'text-indigo-300' : 'text-slate-500' }}">{{ $activity['day'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-700/50 flex justify-between items-center text-sm">
                            <span class="text-slate-400">Activity (Last 7 Days)</span>
                            <span class="text-white font-bold">{{ $weeklyActivity->sum('count') }} <span class="text-slate-500 font-normal">sessions</span></span>
                        </div>
                    </div>

                    <!-- Performance Stats -->
                    <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl p-8 border border-slate-700 shadow-xl">
                        <h3 class="text-white font-bold text-xl mb-8 flex items-center gap-2">
                            <span class="p-1.5 bg-purple-500/20 rounded-lg">📈</span> Performance Stats
                        </h3>
                        <div class="space-y-8">
                            <!-- Accuracy -->
                            <div class="group">
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-300 font-medium">Accuracy Rate</span>
                                    <span class="text-purple-300 font-bold">{{ round($avgScore) }}%</span>
                                </div>
                                <div class="w-full bg-slate-700 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-full rounded-full transition-all duration-1000 group-hover:shadow-[0_0_10px_rgba(168,85,247,0.5)]" style="width: {{ min(max(round($avgScore), 0), 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Games Volume -->
                            <div class="group">
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-300 font-medium">Games Played</span>
                                    <span class="text-blue-300 font-bold">{{ $totalGames }}</span>
                                </div>
                                <div class="w-full bg-slate-700 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full rounded-full transition-all duration-1000 group-hover:shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: {{ min(max(($totalGames / 50 * 100), 0), 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Consistency -->
                            <div class="group">
                                <div class="flex justify-between mb-2">
                                    <span class="text-slate-300 font-medium">Consistency</span>
                                    <span class="text-green-300 font-bold">{{ $streak }} Days</span>
                                </div>
                                <div class="w-full bg-slate-700 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-400 h-full rounded-full transition-all duration-1000 group-hover:shadow-[0_0_10px_rgba(34,197,94,0.5)]" style="width: {{ min(max(($streak / 30 * 100), 0), 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-white mb-6">🎯 Recent Activity</h2>
                <div class="bg-slate-800/80 backdrop-blur-xl rounded-2xl border border-slate-700 overflow-hidden">
                    @if($recentGames->count() > 0)
                        <div class="divide-y divide-slate-700">
                            @foreach($recentGames->take(5) as $session)
                                <div class="group flex items-center justify-between p-5 hover:bg-slate-700/50 transition-all duration-200 cursor-default">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-700 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                            @if(isset($session->details['daily_challenge_id']))
                                                📅
                                            @else
                                                {{ ['puzzle-match' => '🧩', 'drag-drop-sort' => '↔️', 'integer-battle' => '⚔️'][$session->game->slug] ?? '🎮' }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-white font-bold group-hover:text-indigo-300 transition-colors">
                                                @if(isset($session->details['daily_challenge_id']))
                                                    Daily Challenge
                                                    <span class="text-xs text-slate-400 font-normal block">{{ $session->game->name }}</span>
                                                @else
                                                    {{ $session->game->name }}
                                                @endif
                                            </p>
                                            <p class="text-slate-400 text-xs">{{ $session->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="inline-flex items-center gap-1 px-3 py-1 bg-green-500/10 text-green-400 rounded-full text-sm font-bold mb-1">
                                            +{{ $session->score }} pts
                                        </div>
                                        <p class="text-slate-500 text-xs">{{ $session->details['accuracy'] ?? '0' }}% accuracy</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 bg-slate-700/30 text-center border-t border-slate-700">
                            <a href="{{ route('student.progress') }}" class="text-indigo-300 hover:text-white text-sm font-semibold transition-colors">View All History →</a>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4 opacity-30">📜</div>
                            <p class="text-slate-400 text-lg mb-4">No games played yet!</p>
                            <a href="{{ route('student.games.index') }}" class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition-all">
                                Start Playing
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Motivational Footer -->
            <div class="relative overflow-hidden bg-gradient-to-r from-emerald-900/40 to-green-900/40 backdrop-blur-xl rounded-2xl p-10 border border-green-500/20 text-center">
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-green-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>
                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-emerald-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>
                
                <h3 class="text-3xl font-bold text-white mb-4">🌟 You're Crushing It!</h3>
                <p class="text-green-100 mb-8 max-w-2xl mx-auto text-lg leading-relaxed">
                    You've completed <span class="font-bold text-white">{{ $totalGames }}</span> games and achieved <span class="font-bold text-white">{{ round($avgScore) }}%</span> accuracy! Keep up this amazing momentum. You're making real progress! 
                </p>
                <a href="{{ route('student.games.index') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-green-900/50 transition-all duration-300 hover:scale-105 hover:-translate-y-1">
                    Keep Playing 🚀
                </a>
            </div>
        </div>
    </div>
</x-app-layout>