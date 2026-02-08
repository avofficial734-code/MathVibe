<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $game->name }}
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-sm text-gray-500">
                    Time <span id="timer" class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold">00:00</span>
                </div>
                <div class="text-sm text-gray-500">
                    Score <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-semibold" x-data="{s:0}" x-text="s" x-init="$watch(() => $root.__x.$data.score, v => s = v)"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="gameLogic()" x-init="initGame()" x-cloak>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 text-center">
                    <div x-show="loading" class="py-10">
                        <p class="text-xl text-gray-500">Loading Game...</p>
                    </div>

                    <div x-show="error" class="py-10 text-red-500">
                        <p class="text-xl" x-text="errorMessage"></p>
                        <button @click="window.location.reload()" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retry
                        </button>
                    </div>

                    <div x-show="!loading && !error && !playing && !gameOver" class="py-10">
                        <p class="mb-6 text-gray-600">{{ $game->description }}</p>
                        <button @click="startGame" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 px-8 rounded-full text-xl shadow-lg transition transform hover:scale-105 active:scale-95 touch-manipulation">
                            Start Game
                        </button>
                    </div>

                    <div x-show="playing" class="py-6">
                        <div class="mb-6 max-w-2xl mx-auto">
                            <div class="h-2 w-full bg-gray-200 rounded">
                                <div class="h-2 bg-indigo-600 rounded" :style="`width: ${Math.min(Math.floor((currentQuestionIndex / Math.max(questions.length,1)) * 100),100)}%`"></div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <span x-text="currentQuestionIndex + 1"></span> /
                                <span x-text="questions.length"></span> questions
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="text-sm text-gray-500 mb-2">Score: <span class="font-bold text-blue-600 text-xl" x-text="score"></span></div>
                            <div class="text-4xl font-bold text-gray-800 mb-8" x-text="currentQuestion.content"></div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                                <template x-for="(choice, index) in currentQuestion.choices" :key="index">
                                    <button @click="checkAnswer(choice)" 
                                        class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-semibold py-4 px-6 rounded-xl border border-indigo-100 shadow-sm transition"
                                        x-text="choice">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div x-show="gameOver" class="py-10">
                        <div class="max-w-md mx-auto bg-white rounded-2xl shadow p-6">
                            <h3 class="text-3xl font-bold text-gray-800 mb-2">Game Over</h3>
                            <p class="text-xl text-gray-600">Your Score: <span class="font-bold text-indigo-600" x-text="score"></span></p>
                            <div class="mt-2 text-sm text-gray-500">
                                <span x-text="questions.length"></span> questions • <span id="durationText"></span>
                            </div>
                        </div>
                        
                        <div class="flex justify-center space-x-4 mt-6">
                            <button @click="initGame" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Play Again
                            </button>
                            <a href="{{ route('student.games.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Back to Games
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            
            <div x-show="showHint" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
                <div class="bg-white rounded-lg p-8 max-w-md w-full m-4">
                    <h4 class="text-xl font-bold text-red-600 mb-2">Incorrect</h4>
                    <div class="bg-yellow-50 p-4 rounded border border-yellow-200 mb-6">
                        <p class="italic text-gray-700" x-text="currentHint"></p>
                    </div>
                    <button @click="showHint = false; nextQuestion()" class="w-full bg-blue-600 text-white font-bold py-2 rounded">
                        Got it, Next Question
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function gameLogic() {
            return {
                loading: true,
                error: false,
                errorMessage: '',
                playing: false,
                gameOver: false,
                questions: [],
                remainingQuestions: [],
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
                        if (!data || data.length === 0) throw new Error('No questions available for this game');
                        this.questions = data;
                        this.remainingQuestions = [...data];
                        this.loading = false;
                    } catch (error) {
                        console.error('Error fetching questions:', error);
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
                        this.currentHint = this.currentQuestion.mnemonic ? this.currentQuestion.mnemonic.description : "Remember the rules for this operation!";
                        this.showHint = true;
                    }
                },

                nextQuestion() {
                    this.currentQuestionIndex++;
                    this.loadQuestion();
                },

                startTimer() {
                    const timerElement = document.getElementById('timer');
                    if (this.timerInterval) {
                        clearInterval(this.timerInterval);
                    }
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
                        if (duration < 0 || this.score < 0) {
                            console.error('Invalid session data');
                            return;
                        }
                        
                        await fetch("{{ route('student.games.session.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                game_id: {{ $game->id }},
                                score: this.score,
                                duration: duration,
                                details: {
                                    game_type: 'daily_challenge',
                                    total_questions: this.questions.length,
                                    correct_answers: this.totalCorrect,
                                    accuracy: accuracy
                                }
                            })
                        });
                    } catch (error) {
                        console.error('Error saving session:', error);
                    }
                }
            }
        }
    </script>
</x-app-layout>
