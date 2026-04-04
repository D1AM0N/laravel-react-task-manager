<nav x-data="{ open: false }" class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <span class="text-xl font-extrabold tracking-tight uppercase">
                            <span class="text-indigo-600 dark:text-indigo-500">Task</span><span class="text-gray-800 dark:text-white">Master</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="relative text-xs font-bold uppercase tracking-widest transition-all duration-200 border-none {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        {{ __('Dashboard') }}
                        @if(request()->routeIs('dashboard'))
                            <span class="absolute -bottom-[21px] left-0 w-full h-0.5 bg-indigo-600 dark:bg-indigo-400 shadow-[0_0_8px_rgba(79,70,229,0.6)]"></span>
                        @endif
                    </x-nav-link>

                    @if(Auth::user()->hasRole(['admin', 'superadmin', 'task-manager']))
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                            class="relative text-xs font-bold uppercase tracking-widest transition-all duration-200 border-none {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            {{ __('Admin Ops') }}
                            @if(request()->routeIs('admin.dashboard'))
                                <span class="absolute -bottom-[21px] left-0 w-full h-0.5 bg-indigo-600 dark:bg-indigo-400 shadow-[0_0_8px_rgba(79,70,229,0.6)]"></span>
                            @endif
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->hasRole(['admin', 'superadmin']))
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')"
                            class="relative text-xs font-bold uppercase tracking-widest transition-all duration-200 border-none {{ request()->routeIs('admin.users.index') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            {{ __('Personnel') }}
                            @if(request()->routeIs('admin.users.index'))
                                <span class="absolute -bottom-[21px] left-0 w-full h-0.5 bg-indigo-600 dark:bg-indigo-400 shadow-[0_0_8px_rgba(79,70,229,0.6)]"></span>
                            @endif
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->hasRole('superadmin'))
                        <button onclick="window.location.href='{{ route('admin.users.index') }}'"
                            class="inline-flex items-center px-4 py-1.5 border-2 {{ request()->routeIs('admin.users.index') ? 'bg-red-600 text-white border-red-600 shadow-[0_0_15px_rgba(220,38,38,0.4)]' : 'border-red-600 text-red-600 hover:bg-red-600 hover:text-white' }} text-[9px] font-bold uppercase tracking-[0.2em] rounded-full transition-all ml-4">
                            ⚡ AUTHORITY OVERRIDE
                        </button>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-800 text-sm font-bold rounded-xl text-gray-600 dark:text-gray-300 bg-gray-50/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition-all duration-200 focus:outline-none">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                                {{ Auth::user()->name }}
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 opacity-50" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 dark:border-gray-800 mb-1">
                            Control Panel
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="font-bold">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-500 font-bold uppercase text-xs tracking-tighter hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-gray-100 dark:border-gray-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition-all">
                Dashboard
            </a>

            @if(Auth::user()->hasRole(['admin', 'superadmin', 'task-manager']))
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition-all">
                    Admin Ops
                </a>
            @endif

            @if(Auth::user()->hasRole(['admin', 'superadmin']))
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl {{ request()->routeIs('admin.users.index') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition-all">
                    Personnel
                </a>
            @endif

            @if(Auth::user()->hasRole('superadmin'))
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl border-2 border-red-600 text-red-600 text-center transition-all">
                    ⚡ Authority Override
                </a>
            @endif
        </div>

        <!-- Mobile User Menu -->
        <div class="border-t border-gray-100 dark:border-gray-800 pt-3 pb-3 px-4 space-y-1">
            <div class="flex items-center gap-2 px-4 py-2 mb-2">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">{{ Auth::user()->name }}</span>
            </div>

            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                Profile Settings
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-xs font-black uppercase tracking-widest rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>