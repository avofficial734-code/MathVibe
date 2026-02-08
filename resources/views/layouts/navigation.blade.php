<nav x-data="{ open: false }" class="bg-gradient-to-r from-slate-900 via-[#283B60] to-slate-900 border-b border-white/10 sticky top-0 z-50 backdrop-blur-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="bg-gradient-to-r from-[#283B60] to-[#1A3263] p-2 rounded-lg group-hover:shadow-lg group-hover:shadow-[#283B60]/50 transition-all duration-300">
                            <x-application-logo class="block h-6 w-auto fill-white" />
                        </div>
                        <span class="text-lg font-bold bg-gradient-to-r from-[#DFE0E2] to-[#A4A6A8] bg-clip-text text-transparent hidden md:block">MathVibe</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:flex">
                    @if(Auth::user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('student.dashboard') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('student.mnemonics') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('student.mnemonics') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            🧠 Mnemonics
                        </a>
                        <a href="{{ route('student.games.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('student.games.*') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            🎮 Games
                        </a>
                        <a href="{{ route('student.daily-challenge.show') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('student.daily-challenge.*') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            ⭐ Daily Challenge
                        </a>
                        <a href="{{ route('student.progress') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('student.progress') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            📈 Progress
                        </a>
                    @elseif(Auth::user()->isTeacher())
                        <a href="{{ route('teacher.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('teacher.dashboard') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('teacher.students') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('teacher.students') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            👥 Students
                        </a>
                        <a href="{{ route('teacher.admins.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ request()->routeIs('teacher.admins.*') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            👨‍🏫 Teachers
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">👤</span>
                                <div>{{ Auth::user()->name }}</div>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-[#283B60]/30">
                            ⚙️ {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="hover:bg-red-600/30"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                🚪 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gradient-to-b from-slate-800 to-slate-900 border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-2">
            @if(Auth::user()->isStudent())
                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('student.dashboard') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('student.mnemonics') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('student.mnemonics') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    🧠 Mnemonics
                </a>
                <a href="{{ route('student.games.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('student.games.*') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    🎮 Games
                </a>
                <a href="{{ route('student.daily-challenge.show') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('student.daily-challenge.*') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    ⭐ Daily Challenge
                </a>
                <a href="{{ route('student.progress') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('student.progress') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    📈 Progress
                </a>
            @elseif(Auth::user()->isTeacher())
                <a href="{{ route('teacher.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('teacher.dashboard') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('teacher.students') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('teacher.students') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    👥 Students
                </a>
                <a href="{{ route('teacher.admins.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium transition-all {{ request()->routeIs('teacher.admins.*') ? 'bg-[#283B60]/30 text-white border border-[#283B60]/50' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                    👨‍🏫 Teachers
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-white/70 hover:text-white hover:bg-white/5 transition-all">
                    ⚙️ Profile
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-medium text-white/70 hover:text-white hover:bg-red-600/20 transition-all">
                        🚪 Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
