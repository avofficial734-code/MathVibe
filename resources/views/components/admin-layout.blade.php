<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Fallback / Essential CDN Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            [x-cloak] { display: none !important; }
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.1);
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-900 text-white">
        <div class="flex h-screen bg-slate-900 relative overflow-hidden" x-data="{ sidebarOpen: true }">
            <!-- Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
                <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-500/10 rounded-full blur-3xl animate-blob"></div>
                <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
            </div>

            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-slate-800/80 backdrop-blur-xl border-r border-white/10 text-white flex flex-col shadow-2xl transition-all duration-300 z-20 relative">
                <!-- Logo -->
                <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 bg-gradient-to-r from-indigo-600/20 to-blue-600/20">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-10 h-10 min-w-[2.5rem] bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-xl">🎓</span>
                        </div>
                        <span class="text-xl font-bold whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                            Teacher
                        </span>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white/50 hover:text-white transition-colors lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                    <!-- Dashboard -->
                    <a href="{{ route('teacher.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Dashboard</span>
                        
                        @if(request()->routeIs('teacher.dashboard'))
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white/30 rounded-l-full"></div>
                        @endif
                    </a>

                    <!-- Students -->
                    <a href="{{ route('teacher.students') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.students*') ? 'bg-pink-600 shadow-lg shadow-pink-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Students</span>
                    </a>

                    <!-- Admins -->
                    <a href="{{ route('teacher.admins.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.admins*') ? 'bg-cyan-600 shadow-lg shadow-cyan-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Admins</span>
                    </a>

                    <!-- Daily Challenges -->
                    <a href="{{ route('teacher.daily-challenges.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.daily-challenges*') ? 'bg-purple-600 shadow-lg shadow-purple-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Challenges</span>
                    </a>

                    <!-- Questions -->
                    <a href="{{ route('teacher.questions.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.questions*') ? 'bg-blue-600 shadow-lg shadow-blue-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Questions</span>
                    </a>

                    <div class="my-4 border-t border-white/10 mx-4"></div>

                    <!-- Reports -->
                    <a href="{{ route('teacher.reports') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.reports') ? 'bg-teal-600 shadow-lg shadow-teal-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Reports</span>
                    </a>

                    <!-- Feedback -->
                    <a href="{{ route('teacher.feedback') }}" 
                       class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('teacher.feedback') ? 'bg-orange-600 shadow-lg shadow-orange-500/30 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="min-w-[1.5rem]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <span class="font-medium whitespace-nowrap transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Feedback</span>
                    </a>
                </nav>

                <!-- User Profile -->
                <div class="border-t border-white/10 p-4 bg-slate-900/50">
                    <div class="flex items-center gap-3 mb-4 overflow-hidden">
                        <div class="w-10 h-10 min-w-[2.5rem] bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm ring-2 ring-white/20">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0 transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                            <p class="text-sm font-medium truncate text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition-colors flex items-center gap-2 group">
                            <div class="min-w-[1.5rem]">
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="transition-opacity duration-300" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden relative z-10">
                <!-- Top Bar -->
                <div class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-white/5 px-8 flex items-center justify-between sticky top-0 z-20">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-white/70 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">{{ $pageTitle ?? 'Admin Panel' }}</h1>
                            <p class="text-sm text-slate-400 mt-0.5">{{ $pageSubtitle ?? 'Manage your school' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-white">{{ now()->format('l, M j • h:i A') }}</p>
                            <p class="text-xs text-slate-400">Academic Year {{ now()->month >= 7 ? now()->year . '-' . (now()->year + 1) : (now()->year - 1) . '-' . now()->year }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content Scroll Area -->
                <div class="flex-1 overflow-auto custom-scrollbar p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="fixed bottom-4 right-4 bg-red-500/90 backdrop-blur-md text-white px-6 py-4 rounded-xl shadow-lg shadow-red-500/20 max-w-sm z-50 border border-red-400/30 animate-bounce-in">
                <h3 class="font-bold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Action Failed
                </h3>
                <ul class="text-sm space-y-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="fixed bottom-4 right-4 bg-emerald-500/90 backdrop-blur-md text-white px-6 py-4 rounded-xl shadow-lg shadow-emerald-500/20 z-50 border border-emerald-400/30" 
                 x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 @click="show = false" 
                 x-init="setTimeout(() => show = false, 5000)">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="font-medium">{{ session('status') }}</p>
                </div>
            </div>
        @endif
    </body>
</html>