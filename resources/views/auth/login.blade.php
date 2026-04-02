<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Welcome Back</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">Access your Stormbreaker workspace</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1 ml-1">Email Address</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required autofocus 
                   class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-800 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                   placeholder="name@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1 ml-1">
                <label for="password" class="block text-[10px] font-black text-indigo-500 uppercase tracking-widest">Security Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-bold text-gray-400 hover:text-indigo-500 transition-colors" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-800 dark:text-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                   placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 dark:border-gray-800 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-950" name="remember">
                <span class="ms-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">Stay logged in</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-2xl text-sm shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98]">
                Log In
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">
            New here? 
            <a href="{{ route('register') }}" class="text-indigo-500 hover:text-indigo-400 transition-colors">Create Account</a>
        </p>
    @endif
</x-guest-layout>