<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-white leading-tight">
                {{ Auth::user()->is_admin ? 'Admin: My Personal Tasks' : 'My Assignments' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div id="progressSection" class="mb-8 bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex justify-between items-end mb-2">
                    <div>
                        <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Workspace Progress</p>
                        <h3 id="progressText" class="text-2xl font-bold dark:text-white">0%</h3>
                    </div>
                    <p id="taskCount" class="text-xs font-bold text-gray-400">0 of 0 Completed</p>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 h-3 rounded-full overflow-hidden">
                    <div id="progressBar" class="bg-indigo-600 h-full transition-all duration-700 shadow-[0_0_15px_rgba(79,70,229,0.4)]" style="width: 0%"></div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mb-8 justify-between items-center">
                <div class="flex bg-white dark:bg-gray-900 p-1 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm w-fit">
                    <button onclick="setFilter('All')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all" data-f="All">All</button>
                    <button onclick="setFilter('Pending')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all" data-f="Pending">Pending</button>
                    <button onclick="setFilter('Overdue')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all text-red-500" data-f="Overdue">Overdue</button>
                    <button onclick="setFilter('Completed')" class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all" data-f="Completed">Done</button>
                </div>
                <input type="text" id="selfSearch" placeholder="Search my tasks..." onkeyup="render()" class="w-full md:w-64 rounded-lg border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white text-sm focus:ring-indigo-500">
            </div>

            <div id="taskList" class="grid gap-4"></div>
        </div>
    </div>

    <div id="toast" class="fixed bottom-6 right-6 hidden bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-2xl font-bold text-sm z-50"></div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const isAdmin = {{ Auth::user()->is_admin ? 'true' : 'false' }};
        let tasks = [];
        let currentFilter = 'All';

        function notify(msg) {
            const t = document.getElementById('toast');
            t.innerText = msg; t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3000);
        }

        async function loadTasks() {
            const res = await fetch('/api/tasks');
            tasks = await res.json();
            updateProgress();
            render();
        }

        function updateProgress() {
            const total = tasks.length;
            const done = tasks.filter(t => t.status === 'Completed').length;
            const percent = total > 0 ? Math.round((done / total) * 100) : 0;
            
            document.getElementById('progressBar').style.width = `${percent}%`;
            document.getElementById('progressText').innerText = `${percent}%`;
            document.getElementById('taskCount').innerText = `${done} of ${total} Completed`;
        }

        async function updateTask(id, payload) {
            // Students can't call this, but the UI won't even show the option now.
            if(!isAdmin) return; 
            const res = await fetch(`/api/tasks/${id}`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            if(res.ok) { notify('Task Updated'); loadTasks(); }
        }

        function setFilter(f) { currentFilter = f; render(); }

        function render() {
            const list = document.getElementById('taskList');
            const search = document.getElementById('selfSearch').value.toLowerCase();
            list.innerHTML = '';

            const filtered = tasks.filter(t => {
                const matchesSearch = t.title.toLowerCase().includes(search);
                const isOverdue = t.due_date && new Date(t.due_date) < new Date() && t.status !== 'Completed';
                if (currentFilter === 'All') return matchesSearch;
                if (currentFilter === 'Overdue') return isOverdue && matchesSearch;
                return t.status === currentFilter && matchesSearch;
            });

            filtered.forEach(t => {
                const isOverdue = t.due_date && new Date(t.due_date) < new Date() && t.status !== 'Completed';
                const item = document.createElement('div');
                item.className = `bg-white dark:bg-gray-900 p-5 rounded-2xl border flex justify-between items-center transition-all ${isOverdue ? 'border-red-500 bg-red-50/10' : 'border-gray-200 dark:border-gray-800 shadow-sm'}`;
                
                // 🛡️ Logic: Admin sees Input, Student sees static Paragraph
                const titleBlock = isAdmin 
                    ? `<input type="text" value="${t.title}" onblur="updateTask(${t.id}, {title: this.value})" class="bg-transparent border-none p-0 font-bold dark:text-white w-full focus:ring-0">`
                    : `<p class="font-bold text-gray-900 dark:text-white">${t.title}</p>`;

                // 🛡️ Logic: Admin sees Select, Student sees a Badge
                const statusBlock = isAdmin 
                    ? `<select onchange="updateTask(${t.id}, {status: this.value})" class="text-xs font-bold rounded-lg border-none bg-gray-100 dark:bg-gray-800 text-indigo-600 focus:ring-indigo-500">
                        <option value="Pending" ${t.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="In Progress" ${t.status === 'In Progress' ? 'selected' : ''}>Active</option>
                        <option value="Completed" ${t.status === 'Completed' ? 'selected' : ''}>Done</option>
                       </select>`
                    : `<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600">${t.status}</span>`;

                item.innerHTML = `
                    <div class="flex-1">
                        ${titleBlock}
                        <p class="text-[10px] font-bold ${isOverdue ? 'text-red-500' : 'text-gray-400'} uppercase mt-1">
                            ${isOverdue ? '⚠️ Overdue' : 'Due'}: ${t.due_date || 'No Deadline'}
                        </p>
                    </div>
                    ${statusBlock}
                `;
                list.appendChild(item);
            });
        }
        loadTasks();
    </script>
</x-app-layout>