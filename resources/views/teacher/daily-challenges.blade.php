@php
    $pageTitle = 'Daily Challenges';
    $pageSubtitle = 'Monitor daily challenge activity and manage schedule';
    $completionRate = $totalAttempts > 0 ? round(($completedToday / $totalAttempts) * 100) : 0;
@endphp

<x-admin-layout>
    <div class="space-y-8" x-data="dailyChallenges">
        
        <!-- Tab Navigation -->
        <div class="flex space-x-1 bg-slate-800/50 p-1 rounded-xl w-fit border border-white/5">
            <button 
                @click="tab = 'attempts'"
                :class="{'bg-indigo-600 text-white shadow-lg': tab === 'attempts', 'text-slate-400 hover:text-white hover:bg-white/5': tab !== 'attempts'}"
                class="px-6 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                Student Attempts
            </button>
            <button 
                @click="tab = 'schedule'"
                :class="{'bg-indigo-600 text-white shadow-lg': tab === 'schedule', 'text-slate-400 hover:text-white hover:bg-white/5': tab !== 'schedule'}"
                class="px-6 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                Manage Schedule
            </button>
        </div>

        <!-- Attempts Tab -->
        <div x-show="tab === 'attempts'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Hero Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Attempts -->
                <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-sm font-medium mb-1">Total Attempts</p>
                            <h3 class="text-3xl font-bold text-white">{{ $totalAttempts }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 mt-4 flex items-center gap-2 text-sm text-slate-500">
                        <span>Today's activity</span>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-sm font-medium mb-1">Completed</p>
                            <h3 class="text-3xl font-bold text-white">{{ $completedToday }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 mt-4 flex items-center gap-2 text-sm text-slate-500">
                        <span>Successful attempts</span>
                    </div>
                </div>

                <!-- Average Score -->
                <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-sm font-medium mb-1">Avg Score</p>
                            <h3 class="text-3xl font-bold text-white">{{ round($averageScoreToday ?? 0, 1) }} <span class="text-lg text-slate-500 font-normal">/ 20</span></h3>
                        </div>
                        <div class="w-12 h-12 bg-violet-500/10 rounded-xl flex items-center justify-center text-violet-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 mt-4 flex items-center gap-2 text-sm text-slate-500">
                        <span>Overall performance</span>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6 hover:bg-slate-800/80 transition-all group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-slate-400 text-sm font-medium mb-1">Success Rate</p>
                            <h3 class="text-3xl font-bold text-white">{{ $completionRate }}%</h3>
                        </div>
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <div class="relative z-10 mt-4 w-full bg-slate-700 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl p-6">
                <form method="GET" class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Search & Filter
                        </h3>
                        <a href="{{ route('teacher.daily-challenges.index') }}" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 hover:text-white text-sm font-medium rounded-xl transition-all border border-white/5 hover:border-white/10 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Reset Filters
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Student</label>
                            <select name="student_id" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <option value="" class="text-slate-400">All Students</option>
                                @foreach($students as $id => $name)
                                    <option value="{{ $id }}" {{ request('student_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <option value="" class="text-slate-400">All Statuses</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl transition-colors shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Search Attempts
                        </button>
                    </div>
                </form>
            </div>

            <!-- Challenges Table -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Challenge Attempts</h3>
                    <span class="px-3 py-1 bg-white/5 rounded-full text-xs font-medium text-slate-400 border border-white/5">
                        {{ $challenges->total() }} records
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                            <tr>
                                <th class="px-6 py-4">Student</th>
                                <th class="px-6 py-4">Score</th>
                                <th class="px-6 py-4">Duration</th>
                                <th class="px-6 py-4">Completed At</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($challenges as $challenge)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($challenge->user)
                                                <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold">
                                                    {{ substr($challenge->user->name, 0, 1) }}
                                                </div>
                                                <a href="{{ route('teacher.daily-challenges.student', $challenge->user) }}" class="text-slate-200 hover:text-white font-medium transition-colors">
                                                    {{ $challenge->user->name }}
                                                </a>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-slate-500 text-xs font-bold">?</div>
                                                <span class="text-slate-500 italic">Unknown Student</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $challenge->score >= 15 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($challenge->score >= 10 ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20') }}">
                                            {{ $challenge->score }} / 20
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-300">
                                        @if($challenge->duration)
                                            {{ $challenge->duration }}s
                                        @else
                                            <span class="text-slate-600">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-slate-300 font-medium" x-text="formatDate('{{ optional($challenge->completed_at ?? $challenge->created_at)->toIso8601String() }}')">
                                                {{ optional($challenge->completed_at ?? $challenge->created_at)->format('M d, Y H:i') }}
                                            </span>
                                            <span class="text-xs text-slate-500" x-text="toRelativeTime('{{ optional($challenge->completed_at ?? $challenge->created_at)->toIso8601String() }}')">
                                                {{ optional($challenge->completed_at ?? $challenge->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- @if($challenge->user)
                                                <a href="{{ route('teacher.daily-challenges.student', $challenge->user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium rounded-lg transition-colors">
                                                    View
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            @endif -->
                                            
                                            <button @click="openDetailsModal('{{ $challenge->id }}')" class="p-1.5 hover:bg-blue-500/10 rounded-lg text-slate-400 hover:text-blue-400 transition-colors" title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>

                                            <form method="POST" action="{{ route('teacher.daily-challenges.destroy', $challenge) }}" onsubmit="return confirm('Are you sure you want to delete this attempt? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 hover:bg-red-500/10 rounded-lg text-slate-400 hover:text-red-400 transition-colors" title="Delete Attempt">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center text-slate-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            </div>
                                            <p class="font-medium">No challenge attempts found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($challenges->hasPages())
                    <div class="p-6 border-t border-white/5">
                        {{ $challenges->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Schedule Tab -->
        <div x-show="tab === 'schedule'" class="space-y-8" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Schedule Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Challenge Schedule</h2>
                    <p class="text-slate-400 text-sm">Plan upcoming daily challenges manually.</p>
                </div>
                <button @click="openCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Schedule Challenge
                </button>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-red-400 font-medium mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Validation Errors
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-300/80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Schedule List -->
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Assigned Game</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($scheduledChallenges as $config)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex flex-col">
                                                <span class="text-slate-200 font-bold" x-text="formatDate('{{ $config->date->toIso8601String() }}')">
                                                    {{ $config->date->format('M d, Y') }}
                                                </span>
                                                <span class="text-xs text-slate-500">
                                                    {{ $config->date->isToday() ? 'Today' : ($config->date->isPast() ? 'Past' : 'Upcoming') }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-slate-200 font-medium">{{ $config->game->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            Scheduled
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="openEditModal(@json($config))" class="p-2 hover:bg-white/5 rounded-lg text-slate-400 hover:text-white transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            <form method="POST" action="{{ route('teacher.daily-challenges.config.destroy', $config) }}" onsubmit="return confirm('Are you sure you want to remove this scheduled challenge?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 hover:bg-red-500/10 rounded-lg text-slate-400 hover:text-red-400 transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                        <p>No challenges scheduled manually. The system will use random selection.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showCreateModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/5">
                    <form action="{{ route('teacher.daily-challenges.config.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="form_type" value="create">
                        <div class="px-6 py-6">
                            <h3 class="text-lg leading-6 font-bold text-white mb-4" id="modal-title">Schedule Daily Challenge</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Date</label>
                                    <input type="date" name="date" required min="{{ date('Y-m-d') }}" x-model="form.date" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Game</label>
                                    <select name="game_id" required x-model="form.game_id" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                        <option value="">Select a game...</option>
                                        @foreach($availableGames as $game)
                                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-900/50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition-colors">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-colors">Schedule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/5">
                    <form :action="editFormAction" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="edit">
                        <input type="hidden" name="config_id" :value="editingConfig ? editingConfig.id : ''">
                        <div class="px-6 py-6">
                            <h3 class="text-lg leading-6 font-bold text-white mb-4" id="modal-title">Edit Scheduled Challenge</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Date</label>
                                    <input type="date" name="date" required x-model="form.date" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Game</label>
                                    <select name="game_id" required x-model="form.game_id" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                        @foreach($availableGames as $game)
                                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-900/50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition-colors">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-colors">Update Schedule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Details Modal -->
        <div x-show="showDetailsModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDetailsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/80 backdrop-blur-sm" aria-hidden="true" @click="showDetailsModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDetailsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-slate-800 border border-white/10 shadow-xl rounded-2xl">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-white">Challenge Details</h3>
                            <p class="text-slate-400 text-sm mt-1" x-show="!loadingDetails">
                                Score: <span class="text-white font-bold" x-text="details.score"></span> / <span x-text="details.max_score"></span>
                            </p>
                        </div>
                        <button @click="showDetailsModal = false" class="text-slate-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div x-show="loadingDetails" class="flex justify-center py-12">
                        <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div x-show="!loadingDetails" class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                                <tr>
                                    <th class="px-4 py-3 w-16">#</th>
                                    <th class="px-4 py-3">Question</th>
                                    <th class="px-4 py-3">Student Answer</th>
                                    <th class="px-4 py-3">Correct Answer</th>
                                    <th class="px-4 py-3 w-24 text-right">Result</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <template x-for="q in details.questions" :key="q.question_number">
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-3 font-mono text-slate-500" x-text="q.question_number"></td>
                                        <td class="px-4 py-3 text-white font-medium" x-text="q.question"></td>
                                        <td class="px-4 py-3 font-mono text-red-400" x-text="q.user_answer"></td>
                                        <td class="px-4 py-3 font-mono text-emerald-400" x-text="q.correct_answer"></td>
                                        <td class="px-4 py-3 text-right">
                                            <span x-show="q.is_correct" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                Right Answer
                                            </span>
                                            <span x-show="!q.is_correct" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                                Wrong Answer
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="details.questions && details.questions.length === 0">
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                        No detailed question data available for this attempt.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dailyChallenges', () => ({
                tab: 'attempts',
                showCreateModal: false,
                showEditModal: false,
                showDetailsModal: false,
                loadingDetails: false,
                details: { score: 0, max_score: 20, questions: [] },
                editingConfig: null,
                editFormAction: '',
                configs: @json($scheduledChallenges),
                form: {
                    date: '',
                    game_id: ''
                },
                
                init() {
                    // Check if there are validation errors in the create/edit forms
                    @if($errors->any())
                        this.tab = 'schedule';
                        
                        // Re-open modal based on form type and populate with old input
                        @if(old('form_type') === 'create')
                            this.form.date = '{{ old('date') }}';
                            this.form.game_id = '{{ old('game_id') }}';
                            this.showCreateModal = true;
                        @elseif(old('form_type') === 'edit')
                            // Find the config that was being edited
                            const configId = '{{ old('config_id') }}';
                            const config = this.configs.find(c => c.id == configId);
                            if (config) {
                                this.editingConfig = config;
                                // Use the submitted values (which caused error) instead of original config
                                this.form.date = '{{ old('date') }}';
                                this.form.game_id = '{{ old('game_id') }}';
                                this.editFormAction = '{{ route('teacher.daily-challenges.config.update', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', config.id);
                                this.showEditModal = true;
                            }
                        @endif
                    @endif
                },

                openCreateModal() {
                    this.form.date = '';
                    this.form.game_id = '';
                    this.showCreateModal = true;
                },

                openEditModal(config) {
                    this.editingConfig = config;
                    this.form.date = config.date.substring(0, 10);
                    this.form.game_id = config.game_id;
                    // Safe route generation
                    this.editFormAction = '{{ route('teacher.daily-challenges.config.update', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', config.id);
                    this.showEditModal = true;
                },

                async openDetailsModal(challengeId) {
                    this.showDetailsModal = true;
                    this.loadingDetails = true;
                    this.details = { score: 0, max_score: 20, questions: [] };

                    try {
                        const response = await fetch('{{ route('teacher.daily-challenges.details', 'ID_PLACEHOLDER') }}'.replace('ID_PLACEHOLDER', challengeId));
                        if (!response.ok) throw new Error('Failed to fetch details');
                        const data = await response.json();
                        this.details = data;
                    } catch (error) {
                        console.error('Error fetching challenge details:', error);
                        alert('Failed to load challenge details. Please try again.');
                        this.showDetailsModal = false;
                    } finally {
                        this.loadingDetails = false;
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return '—';
                    const date = new Date(dateString);
                    return new Intl.DateTimeFormat('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric', 
                        hour: '2-digit', 
                        minute: '2-digit',
                        timeZone: 'Asia/Manila' 
                    }).format(date);
                },

                toRelativeTime(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const now = new Date();
                    const diffInSeconds = Math.floor((now - date) / 1000);
                    
                    if (diffInSeconds < 60) return 'just now';
                    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
                    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
                    return `${Math.floor(diffInSeconds / 86400)}d ago`;
                }
            }));
        });
    </script>
</x-admin-layout>
