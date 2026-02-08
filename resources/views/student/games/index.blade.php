<x-app-layout>
    <div 
        class="min-h-screen bg-gradient-to-b from-slate-900 via-[#283B60] to-slate-900" 
        x-data="{ 
            search: '', 
            selectedCategory: 'All', 
            games: {{ Js::from($games) }},
            get filteredGames() {
                return this.games.filter(game => {
                    const matchesSearch = (game.name + ' ' + game.description).toLowerCase().includes(this.search.toLowerCase());
                    const matchesCategory = this.selectedCategory === 'All' || game.category === this.selectedCategory;
                    return matchesSearch && matchesCategory;
                });
            }
        }"
    >
        <!-- Hero Banner & Daily Challenge -->
        <div class="relative overflow-hidden bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-900 pb-12 px-4 shadow-xl z-10">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
                <div class="absolute top-10 left-10 text-6xl transform -rotate-12 text-white animate-pulse">🎮</div>
                <div class="absolute bottom-10 right-10 text-8xl transform rotate-12 text-white opacity-50">🎲</div>
                <div class="absolute top-20 right-1/3 text-4xl text-blue-300">⚡</div>
            </div>

            <div class="max-w-7xl mx-auto pt-16 pb-8 relative z-20">
                <div class="flex flex-col lg:flex-row items-center gap-12 mb-10">
                    <!-- Text Content -->
                    <div class="flex-1 text-center lg:text-left">
                        <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-sm font-semibold mb-4 backdrop-blur-sm">
                            Game Zone
                        </span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-blue-200 mb-4 drop-shadow-sm leading-tight">
                            Choose Your Challenge 🎯
                        </h1>
                        <p class="text-blue-100/80 text-lg md:text-xl max-w-2xl leading-relaxed mx-auto lg:mx-0">
                            Pick a game, master the skills, and climb the leaderboard!
                        </p>
                    </div>

                    <!-- Daily Challenge Card -->
                    <div class="w-full lg:w-1/3 max-w-md">
                        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-1 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                            <div class="bg-slate-900/90 backdrop-blur-xl rounded-[22px] p-6 h-full border border-white/10 relative overflow-hidden group">
                                <!-- Content -->
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="bg-orange-500/20 text-orange-300 text-xs font-bold px-3 py-1 rounded-full border border-orange-500/30 flex items-center gap-1">
                                            <span>🔥</span> Daily Challenge
                                        </span>
                                        @if($dailyChallengeCompleted)
                                            <span class="text-green-400 bg-green-400/10 rounded-full p-1" title="Completed">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-2">Today's Quest</h3>
                                    <p class="text-slate-300 text-sm mb-6">Complete the daily challenge to earn bonus XP!</p>
                                    <a href="{{ route('student.daily-challenge.show') }}" class="block w-full py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-center transition-colors shadow-lg shadow-orange-500/30">
                                        {{ $dailyChallengeCompleted ? 'View Results' : 'Play Now' }}
                                    </a>
                                </div>
                                
                                <!-- Background Glow -->
                                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-orange-500/20 blur-3xl rounded-full pointer-events-none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter Controls -->
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Search Bar -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
                        <div class="relative bg-slate-900/50 backdrop-blur-xl border border-white/10 rounded-2xl flex items-center p-2 shadow-2xl">
                            <span class="pl-4 text-white/50 text-xl">🔍</span>
                            <input 
                                x-model="search" 
                                type="text" 
                                class="w-full bg-transparent border-none text-white placeholder-white/50 focus:ring-0 text-lg py-3 px-4"
                                placeholder="Search games (e.g., 'Sorting', 'Integer')..."
                            >
                            <button 
                                x-show="search.length > 0" 
                                @click="search = ''"
                                class="p-2 text-white/50 hover:text-white transition-colors"
                            >
                                ✕
                            </button>
                        </div>
                    </div>

                    <!-- Category Filters -->
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <button 
                            @click="selectedCategory = 'All'"
                            :class="selectedCategory === 'All' ? 'bg-white text-slate-900 shadow-lg scale-105' : 'bg-white/10 text-white hover:bg-white/20'"
                            class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 border border-transparent"
                        >
                            All Games
                        </button>
                        @foreach($categories as $category)
                            <button 
                                @click="selectedCategory = '{{ $category }}'"
                                :class="selectedCategory === '{{ $category }}' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30 scale-105 border-indigo-400' : 'bg-slate-800/50 text-blue-200 hover:bg-slate-700/50 border-white/10'"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 border backdrop-blur-md"
                            >
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Games Section -->
        @if($recentGames->count() > 0)
        <div class="max-w-7xl mx-auto px-4 mt-12 mb-4">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2 px-2">
                <span class="text-blue-400 bg-blue-400/10 p-2 rounded-lg">🕒</span> 
                <span>Jump Back In</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($recentGames as $game)
                    <a href="{{ route('student.games.play', $game) }}" class="flex items-center gap-4 bg-slate-800/40 border border-white/5 rounded-2xl p-4 hover:bg-slate-800 hover:border-indigo-500/50 transition-all group backdrop-blur-sm">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br {{ $game->color }} flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform shrink-0">
                            {{ $game->icon }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-white text-lg group-hover:text-indigo-300 transition-colors truncate">{{ $game->name }}</h4>
                            <span class="text-xs text-slate-400 font-medium px-2 py-0.5 rounded-full bg-white/5 border border-white/5">{{ $game->category }}</span>
                        </div>
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity text-indigo-400">
                            ▶
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Games Grid -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <!-- Results Counter -->
            <div class="mb-8 flex items-center justify-between" x-show="search !== '' || selectedCategory !== 'All'">
                <div class="text-white/60 text-sm font-medium">
                    Showing <span x-text="filteredGames.length" class="text-white font-bold"></span> result(s)
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="filteredGames.length > 0">
                <template x-for="game in filteredGames" :key="game.id">
                    <div class="group relative bg-slate-800/40 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/20 hover:-translate-y-2 flex flex-col h-full">
                        <!-- Card Header / Image Placeholder -->
                        <div class="relative h-48 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br opacity-80 transition-transform duration-700 group-hover:scale-110" :class="game.color"></div>
                            
                            <!-- Icon Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-7xl drop-shadow-lg transform transition-transform duration-500 group-hover:scale-125 group-hover:rotate-12" x-text="game.icon"></span>
                            </div>

                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="px-3 py-1 rounded-full bg-black/30 backdrop-blur-md text-white text-xs font-bold border border-white/20" x-text="game.badge"></span>
                            </div>
                            
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-300 border border-green-500/30 text-xs font-bold backdrop-blur-md flex items-center gap-1">
                                    <span>●</span> Active
                                </span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors" x-text="game.name"></h3>
                            
                            <p class="text-slate-300 text-sm leading-relaxed mb-6 line-clamp-3 flex-1" x-text="game.description"></p>

                            <!-- Stats -->
                            <div class="flex items-center gap-4 text-xs font-medium text-slate-400 mb-6 bg-slate-900/30 p-3 rounded-xl border border-white/5">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-indigo-400">🎮</span>
                                    <span x-text="(game.sessions_count || 0) + ' plays'"></span>
                                </div>
                                <div class="h-3 w-px bg-white/10"></div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-yellow-400">⭐</span>
                                    <span>Top Rated</span>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <a 
                                :href="'/student/games/play/' + game.slug" 
                                class="w-full py-3.5 rounded-xl bg-white/10 hover:bg-white text-white hover:text-indigo-900 font-bold text-center transition-all duration-300 flex items-center justify-center gap-2 group-hover:shadow-lg group-hover:shadow-indigo-500/20"
                            >
                                <span>Play Now</span>
                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredGames.length === 0" class="flex flex-col items-center justify-center py-20 text-center" style="display: none;">
                <div class="w-24 h-24 bg-slate-800/50 rounded-full flex items-center justify-center text-5xl mb-6 shadow-xl border border-white/10 animate-bounce">
                    🎮
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">No games found</h3>
                <p class="text-white/60 max-w-md mx-auto">
                    We couldn't find any games matching your search. Try a different category or keyword!
                </p>
                <button @click="search = ''; selectedCategory = 'All'" class="mt-8 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20">
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Navigation Footer -->
        <div class="border-t border-white/5 bg-slate-900/50 backdrop-blur-xl mt-auto">
            <div class="max-w-7xl mx-auto px-4 py-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <a href="{{ route('student.dashboard') }}" class="text-slate-400 hover:text-white transition-colors flex items-center gap-2 font-medium">
                        <span class="text-lg">←</span> Back to Dashboard
                    </a>
                    
                    <div class="flex items-center gap-2 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-lg">
                        <span class="text-indigo-400">🏆</span>
                        <p class="text-indigo-100/80 text-sm font-medium">Earn badges and climb the leaderboard!</p>
                    </div>

                    <a href="{{ route('student.progress') }}" class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-bold shadow-lg shadow-blue-600/20 overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            View Progress
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>