@php
    $pageTitle = 'Feedback';
    $pageSubtitle = 'Student feedback and system comments';
@endphp

<x-admin-layout>
    <div class="space-y-6" x-data="{
        search: '{{ request('q') }}',
        
        async markResolved(id) {
            if (!confirm('Mark this feedback as resolved?')) return;
            
            try {
                const response = await fetch(`/teacher/feedback/${id}/resolve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ _method: 'PATCH' })
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update feedback status');
            }
        },

        async deleteFeedback(id) {
            if (!confirm('Delete this feedback?')) return;
            
            try {
                const response = await fetch(`/teacher/feedback/${id}`, {
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
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete feedback');
            }
        }
    }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Student Feedback</h2>
                <p class="text-slate-400 mt-1">Review and manage feedback submitted by students</p>
            </div>
            
            <form method="GET" class="flex gap-2 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" 
                           name="q" 
                           x-model="search"
                           placeholder="Search feedback..." 
                           class="w-full px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Feedback Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm font-medium">Total Feedback</p>
                        <p class="text-2xl font-bold text-white">{{ $feedbacks->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/10 rounded-xl text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm font-medium">Recent (7 days)</p>
                        <p class="text-2xl font-bold text-white">
                            {{ $feedbacks->filter(fn($f) => $f->created_at->gt(now()->subDays(7)))->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm font-medium">Resolved</p>
                        <p class="text-2xl font-bold text-white">
                            {{ $feedbacks->filter(fn($f) => $f->status === 'resolved')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="bg-slate-900/50 text-xs uppercase font-medium text-slate-300">
                        <tr>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Message</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Submitted</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($feedbacks as $fb)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-xs">
                                            {{ substr($fb->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-white">{{ $fb->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $fb->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xl text-slate-300">{{ $fb->message }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                        {{ $fb->type === 'bug' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                        {{ $fb->type === 'suggestion' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                                        {{ $fb->type === 'other' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                    ">
                                        {{ ucfirst($fb->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-white">{{ $fb->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-slate-500">{{ $fb->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if($fb->status !== 'resolved')
                                            <button @click="markResolved({{ $fb->id }})" 
                                                    class="p-2 hover:bg-emerald-500/10 text-slate-400 hover:text-emerald-400 rounded-lg transition-colors"
                                                    title="Mark as Resolved">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        @endif
                                        <button @click="deleteFeedback({{ $fb->id }})" 
                                                class="p-2 hover:bg-red-500/10 text-slate-400 hover:text-red-400 rounded-lg transition-colors"
                                                title="Delete Feedback">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        </div>
                                        <p class="text-slate-400 font-medium">No feedback found</p>
                                        @if(request('q'))
                                            <p class="text-slate-500 text-sm mt-1">Try adjusting your search terms</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($feedbacks->hasPages())
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
