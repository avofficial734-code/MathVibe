<x-app-layout>
    <div 
        class="min-h-screen bg-gradient-to-b from-slate-900 via-[#283B60] to-slate-900" 
        x-data="{ 
            search: '', 
            selectedTopic: 'All', 
            items: {{ Js::from($mnemonics) }},
            get filteredItems() {
                return this.items.filter(item => {
                    const matchesSearch = (item.topic + ' ' + item.description).toLowerCase().includes(this.search.toLowerCase());
                    const matchesTopic = this.selectedTopic === 'All' || item.topic === this.selectedTopic;
                    return matchesSearch && matchesTopic;
                });
            }
        }"
    >
        <!-- Hero Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-900 pb-12 px-4 shadow-xl z-10">
            <!-- Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
                <div class="absolute top-10 left-10 text-6xl transform -rotate-12 text-white">∑</div>
                <div class="absolute bottom-10 right-10 text-8xl transform rotate-12 text-white">π</div>
                <div class="absolute top-20 right-1/3 text-4xl text-blue-300">√</div>
            </div>

            <div class="max-w-7xl mx-auto pt-20 pb-8 relative z-20">
                <div class="flex flex-col items-center justify-center mb-10 text-center">
                    <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-sm font-semibold mb-4 backdrop-blur-sm">
                        Knowledge Base
                    </span>
                    <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-blue-200 mb-4 drop-shadow-sm">
                        Smart Memory Aids 🧠
                    </h1>
                    <p class="text-blue-100/80 text-lg md:text-xl max-w-2xl leading-relaxed">
                        Master complex math rules with simple, memorable tricks. Unlock the power of your memory!
                    </p>
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
                                placeholder="Search for mnemonics (e.g., 'PEMDAS', 'SOHCAHTOA')..."
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

                    <!-- Topic Filters -->
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <button 
                            @click="selectedTopic = 'All'"
                            :class="selectedTopic === 'All' ? 'bg-white text-slate-900 shadow-lg scale-105' : 'bg-white/10 text-white hover:bg-white/20'"
                            class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 border border-transparent"
                        >
                            All Topics
                        </button>
                        @foreach($topics as $topic)
                            <button 
                                @click="selectedTopic = '{{ $topic }}'"
                                :class="selectedTopic === '{{ $topic }}' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30 scale-105 border-indigo-400' : 'bg-slate-800/50 text-blue-200 hover:bg-slate-700/50 border-white/10'"
                                class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 border backdrop-blur-md"
                            >
                                {{ $topic }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <!-- Results Counter -->
            <div class="mb-8 text-white/60 text-sm font-medium" x-show="search !== '' || selectedTopic !== 'All'">
                Showing <span x-text="filteredItems.length" class="text-white font-bold"></span> result(s)
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="filteredItems.length > 0">
                <template x-for="item in filteredItems" :key="item.id">
                    <div class="group relative bg-slate-800/40 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-2xl hover:shadow-indigo-500/20 hover:-translate-y-1" x-data="{ expanded: false }">
                        <!-- Card Header -->
                        <div class="p-6 pb-4">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 rounded-lg bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider border border-indigo-500/30" x-text="item.topic"></span>
                                <button class="text-white/40 hover:text-white transition-colors">
                                    <span class="sr-only">Favorite</span>
                                    ⭐
                                </button>
                            </div>
                            
                            <!-- Main Mnemonic -->
                            <div class="bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-2xl p-5 border border-white/5 group-hover:border-white/10 transition-colors">
                                <p class="text-2xl font-bold text-white leading-tight" x-text="item.description"></p>
                            </div>
                        </div>

                        <!-- Rules/Details -->
                        <div class="px-6 pb-6">
                            <div class="relative overflow-hidden transition-all duration-500" :style="expanded ? 'max-height: 500px; opacity: 1' : 'max-height: 100px; opacity: 0.8'">
                                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <span>How it works</span>
                                    <div class="h-px flex-1 bg-white/10"></div>
                                </h4>
                                
                                <ul class="space-y-3">
                                    <template x-for="(rule, index) in item.rules">
                                        <li class="flex items-start gap-3 text-slate-300 text-sm">
                                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-700 text-indigo-400 font-bold text-xs flex items-center justify-center border border-white/10" x-text="index + 1"></span>
                                            <span class="leading-relaxed" x-text="rule"></span>
                                        </li>
                                    </template>
                                </ul>

                                <!-- Fade Overlay for non-expanded state -->
                                <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-slate-900/90 to-transparent" x-show="!expanded && item.rules.length > 2"></div>
                            </div>

                            <!-- Expand Button -->
                            <button 
                                @click="expanded = !expanded" 
                                class="w-full mt-4 flex items-center justify-center gap-2 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-sm font-semibold transition-all group-hover:bg-indigo-600 group-hover:text-white"
                            >
                                <span x-text="expanded ? 'Show Less' : 'View Breakdown'"></span>
                                <span x-text="expanded ? '↑' : '↓'"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredItems.length === 0" class="flex flex-col items-center justify-center py-20 text-center" style="display: none;">
                <div class="w-24 h-24 bg-slate-800/50 rounded-full flex items-center justify-center text-5xl mb-6 shadow-xl border border-white/10 animate-pulse">
                    🤔
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">No mnemonics found</h3>
                <p class="text-white/60 max-w-md mx-auto">
                    We couldn't find any memory aids matching your search. Try a different topic or keyword!
                </p>
                <button @click="search = ''; selectedTopic = 'All'" class="mt-8 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20">
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
                    
                    <div class="flex items-center gap-2 px-4 py-2 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                        <span class="text-yellow-400">💡</span>
                        <p class="text-yellow-100/80 text-sm font-medium">Pro Tip: Use these tricks during games to boost your score!</p>
                    </div>

                    <a href="{{ route('student.games.index') }}" class="group relative px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/20 overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative flex items-center gap-2">
                            Practice Now
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>