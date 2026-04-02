<x-guest-layout>
    <style>
        body { font-family: 'Inter', sans-serif; }
        input:focus { border-color: #4f46e5 !important; --tw-ring-color: #4f46e5 !important; }
    </style>

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase text-gray-800 dark:text-white">
            <span class="text-indigo-600 dark:text-indigo-500">Join</span> TaskMaster
        </h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Create your operative account</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 shadow-sm transition-all" 
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-[10px] font-bold uppercase" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 shadow-sm transition-all" 
                type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="john@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] font-bold uppercase" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Security Password')" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 shadow-sm transition-all"
                type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] font-bold uppercase" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Verify Password')" class="text-[10px] font-black uppercase tracking-widest text-gray-400" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 shadow-sm transition-all"
                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[10px] font-bold uppercase" />
        </div>

        <div class="flex flex-col gap-4 items-center justify-end mt-8">
            <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-black uppercase text-xs tracking-widest rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
                {{ __('Initialize Account') }}
            </x-primary-button>

            <a class="text-[10px] font-bold uppercase tracking-tighter text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" href="{{ route('login') }}">
                {{ __('Return to Secure Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>