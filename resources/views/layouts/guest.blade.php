<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>TaskMaster | Secure Access</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-gray-950 px-4">
            
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center group">
                    <span class="text-4xl font-black tracking-tighter uppercase italic transition-all duration-300 group-hover:scale-105">
                        <span class="text-indigo-600 dark:text-indigo-500">Task</span><span class="text-gray-900 dark:text-white">Master</span>
                    </span>
                    <div class="h-1 w-12 bg-indigo-600 dark:bg-indigo-500 rounded-full mt-1 transition-all duration-500 group-hover:w-24"></div>
                </a>
            </div>

            <div class="w-full sm:max-w-md p-8 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-2xl shadow-indigo-500/10 rounded-3xl overflow-hidden">
                {{ $slot }}
            </div>

            <p class="mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] opacity-50">
                &copy; {{ date('Y') }} Stormbreaker Portal System
            </p>
        </div>
    </body>
</html>