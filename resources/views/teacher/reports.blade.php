@php
    $pageTitle = 'Reports';
    $pageSubtitle = 'Analytics and performance reports';
@endphp

<x-admin-layout>
    <div class="space-y-8">
        <!-- Report Type Selector -->
        <div class="flex flex-wrap gap-2 p-1 bg-slate-800/50 backdrop-blur-xl rounded-xl border border-white/5 w-fit">
            <a href="{{ route('teacher.reports') }}" class="px-6 py-2 rounded-lg font-medium transition-all {{ request('type') === null ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Overall
            </a>
            <a href="{{ route('teacher.reports', ['type' => 'student']) }}" class="px-6 py-2 rounded-lg font-medium transition-all {{ request('type') === 'student' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Student Performance
            </a>
            <a href="{{ route('teacher.reports', ['type' => 'game']) }}" class="px-6 py-2 rounded-lg font-medium transition-all {{ request('type') === 'game' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Game Analytics
            </a>
            <a href="{{ route('teacher.reports', ['type' => 'question']) }}" class="px-6 py-2 rounded-lg font-medium transition-all {{ request('type') === 'question' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                Question Stats
            </a>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Students</p>
                        <p class="text-3xl font-bold text-white">{{ $totalStudents ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Sessions</p>
                        <p class="text-3xl font-bold text-white">{{ $totalSessions ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Avg Score</p>
                        <p class="text-3xl font-bold text-white">{{ round($avgScore ?? 0, 1) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-violet-500/10 rounded-xl flex items-center justify-center text-violet-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6 hover:bg-slate-800/80 transition-all group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Success Rate</p>
                        <p class="text-3xl font-bold text-white">{{ round(($successRate ?? 0) * 100, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        @if(request('type') === 'student')
            <!-- Student Performance Table -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-xl font-bold text-white">Student Performance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4 font-medium">Student</th>
                                <th class="px-6 py-4 font-medium">Sessions</th>
                                <th class="px-6 py-4 font-medium">Avg Score</th>
                                <th class="px-6 py-4 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($students as $student)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-xs">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <span class="text-white font-medium">{{ $student->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $student->game_sessions_count }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-white font-medium">{{ round($student->game_sessions_avg_score ?? 0, 1) }}</span>
                                            <div class="w-24 bg-slate-700 rounded-full h-1.5">
                                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ min(100, $student->game_sessions_avg_score ?? 0) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('teacher.students.progress', $student) }}" class="text-indigo-400 hover:text-indigo-300 font-medium text-sm">View Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">No student data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/5">
                    {{ $students->links() }}
                </div>
            </div>

        @elseif(request('type') === 'game')
            <!-- Game Analytics Table -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-xl font-bold text-white">Game Analytics</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4 font-medium">Game</th>
                                <th class="px-6 py-4 font-medium">Total Sessions</th>
                                <th class="px-6 py-4 font-medium">Avg Score</th>
                                <th class="px-6 py-4 font-medium">Popularity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($games as $game)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-white font-medium">{{ $game->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $game->sessions_count }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-emerald-400 font-medium">{{ round($game->sessions_avg_score ?? 0, 1) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="w-full max-w-xs bg-slate-700 rounded-full h-1.5">
                                            @php
                                                $maxSessions = $games->max('sessions_count') ?: 1;
                                                $width = ($game->sessions_count / $maxSessions) * 100;
                                            @endphp
                                            <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $width }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">No game data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif(request('type') === 'question')
            <!-- Question Stats Table -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-xl font-bold text-white">Question Statistics</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4 font-medium w-1/2">Question</th>
                                <th class="px-6 py-4 font-medium">Total Attempts</th>
                                <th class="px-6 py-4 font-medium">Correct Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($questions as $question)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-white font-medium truncate max-w-md">{{ $question->content }}</div>
                                        <div class="text-slate-500 text-xs mt-1">Answer: {{ $question->correct_answer }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">{{ $question->total_attempts }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $correct = $question->correct_count ?? 0;
                                            $total = $question->total_attempts ?? 0;
                                            $rate = $total > 0 ? ($correct / $total) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="{{ $rate >= 70 ? 'text-emerald-400' : ($rate >= 40 ? 'text-amber-400' : 'text-red-400') }} font-medium">
                                                {{ round($rate, 1) }}%
                                            </span>
                                            <div class="w-16 bg-slate-700 rounded-full h-1.5">
                                                <div class="{{ $rate >= 70 ? 'bg-emerald-500' : ($rate >= 40 ? 'bg-amber-500' : 'bg-red-500') }} h-1.5 rounded-full" style="width: {{ $rate }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500">No question data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/5">
                    {{ $questions->links() }}
                </div>
            </div>

        @else
            <!-- Overall Dashboard -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Activity Chart -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
                    <h3 class="text-lg font-bold text-white mb-6">Activity (Last 30 Days)</h3>
                    <div class="h-64 w-full">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <!-- Top Students -->
                <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Top Performing Students
                    </h3>
                    <div class="space-y-4">
                        @forelse($topStudents ?? [] as $student)
                            <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-xl border border-white/5 hover:border-white/10 transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-violet-500/20 flex items-center justify-center text-violet-400 text-sm font-bold group-hover:scale-110 transition-transform">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-white group-hover:text-violet-300 transition-colors">{{ $student->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $student->sessions_count }} sessions</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-300 rounded-full text-sm font-bold">
                                    {{ round($student->avg_score ?? 0, 1) }}%
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500">
                                <p>No student data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Chart.defaults.color = '#94a3b8';
                    Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.05)';
                    
                    const ctx = document.getElementById('activityChart');
                    if (ctx) {
                        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); 
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json($activityLabels ?? []),
                                datasets: [{
                                    label: 'Sessions',
                                    data: @json($activityValues ?? []),
                                    borderColor: '#6366f1',
                                    backgroundColor: gradient,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#6366f1',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { borderDash: [4, 4] }
                                    },
                                    x: {
                                        grid: { display: false }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
        @endif
    </div>
</x-admin-layout>
