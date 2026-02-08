@php
    $pageTitle = 'Teacher Management';
    $pageSubtitle = 'Manage teacher and admin accounts';
@endphp

<x-admin-layout>
    <div class="space-y-6" x-data="{
        modalOpen: false,
        isEditing: false,
        adminId: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        errors: {},
        
        openCreateModal() {
            this.modalOpen = true;
            this.isEditing = false;
            this.adminId = '';
            this.name = '';
            this.email = '';
            this.password = '';
            this.password_confirmation = '';
            this.errors = {};
        },

        openEditModal(id, name, email) {
            this.modalOpen = true;
            this.isEditing = true;
            this.adminId = id;
            this.name = name;
            this.email = email;
            this.password = '';
            this.password_confirmation = '';
            this.errors = {};
        },

        async saveTeacher() {
            this.errors = {};
            const url = this.isEditing ? `/teacher/admins/${this.adminId}` : '{{ route('teacher.admins.store') }}';
            // Laravel requires POST method with _method field for PATCH/PUT
            // But fetch method should be POST
            
            try {
                const response = await fetch(url, {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        _method: this.isEditing ? 'PATCH' : 'POST',
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                    return;
                }
                
                window.location.reload();
            } catch (error) {
                console.error('Error:', error);
                alert('An unexpected error occurred');
            }
        },

        async deleteTeacher(id) {
            if (!confirm('Are you sure you want to delete this teacher?')) return;
            
            try {
                const response = await fetch(`/teacher/admins/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to delete teacher');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            }
        }
    }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Teacher Management</h2>
                <p class="text-slate-400 mt-1">{{ $admins->total() }} total teachers</p>
            </div>
            <button @click="openCreateModal()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Teacher
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <input type="text" name="q" placeholder="Search by name or email..." value="{{ request('q') }}" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                    <div class="absolute right-3 top-3 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <select name="status" class="w-full px-4 py-2.5 bg-slate-900/50 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                    <option value="" class="bg-slate-900">All Status</option>
                    <option value="active" class="bg-slate-900" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" class="bg-slate-900" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-semibold transition-colors">Search</button>
            </form>
        </div>

        <!-- Teachers Table -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                        <tr>
                            <th class="px-6 py-4">Teacher</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Joined</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($admins as $admin)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-sm border border-indigo-500/30">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-white">{{ $admin->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $admin->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-lg text-xs font-bold uppercase tracking-wider">Teacher</span>
                                </td>
                                <td class="px-6 py-4">{{ $admin->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openEditModal({{ $admin->id }}, '{{ addslashes($admin->name) }}', '{{ $admin->email }}')" 
                                                class="p-2 hover:bg-indigo-500/10 text-slate-400 hover:text-indigo-400 rounded-lg transition-colors"
                                                title="Edit Teacher">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button @click="deleteTeacher({{ $admin->id }})" 
                                                class="p-2 hover:bg-red-500/10 text-slate-400 hover:text-red-400 rounded-lg transition-colors"
                                                title="Delete Teacher">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    No teachers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($admins->hasPages())
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="modalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="modalOpen = false"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-slate-900 border border-white/10 rounded-2xl shadow-2xl p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    <h3 class="text-xl font-bold text-white mb-6" x-text="isEditing ? 'Edit Teacher' : 'Add Teacher'"></h3>
                    
                    <form @submit.prevent="saveTeacher" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Full Name</label>
                            <input type="text" x-model="name" class="w-full px-4 py-2.5 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <p x-show="errors.name" x-text="errors.name?.[0]" class="text-red-400 text-xs mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Email Address</label>
                            <input type="email" x-model="email" class="w-full px-4 py-2.5 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <p x-show="errors.email" x-text="errors.email?.[0]" class="text-red-400 text-xs mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">
                                Password
                                <span x-show="isEditing" class="text-xs text-slate-500 font-normal ml-1">(Leave blank to keep current)</span>
                            </label>
                            <input type="password" x-model="password" class="w-full px-4 py-2.5 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                            <p x-show="errors.password" x-text="errors.password?.[0]" class="text-red-400 text-xs mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Confirm Password</label>
                            <input type="password" x-model="password_confirmation" class="w-full px-4 py-2.5 bg-slate-800 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all">
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="modalOpen = false" class="flex-1 px-4 py-2.5 border border-white/10 rounded-xl text-slate-300 font-medium hover:bg-white/5 transition-colors">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold shadow-lg shadow-indigo-500/25 transition-all">Save Teacher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
