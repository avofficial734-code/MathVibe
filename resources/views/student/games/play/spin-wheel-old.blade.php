<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-[#283B60] to-slate-900">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-500 pt-8 pb-16 px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-2 left-10 text-5xl">🎡</div>
                <div class="absolute top-20 right-20 text-4xl">⭐</div>
                <div class="absolute bottom-5 left-1/3 text-6xl">🎯</div>
            </div>

            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">
                    {{ $game->name }}
                </h1>
                <p class="text-white/90 drop-shadow-lg">{{ $game->description ?? 'Spin the wheel to answer questions and earn points!' }}</p>

                <!-- Live Stats -->
                <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-6">
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Time</div>
                        <div id="timer" class="text-2xl font-bold text-red-300">00:00</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Score</div>
                        <div id="scoreDisplay" class="text-2xl font-bold text-yellow-300">0</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Question</div>
                        <div id="questionCount" class="text-2xl font-bold text-blue-300">1/10</div>
                    </div>
                    <div class="hidden md:block bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Category</div>
                        <div id="categoryDisplay" class="text-2xl font-bold text-green-300">--</div>
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
                            <button onclick="location.reload()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                Try Again
                            </button>
                        </div>
                    </div>

                    <!-- Start Screen -->
                    <div id="startScreen" style="display: none;" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-7xl mb-6">🎡</div>
                            <h2 class="text-3xl font-bold text-white mb-4">Ready to Spin?</h2>
                            <p class="text-white/70 mb-8">Spin the wheel to choose your category, then answer questions to earn points!</p>
                            <button id="startGameBtn" onclick="game.startGame()" class="bg-gradient-to-r from-[#283B60] to-[#1A3263] hover:from-[#283B60] hover:to-[#1A3263] text-white font-bold py-4 px-8 rounded-xl text-lg shadow-lg transition transform hover:scale-105 active:scale-95 touch-manipulation">
                                ▶️ Start Game
                            </button>
                        </div>
                    </div>

                    <!-- Playing State -->
                    <div id="playingState" style="display: none;" class="py-12">
                        <!-- Progress Bar -->
                        <div class="mb-8 max-w-2xl mx-auto">
                            <div class="h-2 w-full bg-slate-700 rounded-full overflow-hidden">
                                <div id="progressBar" class="h-2 bg-gradient-to-r from-[#283B60] to-[#1A3263] rounded-full transition-all" style="width: 0%"></div>
                            </div>
                            <div class="mt-3 text-white/70 text-sm">
                                <span id="questionProgress">1</span> of <span id="totalQuestions">10</span> questions
                            </div>
                        </div>

                        <!-- Spin Wheel Section -->
                        <div id="wheelSection" class="mb-8 flex flex-col items-center gap-4">
                            <p class="text-white/70 text-sm">Spin the wheel to choose an operation</p>
                            <div class="relative inline-block">
                                <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); z-index: 40;">
                                    <div id="wheelPointer" style="width: 0; height: 0; border-left: 12px solid transparent; border-right: 12px solid transparent; border-top: 20px solid #f97316; margin: 0 auto;"></div>
                                </div>
                                <div id="wheel" class="relative" style="width: 300px; height: 300px; position: relative; transform-origin: center center;">
                                    <svg style="width: 100%; height: 100%; display: block;" id="wheelSvg" viewBox="0 0 300 300">
                                        <circle cx="150" cy="150" r="140" fill="none" stroke="#white" stroke-width="2" opacity="0.3"/>
                                    </svg>
                                </div>
                            </div>
                            <button id="spinBtn" onclick="game.spinWheel()" class="bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-slate-900 font-bold py-3 px-8 rounded-xl text-lg shadow-lg transition transform hover:scale-105 active:scale-95 touch-manipulation">
                                🎯 Spin
                            </button>
                            <div class="text-xs text-white/60 mt-1">Press Space or Enter to spin</div>
                            <div id="wheelError" class="text-sm text-red-400" style="display: none;"></div>
                            <div class="mt-4 text-xs text-white/70">Recent categories</div>
                            <div id="spinHistory" class="flex gap-2 flex-wrap justify-center"></div>
                        </div>

                        <!-- Question Card & Answer Section -->
                        <div id="questionSection" style="display: none;">
                            <!-- Question Card -->
                            <div class="bg-gradient-to-br from-[#283B60]/20 to-blue-500/20 backdrop-blur-md rounded-2xl p-8 border border-[#283B60]/30 mb-8 max-w-2xl mx-auto">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-white/80 mb-4">
                                    <span class="text-xs font-semibold" id="categoryBadge"></span>
                                </div>
                                <div class="text-white/70 text-sm mb-3">Question <span id="questionNum">1</span></div>
                                <div id="questionText" class="text-3xl md:text-6xl font-bold text-white mb-8"></div>
                                <div class="text-sm text-white/60">Select the correct answer below</div>
                            </div>

                            <!-- Answer Options -->
                            <div id="answersContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto mb-6"></div>
                        </div>
                    </div>

                    <!-- Game Over Screen -->
                    <div id="gameOverScreen" style="display: none;" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-6xl mb-6">🏆</div>
                            <h2 class="text-4xl font-bold text-white mb-2">Game Over!</h2>
                            <div class="bg-gradient-to-r from-[#283B60]/20 to-[#1A3263]/20 border border-[#283B60]/30 rounded-2xl p-8 mb-8">
                                <p class="text-white/70 text-sm mb-2">Final Score</p>
                                <p class="text-5xl font-bold text-yellow-300 mb-4" id="finalScore">0</p>
                                <p class="text-white/70">
                                    <span id="finalQuestions">10</span> questions completed in <span id="durationText" class="font-bold">0m 0s</span>
                                </p>
                            </div>
                            <div class="flex gap-4 justify-center flex-wrap">
                                <button onclick="game.initGame()" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    🔄 Play Again
                                </button>
                                <a href="{{ route('student.games.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    🎮 More Games
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hint Modal -->
                <div id="hintModal" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 px-4">
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 max-w-md w-full border border-white/10">
                        <div class="text-5xl mb-4 text-center">💡</div>
                        <h4 class="text-2xl font-bold text-yellow-400 mb-4 text-center">Not Quite!</h4>
                        <div class="bg-blue-500/20 border border-blue-400/30 p-4 rounded-lg mb-6">
                            <p id="hintText" class="italic text-white/80"></p>
                        </div>
                        <button onclick="game.closeHint()" class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold py-3 rounded-lg transition active:scale-95 touch-manipulation">
                            Got it, Next Question
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CATEGORIES = [
            { label: 'Addition', topics: ['Integer Addition'], color: '#3b82f6' },
            { label: 'Subtraction', topics: ['Integer Subtraction'], color: '#8b5cf6' },
            { label: 'Multiplication', topics: ['Integer Multiplication'], color: '#10b981' },
            { label: 'Division', topics: ['Integer Division'], color: '#f97316' },
            { label: 'PEMDAS', topics: ['Order of Operations', 'Mixed Integer Operations'], color: '#ef4444' }
        ];

        const game = {
            // State
            questions: [],
            remainingQuestions: [],
            currentQuestion: null,
            selectedCategory: '',
            currentQuestionIndex: 0,
            score: 0,
            totalCorrect: 0,
            startTime: null,
            timerInterval: null,
            spinning: false,
            spinHistory: [],
            gameState: 'loading', // loading, start, playing, gameOver
            wheelRotation: 0,

            // Initialize game
            async initGame() {
                this.showState('loading');
                try {
                    const response = await fetch("{{ route('student.games.questions', $game->id) }}");
                    if (!response.ok) throw new Error('Failed to load questions');
                    const data = await response.json();
                    
                    if (!Array.isArray(data) || data.length === 0) {
                        throw new Error('No questions available');
                    }

                    this.questions = data;
                    this.remainingQuestions = [...data];
                    this.gameState = 'start';
                    this.showState('start');
                } catch (error) {
                    console.error('Error loading game:', error);
                    document.getElementById('errorMessage').textContent = error.message;
                    this.showState('error');
                }
            },

            // Start playing
            startGame() {
                this.gameState = 'playing';
                this.currentQuestionIndex = 0;
                this.score = 0;
                this.totalCorrect = 0;
                this.remainingQuestions = [...this.questions];
                this.spinHistory = [];
                this.startTime = Date.now();
                this.showState('playing');
                this.startTimer();
                this.updateStats(); // Update stats immediately when game starts
                this.renderWheel();
                document.addEventListener('keydown', (e) => this.handleKeyPress(e));
            },

            // Render the spin wheel
            renderWheel() {
                const wheelContainer = document.getElementById('wheel');
                const oldSvg = document.getElementById('wheelSvg');
                
                // Create a fresh SVG element
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('id', 'wheelSvg');
                svg.setAttribute('viewBox', '0 0 300 300');
                svg.setAttribute('style', 'width: 100%; height: 100%; display: block;');
                
                const centerX = 150, centerY = 150, radius = 130;

                // Draw segments
                CATEGORIES.forEach((cat, i) => {
                    const totalSegments = CATEGORIES.length;
                    const angle = (360 / totalSegments);
                    const startAngle = (angle * i - 90) * (Math.PI / 180);
                    const endAngle = (angle * (i + 1) - 90) * (Math.PI / 180);

                    const x1 = centerX + radius * Math.cos(startAngle);
                    const y1 = centerY + radius * Math.sin(startAngle);
                    const x2 = centerX + radius * Math.cos(endAngle);
                    const y2 = centerY + radius * Math.sin(endAngle);

                    const largeArc = angle > 180 ? 1 : 0;
                    const path = `M ${centerX} ${centerY} L ${x1} ${y1} A ${radius} ${radius} 0 ${largeArc} 1 ${x2} ${y2} Z`;

                    const pathEl = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    pathEl.setAttribute('d', path);
                    pathEl.setAttribute('fill', cat.color);
                    pathEl.setAttribute('stroke', '#ffffff');
                    pathEl.setAttribute('stroke-width', '2');
                    svg.appendChild(pathEl);

                    // Add text labels
                    const labelAngle = ((angle * i + angle / 2) - 90) * (Math.PI / 180);
                    const textRadius = 100;
                    const textX = centerX + textRadius * Math.cos(labelAngle);
                    const textY = centerY + textRadius * Math.sin(labelAngle);

                    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    text.setAttribute('x', textX);
                    text.setAttribute('y', textY);
                    text.setAttribute('text-anchor', 'middle');
                    text.setAttribute('dominant-baseline', 'middle');
                    text.setAttribute('fill', '#ffffff');
                    text.setAttribute('font-weight', 'bold');
                    text.setAttribute('font-size', '14');
                    text.textContent = cat.label;
                    svg.appendChild(text);
                });

                // Add center circle
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('cx', centerX);
                circle.setAttribute('cy', centerY);
                circle.setAttribute('r', '20');
                circle.setAttribute('fill', '#ffffff');
                svg.appendChild(circle);
                
                // Replace the old SVG with the new one
                if (oldSvg) {
                    wheelContainer.replaceChild(svg, oldSvg);
                } else {
                    wheelContainer.appendChild(svg);
                }
            },

            // Spin the wheel
            spinWheel() {
                if (this.spinning || this.remainingQuestions.length === 0) return;

                this.spinning = true;
                document.getElementById('spinBtn').disabled = true;

                // Get available categories
                const availableCategories = CATEGORIES.filter(cat =>
                    this.remainingQuestions.some(q => cat.topics.includes(q.topic))
                );

                // If no categories have questions, end the game
                if (availableCategories.length === 0) {
                    this.spinning = false;
                    document.getElementById('spinBtn').disabled = false;
                    this.endGame();
                    return;
                }

                // Select random category from available ones
                const selectedCat = availableCategories[Math.floor(Math.random() * availableCategories.length)];

                const selectedIndex = CATEGORIES.indexOf(selectedCat);
                const segmentAngle = 360 / CATEGORIES.length;
                const targetAngle = (selectedIndex * segmentAngle) + (segmentAngle / 2);
                const totalRotation = 360 * 5 + (360 - targetAngle);

                // Animate wheel
                const wheel = document.getElementById('wheel');
                wheel.style.transition = 'transform 3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                wheel.style.transform = `rotate(${totalRotation}deg)`;

                setTimeout(() => {
                    this.wheelRotation = totalRotation;
                    this.selectedCategory = selectedCat.label;
                    this.selectQuestion(selectedCat);
                    this.spinning = false;
                    document.getElementById('spinBtn').disabled = false;
                    wheel.style.transition = 'none';
                }, 3000);
            },

            // Select question for category
            selectQuestion(category) {
                const categoryQuestions = this.remainingQuestions.filter(q =>
                    category.topics.includes(q.topic)
                );

                if (categoryQuestions.length === 0) {
                    this.showError('No questions available for this category');
                    return;
                }

                this.currentQuestion = categoryQuestions[Math.floor(Math.random() * categoryQuestions.length)];

                // Validate question structure
                if (!this.validateQuestion(this.currentQuestion)) {
                    this.showError('Invalid question format');
                    return;
                }

                // Remove from remaining
                const idx = this.remainingQuestions.findIndex(q => q.id === this.currentQuestion.id);
                if (idx >= 0) this.remainingQuestions.splice(idx, 1);

                // Update UI
                this.updateStats();
                this.showQuestion();
                this.addToHistory(category.label);
            },

            // Validate question has required fields
            validateQuestion(question) {
                if (!question.id || !question.content) return false;
                
                // Check for answer choices - can be 'choices' or 'options'
                const hasChoices = (question.choices && Array.isArray(question.choices) && question.choices.length > 0) ||
                                  (question.options && Array.isArray(question.options) && question.options.length > 0);
                
                if (!hasChoices) {
                    console.warn('Question has no choices:', question);
                    return false;
                }

                // Must have correct answer
                if (!question.correct_answer) return false;

                return true;
            },

            // Show question in UI
            showQuestion() {
                document.getElementById('wheelSection').style.display = 'none';
                document.getElementById('questionSection').style.display = 'block';
                document.getElementById('categoryBadge').textContent = this.selectedCategory;
                document.getElementById('questionNum').textContent = this.currentQuestionIndex + 1;
                document.getElementById('questionText').textContent = this.currentQuestion.content;

                // Get answer choices (handle both 'choices' and 'options')
                const choices = this.currentQuestion.choices || this.currentQuestion.options || [];
                
                const container = document.getElementById('answersContainer');
                container.innerHTML = '';

                choices.forEach((choice, index) => {
                    const btn = document.createElement('button');
                    btn.className = 'bg-gradient-to-r from-blue-500/30 to-cyan-500/30 hover:from-blue-500/50 hover:to-cyan-500/50 border border-blue-400/50 hover:border-blue-400/80 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95 touch-manipulation';
                    btn.type = 'button';
                    btn.textContent = choice;
                    btn.onclick = () => this.checkAnswer(choice);
                    container.appendChild(btn);
                });
            },

            // Check answer
            checkAnswer(answer) {
                if (answer == this.currentQuestion.correct_answer) {
                    this.score += 10;
                    this.totalCorrect++;
                    this.updateStats(); // Update stats immediately after correct answer
                    this.nextQuestion();
                } else {
                    this.showHint();
                }
            },

            // Show hint modal
            showHint() {
                const hint = this.currentQuestion.mnemonic?.description || "Try again!";
                document.getElementById('hintText').textContent = hint;
                document.getElementById('hintModal').style.display = 'flex';
            },

            // Close hint and continue
            closeHint() {
                document.getElementById('hintModal').style.display = 'none';
                this.nextQuestion();
            },

            // Next question
            nextQuestion() {
                this.currentQuestionIndex++;
                document.getElementById('categoryBadge').textContent = '';
                this.updateStats();

                if (this.currentQuestionIndex >= this.questions.length || this.remainingQuestions.length === 0) {
                    this.endGame();
                } else {
                    document.getElementById('questionSection').style.display = 'none';
                    document.getElementById('wheelSection').style.display = 'block';
                    this.currentQuestion = null;
                    this.selectedCategory = '';
                    
                    // Reset wheel rotation without recreating the SVG
                    const wheel = document.getElementById('wheel');
                    wheel.style.transition = 'none';
                    wheel.style.transform = 'rotate(0deg)';
                    this.wheelRotation = 0;
                }
            },

            // Update stats display
            updateStats() {
                document.getElementById('scoreDisplay').textContent = this.score;
                document.getElementById('questionCount').textContent = (this.currentQuestionIndex + 1) + '/' + this.questions.length;
                document.getElementById('categoryDisplay').textContent = this.selectedCategory || '--';
                document.getElementById('questionProgress').textContent = this.currentQuestionIndex + 1;
                document.getElementById('totalQuestions').textContent = this.questions.length;

                const progress = (this.currentQuestionIndex / this.questions.length) * 100;
                document.getElementById('progressBar').style.width = Math.min(progress, 100) + '%';
            },

            // Add to history
            addToHistory(category) {
                this.spinHistory.unshift(category);
                this.spinHistory = this.spinHistory.slice(0, 5);
                const historyDiv = document.getElementById('spinHistory');
                historyDiv.innerHTML = this.spinHistory.map(cat =>
                    `<span class="px-3 py-1 rounded-full bg-white/10 text-white/80 text-xs border border-white/20">${cat}</span>`
                ).join('');
            },

            // End game
            async endGame() {
                this.gameState = 'gameOver';
                clearInterval(this.timerInterval);
                document.removeEventListener('keydown', (e) => this.handleKeyPress(e));

                const duration = Math.floor((Date.now() - this.startTime) / 1000);
                const accuracy = this.questions.length > 0
                    ? Math.round((this.totalCorrect / this.questions.length) * 100)
                    : 0;

                document.getElementById('finalScore').textContent = this.score;
                document.getElementById('finalQuestions').textContent = this.questions.length;
                this.showState('gameOver');

                // Save session
                try {
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
                                game_type: 'spin_wheel',
                                total_questions: this.questions.length,
                                correct_answers: this.totalCorrect,
                                accuracy: accuracy
                            }
                        })
                    });
                } catch (error) {
                    console.error('Error saving session:', error);
                }
            },

            // Timer
            startTimer() {
                this.timerInterval = setInterval(() => {
                    if (!this.startTime) return;
                    const elapsed = Math.floor((Date.now() - this.startTime) / 1000);
                    const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
                    const seconds = (elapsed % 60).toString().padStart(2, '0');
                    document.getElementById('timer').textContent = `${minutes}:${seconds}`;
                    document.getElementById('durationText').textContent = `${minutes}m ${seconds}s`;
                }, 1000);
            },

            // Show state
            showState(state) {
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('errorState').style.display = 'none';
                document.getElementById('startScreen').style.display = 'none';
                document.getElementById('playingState').style.display = 'none';
                document.getElementById('gameOverScreen').style.display = 'none';

                switch(state) {
                    case 'loading':
                        document.getElementById('loadingState').style.display = 'block';
                        break;
                    case 'error':
                        document.getElementById('errorState').style.display = 'block';
                        break;
                    case 'start':
                        document.getElementById('startScreen').style.display = 'block';
                        break;
                    case 'playing':
                        document.getElementById('playingState').style.display = 'block';
                        break;
                    case 'gameOver':
                        document.getElementById('gameOverScreen').style.display = 'block';
                        break;
                }
            },

            // Show error
            showError(message) {
                document.getElementById('wheelError').textContent = message;
                document.getElementById('wheelError').style.display = 'block';
            },

            // Handle keyboard
            handleKeyPress(e) {
                if ((e.code === 'Space' || e.code === 'Enter') && !this.spinning) {
                    e.preventDefault();
                    this.spinWheel();
                }
            }
        };

        // Initialize on load
        game.initGame();
    </script>
</x-app-layout>
