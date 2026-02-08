<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-[#283B60] to-slate-900">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-500 pt-8 pb-16 px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-2 left-10 text-5xl">🎴</div>
                <div class="absolute top-20 right-20 text-4xl">⭐</div>
                <div class="absolute bottom-5 left-1/3 text-6xl">📊</div>
            </div>

            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">
                    {{ $game->name }}
                </h1>
                <p class="text-white/90 drop-shadow-lg">{{ $game->description ?? 'Sort integers and match equations.' }}</p>

                <!-- Live Stats -->
                <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-6">
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">⏱️ Time</div>
                        <div id="timer" class="text-2xl font-bold text-red-300">00:00</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">🎯 Score</div>
                        <div id="scoreDisplay" class="text-2xl font-bold text-yellow-300">0</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">📊 Round</div>
                        <div id="roundDisplay" class="text-2xl font-bold text-blue-300">1/5</div>
                    </div>
                    <div class="hidden md:block bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">✅ Accuracy</div>
                        <div id="accuracyDisplay" class="text-2xl font-bold text-green-300">0%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Game Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            <div class="bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-3xl p-8 border border-white/10 shadow-2xl">
                <div class="text-center" id="gameContainer">
                    <!-- Loading State -->
                    <div id="loadingState" style="display: none;" class="py-20">
                        <div class="inline-block">
                            <div class="text-5xl mb-4">⏳</div>
                            <p class="text-xl text-white">Loading Game...</p>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" style="display: none;" class="py-20">
                        <div class="inline-block text-center">
                            <div class="text-5xl mb-4">⚠️</div>
                            <p class="text-xl text-red-400 mb-6" id="errorMessage"></p>
                            <button onclick="location.reload()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                Try Again
                            </button>
                        </div>
                    </div>

                    <!-- Game Start Screen -->
                    <div id="startScreen" style="display: none;" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-7xl mb-6">🎴</div>
                            <h2 class="text-3xl font-bold text-white mb-4">Ready to Play?</h2>
                            <p class="text-white/70 mb-8">Sort integers in order or match equations to answers. Answer correctly to earn points!</p>
                            
                            <!-- Game Mode Selection -->
                            <div class="mb-8 flex flex-col md:flex-row justify-center gap-6">
                                <div class="text-center">
                                    <button onclick="game.setGameMode('numbers')" class="relative group">
                                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition transform hover:scale-105">
                                            🔢 Number Sorting
                                        </div>
                                        <p class="text-sm text-white/70 mt-2">Sort integers in order</p>
                                    </button>
                                </div>
                                <div class="text-center">
                                    <button onclick="game.setGameMode('equations')" class="relative group">
                                        <div class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition transform hover:scale-105">
                                            🧮 Equation Matching
                                        </div>
                                        <p class="text-sm text-white/70 mt-2">Match equations to answers</p>
                                    </button>
                                </div>
                            </div>

                            <!-- Difficulty Selector -->
                            <div id="difficultySection" style="display: none;" class="mb-8">
                                <p class="text-white font-semibold mb-4">Select Difficulty:</p>
                                <div class="flex justify-center gap-3 flex-wrap">
                                    <button onclick="game.startGame('easy')" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-2 px-6 rounded-full transition transform hover:scale-105 active:scale-95 touch-manipulation">
                                        ⭐ Easy
                                    </button>
                                    <button onclick="game.startGame('medium')" class="bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-2 px-6 rounded-full transition transform hover:scale-105 active:scale-95 touch-manipulation">
                                        ⭐⭐ Medium
                                    </button>
                                    <button onclick="game.startGame('hard')" class="bg-gradient-to-r from-red-500 to-[#1A3263] hover:from-red-600 hover:to-[#1A3263] text-white font-bold py-2 px-6 rounded-full transition transform hover:scale-105 active:scale-95 touch-manipulation">
                                        ⭐⭐⭐ Hard
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Game Playing State -->
                    <div id="playingState" style="display: none;" class="py-6">
                        <!-- Game Instructions -->
                        <div id="instructions" class="mb-6 p-4 bg-blue-500/20 border-l-4 border-blue-400 rounded">
                            <p class="text-sm text-blue-100 font-medium" id="instructionsText"></p>
                        </div>

                        <!-- Progress Indicator -->
                        <div class="mb-8 max-w-2xl mx-auto">
                            <div class="h-2 w-full bg-slate-700 rounded-full overflow-hidden">
                                <div id="progressBar" class="h-2 bg-gradient-to-r from-[#283B60] to-[#1A3263] rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <div class="mt-3 text-white/70 text-sm text-center">
                                <span id="correctCount">0</span> / <span id="totalCount">0</span> correct
                            </div>
                        </div>

                        <!-- Number Sorting Mode -->
                        <div id="numberSortingArea" style="display: none;">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Draggable Cards -->
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-4">🎴 Drag Cards</h3>
                                    <div id="dragCardsContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-3"></div>
                                </div>

                                <!-- Drop Zone -->
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-4">
                                        <span id="sortLabel">📈 Least to Greatest</span>
                                    </h3>
                                    <div id="dropZone" class="p-6 rounded-lg border-4 border-dashed min-h-96 transition-all duration-200 border-slate-500 bg-slate-800/30">
                                        <div id="dropZoneContainer" class="flex flex-col gap-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equation Matching Mode -->
                        <div id="equationMatchingArea" style="display: none;">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Draggable Equation Cards -->
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-4">🧮 Equations</h3>
                                    <div id="equationCardsContainer" class="space-y-3"></div>
                                </div>

                                <!-- Answer Drop Zones -->
                                <div>
                                    <h3 class="text-lg font-bold text-white mb-4">✅ Answers</h3>
                                    <div id="answerZoneContainer" class="space-y-3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex justify-center gap-4">
                            <button onclick="game.resetRound()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105">
                                🔄 Reset
                            </button>
                            <button id="nextRoundBtn" onclick="game.nextRound()" style="display: none;" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105">
                                ✓ Next Round
                            </button>
                        </div>
                    </div>

                    <!-- Game Over State -->
                    <div id="gameOverState" style="display: none;" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-6xl mb-6">🏆</div>
                            <h2 class="text-4xl font-bold text-white mb-2">Great Job!</h2>
                            <div class="bg-gradient-to-r from-[#283B60]/20 to-[#1A3263]/20 border border-[#283B60]/30 rounded-2xl p-8 mb-8">
                                <p class="text-white/70 text-sm mb-2">Final Score</p>
                                <p class="text-5xl font-bold text-yellow-300 mb-4" id="finalScore">0</p>
                                <p class="text-white/70">
                                    Time: <span class="font-semibold" id="finalTime">0m 0s</span><br>
                                    Accuracy: <span class="font-semibold text-green-300" id="finalAccuracy">0%</span>
                                </p>
                            </div>
                            <div class="flex gap-4 justify-center flex-wrap">
                                <button onclick="game.resetGame()" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition">
                                    🔄 Play Again
                                </button>
                                <a href="{{ route('student.games.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                                    🎮 More Games
                                </a>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const game = {
            // State
            gameMode: null,
            difficulty: 'medium',
            sortOrder: 'asc',
            roundNumber: 1,
            maxRounds: 5,
            score: 0,
            totalCorrect: 0,
            correctCards: 0,
            totalCards: 0,
            accuracy: 0,
            startTime: null,
            elapsedTime: 0,
            timerInterval: null,

            // Game data
            numbers: [],
            equations: [],
            answerSlots: [],
            draggedIndex: -1,
            draggedFromContainer: null,
            selectedCard: null, // For tap/click interaction

            // Equation database
            equationDatabase: [
                { eq: '2 + 3', ans: '5' },
                { eq: '-5 + 2', ans: '-3' },
                { eq: '8 - 3', ans: '5' },
                { eq: '-2 - 3', ans: '-5' },
                { eq: '4 × 3', ans: '12' },
                { eq: '-3 × 2', ans: '-6' },
                { eq: '10 ÷ 2', ans: '5' },
                { eq: '9 ÷ -3', ans: '-3' },
                { eq: '-8 + 8', ans: '0' },
                { eq: '5 + (-3)', ans: '2' },
                { eq: '15 - 8', ans: '7' },
                { eq: '6 × 5', ans: '30' },
                { eq: '20 ÷ 4', ans: '5' },
                { eq: '-10 + 5', ans: '-5' },
                { eq: '12 - (-3)', ans: '15' }
            ],

            // Initialize game
            init() {
                this.showState('start');
                document.getElementById('timer').textContent = '00:00';
                document.getElementById('scoreDisplay').textContent = '0';
                document.getElementById('roundDisplay').textContent = '1/5';
                document.getElementById('accuracyDisplay').textContent = '0%';
            },

            // Set game mode
            setGameMode(mode) {
                this.gameMode = mode;
                document.getElementById('difficultySection').style.display = 'block';
            },

            // Start game
            startGame(difficulty) {
                this.difficulty = difficulty;
                this.roundNumber = 1;
                this.score = 0;
                this.startTime = Date.now();
                this.showState('playing');

                if (this.gameMode === 'numbers') {
                    this.generateNumberGame();
                    this.renderNumberGame();
                } else {
                    this.generateEquationGame();
                    this.renderEquationGame();
                }

                this.startTimer();
                this.updateStats();
            },

            // Generate number sorting game
            generateNumberGame() {
                const count = this.difficulty === 'easy' ? 4 : this.difficulty === 'medium' ? 6 : 8;
                const range = this.difficulty === 'hard' ? 100 : 20;

                // Generate unique random numbers
                const numberSet = new Set();
                while (numberSet.size < count) {
                    const num = Math.floor(Math.random() * (range * 2 + 1)) - range;
                    numberSet.add(num);
                }

                this.numbers = Array.from(numberSet);
                this.sortOrder = Math.random() > 0.5 ? 'asc' : 'desc';
                this.totalCards = count;
            },

            // Render number sorting game
            renderNumberGame() {
                document.getElementById('numberSortingArea').style.display = 'block';
                document.getElementById('equationMatchingArea').style.display = 'none';
                document.getElementById('instructionsText').textContent = 
                    `📋 Drag or tap the number cards into the correct order (${this.sortOrder === 'asc' ? 'Least to Greatest' : 'Greatest to Least'})`;

                // Update sort label
                document.getElementById('sortLabel').textContent = 
                    this.sortOrder === 'asc' ? '📈 Least to Greatest' : '📉 Greatest to Least';

                // Render drag cards
                const dragContainer = document.getElementById('dragCardsContainer');
                dragContainer.innerHTML = '';

                const shuffled = [...this.numbers].sort(() => Math.random() - 0.5);
                shuffled.forEach((num, idx) => {
                    const card = document.createElement('div');
                    card.className = 'p-4 rounded-lg border-2 font-bold text-2xl cursor-move transition transform duration-200 shadow-md bg-blue-500/30 border-blue-400 text-blue-100 hover:shadow-lg hover:scale-105';
                    card.draggable = true;
                    card.textContent = num;
                    card.dataset.index = idx;
                    card.dataset.value = num;
                    card.dataset.container = 'drag';

                    card.addEventListener('dragstart', (e) => this.handleDragStart(e));
                    card.addEventListener('dragend', () => this.handleDragEnd());
                    card.addEventListener('click', (e) => this.handleCardClick(e, idx, 'drag'));

                    dragContainer.appendChild(card);
                });

                // Render drop zones
                const sorted = this.sortOrder === 'asc' 
                    ? [...this.numbers].sort((a, b) => a - b)
                    : [...this.numbers].sort((a, b) => b - a);

                const dropContainer = document.getElementById('dropZoneContainer');
                dropContainer.innerHTML = '';

                sorted.forEach((correctNum, idx) => {
                    const slot = document.createElement('div');
                    slot.className = 'p-4 rounded-lg border-2 font-bold text-2xl text-center transition transform duration-200 bg-slate-800/50 border-slate-600 text-slate-500 touch-manipulation';
                    slot.dataset.index = idx;
                    slot.dataset.correctValue = correctNum;
                    slot.dataset.container = 'drop';
                    slot.textContent = '?';

                    slot.addEventListener('dragover', (e) => e.preventDefault());
                    slot.addEventListener('drop', (e) => this.handleDropNumber(e));
                    slot.addEventListener('click', (e) => this.handleSlotClick(e, idx, 'drop'));

                    dropContainer.appendChild(slot);
                });

                document.getElementById('dropZone').addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.currentTarget.classList.add('border-green-400', 'bg-green-500/20');
                });
                document.getElementById('dropZone').addEventListener('dragleave', (e) => {
                    e.currentTarget.classList.remove('border-green-400', 'bg-green-500/20');
                });
            },

            // Generate equation matching game
            generateEquationGame() {
                const count = this.difficulty === 'easy' ? 4 : this.difficulty === 'medium' ? 6 : 8;

                // Select random equations
                this.equations = this.equationDatabase
                    .sort(() => Math.random() - 0.5)
                    .slice(0, count)
                    .map(e => ({ ...e }));

                this.answerSlots = [...this.equations].map(e => ({ answer: e.ans, filled: false }));
                this.totalCards = count;
            },

            // Render equation matching game
            renderEquationGame() {
                document.getElementById('numberSortingArea').style.display = 'none';
                document.getElementById('equationMatchingArea').style.display = 'block';
                document.getElementById('instructionsText').textContent = '🧮 Drag or tap each equation card to match its correct answer';

                // Render equation cards
                const eqContainer = document.getElementById('equationCardsContainer');
                eqContainer.innerHTML = '';

                this.equations.forEach((eq, idx) => {
                    const card = document.createElement('div');
                    card.className = 'p-4 rounded-lg border-2 font-bold text-lg cursor-move transition transform duration-200 shadow-md bg-[#283B60]/30 border-[#283B60] text-[#283B60] hover:shadow-lg hover:scale-105 touch-manipulation active:scale-95';
                    card.draggable = true;
                    card.textContent = eq.eq;
                    card.dataset.index = idx;
                    card.dataset.answer = eq.ans;
                    card.dataset.container = 'drag';

                    card.addEventListener('dragstart', (e) => this.handleDragStart(e));
                    card.addEventListener('dragend', () => this.handleDragEnd());
                    card.addEventListener('click', (e) => this.handleCardClick(e, idx, 'drag'));

                    eqContainer.appendChild(card);
                });

                // Render answer slots
                const ansContainer = document.getElementById('answerZoneContainer');
                ansContainer.innerHTML = '';

                this.answerSlots.forEach((slot, idx) => {
                    const zone = document.createElement('div');
                    zone.className = 'p-4 rounded-lg border-2 border-dashed min-h-20 flex items-center justify-center font-bold text-lg transition-all duration-200 border-slate-500 bg-slate-800/30 text-slate-500 touch-manipulation';
                    zone.dataset.index = idx;
                    zone.dataset.answer = slot.answer;
                    zone.dataset.container = 'drop';
                    zone.textContent = slot.answer;

                    zone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        zone.classList.add('border-green-400', 'bg-green-500/20');
                    });
                    zone.addEventListener('dragleave', () => {
                        zone.classList.remove('border-green-400', 'bg-green-500/20');
                    });
                    zone.addEventListener('drop', (e) => this.handleDropEquation(e));
                    zone.addEventListener('click', (e) => this.handleSlotClick(e, idx, 'drop'));

                    ansContainer.appendChild(zone);
                });
            },

            // Click handlers for mobile/fallback
            handleCardClick(e, index, container) {
                // If clicking the same card, deselect
                if (this.selectedCard && this.selectedCard.index === index && this.selectedCard.container === container) {
                    this.clearSelection();
                    return;
                }

                this.clearSelection();
                
                const card = e.currentTarget;
                if (card.classList.contains('cursor-not-allowed')) return;

                this.selectedCard = { index, container, element: card };
                
                // Visual feedback
                card.classList.add('ring-4', 'ring-yellow-400', 'scale-105');
                
                // Highlight potential drop zones
                const zones = document.querySelectorAll('[data-container="drop"]');
                zones.forEach(zone => {
                    if (!zone.classList.contains('filled') && (zone.textContent === '?' || this.gameMode === 'equations')) {
                         zone.classList.add('bg-yellow-500/10', 'border-yellow-400/50');
                    }
                });
            },

            handleSlotClick(e, index, container) {
                if (!this.selectedCard) return;
                
                if (this.gameMode === 'numbers') {
                    this.handleDropNumber(e, true);
                } else {
                    this.handleDropEquation(e, true);
                }
            },

            clearSelection() {
                if (this.selectedCard) {
                    this.selectedCard.element.classList.remove('ring-4', 'ring-yellow-400', 'scale-105');
                }
                this.selectedCard = null;
                
                // Remove drop zone highlights
                const zones = document.querySelectorAll('[data-container="drop"]');
                zones.forEach(zone => {
                    zone.classList.remove('bg-yellow-500/10', 'border-yellow-400/50');
                });
            },

            // Drag handlers
            handleDragStart(e) {
                this.draggedIndex = parseInt(e.target.dataset.index);
                this.draggedFromContainer = e.target.dataset.container;
                e.target.style.opacity = '0.5';
            },

            handleDragEnd() {
                const cards = document.querySelectorAll('[data-container="drag"]');
                cards.forEach(card => card.style.opacity = '1');
                this.draggedIndex = -1;
            },

            handleDropNumber(e, isClick = false) {
                if (!isClick) e.preventDefault();
                
                let sourceIndex = this.draggedIndex;
                let sourceContainer = this.draggedFromContainer;

                // If coming from click/tap
                if (isClick) {
                    if (!this.selectedCard) return;
                    sourceIndex = this.selectedCard.index;
                    sourceContainer = this.selectedCard.container;
                }

                if (sourceIndex === -1 || sourceContainer !== 'drag') return;

                const slot = e.currentTarget;
                const value = parseInt(e.currentTarget.dataset.correctValue);
                const draggedCard = document.querySelector(`[data-container="drag"][data-index="${sourceIndex}"]`);
                const draggedValue = parseInt(draggedCard.dataset.value);

                if (draggedValue === value && slot.textContent === '?') {
                    slot.textContent = draggedValue;
                    slot.classList.remove('bg-slate-800/50', 'border-slate-600', 'text-slate-500');
                    slot.classList.add('bg-green-500/30', 'border-green-400', 'text-green-100');
                    draggedCard.classList.add('opacity-50', 'cursor-not-allowed');
                    draggedCard.draggable = false;
                    
                    if (isClick) this.clearSelection();

                    this.correctCards++;
                    this.score += 10;
                    this.updateStats();

                    if (this.correctCards === this.totalCards) {
                        setTimeout(() => this.nextRound(), 500);
                    }
                } else {
                    console.log('Incorrect placement');
                    // Visual feedback for incorrect tap?
                    if (isClick) {
                         slot.classList.add('bg-red-500/20', 'border-red-400');
                         setTimeout(() => slot.classList.remove('bg-red-500/20', 'border-red-400'), 500);
                         this.clearSelection();
                    }
                }

                document.getElementById('dropZone').classList.remove('border-green-400', 'bg-green-500/20');
            },

            handleDropEquation(e, isClick = false) {
                if (!isClick) e.preventDefault();

                let sourceIndex = this.draggedIndex;
                let sourceContainer = this.draggedFromContainer;

                // If coming from click/tap
                if (isClick) {
                    if (!this.selectedCard) return;
                    sourceIndex = this.selectedCard.index;
                    sourceContainer = this.selectedCard.container;
                }

                if (sourceIndex === -1 || sourceContainer !== 'drag') return;

                const slot = e.currentTarget;
                const draggedCard = document.querySelector(`[data-container="drag"][data-index="${sourceIndex}"]`);
                const draggedAnswer = draggedCard.dataset.answer;
                const slotAnswer = slot.dataset.answer;

                if (draggedAnswer === slotAnswer && !slot.classList.contains('filled')) {
                    slot.classList.remove('border-slate-500', 'bg-slate-800/30', 'text-slate-500');
                    slot.classList.add('border-green-400', 'bg-green-500/30', 'text-green-100', 'filled');
                    slot.setAttribute('data-filled', 'true');
                    draggedCard.classList.add('opacity-50', 'cursor-not-allowed');
                    draggedCard.draggable = false;

                    if (isClick) this.clearSelection();

                    this.correctCards++;
                    this.score += 10;
                    this.updateStats();

                    if (this.correctCards === this.totalCards) {
                        setTimeout(() => this.nextRound(), 500);
                    }
                } else {
                     if (isClick) {
                         slot.classList.add('bg-red-500/20', 'border-red-400');
                         setTimeout(() => slot.classList.remove('bg-red-500/20', 'border-red-400'), 500);
                         this.clearSelection();
                    }
                }
            },

            // Reset round
            resetRound() {
                this.correctCards = 0;
                if (this.gameMode === 'numbers') {
                    this.renderNumberGame();
                } else {
                    this.renderEquationGame();
                }
            },

            // Next round
            nextRound() {
                this.correctCards = 0;
                this.roundNumber++;
                this.updateStats();

                if (this.roundNumber > this.maxRounds) {
                    this.endGame();
                    return;
                }

                if (this.gameMode === 'numbers') {
                    this.generateNumberGame();
                    this.renderNumberGame();
                } else {
                    this.generateEquationGame();
                    this.renderEquationGame();
                }
            },

            // Update stats
            updateStats() {
                document.getElementById('scoreDisplay').textContent = this.score;
                document.getElementById('roundDisplay').textContent = `${this.roundNumber}/5`;
                document.getElementById('correctCount').textContent = this.correctCards;
                document.getElementById('totalCount').textContent = this.totalCards;

                const progress = this.totalCards > 0 ? (this.correctCards / this.totalCards) * 100 : 0;
                document.getElementById('progressBar').style.width = Math.min(progress, 100) + '%';

                document.getElementById('nextRoundBtn').style.display = 
                    this.correctCards === this.totalCards ? 'block' : 'none';
    
                    // Update live accuracy display
                    const totalAttempted = (this.roundNumber - 1) * this.totalCards + this.correctCards;
                    const maxPossible = this.roundNumber * this.totalCards;
                    const liveAccuracy = totalAttempted > 0 ? Math.round((this.score / (totalAttempted * 10)) * 100) : 0;
                    document.getElementById('accuracyDisplay').textContent = liveAccuracy + '%';
            },

            // Timer
            startTimer() {
                if (this.timerInterval) clearInterval(this.timerInterval);
                this.timerInterval = setInterval(() => {
                    this.elapsedTime = Math.floor((Date.now() - this.startTime) / 1000);
                    const mins = Math.floor(this.elapsedTime / 60);
                    const secs = this.elapsedTime % 60;
                    document.getElementById('timer').textContent = 
                        `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }, 1000);
            },

            // End game
            endGame() {
                if (this.timerInterval) clearInterval(this.timerInterval);

                // Calculate accuracy
                const totalPossible = this.maxRounds * this.totalCards;
                this.accuracy = totalPossible > 0 ? Math.round((this.score / (totalPossible * 10)) * 100) : 0;

                document.getElementById('finalScore').textContent = this.score;
                document.getElementById('finalTime').textContent = 
                    `${Math.floor(this.elapsedTime / 60)}m ${this.elapsedTime % 60}s`;
                document.getElementById('finalAccuracy').textContent = this.accuracy + '%';
                document.getElementById('accuracyDisplay').textContent = this.accuracy + '%';

                this.showState('gameOver');
                this.saveSession();
            },

            // Reset game
            resetGame() {
                this.gameMode = null;
                this.roundNumber = 1;
                this.score = 0;
                this.correctCards = 0;
                this.accuracy = 0;
                this.elapsedTime = 0;
                document.getElementById('difficultySection').style.display = 'none';
                this.showState('start');
            },

            // Show state
            showState(state) {
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('errorState').style.display = 'none';
                document.getElementById('startScreen').style.display = 'none';
                document.getElementById('playingState').style.display = 'none';
                document.getElementById('gameOverState').style.display = 'none';

                if (state === 'loading') document.getElementById('loadingState').style.display = 'block';
                else if (state === 'error') document.getElementById('errorState').style.display = 'block';
                else if (state === 'start') document.getElementById('startScreen').style.display = 'block';
                else if (state === 'playing') document.getElementById('playingState').style.display = 'block';
                else if (state === 'gameOver') document.getElementById('gameOverState').style.display = 'block';
            },

            // Save session
            saveSession() {
                const url = this.isDailyChallenge 
                    ? "{{ route('student.daily-challenge.record') }}"
                    : "{{ route('student.games.session.store') }}";

                const payload = {
                    score: this.score,
                    duration: this.elapsedTime,
                    details: {
                        mode: this.gameMode,
                        difficulty: this.difficulty,
                        rounds_completed: this.roundNumber - 1,
                        accuracy: this.accuracy
                    }
                };

                if (this.isDailyChallenge) {
                    payload.submitted_answer = "Completed";
                    payload.is_correct = true;
                    payload.metadata = payload.details;
                } else {
                    payload.game_id = {{ $game->id }};
                }

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                }).catch(err => console.error('Error saving session:', err));
            }
        };

        // Initialize game on load
        game.init();
    </script>
    @endpush
</x-app-layout>
