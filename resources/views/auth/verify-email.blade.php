<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 dark:bg-indigo-900/20 rounded-3xl mb-4 border border-indigo-100 dark:border-indigo-800 shadow-inner">
            <svg class="w-10 h-10 text-indigo-600 shadow-indigo-500/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Enter Security Code</h2>
        <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ __('We sent a 6-digit verification code to your email address.') }}
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
            <ul class="text-xs text-red-600 font-bold uppercase tracking-wider text-center">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('otp.post') }}" class="space-y-8">
        @csrf

        <div class="relative group">
            <x-text-input id="otp" 
                         class="block w-full text-center text-4xl font-black tracking-[0.6em] py-5 border-2 border-gray-200 dark:border-gray-800 dark:bg-gray-950 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm transition-all placeholder:text-gray-200 dark:placeholder:text-gray-800" 
                         type="text" 
                         name="otp" 
                         placeholder="000000"
                         maxlength="6"
                         required 
                         autofocus 
                         autocomplete="one-time-code" />
        </div>

        <div class="flex flex-col gap-4">
            <x-primary-button class="w-full justify-center py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-xl shadow-indigo-500/30 transition-all font-black uppercase text-sm tracking-widest active:scale-95">
                {{ __('Verify & Unlock Dashboard') }}
            </x-primary-button>
            
            <button type="submit" form="logout-form" class="text-center text-[10px] font-black text-gray-400 hover:text-red-500 uppercase tracking-[0.2em] transition-colors">
                {{ __('Cancel and Sign Out') }}
            </button>
        </div>
    </form>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>
</x-guest-layout>