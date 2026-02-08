<x-app-layout>
    <!-- Custom Dark Theme Override for this page -->
    <style>
        .bg-gray-100 { background-color: #0f172a !important; /* slate-900 */ }
        .bg-white { background-color: #1e293b !important; /* slate-800 */ }
        .text-gray-800 { color: #f8fafc !important; /* slate-50 */ }
        .text-gray-600 { color: #94a3b8 !important; /* slate-400 */ }
        .text-gray-500 { color: #64748b !important; /* slate-500 */ }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ $game->name }} <span class="text-sm font-normal text-indigo-300 ml-2 bg-indigo-500/20 px-2 py-1 rounded-full border border-indigo-500/30">Live Race Mode</span>
            </h2>
            <div class="flex items-center gap-4">
                <div class="text-sm text-slate-400">
                    Time <span id="timer" class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 font-semibold">00:00</span>
                </div>
                <div class="text-sm text-slate-400">
                    Rank <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-semibold" x-text="getRank()"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen bg-slate-900" x-data="gameLogic()" x-init="initGame()" x-cloak>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Loading State -->
            <div x-show="loading" class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-10 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto mb-4"></div>
                <p class="text-xl text-slate-400">Loading Game Resources...</p>
            </div>

            <!-- Error State -->
            <div x-show="error" class="bg-slate-800/50 backdrop-blur-xl border border-red-500/20 shadow-xl sm:rounded-2xl p-10 text-center text-red-400">
                <p class="text-xl" x-text="errorMessage"></p>
                <button @click="window.location.reload()" class="mt-4 bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded-xl transition-colors border border-white/10">
                    Retry
                </button>
            </div>

            <!-- Start Screen -->
            <div x-show="!loading && !error && !playing && !gameOver && !lobby" class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-10 text-center relative overflow-hidden group">
                <!-- Background Gradient Effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="mb-8 transform hover:scale-110 transition-transform duration-300 inline-block">
                        <span class="text-7xl filter drop-shadow-lg">🏎️</span>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-4 tracking-tight">Multiplayer Math Race</h3>
                    <p class="mb-10 text-slate-400 max-w-lg mx-auto text-lg leading-relaxed">{{ $game->description }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-12">
                        <div class="bg-slate-800/80 p-6 rounded-2xl border border-white/5 hover:border-purple-500/30 transition-colors group/card">
                            <div class="text-3xl mb-3 group-hover/card:scale-110 transition-transform">⚡</div>
                            <h4 class="font-bold text-purple-400 text-lg mb-2">Speed Matters</h4>
                            <p class="text-sm text-slate-500">Answer fast to stay ahead of the pack!</p>
                        </div>
                        <div class="bg-slate-800/80 p-6 rounded-2xl border border-white/5 hover:border-blue-500/30 transition-colors group/card">
                            <div class="text-3xl mb-3 group-hover/card:scale-110 transition-transform">🎯</div>
                            <h4 class="font-bold text-blue-400 text-lg mb-2">Accuracy is Key</h4>
                            <p class="text-sm text-slate-500">Wrong answers will slow you down.</p>
                        </div>
                        <div class="bg-slate-800/80 p-6 rounded-2xl border border-white/5 hover:border-orange-500/30 transition-colors group/card">
                            <div class="text-3xl mb-3 group-hover/card:scale-110 transition-transform">🏆</div>
                            <h4 class="font-bold text-orange-400 text-lg mb-2">Top 3 Win</h4>
                            <p class="text-sm text-slate-500">Finish on the podium to earn bonus XP.</p>
                        </div>
                    </div>

                    <button @click="enterLobby" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-4 px-12 rounded-2xl text-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:scale-105 active:scale-95 touch-manipulation flex items-center justify-center mx-auto gap-3 border border-white/10">
                        <span>Find Match</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Lobby Screen -->
            <div x-show="lobby" class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-10 text-center">
                <h3 class="text-2xl font-bold text-white mb-8">Searching for Opponents...</h3>
                
                <div class="max-w-md mx-auto bg-slate-900/50 rounded-2xl p-6 border border-white/5 mb-8">
                    <div class="space-y-4">
                        <!-- Player -->
                        <div class="flex items-center justify-between bg-slate-800 p-4 rounded-xl shadow-sm border-l-4 border-emerald-500">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-emerald-500/10 flex items-center justify-center text-2xl border border-emerald-500/20">👤</div>
                                <span class="font-bold text-white text-lg">You</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20">READY</span>
                        </div>
                        
                        <!-- Opponents -->
                        <template x-for="bot in opponents" :key="bot.id">
                            <div class="flex items-center justify-between bg-slate-800 p-4 rounded-xl shadow-sm border-l-4 border-indigo-500 animate-fade-in-up">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-indigo-500/10 flex items-center justify-center text-2xl border border-indigo-500/20" x-text="bot.avatar"></div>
                                    <span class="font-bold text-slate-200" x-text="bot.name"></span>
                                </div>
                                <span class="text-xs font-bold text-indigo-400 bg-indigo-500/10 px-3 py-1.5 rounded-lg border border-indigo-500/20">READY</span>
                            </div>
                        </template>

                        <!-- Searching Placeholder -->
                        <div x-show="opponents.length < 3" class="flex items-center justify-center p-4 text-slate-500 italic gap-3">
                            <div class="animate-spin h-5 w-5 border-2 border-slate-600 border-t-slate-400 rounded-full"></div>
                            <span>Scanning for players...</span>
                        </div>
                    </div>
                </div>

                <div x-show="opponents.length === 3" class="text-emerald-400 font-bold text-2xl animate-pulse">
                    Match Found! Starting in <span x-text="lobbyCountdown"></span>...
                </div>
            </div>

            <!-- Gameplay Screen -->
            <div x-show="playing" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Main Game Area -->
                <div class="lg:col-span-3 space-y-6">
                    
                    <!-- Race Track -->
                    <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                Race Progress
                            </h3>
                            <div class="text-xs text-slate-500 font-mono">Target: <span class="text-white" x-text="questions.length"></span> Qs</div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Player Bar -->
                            <div class="relative group">
                                <div class="flex justify-between text-xs font-bold mb-2">
                                    <span class="text-emerald-400 flex items-center gap-1">
                                        You
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20" x-show="score > 0" x-text="'+'+score"></span>
                                    </span>
                                    <span class="text-slate-400" x-text="Math.round(getProgress(currentQuestionIndex)) + '%'"></span>
                                </div>
                                <div class="h-3 w-full bg-slate-700/50 rounded-full overflow-hidden border border-white/5">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500 ease-out shadow-[0_0_10px_rgba(16,185,129,0.5)]"
                                         :style="`width: ${getProgress(currentQuestionIndex)}%`"></div>
                                </div>
                                <!-- Avatar Head on track -->
                                <div class="absolute top-5 transition-all duration-500 ease-out transform -translate-x-1/2 z-10" 
                                     :style="`left: ${getProgress(currentQuestionIndex)}%`">
                                    <div class="text-2xl filter drop-shadow-md transform -translate-y-1">👤</div>
                                </div>
                            </div>

                            <!-- Bot Bars -->
                            <template x-for="bot in opponents" :key="bot.id">
                                <div class="relative mt-4">
                                    <div class="flex justify-between text-xs font-semibold mb-2 text-slate-500">
                                        <span x-text="bot.name"></span>
                                        <span x-text="Math.round(getProgress(bot.progress)) + '%'"></span>
                                    </div>
                                    <div class="h-1.5 w-full bg-slate-700/30 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500/60 transition-all duration-500 ease-out"
                                             :style="`width: ${getProgress(bot.progress)}%`"></div>
                                    </div>
                                    <div class="absolute top-3 transition-all duration-500 ease-out transform -translate-x-1/2 opacity-70" 
                                         :style="`left: ${getProgress(bot.progress)}%`">
                                        <div class="text-lg grayscale-[30%]" x-text="bot.avatar"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Question Card -->
                    <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-8 relative overflow-hidden">
                        <!-- Decorative glow -->
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="mb-6 flex justify-between items-center relative z-10">
                            <span class="bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-xs font-bold px-3 py-1 rounded-lg">
                                Question <span x-text="currentQuestionIndex + 1"></span>/<span x-text="questions.length"></span>
                            </span>
                            <span class="text-sm font-bold text-slate-400">Score: <span class="text-white text-lg ml-1" x-text="score"></span></span>
                        </div>

                        <div class="text-3xl md:text-5xl font-bold text-white mb-10 text-center py-6 tracking-tight relative z-10" x-text="currentQuestion.content"></div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-10">
                            <template x-for="(choice, index) in currentQuestion.choices" :key="index">
                                <button @click="checkAnswer(choice)" 
                                    class="bg-slate-700/50 hover:bg-indigo-600/90 text-slate-200 hover:text-white font-semibold py-5 px-6 rounded-2xl border border-white/5 hover:border-indigo-400/50 shadow-lg hover:shadow-indigo-500/20 transition-all active:scale-95 text-xl group touch-manipulation"
                                >
                                    <span class="opacity-50 group-hover:opacity-100 mr-2" x-text="['A','B','C','D'][index] + '.'"></span>
                                    <span x-text="choice"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Sidebar / Feed -->
                <div class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-5 h-fit sticky top-6">
                    <h3 class="text-xs font-bold text-slate-500 uppercase mb-4 pb-2 border-b border-white/5 tracking-wider">Race Feed</h3>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(event, index) in feed" :key="index">
                            <div class="text-sm flex gap-3 items-start animate-fade-in bg-slate-800/50 p-2.5 rounded-lg border border-white/5">
                                <span class="text-lg" x-text="event.icon"></span>
                                <div>
                                    <div class="font-bold text-slate-200" x-text="event.name"></div>
                                    <div class="text-xs text-slate-400" x-text="event.action"></div>
                                </div>
                            </div>
                        </template>
                        <div x-show="feed.length === 0" class="text-center text-slate-600 text-xs italic py-8">
                            Race started!<br>Events will appear here.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Game Over Screen -->
            <div x-show="gameOver" class="bg-slate-800/50 backdrop-blur-xl border border-white/5 shadow-xl sm:rounded-2xl p-10">
                <div class="text-center mb-10">
                    <div class="text-7xl mb-6 animate-bounce" x-text="getRankEmoji()"></div>
                    <h3 class="text-4xl font-bold text-white mb-2">Race Finished!</h3>
                    <p class="text-xl text-slate-400">You finished <span class="font-bold text-indigo-400" x-text="getRankText()"></span></p>
                </div>

                <!-- Leaderboard -->
                <div class="max-w-2xl mx-auto bg-slate-900/50 rounded-2xl overflow-hidden border border-white/10 mb-10 shadow-inner">
                    <div class="bg-slate-800/80 px-8 py-4 border-b border-white/5 flex justify-between font-bold text-slate-400 text-sm uppercase tracking-wider">
                        <span>Player</span>
                        <span>Score</span>
                    </div>
                    <template x-for="(player, index) in getSortedPlayers()" :key="index">
                        <div class="px-8 py-5 flex justify-between items-center border-b border-white/5 last:border-0" 
                             :class="{'bg-indigo-500/10': player.isUser, 'bg-transparent': !player.isUser}">
                            <div class="flex items-center gap-4">
                                <span class="font-bold text-slate-500 w-6 font-mono" x-text="'#' + (index + 1)"></span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg bg-slate-800 border border-white/10" x-text="player.avatar"></div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-lg" :class="player.isUser ? 'text-white' : 'text-slate-300'" x-text="player.name"></span>
                                    <span class="text-xs text-slate-500" x-show="player.isUser">(That's you)</span>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-xl text-indigo-400" x-text="player.score"></span>
                        </div>
                    </template>
                </div>

                <div class="text-center text-slate-500 text-sm mb-10 font-mono">
                    <span x-text="questions.length"></span> questions • <span id="durationText"></span>
                </div>
                
                <div class="flex justify-center space-x-6">
                    <button @click="initGame" class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg transition-colors border border-white/10 active:scale-95 touch-manipulation">
                        Play Again
                    </button>
                    <a href="{{ route('student.games.index') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-indigo-500/20 transition-colors active:scale-95 touch-manipulation">
                        Back to Games
                    </a>
                </div>
            </div>
            
            <!-- Wrong Answer Hint Modal -->
            <div x-show="showHint" class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm z-50" style="display: none;">
                <div class="bg-slate-800 rounded-2xl p-8 max-w-md w-full m-4 shadow-2xl transform scale-100 transition-transform border border-red-500/20">
                    <h4 class="text-xl font-bold text-red-400 mb-4 flex items-center gap-3">
                        <span class="text-2xl">⚠️</span> Incorrect
                    </h4>
                    <div class="bg-red-500/10 p-5 rounded-xl border border-red-500/20 mb-8">
                        <p class="text-slate-200 leading-relaxed" x-text="currentHint"></p>
                    </div>
                    <button @click="showHint = false; nextQuestion()" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-500/20 transition-colors">
                        Got it, keep going!
                    </button>
                </div>
            </div>

        </div>
    </div>

    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(30, 41, 59, 0.5);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.5);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.8);
        }
    </style>

    <script>
        function gameLogic() {
            return {
                loading: true,
                error: false,
                errorMessage: '',
                lobby: false,
                playing: false,
                gameOver: false,
                
                // Game Data
                questions: [],
                currentQuestionIndex: 0,
                currentQuestion: {},
                score: 0,
                totalCorrect: 0,
                
                // Multiplayer Data
                opponents: [],
                feed: [],
                lobbyCountdown: 3,
                botInterval: null,
                
                // Time Data
                startTime: null,
                timerInterval: null,
                
                // UI State
                showHint: false,
                currentHint: '',

                async initGame() {
                    // Reset States
                    this.loading = true;
                    this.error = false;
                    this.lobby = false;
                    this.playing = false;
                    this.gameOver = false;
                    this.score = 0;
                    this.totalCorrect = 0;
                    this.currentQuestionIndex = 0;
                    this.opponents = [];
                    this.feed = [];
                    this.lobbyCountdown = 3;
                    
                    try {
                        const response = await fetch("{{ route('student.games.questions', $game->id) }}");
                        if (!response.ok) throw new Error('Failed to load questions');
                        const data = await response.json();
                        if (!data || data.length === 0) throw new Error('No questions available for this game');
                        this.questions = data;
                        this.loading = false;
                    } catch (error) {
                        console.error('Error fetching questions:', error);
                        this.error = true;
                        this.errorMessage = error.message || 'An error occurred';
                        this.loading = false;
                    }
                },

                enterLobby() {
                    this.lobby = true;
                    this.findOpponents();
                },

                findOpponents() {
                    const possibleBots = [
                        { name: 'Alex', avatar: '🦊', speed: 0.8 },
                        { name: 'Sam', avatar: '🐯', speed: 0.9 },
                        { name: 'Jordan', avatar: '🐸', speed: 0.7 },
                        { name: 'Casey', avatar: '🐼', speed: 0.85 },
                        { name: 'Riley', avatar: '🐨', speed: 0.6 },
                        { name: 'Taylor', avatar: '🦁', speed: 0.95 }
                    ];
                    
                    // Shuffle and pick 3
                    const shuffled = possibleBots.sort(() => 0.5 - Math.random()).slice(0, 3);
                    
                    let delay = 0;
                    shuffled.forEach((bot, index) => {
                        delay += Math.random() * 1500 + 500; // 0.5s to 2s delay
                        setTimeout(() => {
                            this.opponents.push({
                                id: index,
                                name: bot.name,
                                avatar: bot.avatar,
                                speed: bot.speed, // multiplier for answering speed
                                progress: 0, // question index
                                score: 0,
                                finished: false,
                                isUser: false
                            });
                        }, delay);
                    });

                    // Start game after all bots joined + buffer
                    setTimeout(() => {
                        this.startLobbyCountdown();
                    }, delay + 1000);
                },

                startLobbyCountdown() {
                    const interval = setInterval(() => {
                        this.lobbyCountdown--;
                        if (this.lobbyCountdown <= 0) {
                            clearInterval(interval);
                            this.startGame();
                        }
                    }, 1000);
                },

                startGame() {
                    this.lobby = false;
                    this.playing = true;
                    this.startTime = Date.now();
                    this.loadQuestion();
                    this.startTimer();
                    this.startBotAI();
                },

                startBotAI() {
                    if (this.botInterval) clearInterval(this.botInterval);
                    
                    // Check bot progress every second
                    this.botInterval = setInterval(() => {
                        if (this.gameOver) return;

                        this.opponents.forEach(bot => {
                            if (bot.finished) return;

                            // Random chance to answer based on speed
                            // Base chance: 30% per second + speed modifier
                            const chance = 0.3 * bot.speed;
                            
                            if (Math.random() < chance) {
                                this.botAnswer(bot);
                            }
                        });
                    }, 1000);
                },

                botAnswer(bot) {
                    // Logic for bot correctness (80% accuracy)
                    const isCorrect = Math.random() < 0.8;
                    
                    if (isCorrect) {
                        bot.score += 10;
                        bot.progress++;
                        
                        // Add to feed occasionally
                        if (Math.random() < 0.3) {
                            this.addToFeed(bot.name, 'got it right!', '✅');
                        }

                        if (bot.progress >= this.questions.length) {
                            bot.finished = true;
                            bot.progress = this.questions.length; // Cap at max
                            this.addToFeed(bot.name, 'finished the race!', '🏁');
                            this.checkRaceFinish();
                        }
                    } else {
                        // Bot got it wrong
                        if (Math.random() < 0.3) {
                            this.addToFeed(bot.name, 'slipped up!', '❌');
                        }
                    }
                },

                addToFeed(name, action, icon) {
                    this.feed.unshift({ name, action, icon });
                    if (this.feed.length > 5) this.feed.pop();
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
                        this.addToFeed('You', 'answered correctly!', '⭐');
                        this.nextQuestion();
                    } else {
                        this.currentHint = this.currentQuestion.mnemonic ? this.currentQuestion.mnemonic.description : "Remember the rules for this operation!";
                        this.showHint = true;
                        // Penalty? Maybe just time loss.
                    }
                },

                nextQuestion() {
                    this.currentQuestionIndex++;
                    // Check for finish
                    if (this.currentQuestionIndex >= this.questions.length) {
                        this.endGame();
                    } else {
                        this.loadQuestion();
                    }
                },

                getProgress(index) {
                    if (this.questions.length === 0) return 0;
                    return Math.min(Math.round((index / this.questions.length) * 100), 100);
                },

                checkRaceFinish() {
                    // If all opponents finished, force end game?
                    // Or let user finish. Let's let user finish.
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

                getSortedPlayers() {
                    const user = {
                        name: 'You',
                        avatar: '👤',
                        score: this.score,
                        progress: this.currentQuestionIndex, // Or finished status
                        isUser: true,
                        finished: this.gameOver
                    };
                    
                    const all = [...this.opponents, user];
                    
                    // Sort by: Score descending.
                    return all.sort((a, b) => b.score - a.score);
                },

                getRank() {
                    const sorted = this.getSortedPlayers();
                    const rank = sorted.findIndex(p => p.isUser) + 1;
                    return '#' + rank;
                },
                
                getRankText() {
                    const rank = this.getRank();
                    if (rank === '#1') return '1st Place! 🥇';
                    if (rank === '#2') return '2nd Place! 🥈';
                    if (rank === '#3') return '3rd Place! 🥉';
                    return rank;
                },

                getRankEmoji() {
                    const rank = this.getRank();
                    if (rank === '#1') return '🏆';
                    if (rank === '#2') return '🥈';
                    if (rank === '#3') return '🥉';
                    return '👏';
                },

                async endGame() {
                    this.playing = false;
                    this.gameOver = true;
                    clearInterval(this.timerInterval);
                    if (this.botInterval) clearInterval(this.botInterval);
                    
                    const duration = Math.floor((Date.now() - this.startTime) / 1000);
                    const accuracy = this.questions.length > 0
                        ? Math.round((this.totalCorrect / this.questions.length) * 100)
                        : 0;

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
                                game_type: 'multiplayer_sim',
                                total_questions: this.questions.length,
                                correct_answers: this.totalCorrect,
                                accuracy: accuracy,
                                rank: this.getRank()
                            }
                        };

                        if (this.isDailyChallenge) {
                            payload.submitted_answer = this.getRankText();
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