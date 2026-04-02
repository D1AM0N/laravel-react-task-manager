<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-800 dark:text-white leading-tight uppercase tracking-tighter italic">
                {{ Auth::user()->hasRole('admin') ? 'Admin Operations' : 'My Assignments' }}
            </h2>
            <div class="hidden md:block">
                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] bg-indigo-500/10 px-3 py-1 rounded-full">
                    System Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen transition-colors duration-500">
        <div class="max-w-4xl mx-auto px-4">
            
            <div class="mb-8 bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm transition-all hover:shadow-indigo-500/5">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-4 gap-4 text-center md:text-left">
                    <div>
                        <p class="text-[10px] font-black text-indigo-600 dark:text-indigo-500 uppercase tracking-[0.2em] mb-1">Performance Index</p>
                        <h3 id="progressText" class="text-5xl font-black dark:text-white italic tracking-tighter">0%</h3>
                    </div>
                    <div class="text-center md:text-right">
                        <p id="taskCount" class="text-xs font-bold text-gray-400 uppercase tracking-tight">Initializing Tasks...</p>
                    </div>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 h-4 rounded-full overflow-hidden p-1">
                    <div id="progressBar" class="bg-indigo-600 h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_20px_rgba(79,70,229,0.4)]" style="width: 0%"></div>
                </div>
            </div>

            <div id="taskList" class="grid gap-4">
                <div class="flex justify-center py-20">
                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-500"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const currentUserId = {{ Auth::id() }};
        let tasks = [];

        // Stormbreaker Custom Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: document.documentElement.classList.contains('dark') ? '#111827' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        async function loadTasks() {
            try {
                const res = await fetch('/api/tasks');
                const allTasks = await res.json();
                
                // Logic: Admins see everything, Students see their own.
                // We use your filter logic but allow for Admin oversight if needed.
                @if(Auth::user()->hasRole('admin'))
                    tasks = allTasks;
                @else
                    tasks = allTasks.filter(t => t.user_id == currentUserId);
                @endif
                
                renderUI();
            } catch (err) { 
                console.error("Load Failed", err); 
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Lost',
                    text: 'Unable to reach the TaskMaster API.',
                    confirmButtonColor: '#4f46e5'
                });
            }
        }

        async function updateTask(id, payload) {
            const task = tasks.find(t => t.id === id);
            const newStatus = payload.status;

            try {
                const res = await fetch(`/api/tasks/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'X-CSRF-TOKEN': csrf, 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) { 
                    if (newStatus === 'Completed') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Task Finalized',
                            text: `"${task.title}" was marked as done.`,
                        });
                    } else {
                        Toast.fire({
                            icon: 'info',
                            title: 'Task Reopened',
                            text: `"${task.title}" moved to pending.`,
                        });
                    }
                    loadTasks(); 
                }
            } catch (err) {
                console.error("Update error", err);
            }
        }

        function renderUI() {
            const list = document.getElementById('taskList');
            const total = tasks.length;
            const done = tasks.filter(t => t.status === 'Completed').length;
            const percent = total > 0 ? Math.round((done / total) * 100) : 0;

            document.getElementById('progressBar').style.width = `${percent}%`;
            document.getElementById('progressText').innerText = `${percent}%`;
            document.getElementById('taskCount').innerText = `${done} OF ${total} OBJECTIVES CLEARED`;

            if (tasks.length === 0) {
                list.innerHTML = `
                    <div class="bg-white dark:bg-gray-900 rounded-[2rem] p-16 border border-dashed border-gray-300 dark:border-gray-800 text-center shadow-inner">
                        <p class="text-gray-400 font-black italic text-sm uppercase tracking-[0.3em]">No Missions Assigned</p>
                    </div>`;
                return;
            }

            list.innerHTML = tasks.map(t => {
                const isOverdue = t.due_date && new Date(t.due_date) < new Date() && t.status !== 'Completed';
                const isCompleted = t.status === 'Completed';
                
                return `
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border transition-all duration-500 ${isOverdue ? 'border-red-500/40 bg-red-500/[0.02]' : 'border-gray-200 dark:border-gray-800'} flex justify-between items-center group hover:shadow-xl hover:shadow-indigo-500/5 hover:border-indigo-500/40">
                    <div class="flex-1">
                        <p class="font-black text-xl tracking-tight dark:text-white transition-all duration-500 ${isCompleted ? 'line-through opacity-20 grayscale' : ''}">${t.title}</p>
                        
                        <div class="flex items-center gap-4 mt-2">
                            ${t.due_date ? `
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black uppercase tracking-widest ${isOverdue ? 'text-red-500 animate-pulse' : 'text-gray-400'}">
                                        ${isOverdue ? '⚠️ Overdue' : 'Due'}: ${new Date(t.due_date).toLocaleDateString()}
                                    </span>
                                </div>` : ''}
                            
                            ${isCompleted ? `
                                <span class="text-[9px] font-black bg-green-500/10 text-green-500 px-3 py-1 rounded-full uppercase italic tracking-widest">
                                    Verified
                                </span>` : ''}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <select onchange="updateTask(${t.id}, {status: this.value})" 
                                class="text-[10px] font-black uppercase tracking-widest rounded-xl border-none bg-gray-100 dark:bg-gray-800 text-indigo-600 focus:ring-2 focus:ring-indigo-500 py-2.5 px-4 cursor-pointer transition-all hover:bg-indigo-500 hover:text-white dark:hover:bg-indigo-600">
                            <option value="Pending" ${t.status === 'Pending' ? 'selected' : ''}>Pending</option>
                            <option value="Completed" ${t.status === 'Completed' ? 'selected' : ''}>Complete</option>
                        </select>
                    </div>
                </div>`;
            }).join('');
        }

        loadTasks();
    </script>
</x-app-layout>