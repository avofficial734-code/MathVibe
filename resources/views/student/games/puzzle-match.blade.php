<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-[#283B60] to-slate-900" x-data="puzzleMatchGame()" x-init="initGame()" x-cloak>
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-blue-600 via-[#283B60] to-[#1A3263] pt-8 pb-16 px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-2 left-10 text-5xl">🧩</div>
                <div class="absolute top-20 right-20 text-4xl">✨</div>
                <div class="absolute bottom-5 left-1/3 text-6xl">🎯</div>
            </div>

            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">
                    {{ $game->name }}
                </h1>
                <p class="text-white/90 drop-shadow-lg">{{ $game->description ?? 'Match problems with their answers to reveal the hidden picture!' }}</p>

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
                        <div class="text-sm text-white/80">Matches</div>
                        <div class="text-2xl font-bold text-blue-300" x-text="matchesFound + '/16'">0/16</div>
                    </div>
                    <div class="hidden md:block bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Accuracy</div>
                        <div class="text-2xl font-bold text-green-300" x-text="accuracy + '%'">0%</div>
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
                            <button @click="window.location.reload()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                Try Again
                            </button>
                        </div>
                    </div>

                    <!-- Game Start Screen -->
                    <div x-show="!loading && !error && !playing && !gameOver" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-7xl mb-6">🧩</div>
                            <h2 class="text-3xl font-bold text-white mb-4">Ready to Match?</h2>
                            <p class="text-white/70 mb-8">Match math problems with their answers to reveal the hidden picture! Complete all 16 matches to win!</p>
                            <button @click="startGame" class="bg-gradient-to-r from-blue-500 to-[#283B60] hover:from-blue-600 hover:to-[#283B60] text-white font-bold py-4 px-8 rounded-xl text-lg shadow-lg transition transform hover:scale-105 active:scale-95 touch-manipulation">
                                ▶️ Start Game
                            </button>
                        </div>
                    </div>

                    <!-- Game Playing State -->
                    <div x-show="playing" class="py-12">
                        <!-- Progress Bar -->
                        <div class="mb-8 max-w-2xl mx-auto">
                            <div class="h-2 w-full bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-2 bg-gradient-to-r from-blue-500 to-[#283B60] rounded-full transition-all" :style="`width: ${Math.min(Math.floor((matchesFound / totalPairs) * 100),100)}%`"></div>
                            </div>
                            <div class="mt-3 text-white/70 text-sm">
                                <span x-text="matchesFound"></span> of <span x-text="totalPairs"></span> matches found
                            </div>
                        </div>

                        <!-- Game Instructions -->
                        <div class="mb-6 p-4 bg-blue-500/20 border border-blue-400/30 rounded-lg max-w-2xl mx-auto">
                            <p class="text-sm text-blue-100">💡 Select a problem card, then select its matching answer card. Each correct match reveals part of the hidden picture!</p>
                        </div>

                        <!-- Game Board -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                            <!-- Problem Cards Section -->
                            <div class="lg:col-span-1">
                                <h3 class="text-lg font-bold text-white mb-4">Problems</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="(card, idx) in problemCards" :key="idx">
                                        <button @click="selectCard('problem', idx)"
                                            :class="[
                                                'p-4 rounded-lg border-2 transition transform duration-200 font-semibold text-sm',
                                                card.matched ? 'bg-green-500/30 border-green-400/50 text-green-200 opacity-50 cursor-not-allowed' : 'bg-blue-500/30 hover:bg-blue-500/50 border-blue-400/50 hover:border-blue-400/80 text-blue-100 hover:scale-105 active:scale-95 touch-manipulation cursor-pointer',
                                                selectedCard.type === 'problem' && selectedCard.index === idx ? 'ring-4 ring-yellow-400' : ''
                                            ]"
                                            :disabled="card.matched">
                                            <span x-text="card.content"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Puzzle Image Section -->
                            <div class="lg:col-span-1">
                                <h3 class="text-lg font-bold text-white mb-4">Hidden Picture</h3>
                                <div class="bg-gradient-to-br from-[#283B60]/20 to-blue-500/20 backdrop-blur-md rounded-lg p-4 border-2 border-[#283B60]/30">
                                    <div class="grid grid-cols-4 gap-2">
                                        <template x-for="(tile, idx) in puzzleTiles" :key="idx">
                                            <div :class="[
                                                'aspect-square rounded-md border border-[#283B60]/50 flex items-center justify-center font-bold text-2xl transition-all duration-300',
                                                tile.revealed ? 'bg-gradient-to-br from-yellow-300 to-yellow-400 shadow-lg' : 'bg-gradient-to-br from-slate-700 to-slate-800'
                                            ]">
                                                <span x-show="tile.revealed" x-text="tile.emoji"></span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mt-4 text-center text-sm text-white/70">
                                        <span x-text="matchesFound"></span> / <span x-text="totalPairs"></span> revealed
                                    </div>
                                </div>
                            </div>

                            <!-- Answer Cards Section -->
                            <div class="lg:col-span-1">
                                <h3 class="text-lg font-bold text-white mb-4">Answers</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="(card, idx) in answerCards" :key="idx">
                                        <button @click="selectCard('answer', idx)"
                                            :class="[
                                                'p-4 rounded-lg border-2 transition transform duration-200 font-semibold text-sm touch-manipulation active:scale-95',
                                                card.matched ? 'bg-green-500/30 border-green-400/50 text-green-200 opacity-50 cursor-not-allowed' : 'bg-green-500/30 hover:bg-green-500/50 border-green-400/50 hover:border-green-400/80 text-green-100 hover:scale-105 cursor-pointer',
                                                selectedCard.type === 'answer' && selectedCard.index === idx ? 'ring-4 ring-yellow-400' : ''
                                            ]"
                                            :disabled="card.matched">
                                            <span x-text="card.content"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback Message -->
                        <div x-show="feedback.show" :class="[
                            'mt-6 p-4 rounded-lg font-semibold text-center transition-all duration-300 max-w-2xl mx-auto',
                            feedback.correct ? 'bg-green-500/30 border border-green-400/30 text-green-200' : 'bg-red-500/30 border border-red-400/30 text-red-200'
                        ]">
                            <span x-text="feedback.message"></span>
                        </div>

                        <!-- Score Display -->
                        <div class="mt-8 text-center">
                            <p class="text-white/70 text-sm">Current Score</p>
                            <p class="text-4xl font-bold text-yellow-300" x-text="score"></p>
                        </div>
                    </div>

                    <!-- Game Over State -->
                    <div x-show="gameOver" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-6xl mb-6">🏆</div>
                            <h2 class="text-4xl font-bold text-white mb-2">Congratulations!</h2>
                            <p class="text-white/70 mb-6">You've revealed the complete picture!</p>
                            
                            <!-- Final Puzzle Image -->
                            <div class="bg-gradient-to-br from-[#283B60]/20 to-blue-500/20 border border-[#283B60]/30 rounded-2xl p-6 mb-8">
                                <div class="grid grid-cols-4 gap-2 mb-4">
                                    <template x-for="(tile, idx) in puzzleTiles" :key="idx">
                                        <div class="aspect-square rounded-md bg-gradient-to-br from-yellow-300 to-yellow-400 shadow-lg flex items-center justify-center font-bold text-3xl">
                                            <span x-text="tile.emoji"></span>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-center text-white font-semibold text-lg">Perfect Match!</p>
                            </div>

                            <!-- Stats -->
                            <div class="bg-gradient-to-r from-[#283B60]/20 to-[#1A3263]/20 border border-[#283B60]/30 rounded-2xl p-8 mb-8">
                                <p class="text-white/70 text-sm mb-2">Final Score</p>
                                <p class="text-5xl font-bold text-yellow-300 mb-4" x-text="score"></p>
                                <p class="text-white/70">
                                    <span x-text="totalPairs"></span> matches completed in <span id="durationText" class="font-bold">0m 0s</span>
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-4 justify-center flex-wrap">
                                <button @click="initGame" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    🔄 Play Again
                                </button>
                                <a href="{{ route('student.games.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    🎮 More Games
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function puzzleMatchGame() {
            return {
                loading: true,
                error: false,
                errorMessage: '',
                playing: false,
                gameOver: false,
                startTime: null,
                timerInterval: null,
                accuracy: 0,
                
                // Game data
                problemCards: [],
                answerCards: [],
                puzzleTiles: [],
                totalPairs: 16,
                matchesFound: 0,
                score: 0,
                totalAttempts: 0,
                correctAttempts: 0,
                
                // Selection state
                selectedCard: {
                    type: null,
                    index: null
                },
                
                // Feedback
                feedback: {
                    show: false,
                    correct: false,
                    message: ''
                },

                // Sample math problems and answers (8 pairs to match, creating 16 cards for 4x4 grid)
                mathPairs: [
                    { problem: '2 + 3', answer: '5' },
                    { problem: '10 - 4', answer: '6' },
                    { problem: '3 × 4', answer: '12' },
                    { problem: '15 ÷ 3', answer: '5' },
                    { problem: '7 + 8', answer: '15' },
                    { problem: '20 - 5', answer: '15' },
                    { problem: '6 × 2', answer: '12' },
                    { problem: '24 ÷ 4', answer: '6' },
                    { problem: '5 + 9', answer: '14' },
                    { problem: '18 - 9', answer: '9' },
                    { problem: '8 × 2', answer: '16' },
                    { problem: '32 ÷ 4', answer: '8' },
                    { problem: '-5 + 2', answer: '-3' },
                    { problem: '-10 - 5', answer: '-15' },
                    { problem: '2² + 3', answer: '7' },
                    { problem: '4 × 5 ÷ 2', answer: '10' }
                ],

                puzzleEmojis: ['😊', '🎓', '🌟', '📚', '🎯', '💡', '🚀', '⭐', '🎨', '🏆', '🎪', '🎭', '🎬', '🎸', '🎺', '🎻'],

                async initGame() {
                    this.loading = true;
                    this.error = false;
                    this.gameOver = false;
                    this.playing = false;
                    this.matchesFound = 0;
                    this.score = 0;
                    this.totalAttempts = 0;
                    this.correctAttempts = 0;
                    this.accuracy = 0;
                    // Reset timing state
                    this.startTime = null;
                    if (this.timerInterval) {
                        clearInterval(this.timerInterval);
                        this.timerInterval = null;
                    }
                    // Reset timer display
                    const timerEl = document.getElementById('timer');
                    if (timerEl) timerEl.textContent = '00:00';

                    this.selectedCard = { type: null, index: null };
                    this.feedback = { show: false, correct: false, message: '' };

                    // Setup puzzle tiles
                    this.puzzleTiles = this.puzzleEmojis.map((emoji, idx) => ({
                        emoji: emoji,
                        revealed: false,
                        index: idx
                    }));

                    // Setup problem and answer cards
                    const shuffledPairs = [...this.mathPairs].sort(() => 0.5 - Math.random());
                    
                    this.problemCards = shuffledPairs.map((pair, idx) => ({
                        id: idx,
                        content: pair.problem,
                        answer: pair.answer,
                        matched: false
                    }));

                    this.answerCards = shuffledPairs.map((pair, idx) => ({
                        id: idx,
                        content: pair.answer,
                        problem: pair.problem,
                        matched: false
                    })).sort(() => 0.5 - Math.random());

                    this.loading = false;
                },

                startGame() {
                    this.playing = true;
                    // Start timing from now (reset if restarting)
                    this.startTime = Date.now();
                    this.startTimer();
                },

                selectCard(type, index) {
                    const cards = type === 'problem' ? this.problemCards : this.answerCards;
                    
                    if (cards[index].matched) return;

                    // If no card selected, select this one
                    if (this.selectedCard.type === null) {
                        this.selectedCard = { type, index };
                        return;
                    }

                    // If same card type selected, just switch selection
                    if (this.selectedCard.type === type) {
                        this.selectedCard = { type, index };
                        return;
                    }

                    // Both cards selected, check for match
                    this.checkMatch(type, index);
                },

                checkMatch(answerType, answerIndex) {
                    const problemCard = this.problemCards[this.selectedCard.index];
                    const answerCard = this.answerCards[answerIndex];
                    
                    // Track attempt
                    this.totalAttempts++;

                    // Strict validation: answer must match exactly
                    if (problemCard.answer === answerCard.content && 
                        answerCard.problem === problemCard.content) {
                        // Correct match!
                        problemCard.matched = true;
                        answerCard.matched = true;
                        this.matchesFound++;
                        this.correctAttempts++;
                        this.score = this.matchesFound * 10; // 10 points per match

                        // Reveal puzzle tile
                        this.puzzleTiles[this.matchesFound - 1].revealed = true;

                        this.showFeedback(true, '✨ Perfect Match! +10 pts');

                        // Check for game over
                        if (this.matchesFound === this.totalPairs) {
                            setTimeout(() => this.endGame(), 1500);
                        }
                    } else {
                        // Incorrect match
                        this.showFeedback(false, '❌ Try Again!');
                    }

                    // Recalculate and push live stats to header
                    this.updateStats();

                    this.selectedCard = { type: null, index: null };
                },

                showFeedback(correct, message) {
                    this.feedback = {
                        show: true,
                        correct: correct,
                        message: message
                    };

                    setTimeout(() => {
                        this.feedback.show = false;
                    }, 1500);
                },

                // Centralized live stats updater
                updateStats() {
                    // Recalculate accuracy and ensure reactive fields are current
                    this.accuracy = this.totalAttempts > 0
                        ? Math.round((this.correctAttempts / this.totalAttempts) * 100)
                        : 0;

                    // Ensure score and matches are consistent (redundant safe-guard)
                    this.score = this.matchesFound * 10;

                    // Trigger Alpine reactivity (no-op assignment pattern when needed)
                    this.matchesFound = this.matchesFound;
                    this.totalPairs = this.totalPairs;
                },

                startTimer() {
                    const timerElement = document.getElementById('timer');
                    if (this.timerInterval) {
                        clearInterval(this.timerInterval);
                    }
                    // Use startTime to calculate elapsed (prevents drift)
                    this.timerInterval = setInterval(() => {
                        if (!this.startTime) return;
                        const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
                        const seconds = (elapsed % 60).toString().padStart(2, '0');
                        if (timerElement) timerElement.textContent = `${minutes}:${seconds}`;
                    }, 1000);
                },

                endGame() {
                    this.playing = false;
                    this.gameOver = true;
                    clearInterval(this.timerInterval);

                    const durationText = document.getElementById('durationText');
                    const timerElement = document.getElementById('timer');
                    if (durationText && timerElement) {
                        durationText.textContent = timerElement.textContent;
                    }

                    // Save session
                    const duration = Math.floor((Date.now() - this.startTime) / 1000);
                    this.saveSession(duration);
                },

                async saveSession(duration) {
                    try {
                        // Validate data before saving
                        if (duration < 0 || this.score < 0 || this.matchesFound < 0) {
                            console.error('Invalid session data');
                            return;
                        }
                        
                        const accuracy = this.totalAttempts > 0 
                            ? Math.round((this.correctAttempts / this.totalAttempts) * 100)
                            : 0;
                        
                        const url = this.isDailyChallenge 
                            ? "{{ route('student.daily-challenge.record') }}"
                            : "{{ route('student.games.session.store') }}";

                        const payload = {
                            score: this.score,
                            duration: duration,
                            details: {
                                matches_found: this.matchesFound,
                                total_matches: this.totalPairs,
                                total_attempts: this.totalAttempts,
                                correct_attempts: this.correctAttempts,
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

                        await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });
                    } catch (error) {
                        console.error('Error saving session:', error);
                    }
                }
            }
        }
    </script>
</x-app-layout>
