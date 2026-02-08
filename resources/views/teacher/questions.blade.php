@php
    $pageTitle = 'Questions';
    $pageSubtitle = 'Manage game questions and answers';
@endphp

<x-admin-layout>
    <div x-data="questionManager()" x-init="init()" class="space-y-6">
        <!-- Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Search & Filters -->
            <div class="flex flex-wrap gap-3 flex-1">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <input type="text" 
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search questions..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                           @keydown.enter="applyFilters($el.value, 'search')"
                    >
                    <svg class="w-5 h-5 text-slate-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                <select name="topic" 
                        class="px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl text-slate-300 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                        @change="applyFilters($el.value, 'topic')">
                    <option value="">All Topics</option>
                    <option value="addition" {{ request('topic') == 'addition' ? 'selected' : '' }}>Addition</option>
                    <option value="subtraction" {{ request('topic') == 'subtraction' ? 'selected' : '' }}>Subtraction</option>
                    <option value="multiplication" {{ request('topic') == 'multiplication' ? 'selected' : '' }}>Multiplication</option>
                    <option value="division" {{ request('topic') == 'division' ? 'selected' : '' }}>Division</option>
                    <option value="pemdas" {{ request('topic') == 'pemdas' ? 'selected' : '' }}>PEMDAS</option>
                </select>

                <select name="difficulty" 
                        class="px-4 py-2.5 bg-slate-800/50 border border-white/10 rounded-xl text-slate-300 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                        @change="applyFilters($el.value, 'difficulty')">
                    <option value="">All Difficulties</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>

            <button @click="openCreateModal()" 
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Question
            </button>
        </div>

        <!-- Questions Table -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-900/50 text-slate-400 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 font-medium w-2/5">Question</th>
                            <th class="px-6 py-4 font-medium">Topic</th>
                            <th class="px-6 py-4 font-medium">Difficulty</th>
                            <th class="px-6 py-4 font-medium">Answer</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($questions as $question)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="text-white font-medium">{{ $question->content }}</div>
                                    <div class="text-slate-500 text-xs mt-1 truncate max-w-xs">
                                        Options: {{ implode(', ', $question->choices ?? []) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20 capitalize">
                                        {{ $question->topic }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $question->difficulty === 'easy' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 
                                          ($question->difficulty === 'medium' ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 
                                          'bg-rose-500/10 text-rose-400 border-rose-500/20') }} capitalize">
                                        {{ $question->difficulty }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-emerald-400 font-medium">
                                    {{ $question->correct_answer }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openEditModal('{{ $question->id }}')" 
                                                class="p-2 hover:bg-white/10 rounded-lg text-slate-400 hover:text-white transition-colors"
                                                title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click="confirmDelete('{{ $question->id }}')" 
                                                class="p-2 hover:bg-rose-500/10 rounded-lg text-slate-400 hover:text-rose-400 transition-colors"
                                                title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-slate-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p>No questions found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($questions->hasPages())
                <div class="p-4 border-t border-white/5">
                    {{ $questions->links() }}
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
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="closeModal"></div>

            <!-- Modal Panel -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-slate-800 rounded-2xl border border-white/10 shadow-2xl overflow-hidden"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-slate-900/50">
                        <h3 class="text-xl font-bold text-white" x-text="isEditing ? 'Edit Question' : 'New Question'"></h3>
                        <button @click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitForm" id="questionForm" class="p-6 space-y-6 max-h-[calc(100vh-200px)] overflow-y-auto custom-scrollbar">
                        
                        <!-- Question Content -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-300">Question Content</label>
                            <textarea name="content" x-model="formData.content" rows="3" required
                                    class="w-full px-4 py-3 bg-slate-900/50 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all resize-none"></textarea>
                            <p x-show="errors.content" x-text="errors.content" class="text-red-400 text-xs mt-1"></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Topic -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-300">Topic</label>
                                <select name="topic" x-model="formData.topic" required
                                        class="w-full px-4 py-3 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                                    <option value="" disabled>Select Topic</option>
                                    <option value="addition">Addition</option>
                                    <option value="subtraction">Subtraction</option>
                                    <option value="multiplication">Multiplication</option>
                                    <option value="division">Division</option>
                                    <option value="pemdas">PEMDAS</option>
                                </select>
                                <p x-show="errors.topic" x-text="errors.topic" class="text-red-400 text-xs mt-1"></p>
                            </div>

                            <!-- Difficulty -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-300">Difficulty</label>
                                <select name="difficulty" x-model="formData.difficulty" required
                                        class="w-full px-4 py-3 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                                <p x-show="errors.difficulty" x-text="errors.difficulty" class="text-red-400 text-xs mt-1"></p>
                            </div>
                        </div>

                        <!-- Choices & Correct Answer -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-slate-300">Answer Options</label>
                            
                            <template x-for="(choice, index) in formData.choices" :key="index">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 relative">
                                        <input type="text" x-model="formData.choices[index]" required
                                               :placeholder="'Option ' + (index + 1)"
                                               class="w-full pl-4 pr-10 py-3 bg-slate-900/50 border border-white/10 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all">
                                        
                                        <!-- Correct Answer Radio -->
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                            <input type="radio" name="correct_answer_selector" 
                                                   :checked="formData.correctAnswer === formData.choices[index] && formData.choices[index] !== ''"
                                                   @change="formData.correctAnswer = formData.choices[index]"
                                                   class="w-4 h-4 text-indigo-600 bg-slate-800 border-slate-600 focus:ring-indigo-500 cursor-pointer"
                                                   title="Mark as correct answer">
                                        </div>
                                    </div>
                                    
                                    <button type="button" @click="removeChoice(index)" x-show="formData.choices.length > 2"
                                            class="p-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <div class="flex items-center justify-between">
                                <button type="button" @click="addChoice" 
                                        class="text-sm text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-1 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Option
                                </button>
                                <div class="text-xs text-slate-500">
                                    Select the radio button next to the correct answer
                                </div>
                            </div>
                            <p x-show="errors.choices" x-text="errors.choices" class="text-red-400 text-xs mt-1"></p>
                            <p x-show="errors.correct_answer" x-text="errors.correct_answer" class="text-red-400 text-xs mt-1"></p>
                        </div>

                        <!-- Footer -->
                        <div class="pt-6 border-t border-white/5 flex items-center justify-end gap-3">
                            <button type="button" @click="closeModal" 
                                    class="px-5 py-2.5 text-slate-300 hover:text-white font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    :disabled="isSubmitting"
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-medium shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <span x-show="isSubmitting" class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                                <span x-text="isEditing ? 'Update Question' : 'Create Question'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="closeDeleteModal"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-slate-800 rounded-2xl border border-white/10 shadow-2xl overflow-hidden p-6 text-center"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                    
                    <div class="w-16 h-16 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-4 text-rose-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2">Delete Question?</h3>
                    <p class="text-slate-400 mb-6">Are you sure you want to delete this question? This action cannot be undone.</p>

                    <form :action="`/teacher/questions/${deleteId}`" method="POST" class="flex items-center justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="closeDeleteModal" 
                                class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-medium transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl font-medium shadow-lg shadow-rose-500/20 hover:shadow-rose-500/30 transition-all">
                            Delete Question
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function questionManager() {
            return {
                modalOpen: false,
                deleteModalOpen: false,
                isEditing: false,
                isSubmitting: false,
                deleteId: null,
                
                // Form Data
                formData: {
                    id: null,
                    content: '',
                    topic: '',
                    difficulty: 'medium',
                    choices: ['', '', '', ''],
                    correctAnswer: ''
                },
                
                errors: {},

                init() {
                    // Watch for URL params to maybe open modal automatically if needed? 
                    // For now, clean init.
                },

                applyFilters(value, type) {
                    const url = new URL(window.location.href);
                    if (value) {
                        url.searchParams.set(type, value);
                    } else {
                        url.searchParams.delete(type);
                    }
                    // Reset page on filter change
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                },

                openCreateModal() {
                    this.isEditing = false;
                    this.errors = {};
                    this.formData = {
                        id: null,
                        content: '',
                        topic: '',
                        difficulty: 'medium',
                        choices: ['', '', '', ''],
                        correctAnswer: ''
                    };
                    this.modalOpen = true;
                },

                async openEditModal(id) {
                    try {
                        const response = await fetch(`/teacher/questions/${id}/edit`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        
                        this.formData = {
                            id: data.id,
                            content: data.content,
                            topic: data.topic,
                            difficulty: data.difficulty,
                            choices: Array.isArray(data.choices) ? data.choices : JSON.parse(data.choices),
                            correctAnswer: data.correct_answer
                        };
                        
                        this.isEditing = true;
                        this.errors = {};
                        this.modalOpen = true;
                    } catch (error) {
                        console.error('Error fetching question:', error);
                        alert('Failed to load question details.');
                    }
                },

                closeModal() {
                    this.modalOpen = false;
                    this.isSubmitting = false;
                },

                addChoice() {
                    this.formData.choices.push('');
                },

                removeChoice(index) {
                    if (this.formData.choices.length > 2) {
                        const removedValue = this.formData.choices[index];
                        this.formData.choices = this.formData.choices.filter((_, i) => i !== index);
                        // If we removed the correct answer, reset it
                        if (this.formData.correctAnswer === removedValue) {
                            this.formData.correctAnswer = '';
                        }
                    }
                },

                confirmDelete(id) {
                    this.deleteId = id;
                    this.deleteModalOpen = true;
                },

                closeDeleteModal() {
                    this.deleteModalOpen = false;
                    this.deleteId = null;
                },

                async submitForm() {
                    this.isSubmitting = true;
                    this.errors = {};
                    
                    // Client validation
                    if (!this.formData.correctAnswer) {
                        this.errors.correct_answer = 'Please select a correct answer.';
                        this.isSubmitting = false;
                        return;
                    }
                    if (!this.formData.choices.includes(this.formData.correctAnswer)) {
                         this.errors.correct_answer = 'Correct answer must be one of the options.';
                         this.isSubmitting = false;
                         return;
                    }

                    const form = document.getElementById('questionForm');
                    const formData = new FormData();
                    
                    formData.append('content', this.formData.content);
                    formData.append('topic', this.formData.topic);
                    formData.append('difficulty', this.formData.difficulty);
                    formData.append('correct_answer', this.formData.correctAnswer);
                    
                    this.formData.choices.forEach(choice => {
                        formData.append('choices[]', choice);
                    });

                    if (this.isEditing) {
                        formData.append('_method', 'PUT');
                    }

                    try {
                        const url = this.isEditing 
                            ? `/teacher/questions/${this.formData.id}` 
                            : '{{ route('teacher.questions.store') }}';
                        
                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (!response.ok) {
                            if (data.errors) {
                                this.errors = data.errors;
                            } else {
                                alert(data.message || 'An error occurred.');
                            }
                            this.isSubmitting = false;
                            return;
                        }

                        // Success
                        window.location.reload();

                    } catch (error) {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>
</x-admin-layout>
