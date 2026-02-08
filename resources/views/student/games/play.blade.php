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
                    Score <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-semibold" x-data="{s:0}" x-text="s" x-init=\"$watch(() => $root.__x.$data.score, v => s = v)\"></span>
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
                        <template x-if="isSpinWheel && (!currentQuestion || !currentQuestion.content)">
                            <div class="mb-6 flex flex-col items-center gap-4">
                                <div class="text-sm text-gray-600">Spin the wheel to choose an operation</div>
                                <div class="relative inline-block" style="position: relative;">
                                    <!-- Fixed pointer at top -->
                                    <div class="wheel-pointer-container" style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); z-index: 40;">
                                        <div class="wheel-pointer" :class="{'pointer-bounce': pointerBounce, 'pointer-flash': pointerFlash}"></div>
                                    </div>
                                    <!-- Spinning wheel -->
                                    <div class="relative" role="region" aria-label="Spin the wheel selector" :style="`transform: rotate(${rotateDeg}deg); transition-duration: ${spinDuration}ms;`" :class="{'transition-transform ease-out': true, 'spinning': spinning}">
                                        <div class="spinwheel" style="background: conic-gradient(from -90deg, #3b82f6 0deg 72deg, #8b5cf6 72deg 144deg, #10b981 144deg 216deg, #f97316 216deg 288deg, #ef4444 288deg 360deg)"></div>
                                        <div class="spinwheel-ticks"></div>
                                        <div class="spinwheel-outer"></div>
                                        <div class="spinwheel-ring"></div>
                                        <div class="spinwheel-cap"></div>
                                        <!-- Labels positioned dynamically -->
                                        <template x-for="(cat, i) in categories" :key="i">
                                            <span class="spinwheel-label label-spot"
                                                  :class="[(selectedIndex === i) ? 'selected-glow' : '', cat.bgClass]"
                                                  :style="labelStyle(i)">
                                                  <span x-text="cat.label"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                <button @click="spinWheel" :disabled="spinning || cooldown || remainingQuestions.length === 0" class="spin-trigger active:scale-95 touch-manipulation" aria-label="Spin the wheel to select an operation">
                                    <span x-show="!spinning">🎯 Spin</span>
                                    <span x-show="spinning" aria-live="polite">🎡 Spinning...</span>
                                </button>
                                <div class="text-xs text-gray-400 mt-1 hidden md:block">Press Space or Enter to spin</div>
                                <div class="text-xs text-gray-400 mt-1 md:hidden">Tap button to spin</div>
                                <div x-show="uiError" class="text-sm text-red-600" x-text="uiErrorMessage" aria-live="polite"></div>
                                <div class="sr-only" aria-live="polite" x-text="selectedCategory ? `Selected: ${selectedCategory}` : ''"></div>
                                <div class="mt-2 text-xs text-gray-500">Recent spins</div>
                                <div class="flex gap-2 flex-wrap justify-center">
                                    <template x-for="item in spinHistory" :key="item">
                                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 text-xs" x-text="item"></span>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="isSpinWheel && selectedCategory">
                            <div class="mb-4 text-center">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700">
                                    <span class="text-xs font-semibold" x-text="selectedCategory"></span>
                                    <span class="text-[10px] text-gray-500">Ready</span>
                                </div>
                            </div>
                        </template>
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
                // Game state
                isDailyChallenge: {{ $isDailyChallenge ? 'true' : 'false' }},
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
                isSpinWheel: {{ $game->slug === 'spin-wheel' ? 'true' : 'false' }},
                selectedCategory: '',
                selectedIndex: -1,
                spinning: false,
                cooldown: false,
                pointerBounce: false,
                pointerFlash: false,
                rotateDeg: 0,
                spinDuration: 1800,
                categories: [
                    { label: 'Addition', topics: ['Integer Addition'], bgClass: 'label-add' },
                    { label: 'Subtraction', topics: ['Integer Subtraction'], bgClass: 'label-sub' },
                    { label: 'Multiplication', topics: ['Integer Multiplication'], bgClass: 'label-mul' },
                    { label: 'Division', topics: ['Integer Division'], bgClass: 'label-div' },
                    { label: 'PEMDAS', topics: ['Order of Operations', 'Mixed Integer Operations'], bgClass: 'label-pem' },
                ],
                labelRadius: '8rem',
                spinHistory: [],
                uiError: false,
                uiErrorMessage: '',
                lastSelectedIndex: -1,
                handleKeyPress(event) {
                    if (this.isSpinWheel && !this.spinning && !this.cooldown && this.remainingQuestions.length > 0) {
                        if (event.code === 'Space' || event.code === 'Enter') {
                            event.preventDefault();
                            this.spinWheel();
                        }
                    }
                },

                labelStyle(i) {
                    const seg = this.categories.length;
                    const anglePer = 360 / seg;
                    const angle = (anglePer * i) + (anglePer / 2) - 90; // Center of each segment, -90 to align with gradient start
                    const radius = '6.5rem'; // Distance from center to place labels
                    return `transform: translate(-50%, -50%) rotate(${angle}deg) translate(${radius}) rotate(${-angle}deg)`;
                },

                async initGame() {
                    this.loading = true;
                    this.error = false;
                    this.gameOver = false;
                    this.score = 0;
                    this.totalCorrect = 0;
                    this.currentQuestionIndex = 0;
                    
                    // Fetch questions from API
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
                    if (this.isSpinWheel) {
                        this.spinning = false;
                        this.cooldown = false;
                        this.pointerBounce = false;
                        this.rotateDeg = 0;
                        this.spinHistory = [];
                        this.selectedCategory = '';
                        this.selectedIndex = -1;
                        this.lastSelectedIndex = -1;
                        this.uiError = false;
                        this.uiErrorMessage = '';
                        this.currentQuestion = {};
                        // Add keyboard support
                        document.addEventListener('keydown', this.handleKeyPress.bind(this));
                    } else {
                        this.loadQuestion();
                    }
                    this.startTimer();
                },

                loadQuestion() {
                    if (this.currentQuestionIndex >= this.questions.length) {
                        this.endGame();
                        return;
                    }
                    this.currentQuestion = this.questions[this.currentQuestionIndex];
                },
                
                // Helper function to determine which category the pointer is pointing at
                getCategoryAtPointer(angle) {
                    const seg = this.categories.length;
                    const anglePer = 360 / seg;
                    // Normalize angle to 0-360 range
                    const normalizedAngle = ((angle % 360) + 360) % 360;
                    // Pointer is at top (0 degrees), each segment is 72 degrees
                    // Adjust for the fact that segments are centered on their angles
                    const categoryIndex = Math.floor(normalizedAngle / anglePer) % seg;
                    return categoryIndex;
                },

                // Validate that selected question matches the category pointed by the pointer
                validateSelection(selectedIdx, questionData) {
                    const selectedCategory = this.categories[selectedIdx];
                    const isValidTopic = selectedCategory.topics.includes(questionData.topic);
                    
                    if (!isValidTopic) {
                        console.warn(`Validation failed: Question topic "${questionData.topic}" not in category "${selectedCategory.label}"`);
                        return false;
                    }
                    return true;
                },

                spinWheel() {
                    if (this.spinning || this.cooldown) return;
                    if (this.remainingQuestions.length === 0) {
                        this.uiError = true;
                        this.uiErrorMessage = 'No questions remaining.';
                        this.endGame();
                        return;
                    }
                    this.uiError = false;
                    this.uiErrorMessage = '';
                    this.spinning = true;
                    
                    // Calculate available categories
                    const seg = this.categories.length;
                    const anglePer = 360 / seg;
                    const availability = this.categories.map((cat, i) => ({
                        i,
                        available: this.remainingQuestions.some(q => cat.topics.includes(q.topic))
                    }));
                    const availableIdxs = availability.filter(a => a.available).map(a => a.i);
                    let candidateIdxs = availableIdxs.length > 0 ? availableIdxs : Array.from({length: seg}, (_, i) => i);
                    
                    // Avoid repeating the last selected category if possible
                    if (candidateIdxs.length > 1 && this.lastSelectedIndex !== -1) {
                        candidateIdxs = candidateIdxs.filter(i => i !== this.lastSelectedIndex);
                    }
                    
                    // Randomly select a category
                    const selectedIdx = candidateIdxs[Math.floor(Math.random() * candidateIdxs.length)];
                    
                    // Calculate spin animation
                    const turns = 3 + Math.floor(Math.random() * 4); // 3-6 full turns
                    const targetAngle = (turns * 360) + (selectedIdx * anglePer);
                    
                    // Smooth spin duration based on turns
                    this.spinDuration = 2000 + (turns * 200) + Math.floor(Math.random() * 500);
                    
                    // Animate the spin
                    this.rotateDeg = targetAngle;
                    
                    // Handle spin completion
                    setTimeout(() => {
                        // Validate which category the pointer is actually pointing at
                        const pointerCategoryIdx = this.getCategoryAtPointer(this.rotateDeg);
                        const cat = this.categories[pointerCategoryIdx];
                        
                        // Verify the pointer category matches our selection
                        if (pointerCategoryIdx !== selectedIdx) {
                            console.warn(`Pointer mismatch: Expected ${selectedIdx}, pointer at ${pointerCategoryIdx}. Using pointer position.`);
                        }
                        
                        this.selectedCategory = cat.label;
                        this.selectedIndex = pointerCategoryIdx;
                        this.lastSelectedIndex = pointerCategoryIdx;
                        
                        // Find a question for the category the pointer is pointing at
                        const pool = this.remainingQuestions.filter(q => cat.topics.includes(q.topic));
                        let picked = null;
                        
                        if (pool.length > 0) {
                            const pIdx = Math.floor(Math.random() * pool.length);
                            picked = pool[pIdx];
                            
                            // Validate the picked question matches the category
                            if (!this.validateSelection(pointerCategoryIdx, picked)) {
                                this.uiError = true;
                                this.uiErrorMessage = 'Validation error: Question does not match category. Retrying...';
                                // Find another question that definitely matches
                                const validQuestions = pool.filter(q => this.validateSelection(pointerCategoryIdx, q));
                                if (validQuestions.length > 0) {
                                    picked = validQuestions[Math.floor(Math.random() * validQuestions.length)];
                                    this.uiError = false;
                                    this.uiErrorMessage = '';
                                }
                            }
                        } else {
                            // No questions for pointed category, fallback
                            this.uiError = true;
                            this.uiErrorMessage = 'No questions for that category. Selecting from available...';
                            const anyIdx = Math.floor(Math.random() * this.remainingQuestions.length);
                            picked = this.remainingQuestions[anyIdx];
                        }
                        
                        if (picked) {
                            this.currentQuestion = picked;
                            const rmIdx = this.remainingQuestions.findIndex(q => q.id === picked.id);
                            if (rmIdx >= 0) this.remainingQuestions.splice(rmIdx, 1);
                            
                            // Add to spin history
                            this.spinHistory.unshift(this.selectedCategory);
                            this.spinHistory = this.spinHistory.slice(0, 5);
                        } else {
                            this.endGame();
                            return;
                        }
                        
                        this.spinning = false;
                        
                        // Success animations
                        this.pointerBounce = true;
                        setTimeout(() => { this.pointerBounce = false; }, 400);
                        
                        this.pointerFlash = true;
                        setTimeout(() => { this.pointerFlash = false; }, 600);
                        
                        // Brief cooldown to prevent accidental double-spins
                        this.cooldown = true;
                        setTimeout(() => { this.cooldown = false; }, 800);
                        
                    }, this.spinDuration);
                },

                checkAnswer(answer) {
                    if (answer == this.currentQuestion.correct_answer) {
                        this.score += 10;
                        this.totalCorrect++;
                        this.showHint = false;
                        this.currentHint = '';
                        this.nextQuestion();
                    } else {
                        // Show hint if wrong
                        this.currentHint = this.currentQuestion.mnemonic ? this.currentQuestion.mnemonic.description : "Remember the rules for this operation!";
                        this.showHint = true;
                    }
                },

                nextQuestion() {
                    this.currentQuestionIndex++;
                    if (this.isSpinWheel) {
                        this.currentQuestion = {};
                        this.selectedCategory = '';
                        this.selectedIndex = -1;
                        if (this.currentQuestionIndex >= this.questions.length) {
                            this.endGame();
                        }
                    } else {
                        this.loadQuestion();
                    }
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
                    
                    // Clean up event listeners
                    if (this.isSpinWheel) {
                        document.removeEventListener('keydown', this.handleKeyPress.bind(this));
                    }
                    
                    const duration = Math.floor((Date.now() - this.startTime) / 1000);
                    
                    // Calculate accuracy
                    const accuracy = this.questions.length > 0
                        ? Math.round((this.totalCorrect / this.questions.length) * 100)
                        : 0;

                    // Save session with validation
                    try {
                        if (duration < 0 || this.score < 0) {
                            console.error('Invalid session data');
                            return;
                        }
                        
                        const url = this.isDailyChallenge 
                            ? "{{ route('student.daily-challenge.record') }}"
                            : "{{ route('student.games.session.store') }}";

                        const payload = {
                            score: this.score,
                            duration: duration,
                            details: {
                                game_type: this.isSpinWheel ? 'spin_wheel' : 'standard',
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
