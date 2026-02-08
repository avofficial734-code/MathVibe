<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-[#283B60] to-slate-900" x-data="gameLogic()" x-init="initGame()" x-cloak>
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-500 pt-8 pb-16 px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-2 left-10 text-5xl">🎯</div>
                <div class="absolute top-20 right-20 text-4xl">⭐</div>
                <div class="absolute bottom-5 left-1/3 text-6xl">📊</div>
            </div>

            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">
                    {{ $game->name }}
                </h1>
                <p class="text-white/90 drop-shadow-lg">{{ $game->description }}</p>

                <!-- Live Stats -->
                <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-6">
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Time</div>
                        <div id="timer" class="text-2xl font-bold text-red-300">00:00</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Score</div>
                        <div class="text-2xl font-bold text-yellow-300" x-text="score">0</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Question</div>
                        <div class="text-2xl font-bold text-blue-300" x-text="(currentQuestionIndex + 1) + '/' + questions.length">1/10</div>
                    </div>
                    <div class="hidden md:block bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Accuracy</div>
                        <div class="text-2xl font-bold text-green-300" x-text="questions.length > 0 ? Math.round((totalCorrect / Math.min(currentQuestionIndex, questions.length)) * 100) + '%' : '--'">--</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Game Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            <div class="bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-3xl p-8 border border-white/10 shadow-2xl">
                <div class="text-center">
                    <!-- Loading State -->
                    <div x-show="loading" class="py-20">
                        <div class="inline-block">
                            <div class="text-5xl mb-4">⏳</div>
                            <p class="text-xl text-white">Loading Game...</p>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div x-show="error" class="py-20">
                        <div class="inline-block text-center">
                            <div class="text-5xl mb-4">⚠️</div>
                            <p class="text-xl text-red-400 mb-6" x-text="errorMessage"></p>
                            <button @click="window.location.reload()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Try Again
                            </button>
                        </div>
                    </div>

                    <!-- Start Screen -->
                    <div x-show="!loading && !error && !playing && !gameOver" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-7xl mb-6">🎮</div>
                            <h2 class="text-3xl font-bold text-white mb-4">Ready to Play?</h2>
                            <p class="text-white/70 mb-8">Answer PEMDAS and algebra questions as they appear. Answer correctly to earn points!</p>
                            <button @click="startGame" class="bg-gradient-to-r from-[#283B60] to-[#1A3263] hover:from-[#283B60] hover:to-[#1A3263] text-white font-bold py-4 px-8 rounded-xl text-lg shadow-lg transition transform hover:scale-105">
                                ▶️ Start Game
                            </button>
                        </div>
                    </div>

                    <!-- Playing State -->
                    <div x-show="playing" class="py-12">
                        <!-- Progress Bar -->
                        <div class="mb-8 max-w-2xl mx-auto">
                            <div class="h-2 w-full bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-2 bg-gradient-to-r from-[#283B60] to-[#1A3263] rounded-full transition-all" :style="`width: ${Math.min(Math.floor((currentQuestionIndex / Math.max(questions.length,1)) * 100),100)}%`"></div>
                            </div>
                            <div class="mt-3 text-white/70 text-sm">
                                <span x-text="currentQuestionIndex + 1"></span> of <span x-text="questions.length"></span> questions
                            </div>
                        </div>

                        <!-- Question Card -->
                        <div class="bg-gradient-to-br from-[#283B60]/20 to-blue-500/20 backdrop-blur-md rounded-2xl p-8 border border-[#283B60]/30 mb-8 max-w-2xl mx-auto">
                            <div class="text-white/70 text-sm mb-3">Question <span x-text="currentQuestionIndex + 1"></span></div>
                            <div class="text-3xl md:text-6xl font-bold text-white mb-6" x-text="currentQuestion.content"></div>
                            <div class="text-sm text-white/60">Select the correct answer below</div>
                        </div>

                        <!-- Answer Options -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                            <template x-for="(choice, index) in currentQuestion.choices" :key="index">
                                <button @click="checkAnswer(choice)" 
                                    class="bg-gradient-to-r from-blue-500/30 to-cyan-500/30 hover:from-blue-500/50 hover:to-cyan-500/50 border border-blue-400/50 hover:border-blue-400/80 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95 touch-manipulation">
                                    <span x-text="choice" class="text-lg"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Score Display -->
                        <div class="mt-8 text-center">
                            <p class="text-white/70 text-sm">Current Score</p>
                            <p class="text-4xl font-bold text-yellow-300" x-text="score"></p>
                        </div>
                    </div>

                    <!-- Game Over Screen -->
                    <div x-show="gameOver" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-6xl mb-6">🏆</div>
                            <h2 class="text-4xl font-bold text-white mb-2">Game Over!</h2>
                            <div class="bg-gradient-to-r from-[#283B60]/20 to-[#1A3263]/20 border border-[#283B60]/30 rounded-2xl p-8 mb-8">
                                <p class="text-white/70 text-sm mb-2">Final Score</p>
                                <p class="text-5xl font-bold text-yellow-300 mb-4" x-text="score"></p>
                                <p class="text-white/70">
                                    <span x-text="questions.length"></span> questions completed in <span id="durationText" class="font-bold">0m 0s</span>
                                </p>
                            </div>
                            <div class="flex gap-4 justify-center flex-wrap">
                                <button @click="initGame" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition">
                                    🔄 Play Again
                                </button>
                                <a href="{{ route('student.games.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                    🎮 More Games
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hint Modal -->
                <div x-show="showHint" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 px-4" style="display: none;" @click.outside="showHint = false" x-transition>
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 max-w-md w-full border border-white/10" @click.stop>
                        <div class="text-5xl mb-4 text-center">💡</div>
                        <h4 class="text-2xl font-bold text-yellow-400 mb-4 text-center">Not Quite!</h4>
                        <div class="bg-blue-500/20 border border-blue-400/30 p-4 rounded-lg mb-6">
                            <p class="italic text-white/80" x-text="currentHint"></p>
                        </div>
                        <button @click="showHint = false; nextQuestion()" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3 rounded-lg transition active:scale-95 touch-manipulation">
                            Got it, Next Question
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        function gameLogic() {
            return {
                isDailyChallenge: {{ $isDailyChallenge ? 'true' : 'false' }},
                loading: true,
                error: false,
                errorMessage: '',
                playing: false,
                gameOver: false,
                questions: [],
                currentQuestionIndex: 0,
                currentQuestion: {},
                score: 0,
                totalCorrect: 0,
                startTime: null,
                timerInterval: null,
                showHint: false,
                currentHint: '',
                isSpinWheel: false,

                async initGame() {
                    this.loading = true;
                    this.error = false;
                    this.gameOver = false;
                    this.score = 0;
                    this.totalCorrect = 0;
                    this.currentQuestionIndex = 0;
                    
                    try {
                        const response = await fetch("{{ route('student.games.questions', $game->id) }}");
                        if (!response.ok) throw new Error('Failed to load questions');
                        const data = await response.json();
                        if (!data || data.length === 0) throw new Error('No questions available');
                        this.questions = data;
                        this.loading = false;
                    } catch (error) {
                        console.error('Error:', error);
                        this.error = true;
                        this.errorMessage = error.message || 'An error occurred';
                        this.loading = false;
                    }
                },

                startGame() {
                    this.playing = true;
                    this.startTime = Date.now();
                    this.loadQuestion();
                    this.startTimer();
                },

                loadQuestion() {
                    if (this.currentQuestionIndex >= this.questions.length) {
                        this.endGame();
                        return;
                    }
                    this.currentQuestion = this.questions[this.currentQuestionIndex];
                },

                checkAnswer(answer) {
                    if (answer == this.currentQuestion.correct_answer) {
                        this.score += 10;
                        this.totalCorrect++;
                        this.showHint = false;
                        this.currentHint = '';
                        this.nextQuestion();
                    } else {
                        this.currentHint = this.currentQuestion.mnemonic ? this.currentQuestion.mnemonic.description : "Try using the order of operations (PEMDAS)!";
                        this.showHint = true;
                    }
                },

                nextQuestion() {
                    this.currentQuestionIndex++;
                    this.loadQuestion();
                },

                startTimer() {
                    const timerElement = document.getElementById('timer');
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    
                    this.timerInterval = setInterval(() => {
                        if (!this.startTime) return;
                        const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
                        const seconds = (elapsed % 60).toString().padStart(2, '0');
                        if (timerElement) timerElement.textContent = `${minutes}:${seconds}`;
                        const durationText = document.getElementById('durationText');
                        if (durationText) durationText.textContent = `${minutes}m ${seconds}s`;
                    }, 1000);
                },

                async endGame() {
                    this.playing = false;
                    this.gameOver = true;
                    clearInterval(this.timerInterval);
                    
                    const duration = Math.floor((Date.now() - this.startTime) / 1000);
                    const accuracy = this.questions.length > 0
                        ? Math.round((this.totalCorrect / this.questions.length) * 100)
                        : 0;

                    try {
                        // Validate duration
                        if (!Number.isFinite(duration) || duration < 0 || duration > 3600) {
                            console.error('Invalid duration:', duration);
                            return;
                        }

                        // Validate score
                        if (!Number.isFinite(this.score) || this.score < 0 || this.score > 100000) {
                            console.error('Invalid score:', this.score);
                            return;
                        }

                        // Validate game ID
                        if (!Number.isFinite({{ $game->id }})) {
                            console.error('Invalid game ID');
                            return;
                        }

                        // Validate questions array
                        if (!Array.isArray(this.questions) || this.questions.length === 0) {
                            console.error('Invalid questions array');
                            return;
                        }

                        // Validate totalCorrect
                        if (!Number.isFinite(this.totalCorrect) || this.totalCorrect < 0 || this.totalCorrect > this.questions.length) {
                            console.error('Invalid totalCorrect:', this.totalCorrect);
                            return;
                        }

                        // Validate accuracy
                        if (!Number.isFinite(accuracy) || accuracy < 0 || accuracy > 100) {
                            console.error('Invalid accuracy:', accuracy);
                            return;
                        }
                        
                        const url = this.isDailyChallenge 
                            ? "{{ route('student.daily-challenge.record') }}"
                            : "{{ route('student.games.session.store') }}";

                        const payload = {
                            score: this.score,
                            duration: duration,
                            details: {
                                game_type: 'pemdas_rush',
                                total_questions: this.questions.length,
                                correct_answers: this.totalCorrect,
                                accuracy: accuracy
                            }
                        };

                        if (this.isDailyChallenge) {
                            payload.submitted_answer = "Completed";
                            payload.is_correct = true;
                            payload.metadata = payload.details;
                        } else {
                            payload.game_id = {{ $game->id }};
                        }
                        
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });

                        if (!response.ok) {
                            console.error('Server error:', response.status, response.statusText);
                            return;
                        }

                        const data = await response.json();
                        if (!data || !data.success) {
                            console.error('Failed to save session:', data);
                            return;
                        }
                    } catch (error) {
                        console.error('Error saving session:', error);
                    }
                }
            }
        }
    </script>
</x-app-layout>
