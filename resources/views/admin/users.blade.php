<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 dark:text-white uppercase tracking-tighter italic">
                Personnel <span class="text-indigo-600">Authority</span> Registry
            </h2>
            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] bg-indigo-500/10 px-4 py-1.5 rounded-full border border-indigo-500/20">
                Lvl: {{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'Student' }}
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">
            
            @if(session('status'))
                <div class="mb-8 p-5 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest italic shadow-lg">
                    ⚡ {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-200 dark:border-gray-800 overflow-hidden shadow-2xl">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/[0.02] border-b border-gray-100 dark:border-gray-800">
                            <th class="p-8 text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 italic">User Identity</th>
                            <th class="p-8 text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 italic">Current Rank</th>
                            <th class="p-8 text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 text-right italic">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($users as $user)
                        <tr class="group hover:bg-indigo-500/[0.01] transition-all">
                            <td class="p-8">
                                <p class="font-black text-xl dark:text-white italic uppercase tracking-tighter">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $user->email }}</p>
                            </td>
                            <td class="p-8">
                                @forelse($user->roles as $role)
                                    <span class="text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest italic bg-indigo-600 text-white shadow-md mr-1">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest italic bg-gray-200 dark:bg-gray-800 text-gray-400">Student</span>
                                @endforelse
                            </td>
                            <td class="p-8 text-right">
                                @if(Auth::user()->hasRole('superadmin'))
                                    <button type="button" 
                                        onclick="confirmRoleChange({{ $user->id }}, '{{ $user->name }}', {{ $user->roles->pluck('id') }})"
                                        class="bg-red-600 text-white font-black text-[10px] uppercase tracking-widest px-6 py-2.5 rounded-xl hover:scale-105 transition-all shadow-lg shadow-red-500/20 border-none">
                                        Modify Rank
                                    </button>

                                    <form id="role-form-{{ $user->id }}" action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                @else
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic opacity-50">View Only</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmRoleChange(userId, userName, currentRoleIds) {
            Swal.fire({
                title: 'AUTHORITY OVERRIDE',
                text: `Update clearance level for: ${userName}`,
                html: `
                    <div class="text-left space-y-2 p-4">
                        @foreach($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" id="role_{{ $role->id }}" value="{{ $role->id }}" class="role-cb rounded text-indigo-600 focus:ring-indigo-500 mr-2" ${currentRoleIds.includes({{ $role->id }}) ? 'checked' : ''}>
                            <label for="role_{{ $role->id }}" class="text-sm font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">{{ $role->name }}</label>
                        </div>
                        @endforeach
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#1f2937',
                confirmButtonText: 'CONFIRM CHANGES',
                background: document.documentElement.classList.contains('dark') ? '#111827' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1f2937',
                preConfirm: () => {
                    const selected = Array.from(document.querySelectorAll('.role-cb:checked')).map(el => el.value);
                    if (selected.length === 0) {
                        Swal.showValidationMessage('User must have at least one rank');
                    }
                    return selected;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('role-form-' + userId);
                    form.innerHTML = '@csrf'; // reset
                    result.value.forEach(roleId => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'role_ids[]';
                        input.value = roleId;
                        form.appendChild(input);
                    });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>