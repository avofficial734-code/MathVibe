@php
    $pageTitle = '👥 Students';
    $pageSubtitle = 'Manage student accounts and view performance';
@endphp

<x-admin-layout>
    <div x-data="{ 
        modalOpen: false, 
        isEditing: false, 
        studentId: '', 
        name: '', 
        email: '', 
        errors: {},
        
        openCreateModal() {
            this.modalOpen = true;
            this.isEditing = false;
            this.studentId = '';
            this.name = '';
            this.email = '';
            this.errors = {};
        },
        
        openEditModal(id, name, email) {
            this.modalOpen = true;
            this.isEditing = true;
            this.studentId = id;
            this.name = name;
            this.email = email;
            this.errors = {};
        },
        
        closeModal() {
            this.modalOpen = false;
        },

        async submitForm() {
            this.errors = {};
            const form = document.getElementById('studentForm');
            const formData = new FormData(form);
            
            if (this.isEditing) {
                formData.append('_method', 'PUT');
            }

            try {
                const url = this.isEditing ? `/teacher/students/${this.studentId}` : '{{ route('teacher.students.store') }}';
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.errors) {
                    this.errors = data.errors;
                } else {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },

        deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/teacher/students/${id}`;
                form.innerHTML = '<input type=\'hidden\' name=\'_token\' value=\'{{ csrf_token() }}\'><input type=\'hidden\' name=\'_method\' value=\'DELETE\'>';
                document.body.appendChild(form);
                form.submit();
            }
        }
    }">
        <div class="space-y-6">
            <!-- Header with Create Button -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Student Management</h2>
                    <p class="text-slate-400 mt-1">{{ $students->total() }} total students</p>
                </div>
                <button @click="openCreateModal()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Student
                </button>
            </div>

            <!-- Search and Filter -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="q" placeholder="Search by name or email..." value="{{ request('q') }}" class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                        </div>
                        
                        <div class="relative">
                            <select name="status" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white appearance-none focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                                <option value="" class="bg-slate-900">All Status</option>
                                <option value="active" class="bg-slate-900" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" class="bg-slate-900" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <button type="submit" class="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-medium transition-colors border border-white/5">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Students Table -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                            <tr>
                                <th class="px-6 py-4">Student</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Sessions</th>
                                <th class="px-6 py-4">Avg Score</th>
                                <th class="px-6 py-4">Joined</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($students as $student)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-white">{{ $student->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $student->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-700 text-slate-300">
                                            {{ $student->game_sessions_count ?? 0 }} plays
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold {{ ($student->game_sessions_avg_score ?? 0) >= 80 ? 'text-emerald-400' : (($student->game_sessions_avg_score ?? 0) >= 50 ? 'text-amber-400' : 'text-red-400') }}">
                                                {{ round($student->game_sessions_avg_score ?? 0, 1) }}%
                                            </span>
                                            <div class="w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ ($student->game_sessions_avg_score ?? 0) >= 80 ? 'bg-emerald-500' : (($student->game_sessions_avg_score ?? 0) >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $student->game_sessions_avg_score ?? 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $student->created_at->format('M j, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('teacher.students.progress', $student) }}" class="p-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-400/10 rounded-lg transition-colors" title="View Progress">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            </a>
                                            <button @click="openEditModal({{ $student->id }}, {{ json_encode($student->name) }}, '{{ $student->email }}')" class="p-2 text-amber-400 hover:text-amber-300 hover:bg-amber-400/10 rounded-lg transition-colors" title="Edit Student">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button @click="deleteStudent({{ $student->id }})" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors" title="Delete Student">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                        No students found matching your criteria
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($students->hasPages())
                    <div class="px-6 py-4 border-t border-white/5 bg-slate-900/30">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="modalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="modalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" 
                 @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-slate-800 border border-white/10 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500/10 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-semibold leading-6 text-white" id="modal-title" x-text="isEditing ? 'Edit Student' : 'Add Student'"></h3>
                                <div class="mt-4">
                                    <form id="studentForm" @submit.prevent="submitForm" class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium leading-6 text-slate-300">Full Name</label>
                                            <div class="mt-1">
                                                <input type="text" name="name" id="name" x-model="name" class="block w-full rounded-xl border-0 bg-slate-900/50 py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="John Doe">
                                            </div>
                                            <p x-show="errors.name" x-text="errors.name && errors.name[0]" class="mt-1 text-sm text-red-400"></p>
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-medium leading-6 text-slate-300">Email Address</label>
                                            <div class="mt-1">
                                                <input type="email" name="email" id="email" x-model="email" class="block w-full rounded-xl border-0 bg-slate-900/50 py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="john@example.com">
                                            </div>
                                            <p x-show="errors.email" x-text="errors.email && errors.email[0]" class="mt-1 text-sm text-red-400"></p>
                                        </div>

                                        <div>
                                            <label for="password" class="block text-sm font-medium leading-6 text-slate-300">
                                                Password
                                                <span x-show="isEditing" class="text-xs text-slate-500 font-normal ml-1">(Leave blank to keep current)</span>
                                            </label>
                                            <div class="mt-1">
                                                <input type="password" name="password" id="password" class="block w-full rounded-xl border-0 bg-slate-900/50 py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="••••••••">
                                            </div>
                                            <p x-show="errors.password" x-text="errors.password && errors.password[0]" class="mt-1 text-sm text-red-400"></p>
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium leading-6 text-slate-300">Confirm Password</label>
                                            <div class="mt-1">
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-xl border-0 bg-slate-900/50 py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-slate-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6" placeholder="••••••••">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-900/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="submitForm()" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">Save Changes</button>
                        <button type="button" @click="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-slate-700 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-white/10 hover:bg-slate-600 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
