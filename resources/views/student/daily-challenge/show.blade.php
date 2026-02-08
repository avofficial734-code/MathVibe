<x-app-layout>
    <div 
        class="min-h-screen bg-gradient-to-b from-slate-900 via-[#283B60] to-slate-900" 
        x-data="dailyChallenge()"
    >
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-orange-900/40 via-[#283B60] to-slate-900 pb-12 px-4 shadow-xl z-10">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
                <div class="absolute top-10 left-10 text-8xl transform -rotate-12 text-white animate-pulse">🔥</div>
                <div class="absolute bottom-10 right-10 text-9xl transform rotate-12 text-orange-500 opacity-20">⚡</div>
            </div>

            <div class="max-w-7xl mx-auto pt-10 pb-6 relative z-20">
                <!-- Breadcrumb -->
                <div class="mb-8">
                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 text-blue-200/60 hover:text-white transition-colors group">
                        <span class="bg-white/5 p-1.5 rounded-lg group-hover:bg-white/10 transition-colors">←</span>
                        <span class="font-medium">Back to Dashboard</span>
                    </a>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-center md:text-left">
                        <span class="inline-block py-1 px-3 rounded-full bg-orange-500/20 border border-orange-400/30 text-orange-300 text-sm font-semibold mb-4 backdrop-blur-sm">
                            Daily Quest
                        </span>
                        <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-orange-100 to-orange-200 mb-4 drop-shadow-sm">
                            {{ $dailyGame->name }}
                        </h1>
                        <p class="text-orange-100/80 text-lg md:text-xl max-w-2xl leading-relaxed">
                            {{ $dailyGame->description }}
                        </p>
                    </div>

                    <!-- Quick Stats Cards -->
                    <div class="flex gap-4">
                        <div class="bg-slate-900/50 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px]">
                            <div class="text-3xl font-bold text-white mb-1">20</div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold">Questions</div>
                        </div>
                        <div class="bg-slate-900/50 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px]">
                            <div class="text-3xl font-bold text-blue-300 mb-1">20m</div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold">Time Limit</div>
                        </div>
                        <div class="bg-slate-900/50 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px]">
                            <div class="text-3xl font-bold text-red-400 mb-1">HARD</div>
                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold">Difficulty</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Stats & Action -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- User Today Stats -->
                    @if(auth()->check())
                        <div class="bg-white/5 border border-white/10 rounded-3xl p-8 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                            
                            <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                                <span>📊</span> Your Performance Today
                            </h3>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <div class="bg-slate-800/50 rounded-2xl p-4 border border-white/5">
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Attempts</div>
                                    <div class="text-3xl font-bold text-white">{{ $stats['attempts'] ?? 0 }}</div>
                                </div>
                                <div class="bg-slate-800/50 rounded-2xl p-4 border border-white/5">
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Best Score</div>
                                    <div class="text-3xl font-bold text-yellow-400">{{ $stats['bestScore'] ?? 0 }}<span class="text-lg text-white/50">/20</span></div>
                                </div>
                                <div class="bg-slate-800/50 rounded-2xl p-4 border border-white/5">
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Avg Score</div>
                                    <div class="text-3xl font-bold text-blue-300">{{ $stats['averageScore'] ?? 0 }}</div>
                                </div>
                                <div class="bg-slate-800/50 rounded-2xl p-4 border border-white/5">
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Status</div>
                                    <div class="text-2xl font-bold">
                                        @if($stats['completedToday'])
                                            <span class="text-green-400 flex items-center gap-1">Completed ✓</span>
                                        @else
                                            <span class="text-slate-400">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($stats['completedToday'])
                                <div class="mt-6 bg-green-500/10 border border-green-500/20 rounded-xl p-4 flex items-center gap-4">
                                    <div class="bg-green-500/20 p-2 rounded-full text-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-green-300 font-bold">Challenge Completed!</p>
                                        <p class="text-green-300/70 text-sm">Great job! You can still play again to improve your score.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a 
                            href="{{ route('student.daily-challenge.play') }}" 
                            @click="confirmStart($event)"
                            class="relative overflow-hidden bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white p-6 rounded-2xl font-bold text-xl text-center shadow-lg shadow-orange-500/20 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-3 group"
                        >
                            <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                            <span class="relative z-10">🎮 Start Challenge</span>
                        </a>
                        
                        <button 
                            @click="openModal"
                            class="bg-slate-800 hover:bg-slate-700 border border-white/10 text-white p-6 rounded-2xl font-bold text-xl text-center transition-all flex items-center justify-center gap-3 hover:shadow-lg"
                        >
                            <span>📋 View My Answers</span>
                        </button>
                    </div>

                    <!-- Question Preview -->
                    @if($dailyGame->slug === 'daily-challenge')
                    <div class="bg-slate-800/40 border border-white/5 rounded-3xl p-8">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span>📝</span> Sample Question
                        </h3>
                        <div class="bg-slate-900/80 rounded-xl p-6 border-l-4 border-orange-500 font-mono text-lg text-white shadow-inner">
                            3 - (2³ + (-5) × 4) ÷ 2 + 1
                        </div>
                        <p class="mt-3 text-slate-400 text-sm flex items-center gap-2">
                            <span class="text-green-400 font-bold">Answer:</span> 10
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Right Column: Info -->
                <div class="space-y-8">
                    <!-- Rules -->
                    <div class="bg-slate-800/40 border border-white/5 rounded-3xl p-8">
                        <h3 class="text-xl font-bold text-white mb-6">Challenge Rules</h3>
                        <ul class="space-y-4">
                            @if($dailyGame->slug === 'daily-challenge')
                            <li class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-sm shrink-0">1</div>
                                <p class="text-slate-300 text-sm leading-relaxed">Solve 20 randomly generated hard-level PEMDAS questions.</p>
                            </li>
                            <li class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-sm shrink-0">2</div>
                                <p class="text-slate-300 text-sm leading-relaxed">Each question involves 4+ operations, including exponents and negative integers.</p>
                            </li>
                            <li class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-sm shrink-0">3</div>
                                <p class="text-slate-300 text-sm leading-relaxed">You have exactly 20 minutes to complete the challenge.</p>
                            </li>
                            <li class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-sm shrink-0">4</div>
                                <p class="text-slate-300 text-sm leading-relaxed">No calculators! All answers are whole numbers.</p>
                            </li>
                            @else
                            <li class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-sm shrink-0">1</div>
                                <p class="text-slate-300 text-sm leading-relaxed">Complete the challenge to earn your daily streak.</p>
                            </li>
                            <li class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-sm shrink-0">2</div>
                                <p class="text-slate-300 text-sm leading-relaxed">Do your best and try to beat your high score!</p>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Benefits -->
                    <div class="bg-gradient-to-br from-indigo-900/40 to-slate-900/40 border border-indigo-500/20 rounded-3xl p-8">
                        <h3 class="text-xl font-bold text-white mb-6">Why Play?</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-indigo-200 text-sm">
                                <span class="text-xl">🏆</span> Earn badges & achievements
                            </div>
                            <div class="flex items-center gap-3 text-indigo-200 text-sm">
                                <span class="text-xl">📈</span> Track your daily progress
                            </div>
                            <div class="flex items-center gap-3 text-indigo-200 text-sm">
                                <span class="text-xl">🧠</span> Master complex mental math
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Answers Modal -->
        <div 
            x-show="showModal" 
            style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>

            <!-- Modal Content -->
            <div 
                class="bg-slate-900 border border-white/10 rounded-3xl w-full max-w-4xl max-h-[90vh] flex flex-col relative shadow-2xl shadow-black/50"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <!-- Header -->
                <div class="p-6 border-b border-white/10 flex items-center justify-between bg-slate-800/50 rounded-t-3xl">
                    <h3 class="text-2xl font-bold text-white">My Answer History</h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Stats Bar -->
                <div class="p-4 bg-slate-800/30 flex items-center justify-around text-sm border-b border-white/5">
                    <div class="text-center">
                        <div class="text-slate-400">Total</div>
                        <div class="text-xl font-bold text-white" x-text="stats.total">0</div>
                    </div>
                    <div class="text-center">
                        <div class="text-slate-400">Correct</div>
                        <div class="text-xl font-bold text-green-400" x-text="stats.correct">0</div>
                    </div>
                    <div class="text-center">
                        <div class="text-slate-400">Incorrect</div>
                        <div class="text-xl font-bold text-red-400" x-text="stats.incorrect">0</div>
                    </div>
                </div>

                <!-- List -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <template x-if="loading && answers.length === 0">
                        <div class="text-center py-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-4"></div>
                            <p class="text-slate-400">Loading your history...</p>
                        </div>
                    </template>

                    <template x-if="!loading && answers.length === 0">
                        <div class="text-center py-12 bg-white/5 rounded-2xl border border-white/5">
                            <div class="text-4xl mb-4">📭</div>
                            <p class="text-slate-400">No answers recorded yet.</p>
                            <p class="text-slate-500 text-sm mt-2">Play the challenge to start building your history!</p>
                        </div>
                    </template>

                    <template x-for="answer in answers" :key="answer.id">
                        <div class="bg-white/5 border border-white/5 rounded-xl p-4 flex items-center justify-between hover:bg-white/10 transition-colors">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span 
                                        class="px-2.5 py-0.5 rounded-full text-xs font-bold"
                                        :class="answer.is_correct ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'"
                                        x-text="answer.is_correct ? 'Correct' : 'Incorrect'"
                                    ></span>
                                    <span class="text-slate-400 text-xs" x-text="new Date(answer.created_at).toLocaleString()"></span>
                                </div>
                                <div class="text-white font-mono text-lg">
                                    <span class="text-slate-400 mr-2">Your Answer:</span>
                                    <span x-text="answer.submitted_answer || 'None'"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500 uppercase font-bold mb-1">Score</div>
                                <div class="text-xl font-bold text-white" x-text="answer.score"></div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="loading && answers.length > 0" class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white/50 mx-auto"></div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-white/10 bg-slate-800/50 rounded-b-3xl flex justify-center" x-show="hasMore">
                    <button 
                        @click="loadAnswers(page + 1)" 
                        class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg font-medium transition-colors"
                        :disabled="loading"
                    >
                        Load More Records
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div 
            x-show="showConfirmModal" 
            style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showConfirmModal = false"></div>

            <!-- Modal Content -->
            <div 
                class="bg-slate-900 border border-white/10 rounded-3xl w-full max-w-md p-6 relative shadow-2xl shadow-black/50 text-center"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="w-16 h-16 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    ⚠️
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-2">Challenge Completed</h3>
                
                <p class="text-slate-300 mb-6 leading-relaxed">
                    You have already completed today's challenge. <br>
                    Would you like to try again?
                    <span class="block text-sm text-slate-500 mt-2">(Your best score will be preserved)</span>
                </p>
                
                <div class="flex gap-3 justify-center">
                    <button 
                        @click="showConfirmModal = false" 
                        class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-colors border border-white/10"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="proceedToPlay" 
                        class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-orange-500/20"
                    >
                        Yes, Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dailyChallenge', () => ({
                showModal: false,
                showConfirmModal: false,
                playUrl: '',
                answers: [],
                loading: false,
                page: 1,
                hasMore: false,
                stats: { total: 0, correct: 0, incorrect: 0 },
                
                async loadAnswers(page = 1) {
                    if (this.loading) return;
                    this.loading = true;
                    
                    try {
                        const res = await fetch(`{{ route('student.daily-challenge.answers') }}?page=${page}`);
                        const data = await res.json();
                        
                        if (page === 1) {
                            this.answers = data.answers;
                        } else {
                            this.answers = [...this.answers, ...data.answers];
                        }
                        
                        this.stats = {
                            total: data.total,
                            correct: data.total_correct,
                            incorrect: data.total_incorrect
                        };
                        
                        this.page = data.current_page;
                        this.hasMore = data.current_page < data.last_page;
                    } catch (e) {
                        console.error('Failed to load answers:', e);
                    } finally {
                        this.loading = false;
                    }
                },
                
                openModal() {
                    this.showModal = true;
                    if (this.answers.length === 0) {
                        this.loadAnswers();
                    }
                },
                
                confirmStart(e) {
                     const completedToday = {{ $stats['completedToday'] ? 'true' : 'false' }};
                     if (completedToday) {
                         e.preventDefault();
                         this.playUrl = e.currentTarget.href;
                         this.showConfirmModal = true;
                     }
                },

                proceedToPlay() {
                    window.location.href = this.playUrl;
                }
            }))
        })
    </script>
</x-app-layout>