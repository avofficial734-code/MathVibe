@php
    $pageTitle = '📊 Dashboard';
    $pageSubtitle = 'School Performance Overview';
@endphp

<x-admin-layout>
    <div class="space-y-8">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-blue-100 text-lg">Here's what's happening in your school today.</p>
                </div>
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20">
                    <div class="text-right">
                        <p class="text-sm text-blue-100">Current Session</p>
                        <p class="font-bold">{{ now()->format('h:i A') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl shadow-inner">
                        📅
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Students -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Students</p>
                        <h3 class="text-3xl font-bold text-white">{{ $totalStudents }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span>Active Learners</span>
                </div>
            </div>

            <!-- Total Games -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Available Games</p>
                        <h3 class="text-3xl font-bold text-white">{{ $totalGames }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-slate-400">
                    <span>Curriculum aligned</span>
                </div>
            </div>

            <!-- Total Sessions -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Sessions</p>
                        <h3 class="text-3xl font-bold text-white">{{ $totalSessions }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-pink-500/10 rounded-xl flex items-center justify-center text-pink-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span>Growing engagement</span>
                </div>
            </div>

            <!-- Avg Score -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Avg Score</p>
                        <h3 class="text-3xl font-bold text-white">{{ round($recentSessions->avg('score') ?? 0, 1) }}%</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-700/50 rounded-full h-1.5">
                    <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-1.5 rounded-full" style="width: {{ round($recentSessions->avg('score') ?? 0) }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Chart Section -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Performance Chart -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-white">Performance Overview</h3>
                            <p class="text-slate-400 text-sm">Average scores across different games</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 text-xs font-medium border border-indigo-500/20">Live Data</span>
                        </div>
                    </div>
                    <div class="h-80 w-full">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity Table -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 overflow-hidden">
                    <div class="p-6 border-b border-white/5 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-white">Recent Sessions</h3>
                            <p class="text-slate-400 text-sm">Latest student activity</p>
                        </div>
                        <a href="{{ route('teacher.students') }}" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-white text-sm rounded-xl transition-colors border border-white/5">
                            View All
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                                <tr>
                                    <th class="px-6 py-4">Student</th>
                                    <th class="px-6 py-4">Game</th>
                                    <th class="px-6 py-4">Score</th>
                                    <th class="px-6 py-4">Time</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($recentSessions as $session)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($session->user->name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-white">{{ $session->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">{{ $session->game->name }}</td>
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
                                        <td class="px-6 py-4">{{ $session->created_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('teacher.students.progress', $session->user) }}" class="text-indigo-400 hover:text-indigo-300 font-medium">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                            No recent activity found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="space-y-8">
                <!-- Activity Chart -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Weekly Activity</h3>
                    <div class="h-64 w-full">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <!-- New Students -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-3xl border border-white/5 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">New Students</h3>
                        <a href="{{ route('teacher.students') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium uppercase tracking-wider">View All</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentStudents as $student)
                            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-orange-400 flex items-center justify-center text-white font-bold shadow-lg group-hover:scale-110 transition-transform">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-medium truncate">{{ $student->name }}</h4>
                                    <p class="text-xs text-slate-400">Joined {{ $student->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-500">
                                No new students recently
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.05)';
            
            // Performance Chart
            const perfCtx = document.getElementById('performanceChart');
            if (perfCtx) {
                const perfGrad = perfCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                perfGrad.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo
                perfGrad.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

                new Chart(perfCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($gamePerformance->pluck('game')),
                        datasets: [{
                            label: 'Average Score',
                            data: @json($gamePerformance->pluck('score')),
                            backgroundColor: perfGrad,
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                            barThickness: 40,
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

            // Activity Chart
            const actCtx = document.getElementById('activityChart');
            if (actCtx) {
                const activityData = @json($activityData);
                const labels = [];
                const data = [];
                const today = new Date();

                for (let i = 6; i >= 0; i--) {
                    const d = new Date(today);
                    d.setDate(d.getDate() - i);
                    const dateStr = d.toISOString().split('T')[0];
                    labels.push(d.toLocaleDateString('en-US', { weekday: 'short' }));
                    const record = activityData.find(item => item.date === dateStr);
                    data.push(record ? record.count : 0);
                }

                const actGrad = actCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                actGrad.addColorStop(0, 'rgba(16, 185, 129, 0.5)'); // Emerald
                actGrad.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                new Chart(actCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sessions',
                            data: data,
                            borderColor: '#10b981',
                            backgroundColor: actGrad,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#10b981',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
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
                                ticks: { precision: 0 },
                                grid: { borderDash: [4, 4] }
                            },
                            x: {
                                grid: { display: false }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            }
        });
    </script>
</x-admin-layout>
