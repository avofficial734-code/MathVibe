<x-admin-layout>
    <div class="space-y-8">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="relative z-10 flex items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl shadow-inner">
                            🎓
                        </div>
                        <h2 class="text-3xl font-bold">{{ $student->name }}'s Progress</h2>
                    </div>
                    <p class="text-indigo-100 text-lg">Detailed analytics and performance overview</p>
                </div>
                <div class="hidden md:block">
                    <div class="px-6 py-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 text-center">
                        <p class="text-xs text-indigo-100 uppercase tracking-wider font-medium">Joined</p>
                        <p class="font-bold text-lg">{{ $student->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Sessions -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Sessions</p>
                        <h3 class="text-3xl font-bold text-white">{{ $totalSessions }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Avg Score -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Avg. Score</p>
                        <h3 class="text-3xl font-bold text-white">{{ $avgScore }}%</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-700/50 rounded-full h-1.5">
                    <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-1.5 rounded-full" style="width: {{ $avgScore }}%"></div>
                </div>
            </div>

            <!-- Best Score -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Best Score</p>
                        <h3 class="text-3xl font-bold text-white">{{ $bestScore }}%</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Streak -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Streak</p>
                        <h3 class="text-3xl font-bold text-white">{{ $streak }} <span class="text-lg font-normal text-slate-400">days</span></h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-500/10 rounded-xl flex items-center justify-center text-orange-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                    </div>
                </div>
            </div>
        </div>


        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Score Trend -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 p-6">
                <h3 class="text-xl font-bold text-white mb-6">Score Trend (Last 14 Days)</h3>
                <div class="h-64 w-full">
                    <canvas id="scoreTrendChart"></canvas>
                </div>
            </div>

            <!-- Sessions by Game -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 p-6">
                <h3 class="text-xl font-bold text-white mb-6">Sessions by Game</h3>
                <div class="h-64 w-full flex items-center justify-center">
                    <canvas id="sessionsByGameChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Average Score by Game Chart -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 p-6">
            <h3 class="text-xl font-bold text-white mb-6">Average Score by Game</h3>
            <div class="h-64 w-full">
                <canvas id="avgScoreByGameChart"></canvas>
            </div>
        </div>

        <!-- Recent Sessions Table -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 overflow-hidden">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Recent Sessions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Game</th>
                            <th class="px-6 py-4">Score</th>
                            <th class="px-6 py-4">Duration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($recentSessions as $session)
                            @php
                                $dt = $session->completed_at ?? $session->created_at;
                            @endphp
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div data-ts="{{ optional($dt)?->toIso8601String() }}">
                                        <div class="text-white font-medium js-localized-date">{{ optional($dt)->format('M d, Y H:i') }}</div>
                                        <div class="text-xs text-slate-500 js-relative-time">...</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-300">
                                    @if(isset($session->details['daily_challenge_id']))
                                        Daily Challenge
                                        <span class="text-xs text-slate-500 block font-normal">{{ optional($session->game)->name ?? 'Unknown' }}</span>
                                    @else
                                        {{ optional($session->game)->name ?? 'Unknown' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold {{ $session->score >= 80 ? 'text-emerald-400' : ($session->score >= 50 ? 'text-amber-400' : 'text-red-400') }}">
                                            {{ $session->score }}%
                                        </span>
                                        <div class="w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $session->score >= 80 ? 'bg-emerald-500' : ($session->score >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $session->score }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-400">{{ gmdate('i:s', (int) $session->duration) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">No sessions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.05)';

            // Score Trend
            const trendCanvas = document.getElementById('scoreTrendChart');
            if (trendCanvas) {
                const trendCtx = trendCanvas.getContext('2d');
                const trendGrad = trendCtx.createLinearGradient(0, 0, 0, 300);
                trendGrad.addColorStop(0, 'rgba(139, 92, 246, 0.5)'); // Violet
                trendGrad.addColorStop(1, 'rgba(139, 92, 246, 0.0)');
                
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: @json($trendLabels),
                        datasets: [{
                            label: 'Avg Score',
                            data: @json($trendData),
                            borderColor: '#8b5cf6',
                            backgroundColor: trendGrad,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#8b5cf6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                borderColor: 'rgba(255,255,255,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { borderDash: [4, 4] }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Sessions by Game
            const gameCanvas = document.getElementById('sessionsByGameChart');
            if (gameCanvas) {
                const gameCtx = gameCanvas.getContext('2d');
                new Chart(gameCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($gameLabels),
                        datasets: [{
                            data: @json($gameSessionCounts),
                            backgroundColor: [
                                '#6366f1', // Indigo
                                '#ec4899', // Pink
                                '#10b981', // Emerald
                                '#f59e0b', // Amber
                                '#8b5cf6', // Violet
                                '#3b82f6', // Blue
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    color: '#cbd5e1'
                                }
                            }
                        },
                        cutout: '75%'
                    }
                });
            }

            // Average Score by Game
            const avgCanvas = document.getElementById('avgScoreByGameChart');
            if (avgCanvas) {
                const avgCtx = avgCanvas.getContext('2d');
                new Chart(avgCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($gameLabels),
                        datasets: [{
                            label: 'Avg Score',
                            data: @json($gameAvgScores),
                            backgroundColor: 'rgba(99, 102, 241, 0.8)',
                            borderColor: '#6366f1',
                            borderWidth: 0,
                            borderRadius: 6,
                            barThickness: 30
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                borderColor: 'rgba(255,255,255,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 100,
                                grid: { borderDash: [4, 4] }
                            },
                            y: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>

    <script>
        // Localize ISO timestamps (used across teacher views)
        (function(){
            function timeAgo(date){
                const seconds = Math.floor((Date.now() - date.getTime()) / 1000);
                const intervals = [
                    { label: 'year', secs: 31536000 },
                    { label: 'month', secs: 2592000 },
                    { label: 'day', secs: 86400 },
                    { label: 'hour', secs: 3600 },
                    { label: 'minute', secs: 60 },
                    { label: 'second', secs: 1 }
                ];
                for (const i of intervals) {
                    const count = Math.floor(seconds / i.secs);
                    if (count > 0) return `${count} ${i.label}${count>1?'s':''} ago`;
                }
                return 'just now';
            }

            document.querySelectorAll('[data-ts]').forEach(function(el){
                const ts = el.getAttribute('data-ts');
                if (!ts) return;
                const d = new Date(ts);
                if (isNaN(d.getTime())) return;
                const local = el.querySelector('.js-localized-date');
                const rel = el.querySelector('.js-relative-time');
                if (local) local.textContent = d.toLocaleString();
                if (rel) rel.textContent = timeAgo(d);
            });
        })();
    </script>
</x-admin-layout>
