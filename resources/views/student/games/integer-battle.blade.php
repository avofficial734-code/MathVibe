<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-[#283B60] to-slate-900" x-data="integerBattleGame()" x-init="initGame()" x-cloak>
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-500 pt-8 pb-16 px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-2 left-10 text-5xl">⚔️</div>
                <div class="absolute top-20 right-20 text-4xl">👹</div>
                <div class="absolute bottom-5 left-1/3 text-6xl">💪</div>
            </div>

            <div class="max-w-7xl mx-auto relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">
                    {{ $game->name }}
                </h1>
                <p class="text-white/90 drop-shadow-lg">{{ $game->description ?? 'Battle monsters by solving integer problems!' }}</p>

                <!-- Live Stats -->
                <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-6">
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Time</div>
                        <div class="text-2xl font-bold text-red-300" x-text="formattedTime">00:00</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Score</div>
                        <div class="text-2xl font-bold text-yellow-300" x-text="score">0</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Wave</div>
                        <div class="text-2xl font-bold text-blue-300" x-text="waveNumber">1</div>
                    </div>
                    <div class="hidden md:block bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/20">
                        <div class="text-sm text-white/80">Combo</div>
                        <div class="text-2xl font-bold text-green-300" x-text="comboCount">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Game Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
            <div class="bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-3xl p-8 border border-white/10 shadow-2xl">
                <div class="text-center">

                    <!-- Start Screen -->
                    <div x-show="!playing && !gameOver" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <div class="text-7xl mb-6">⚔️</div>
                            <h2 class="text-3xl font-bold text-white mb-4">Ready to Battle?</h2>
                            <p class="text-white/70 mb-8">Solve integer problems to defeat monsters. Choose your difficulty and prove your skills!</p>
                            
                            <!-- Difficulty Selection -->
                            <div class="space-y-3">
                                <button @click="difficulty = 'easy'; startGame()" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    ⭐ Easy - Beginner
                                </button>
                                <button @click="difficulty = 'medium'; startGame()" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    ⭐⭐ Medium - Warrior
                                </button>
                                <button @click="difficulty = 'hard'; startGame()" class="w-full bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
                                    ⭐⭐⭐ Hard - Champion
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Battle Screen -->
                    <div x-show="playing" class="py-12">
                        <!-- Progress Bar -->
                        <div class="mb-8 max-w-2xl mx-auto">
                            <div class="h-4 w-full bg-slate-700/50 rounded-full overflow-hidden border border-white/10">
                                <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full transition-all duration-500 shadow-[0_0_10px_rgba(34,211,238,0.5)]" :style="`width: ${Math.min(Math.floor((waveNumber / Math.max(maxWaves, 1)) * 100), 100)}%`"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 px-1">
                                <div class="text-cyan-300 font-bold text-sm">
                                    Wave <span x-text="waveNumber"></span> of <span x-text="maxWaves"></span>
                                </div>
                                <div class="text-white/50 text-xs uppercase tracking-wider font-bold">Progress</div>
                            </div>
                        </div>

                        <!-- Problem Card -->
                        <div class="bg-gradient-to-br from-[#283B60]/20 to-blue-500/20 backdrop-blur-md rounded-2xl p-8 border border-[#283B60]/30 mb-8 max-w-2xl mx-auto">
                            <div class="text-white/70 text-sm mb-3">Monster <span x-text="waveNumber"></span></div>
                            <div class="text-5xl md:text-6xl font-bold text-white mb-4 text-center" x-text="currentMonster.emoji"></div>
                            <div class="text-3xl md:text-6xl font-bold text-yellow-300 font-mono mb-6 text-center" x-text="currentProblem.expression"></div>
                            <div class="text-sm text-white/60 text-center mb-6">Solve the problem to attack!</div>

                            <!-- Answer Input & Button -->
                            <div class="flex gap-2 flex-col sm:flex-row">
                                <input 
                                    type="text" 
                                    inputmode="decimal"
                                    pattern="[0-9.-]*"
                                    x-model="userAnswer" 
                                    @keyup.enter="submitAnswer()"
                                    placeholder="?"
                                    :disabled="battleAnimating"
                                    class="flex-1 bg-white/10 backdrop-blur-md text-white px-4 py-3 rounded-lg border border-white/30 focus:border-[#283B60] focus:outline-none placeholder-white/50 text-center text-lg font-bold"
                                />
                                <button @click="submitAnswer()" :disabled="battleAnimating" class="bg-gradient-to-r from-[#283B60] to-[#1A3263] hover:from-[#283B60] hover:to-[#1A3263] disabled:opacity-50 text-white px-8 py-3 rounded-lg font-bold transition transform hover:scale-105 active:scale-95 whitespace-nowrap touch-manipulation">
                                    ⚡ Attack!
                                </button>
                            </div>

                            <!-- Feedback Message -->
                            <div x-show="feedbackMessage" :class="lastCorrect ? 'text-green-400' : 'text-red-400'" class="text-center font-semibold mt-4 text-lg" x-text="feedbackMessage"></div>
                        </div>

                        <!-- Battle Status -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
                            <div class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 backdrop-blur-md rounded-xl p-4 border border-blue-400/30">
                                <div class="text-sm text-white/70 mb-2">Your Health</div>
                                <div class="w-full bg-slate-600 rounded-full h-2 overflow-hidden border border-blue-400/50 mb-2">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 transition-all duration-300" :style="`width: ${(playerHealth / maxPlayerHealth) * 100}%`"></div>
                                </div>
                                <p class="text-sm text-blue-300 font-bold" x-text="playerHealth + '/' + maxPlayerHealth"></p>
                            </div>

                            <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 backdrop-blur-md rounded-xl p-4 border border-red-400/30">
                                <div class="text-sm text-white/70 mb-2">Enemy Health</div>
                                <div class="w-full bg-slate-600 rounded-full h-2 overflow-hidden border border-red-400/50 mb-2">
                                    <div class="h-full bg-gradient-to-r from-red-500 to-orange-400 transition-all duration-300" :style="`width: ${(monsterHealth / currentMonster.maxHealth) * 100}%`"></div>
                                </div>
                                <p class="text-sm text-red-300 font-bold" x-text="monsterHealth + '/' + currentMonster.maxHealth"></p>
                            </div>

                            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 backdrop-blur-md rounded-xl p-4 border border-green-400/30">
                                <div class="text-sm text-white/70 mb-2">Combo</div>
                                <div class="text-3xl font-bold text-green-300" x-text="comboCount"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Game Over Screen -->
                    <div x-show="gameOver" class="py-20">
                        <div class="inline-block text-center max-w-md">
                            <template x-if="playerWon">
                                <div>
                                    <div class="text-6xl mb-6">🏆</div>
                                    <h2 class="text-4xl font-bold text-white mb-2">Victory!</h2>
                                    <div class="bg-gradient-to-r from-[#283B60]/20 to-[#1A3263]/20 border border-[#283B60]/30 rounded-2xl p-8 mb-8">
                                        <p class="text-white/70 text-sm mb-2">Final Score</p>
                                        <p class="text-5xl font-bold text-yellow-300 mb-4" x-text="score"></p>
                                        <p class="text-white/70">
                                            <span x-text="monstersDefeated"></span> monsters defeated with <span x-text="totalCorrect"></span> correct answers
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!playerWon">
                                <div>
                                    <div class="text-6xl mb-6">⚔️</div>
                                    <h2 class="text-4xl font-bold text-white mb-2">Defeated!</h2>
                                    <div class="bg-gradient-to-r from-[#283B60]/20 to-[#1A3263]/20 border border-[#283B60]/30 rounded-2xl p-8 mb-8">
                                        <p class="text-white/70 text-sm mb-2">Final Score</p>
                                        <p class="text-5xl font-bold text-yellow-300 mb-4" x-text="score"></p>
                                        <p class="text-white/70">
                                            Survived <span x-text="waveNumber"></span> waves with <span x-text="totalCorrect"></span> correct answers
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <div class="flex gap-4 justify-center flex-wrap">
                                <button @click="resetGame()" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-bold py-3 px-6 rounded-lg transition active:scale-95 touch-manipulation">
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

    @push('scripts')
    <script>
        function integerBattleGame() {
            return {
                // Game state
                isDailyChallenge: {{ $isDailyChallenge ? 'true' : 'false' }},
                playing: false,
                gameOver: false,
                playerWon: false,
                
                // Game configuration
                difficulty: 'medium',
                
                // Game progress
                score: 0,
                waveNumber: 1,
                maxWaves: 0,
                formattedTime: '00:00',
                timerInterval: null,
                monstersDefeated: 0,
                totalCorrect: 0,
                totalAttempts: 0,
                accuracy: 0,
                comboCount: 0,
                
                // Health system
                playerHealth: 100,
                maxPlayerHealth: 100,
                monsterHealth: 0,
                
                // Battle data
                currentProblem: { expression: '', answer: 0 },
                currentMonster: { name: 'Goblin', emoji: '👹', maxHealth: 50 },
                userAnswer: '',
                feedbackMessage: '',
                lastCorrect: false,
                battleAnimating: false,
                monsterTakingDamage: false,
                showPlayerDamage: false,
                showMonsterDamage: false,
                lastPlayerDamage: 0,
                lastMonsterDamage: 0,
                
                // Game ID
                gameId: {{ $game->id }},
                
                // Monster data
                monsters: [
                    { name: 'Goblin', emoji: '👹', baseHealth: 50 },
                    { name: 'Orc', emoji: '🧟', baseHealth: 75 },
                    { name: 'Troll', emoji: '👺', baseHealth: 100 },
                    { name: 'Dragon', emoji: '🐉', baseHealth: 150 },
                    { name: 'Demon', emoji: '😈', baseHealth: 200 },
                ],
                
                // Initialize game
                initGame() {
                    this.loadMonster(1);
                },
                
                // Start game
                startGame() {
                    this.playing = true;
                    this.gameOver = false;
                    this.playerWon = false;
                    this.score = 0;
                    this.waveNumber = 1;
                    this.monstersDefeated = 0;
                    this.totalCorrect = 0;
                    this.totalAttempts = 0;
                    this.comboCount = 0;
                    this.setPlayerHealth(100);
                    // Set max waves at start (3-5 waves, or fixed for daily challenge)
                    if (this.isDailyChallenge) {
                        this.maxWaves = 5; // Standardize for daily challenge
                    } else {
                        this.maxWaves = Math.floor(Math.random() * 3) + 3;
                    }
                    this.loadMonster(1);
                    this.generateProblem();
                    // Start timing
                    this.startTimer();
                },
                
                startTimer() {
                    this.startTime = Date.now();
                    this.formattedTime = '00:00';
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    
                    this.timerInterval = setInterval(() => {
                        const seconds = Math.floor((Date.now() - this.startTime) / 1000);
                        const mins = Math.floor(seconds / 60);
                        const secs = seconds % 60;
                        this.formattedTime = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    }, 1000);
                },

                stopTimer() {
                    if (this.timerInterval) {
                        clearInterval(this.timerInterval);
                        this.timerInterval = null;
                    }
                },
                
                // Load monster for wave
                loadMonster(wave) {
                    const monsterIndex = (wave - 1) % this.monsters.length;
                    this.currentMonster = { ...this.monsters[monsterIndex] };
                    
                    // Scale health by difficulty and wave
                    let healthMultiplier = 1;
                    if (this.difficulty === 'easy') healthMultiplier = 0.8;
                    if (this.difficulty === 'hard') healthMultiplier = 1.5;
                    
                    const waveMultiplier = 1 + (wave - 1) * 0.3;
                    this.currentMonster.maxHealth = Math.floor(this.currentMonster.baseHealth * healthMultiplier * waveMultiplier);
                    this.monsterHealth = this.currentMonster.maxHealth;
                },
                
                // Generate math problem
                generateProblem() {
                    const isAddition = Math.random() > 0.5;
                    let a, b;
                    
                    if (this.difficulty === 'easy') {
                        a = Math.floor(Math.random() * 20) - 10;
                        b = Math.floor(Math.random() * 20) - 10;
                    } else if (this.difficulty === 'medium') {
                        a = Math.floor(Math.random() * 50) - 25;
                        b = Math.floor(Math.random() * 50) - 25;
                    } else {
                        a = Math.floor(Math.random() * 100) - 50;
                        b = Math.floor(Math.random() * 100) - 50;
                    }
                    
                    if (isAddition) {
                        this.currentProblem = {
                            expression: `${a} + ${b}`,
                            answer: a + b,
                            type: 'addition'
                        };
                    } else {
                        this.currentProblem = {
                            expression: `${a} - ${b}`,
                            answer: a - b,
                            type: 'subtraction'
                        };
                    }
                    
                    this.userAnswer = '';
                    this.feedbackMessage = '';
                },
                
                // Submit answer
                submitAnswer() {
                    if (this.battleAnimating || !this.userAnswer.trim()) {
                        this.feedbackMessage = '✗ Enter a valid answer';
                        return;
                    }
                    
                    this.totalAttempts++;
                    
                    // Parse answer and validate
                    let answer;
                    try {
                        answer = parseFloat(this.userAnswer.trim());
                        if (isNaN(answer)) {
                            this.feedbackMessage = '✗ Invalid number';
                            this.userAnswer = '';
                            return;
                        }
                    } catch (e) {
                        this.feedbackMessage = '✗ Invalid input';
                        this.userAnswer = '';
                        return;
                    }
                    
                    const isCorrect = answer === this.currentProblem.answer;
                    
                    this.battleAnimating = true;
                    this.lastCorrect = isCorrect;
                    
                    if (isCorrect) {
                        this.totalCorrect++;
                        this.comboCount++;
                        this.feedbackMessage = '✓ Correct!';
                        
                        // Calculate damage
                        let damage = 10 + this.comboCount * 2;
                        if (this.difficulty === 'medium') damage = 15 + this.comboCount * 3;
                        if (this.difficulty === 'hard') damage = 20 + this.comboCount * 5;
                        
                        this.lastMonsterDamage = damage;
                        this.showMonsterDamage = true;
                        this.monsterTakingDamage = true;
                        
                        this.monsterHealth = Math.max(0, this.monsterHealth - damage);
                        this.score += 10 + this.comboCount * 5;
                        
                        this.playSound('correct');
                        
                        setTimeout(() => {
                            this.showMonsterDamage = false;
                            this.monsterTakingDamage = false;
                            
                            if (this.monsterHealth <= 0) {
                                this.defeatMonster();
                            } else {
                                this.generateProblem();
                                this.battleAnimating = false;
                            }
                        }, 800);
                    } else {
                        this.comboCount = 0;
                        this.feedbackMessage = `✗ Wrong! Answer was ${this.currentProblem.answer}`;
                        
                        // Calculate damage to player
                        let damage = 5;
                        if (this.difficulty === 'medium') damage = 10;
                        if (this.difficulty === 'hard') damage = 15;
                        
                        this.lastPlayerDamage = damage;
                        this.showPlayerDamage = true;
                        this.playerHealth = Math.max(0, this.playerHealth - damage);
                        
                        this.playSound('wrong');
                        
                        setTimeout(() => {
                            this.showPlayerDamage = false;
                            
                            if (this.playerHealth <= 0) {
                                this.endGame(false);
                            } else {
                                this.generateProblem();
                                this.battleAnimating = false;
                            }
                        }, 800);
                    }
                },
                
                // Defeat monster
                defeatMonster() {
                    this.monstersDefeated++;
                    this.playSound('victory');
                    
                    setTimeout(() => {
                        this.waveNumber++;
                        
                        // Check if we've completed all waves
                        if (this.waveNumber > this.maxWaves) {
                            this.endGame(true);
                        } else {
                            this.loadMonster(this.waveNumber);
                            this.generateProblem();
                            this.battleAnimating = false;
                            this.comboCount = 0;
                        }
                    }, 1000);
                },
                
                // Set player health
                setPlayerHealth(health) {
                    this.playerHealth = health;
                    this.maxPlayerHealth = health > this.maxPlayerHealth ? health : this.maxPlayerHealth;
                    if (health > this.maxPlayerHealth) {
                        this.maxPlayerHealth = health;
                    }
                },
                
                // End game
                endGame(won) {
                    this.stopTimer();
                    this.playing = false;
                    this.gameOver = true;
                    this.playerWon = won;
                    
                    // Calculate accuracy with proper validation
                    if (this.totalAttempts > 0) {
                        this.accuracy = Math.round((this.totalCorrect / this.totalAttempts) * 100);
                    } else {
                        this.accuracy = 0;
                    }
                    
                    // Ensure accuracy is between 0-100
                    this.accuracy = Math.max(0, Math.min(100, this.accuracy));
                    
                    this.saveGameSession();
                },
                
                // Surrender
                surrender() {
                    this.endGame(false);
                },
                
                // Reset game
                resetGame() {
                    this.stopTimer();
                    this.playing = false;
                    this.gameOver = false;
                    this.playerWon = false;
                    this.score = 0;
                    this.waveNumber = 1;
                    this.maxWaves = 0;
                    this.monstersDefeated = 0;
                    this.totalCorrect = 0;
                    this.totalAttempts = 0;
                    this.accuracy = 0;
                    this.comboCount = 0;
                    this.playerHealth = 100;
                    this.monsterHealth = 0;
                    this.userAnswer = '';
                    this.feedbackMessage = '';
                    this.formattedTime = '00:00';
                    this.startTime = null;
                },
                
                // Audio
                playSound(type) {
                    try {
                        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                        const oscillator = audioContext.createOscillator();
                        const gain = audioContext.createGain();
                        
                        oscillator.connect(gain);
                        gain.connect(audioContext.destination);
                        
                        if (type === 'correct') {
                            oscillator.frequency.value = 800;
                            gain.gain.setValueAtTime(0.3, audioContext.currentTime);
                            gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                        } else if (type === 'wrong') {
                            oscillator.frequency.value = 300;
                            gain.gain.setValueAtTime(0.2, audioContext.currentTime);
                            gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                        } else if (type === 'victory') {
                            oscillator.frequency.value = 1000;
                            gain.gain.setValueAtTime(0.2, audioContext.currentTime);
                            gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                        }
                        
                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + 0.3);
                    } catch (e) {
                        // Audio not supported
                    }
                },
                
                // Save game session
                saveGameSession() {
                    // Track game duration
                    const duration = Math.floor((Date.now() - this.startTime) / 1000);
                    
                    // Validate all data
                    const validatedScore = Math.max(0, parseInt(this.score) || 0);
                    const validatedDuration = Math.max(0, duration);
                    const validatedAccuracy = Math.max(0, Math.min(100, this.accuracy));
                    
                    const url = this.isDailyChallenge 
                        ? "{{ route('student.daily-challenge.record') }}"
                        : "{{ route('student.games.session.store') }}";

                    const payload = {
                        score: validatedScore,
                        duration: validatedDuration,
                        details: {
                            difficulty: this.difficulty,
                            waves: this.waveNumber - 1,
                            monsters_defeated: this.monstersDefeated,
                            total_correct: this.totalCorrect,
                            total_attempts: this.totalAttempts,
                            accuracy: validatedAccuracy,
                            won: this.playerWon
                        }
                    };

                    if (this.isDailyChallenge) {
                        payload.submitted_answer = this.playerWon ? "Victory" : "Defeat";
                        payload.is_correct = this.playerWon;
                        payload.metadata = payload.details;
                    } else {
                        payload.game_id = this.gameId;
                    }
                    
                    axios.post(url, payload).catch(err => {
                        console.error('Error saving game session:', err);
                    });
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
