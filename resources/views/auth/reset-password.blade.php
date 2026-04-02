<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black uppercase italic tracking-tighter dark:text-white">
            Secure <span class="text-indigo-600 dark:text-indigo-500">Reset</span>
        </h2>
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Update your portal credentials</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest" />
            <x-text-input id="email" 
                class="block mt-1 w-full bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all" 
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
                required 
                autofocus 
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('New Password')" class="dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest" />
            <x-text-input id="password" 
                class="block mt-1 w-full bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="dark:text-gray-400 font-bold uppercase text-[10px] tracking-widest" />
            <x-text-input id="password_confirmation" 
                class="block mt-1 w-full bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 rounded-xl transition-all"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-8">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase tracking-widest py-3 rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95 italic">
                {{ __('Update Credentials') }}
            </button>
        </div>
    </form>
</x-guest-layout>