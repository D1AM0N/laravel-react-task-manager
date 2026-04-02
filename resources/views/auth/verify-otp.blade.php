<x-guest-layout>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .otp-input:focus { border-color: #4f46e5 !important; --tw-ring-color: #4f46e5 !important; letter-spacing: 0.5em; }
    </style>

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase text-gray-800 dark:text-white">
            <span class="text-indigo-600 dark:text-indigo-500">Security</span> Check
        </h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">
            Verification required to access Stormbreaker
        </p>
    </div>

    <div class="mb-6 p-4 bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800/50 rounded-xl">
        <p class="text-xs font-medium text-indigo-700 dark:text-indigo-300 leading-relaxed text-center">
            {{ __('A 6-digit code was sent to your encrypted email. Enter it below to unlock your account.') }}
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-lg text-[11px] font-bold text-green-600 dark:text-green-400 uppercase tracking-tight text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.submit') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="otp_code" :value="__('Verification Code')" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2" />
            <x-text-input id="otp_code" 
                class="block w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-center text-2xl font-black tracking-[0.3em] py-3 focus:ring-indigo-500 transition-all otp-input" 
                type="text" 
                name="otp_code" 
                required 
                autofocus 
                maxlength="6"
                placeholder="000000" />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2 text-[10px] font-bold uppercase" />
        </div>

        <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-black uppercase text-xs tracking-widest rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95">
            {{ __('Authenticate Account') }}
        </x-primary-button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col items-center gap-4">
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                {{ __('Request New Code') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-[9px] font-bold uppercase tracking-tighter text-gray-400 hover:text-red-500 transition-colors">
                {{ __('Abort & Terminate Session') }}
            </button>
        </form>
    </div>
</x-guest-layout>