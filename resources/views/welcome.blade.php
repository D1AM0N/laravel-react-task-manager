<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskMaster | Stormbreaker Portal</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-white transition-colors duration-300 font-sans">
        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
            
            <nav class="absolute top-0 w-full p-6 flex justify-between items-center max-w-7xl mx-auto z-10">
                <div class="flex items-center gap-2 group cursor-pointer">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:rotate-12 transition-transform">
                        <span class="text-white font-black text-xl italic">T</span>
                    </div>
                    <span class="font-black text-2xl tracking-tighter uppercase italic">
                        Task<span class="text-indigo-600 dark:text-indigo-500">Master</span>
                    </span>
                </div>

                <div class="flex gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 font-bold text-sm hover:bg-indigo-600 hover:text-white transition-all active:scale-95 shadow-sm">
                                Enter Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 font-bold text-sm text-gray-500 hover:text-indigo-500 transition-colors">Log In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 active:scale-95">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <main class="relative text-center px-6 mt-24 z-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-500 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Stormbreaker Engine v2.0
                </div>

                <h1 class="text-6xl md:text-8xl font-black tracking-tighter mb-6 leading-tight italic uppercase">
                    Master your tasks <br/>
                    <span class="text-indigo-600 dark:text-indigo-500 drop-shadow-sm">With Zero Friction.</span>
                </h1>
                
                <p class="max-w-2xl mx-auto text-lg text-gray-500 dark:text-gray-400 mb-12 font-medium leading-relaxed">
                    A high-performance workspace built for speed. 
                    Real-time monitoring, deep dark mode support, and full administrative oversight.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-10 py-5 rounded-2xl bg-indigo-600 text-white font-black text-lg shadow-xl shadow-indigo-500/30 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95">
                            CONTINUE TO TASKS
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 rounded-2xl bg-indigo-600 text-white font-black text-lg shadow-xl shadow-indigo-500/30 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95">
                            CREATE YOUR WORKSPACE
                        </a>
                    @endauth
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 rounded-2xl border border-gray-200 dark:border-gray-800 font-bold text-lg hover:bg-white dark:hover:bg-gray-900 transition-all text-gray-500">
                        Checklist Specs
                    </a>
                </div>

                <div class="mt-32 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto pb-20">
                    <div class="p-8 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 text-left shadow-sm hover:border-indigo-500/50 transition-colors">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-2xl mb-6">⚡</div>
                        <h3 class="font-black uppercase tracking-tight mb-3 dark:text-white">Pure Performance</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed font-medium">Native AJAX integration for a seamless "No-Reload" experience.</p>
                    </div>
                    
                    <div class="p-8 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 text-left shadow-sm hover:border-indigo-500/50 transition-colors">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-2xl mb-6">🛡️</div>
                        <h3 class="font-black uppercase tracking-tight mb-3 dark:text-white">Secure Admin</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed font-medium">Role-based access control with a dedicated Admin Panel for oversight.</p>
                    </div>

                    <div class="p-8 rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 text-left shadow-sm hover:border-indigo-500/50 transition-colors">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-2xl mb-6">🌙</div>
                        <h3 class="font-black uppercase tracking-tight mb-3 dark:text-white">Stormy UI</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed font-medium">Native dark mode support that adapts to your system preferences.</p>
                    </div>
                </div>
            </main>

            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

            <footer class="mt-auto py-10 text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] opacity-40">
                &copy; {{ date('Y') }} Stormbreaker Portal System // TaskMaster v2.0
            </footer>
        </div>
    </body>
</html>