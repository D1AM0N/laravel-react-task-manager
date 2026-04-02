<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
            .swal2-popup { font-family: 'Inter', sans-serif !important; border-radius: 1.25rem !important; }
        </style>
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 tracking-tight">Admin Management Console</h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 mb-8 border border-gray-200 dark:border-gray-800 shadow-sm">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Assign New Task</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" id="taskTitle" placeholder="Task objective..." class="rounded-lg border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <select id="targetStudent" class="rounded-lg border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 focus:border-indigo-500"></select>
                    <input type="date" id="taskDate" class="rounded-lg border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <button onclick="deployTask()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm shadow-md shadow-indigo-500/20 transition-all active:scale-95">Assign Task</button>
                </div>
            </div>

            <div class="mb-8">
                <div class="relative max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" id="studentSearch" placeholder="Search students by name..." onkeyup="render()" class="w-full pl-10 rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div id="adminPanelContent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        let masterData = { students: [] };
        let cardFilters = {}; 

        // Global Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            background: document.documentElement.classList.contains('dark') ? '#111827' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#111827',
        });

        async function fetchMasterData() {
            const res = await fetch('/api/admin/summary');
            masterData = await res.json();
            
            const select = document.getElementById('targetStudent');
            select.innerHTML = `<option value="{{ auth()->id() }}">Self (Me)</option>` + 
                masterData.students.filter(s => s.id != "{{ auth()->id() }}")
                          .map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            render();
        }

        async function deployTask() {
            const title = document.getElementById('taskTitle').value;
            const user_id = document.getElementById('targetStudent').value;
            const due_date = document.getElementById('taskDate').value;
            
            if(!title) {
                return Toast.fire({ icon: 'error', title: 'Task objective is required' });
            }

            const res = await fetch('/api/tasks', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ title, user_id, due_date })
            });

            if(res.ok) {
                document.getElementById('taskTitle').value = '';
                Toast.fire({ icon: 'success', title: 'Task successfully assigned' });
                fetchMasterData();
            }
        }

        async function updateTask(id, field, value) {
            const res = await fetch(`/api/tasks/${id}`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
                body: JSON.stringify({ [field]: value })
            });
            
            if(res.ok) {
                Toast.fire({ icon: 'info', title: 'Task updated', timer: 1500 });
                fetchMasterData();
            }
        }

        async function deleteTask(id) {
            const result = await Swal.fire({
                title: 'Confirm Deletion',
                text: "This task will be permanently removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Indigo-600
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete Task',
                background: document.documentElement.classList.contains('dark') ? '#111827' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#111827',
            });

            if (result.isConfirmed) {
                const res = await fetch(`/api/tasks/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } });
                if(res.ok) {
                    Toast.fire({ icon: 'success', title: 'Task removed' });
                    fetchMasterData();
                }
            }
        }

        function setLocalFilter(studentId, status) {
            cardFilters[studentId] = status;
            render();
        }

        function render() {
            const container = document.getElementById('adminPanelContent');
            const searchQuery = document.getElementById('studentSearch').value.toLowerCase();
            container.innerHTML = '';

            masterData.students.filter(s => s.name.toLowerCase().includes(searchQuery)).forEach(student => {
                const currentFilter = cardFilters[student.id] || 'All';
                const tasks = student.tasks || [];
                
                let filteredTasks = tasks.filter(t => {
                    if (currentFilter === 'All') return true;
                    if (currentFilter === 'Overdue') {
                        return t.due_date && new Date(t.due_date) < new Date() && t.status !== 'Completed';
                    }
                    return t.status === currentFilter;
                });

                const completed = tasks.filter(t => t.status === 'Completed').length;
                const percent = tasks.length > 0 ? Math.round((completed / tasks.length) * 100) : 0;

                const card = document.createElement('div');
                card.className = "bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col h-full hover:border-indigo-500/50 transition-colors";
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 tracking-tight">${student.name}</h4>
                        <div class="flex bg-gray-50 dark:bg-gray-800 p-1 rounded-lg text-[9px] space-x-1 border dark:border-gray-700">
                            ${['All', 'Pending', 'Completed', 'Overdue'].map(f => `
                                <button onclick="setLocalFilter(${student.id}, '${f}')" 
                                    class="px-2 py-1 rounded transition-all ${currentFilter === f ? 'bg-white dark:bg-gray-700 shadow-sm font-bold text-indigo-600' : 'text-gray-400 font-medium'}">
                                    ${f}
                                </button>
                            `).join('')}
                        </div>
                    </div>

                    <div class="mb-5">
                        <div class="flex justify-between text-[10px] font-bold text-gray-400 mb-1 tracking-widest uppercase">
                            <span>Completion</span><span>${percent}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-indigo-600 h-full transition-all duration-500" style="width: ${percent}%"></div>
                        </div>
                    </div>

                    <div class="space-y-3 flex-1 overflow-y-auto max-h-72 pr-1 custom-scrollbar">
                        ${filteredTasks.length ? filteredTasks.map(t => `
                            <div class="bg-gray-50 dark:bg-gray-800/40 p-3 rounded-xl border border-gray-100 dark:border-gray-700 group hover:shadow-md transition-all">
                                <input type="text" value="${t.title}" onblur="updateTask(${t.id}, 'title', this.value)" 
                                    class="bg-transparent border-none p-0 w-full text-xs font-semibold text-gray-800 dark:text-gray-200 focus:ring-0">
                                <div class="flex justify-between items-center mt-3">
                                    <select onchange="updateTask(${t.id}, 'status', this.value)" class="text-[10px] bg-indigo-50 dark:bg-indigo-900/20 px-2 py-0.5 rounded border-none font-bold text-indigo-600 dark:text-indigo-400 focus:ring-0">
                                        <option value="Pending" ${t.status === 'Pending' ? 'selected' : ''}>Pending</option>
                                        <option value="In Progress" ${t.status === 'In Progress' ? 'selected' : ''}>Active</option>
                                        <option value="Completed" ${t.status === 'Completed' ? 'selected' : ''}>Done</option>
                                    </select>
                                    <button onclick="deleteTask(${t.id})" class="opacity-0 group-hover:opacity-100 text-[10px] text-gray-400 hover:text-red-500 transition-all font-bold uppercase tracking-tighter">Delete</button>
                                </div>
                            </div>
                        `).join('') : '<p class="text-xs text-center text-gray-400 py-6 italic">No tasks found.</p>'}
                    </div>
                `;
                container.appendChild(card);
            });
        }
        fetchMasterData();
    </script>
</x-app-layout>