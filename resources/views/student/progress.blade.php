<x-app-layout>
    <div x-data="progressCharts" class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
        
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400 mb-2">
                        Your Progress
                    </h1>
                    <p class="text-slate-300 text-lg">Track your improvements and milestones.</p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('student.dashboard') }}" 
                       class="px-5 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white font-medium hover:bg-white/10 transition-all flex items-center gap-2 backdrop-blur-sm">
                        <span>←</span> Dashboard
                    </a>
                    <a href="{{ route('student.games.index') }}" 
                       class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold hover:shadow-lg hover:shadow-blue-500/25 transition-all flex items-center gap-2">
                        Play Games <span>→</span>
                    </a>
                </div>
            </div>

            @if($sessions->isEmpty() && $dailyChallenges->isEmpty())
                <!-- Empty State -->
                <div class="bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 p-12 text-center max-w-2xl mx-auto">
                    <div class="text-8xl mb-6 animate-bounce">🎮</div>
                    <h2 class="text-3xl font-bold text-white mb-3">No Games Played Yet</h2>
                    <p class="text-slate-400 text-lg mb-8">Start playing games to track your stats, visualize your progress, and earn achievements!</p>
                    <a href="{{ route('student.games.index') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-lg hover:shadow-lg hover:shadow-blue-500/25 transition-all transform hover:-translate-y-1">
                        Start Playing Now
                    </a>
                </div>
            @else
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <!-- Sessions Played -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white/5 border border-white/10 p-6 hover:bg-white/10 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <span class="text-6xl">🎮</span>
                        </div>
                        <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider mb-2">Total Sessions</h3>
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-black text-white">{{ $stats['total_sessions'] }}</span>
                            <span class="text-emerald-400 text-sm font-bold mb-1">Played</span>
                        </div>
                    </div>

                    <!-- Average Score -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white/5 border border-white/10 p-6 hover:bg-white/10 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <span class="text-6xl">⭐</span>
                        </div>
                        <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider mb-2">Average Score</h3>
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-black text-white">{{ $stats['avg_score'] }}</span>
                            <span class="text-blue-400 text-sm font-bold mb-1">Points</span>
                        </div>
                    </div>

                    <!-- Total Time -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white/5 border border-white/10 p-6 hover:bg-white/10 transition-all duration-300">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <span class="text-6xl">⏱️</span>
                        </div>
                        <h3 class="text-slate-400 text-sm font-medium uppercase tracking-wider mb-2">Total Time</h3>
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-black text-white">{{ $stats['total_minutes'] }}</span>
                            <span class="text-purple-400 text-sm font-bold mb-1">Minutes</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <!-- Score Trend -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <span class="w-2 h-6 bg-purple-500 rounded-full"></span>
                                    Score History
                                </h3>
                                <p class="text-slate-400 text-sm mt-1">Your last 30 games</p>
                            </div>
                        </div>
                        <div class="h-72 w-full">
                            <canvas x-ref="trendChart"></canvas>
                        </div>
                    </div>

                    <!-- Game Performance -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                                    Performance by Game
                                </h3>
                                <p class="text-slate-400 text-sm mt-1">Average score breakdown</p>
                            </div>
                        </div>
                        <div class="h-72 w-full">
                            <canvas x-ref="gameChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    
                    <!-- Game History -->
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <span>📜</span> Recent Games
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-white/10">
                                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-slate-400 font-semibold">Game</th>
                                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-slate-400 font-semibold">Score</th>
                                        <th class="px-4 py-3 text-right text-xs uppercase tracking-wider text-slate-400 font-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($sessions as $session)
                                        <tr class="hover:bg-white/5 transition-colors group">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="font-medium text-white group-hover:text-blue-400 transition-colors">
                                                        @if(isset($session->details['daily_challenge_id']))
                                                            Daily Challenge
                                                            <span class="text-xs text-slate-500 block font-normal">{{ $session->game->name }}</span>
                                                        @else
                                                            {{ $session->game->name }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                    {{ $session->score }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right text-slate-400 text-sm">
                                                {{ $session->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $sessions->appends(['challenges_page' => request('challenges_page')])->links() }}
                        </div>
                    </div>

                    <!-- Daily Challenge History -->
                    @if($dailyChallenges->isNotEmpty())
                    <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <span>📅</span> Daily Challenges
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-white/10">
                                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-slate-400 font-semibold">Date</th>
                                        <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-slate-400 font-semibold">Score</th>
                                        <th class="px-4 py-3 text-right text-xs uppercase tracking-wider text-slate-400 font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($dailyChallenges as $challenge)
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="px-4 py-3 text-white font-medium">
                                                {{ $challenge->created_at->format('M d, Y') }}
                                                <div class="text-xs text-slate-500">{{ $challenge->created_at->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-white font-bold">{{ $challenge->score }}</span>
                                                    <span class="text-slate-500 text-sm">/ 20</span>
                                                    
                                                    @php
                                                        $percent = ($challenge->score / 20) * 100;
                                                        $color = $percent >= 80 ? 'bg-emerald-500' : ($percent >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                                    @endphp
                                                    
                                                    <div class="w-24 h-2 bg-slate-700 rounded-full overflow-hidden">
                                                        <div class="h-full {{ $color }}" style="width: {{ $percent }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                @if($challenge->score == 20)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                        🏆 Perfect
                                                    </span>
                                                @elseif($challenge->score >= 10)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                        ✅ Completed
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                                        Attempted
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $dailyChallenges->appends(['page' => request('page')])->links() }}
                        </div>
                    </div>
                    @endif
                </div>

            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('progressCharts', () => ({
                init() {
                    this.initTrendChart();
                    this.initGameChart();
                },

                initTrendChart() {
                    const canvas = this.$refs.trendChart;
                    if (!canvas) return;

                    const rawSessions = @json($chartSessions);
                    // Sort by date ascending (oldest to newest) for the chart
                    const sessions = [...rawSessions].reverse();

                    const labels = sessions.map(s => {
                        const d = new Date(s.created_at);
                        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    });
                    const data = sessions.map(s => s.score);

                    const ctx = canvas.getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(168, 85, 247, 0.4)');
                    gradient.addColorStop(1, 'rgba(168, 85, 247, 0.0)');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Score',
                                data: data,
                                borderColor: '#a855f7', // purple-500
                                backgroundColor: gradient,
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#a855f7',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleColor: '#fff',
                                    bodyColor: '#cbd5e1',
                                    padding: 12,
                                    borderColor: 'rgba(255,255,255,0.1)',
                                    borderWidth: 1,
                                    displayColors: false,
                                    callbacks: {
                                        label: (context) => `Score: ${context.raw}`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                    ticks: { color: 'rgba(148, 163, 184, 1)' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: 'rgba(148, 163, 184, 1)', maxTicksLimit: 8 }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                        }
                    });
                },

                initGameChart() {
                    const canvas = this.$refs.gameChart;
                    if (!canvas) return;

                    const sessions = @json($chartSessions);
                    const stats = {};
                    
                    sessions.forEach(s => {
                        let name = s.game ? s.game.name : 'Unknown';
                        if (s.details && s.details.daily_challenge_id) {
                            name = 'Daily Challenge';
                        }
                        if (!stats[name]) stats[name] = { total: 0, count: 0 };
                        stats[name].total += s.score;
                        stats[name].count += 1;
                    });

                    const labels = Object.keys(stats);
                    const data = labels.map(n => Math.round((stats[n].total / stats[n].count) * 10) / 10);

                    const ctx = canvas.getContext('2d');
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Avg Score',
                                data: data,
                                backgroundColor: 'rgba(59, 130, 246, 0.6)', // blue-500
                                hoverBackgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderRadius: 6,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y', // Horizontal bar chart is often better for names
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleColor: '#fff',
                                    bodyColor: '#cbd5e1',
                                    padding: 12,
                                    borderColor: 'rgba(255,255,255,0.1)',
                                    borderWidth: 1,
                                    displayColors: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                    ticks: { color: 'rgba(148, 163, 184, 1)' }
                                },
                                y: {
                                    grid: { display: false },
                                    ticks: { color: 'rgba(148, 163, 184, 1)' }
                                }
                            }
                        }
                    });
                }
            }))
        })
    </script>

    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</x-app-layout>