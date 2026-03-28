<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskMaster | Minimalist Task Management</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-[#FDFDFC] text-[#1B1B18] dark:bg-[#121212] dark:text-white transition-colors duration-300">
        <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
            
            <nav class="absolute top-0 w-full p-6 flex justify-between items-center max-w-7xl mx-auto">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-black dark:bg-white rounded-lg flex items-center justify-center">
                        <span class="text-white dark:text-black font-bold">T</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight">TaskMaster</span>
                </div>

                <div class="flex gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-full border border-black dark:border-white font-medium hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2 font-medium hover:text-gray-500 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-black text-white dark:bg-white dark:text-black font-medium hover:opacity-80 transition">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>

            <main class="text-center px-6 mt-20">
                <span class="px-4 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-sm font-medium mb-6 inline-block">
                    🚀 Now with React & Laravel API support
                </span>
                <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6">
                    Manage tasks with <br/>
                    <span class="text-gray-400">zero friction.</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400 mb-10 leading-relaxed">
                    A high-performance task management tool built for speed. 
                    Real-time updates, dark mode support, and seamless API integration.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-black text-white dark:bg-white dark:text-black font-semibold text-lg shadow-lg hover:scale-105 transition-transform">
                        Create Your First Task
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 rounded-full border border-gray-200 dark:border-gray-800 font-semibold text-lg hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                        View Assessment Checklist
                    </a>
                </div>

                <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto pb-20">
                    <div class="p-8 rounded-2xl border border-gray-100 dark:border-gray-800 text-left">
                        <div class="mb-4 text-2xl">⚡</div>
                        <h3 class="font-bold mb-2">React Engine</h3>
                        <p class="text-gray-500 text-sm">Dynamic UI rendering using React hooks and AJAX for a "No-Reload" experience.</p>
                    </div>
                    <div class="p-8 rounded-2xl border border-gray-100 dark:border-gray-800 text-left">
                        <div class="mb-4 text-2xl">🛡️</div>
                        <h3 class="font-bold mb-2">Laravel Backend</h3>
                        <p class="text-gray-500 text-sm">Secure RESTful API routes with full authentication and per-user authorization.</p>
                    </div>
                    <div class="p-8 rounded-2xl border border-gray-100 dark:border-gray-800 text-left">
                        <div class="mb-4 text-2xl">🌙</div>
                        <h3 class="font-bold mb-2">Modern UX</h3>
                        <p class="text-gray-500 text-sm">Native dark mode support and live search filtering built directly into the UI.</p>
                    </div>
                </div>
            </main>

            <footer class="mt-auto py-10 text-gray-400 text-sm">
                &copy; {{ date('Y') }} Full-Stack Assessment Project.
            </footer>
        </div>
    </body>
</html>