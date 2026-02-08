@php
    $pageTitle = '⚡ ' . $student->name . "'s Challenges";
    $pageSubtitle = 'Detailed daily challenge history and performance';
@endphp

<x-admin-layout>
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-500/15 via-pink-500/10 to-blue-500/15 rounded-2xl border border-white/20 p-8 backdrop-blur-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white flex items-center gap-3">
                        <span class="text-4xl">👤</span>
                        <span>{{ $student->name }}</span>
                    </h2>
                    <p class="text-white/70 text-sm mt-2">Daily Challenge Performance & History</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('teacher.daily-challenges.index') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <span>←</span>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Total Attempts -->
            <div class="bg-gradient-to-br from-orange-500/25 to-orange-600/15 rounded-2xl border border-orange-400/40 p-6 hover:shadow-2xl hover:shadow-orange-500/30 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-200/80 text-xs font-bold uppercase tracking-wider">📊 Attempts</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalAttempts }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <span class="text-xl">🎯</span>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="bg-gradient-to-br from-green-500/25 to-green-600/15 rounded-2xl border border-green-400/40 p-6 hover:shadow-2xl hover:shadow-green-500/30 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-200/80 text-xs font-bold uppercase tracking-wider">✅ Completed</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalCompleted }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <span class="text-xl">🎁</span>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="bg-gradient-to-br from-purple-500/25 to-purple-600/15 rounded-2xl border border-purple-400/40 p-6 hover:shadow-2xl hover:shadow-purple-500/30 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-200/80 text-xs font-bold uppercase tracking-wider">⭐ Avg Score</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ round($averageScore ?? 0, 1) }} / 20</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <span class="text-xl">📈</span>
                    </div>
                </div>
            </div>

            <!-- Best Score -->
            <div class="bg-gradient-to-br from-indigo-500/25 to-indigo-600/15 rounded-2xl border border-indigo-400/40 p-6 hover:shadow-2xl hover:shadow-indigo-500/30 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-200/80 text-xs font-bold uppercase tracking-wider">🏅 Best Score</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $bestScore ?? 0 }} / 20</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <span class="text-xl">🏆</span>
                    </div>
                </div>
            </div>

            <!-- Streak -->
            <div class="bg-gradient-to-br from-blue-500/25 to-blue-600/15 rounded-2xl border border-blue-400/40 p-6 hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200/80 text-xs font-bold uppercase tracking-wider">🔥 Streak</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $streak }} <span class="text-base font-normal">days</span></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <span class="text-xl">⚡</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Challenge History Table -->
        <div class="bg-gradient-to-br from-white/5 to-white/0 backdrop-blur-lg rounded-2xl border border-white/20 overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-white/15 bg-gradient-to-r from-purple-500/10 via-pink-500/5 to-blue-500/10">
                <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span class="text-3xl">📋</span>
                    <span>Challenge History</span>
                    <span class="text-sm font-normal text-white/60">({{ $challenges->total() ?? 0 }} total)</span>
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-white/10">
                    <thead class="bg-gradient-to-r from-purple-500/25 via-pink-500/15 to-blue-500/10 sticky top-0">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-white/95 uppercase tracking-wider">#️⃣ Attempt</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-white/95 uppercase tracking-wider">📊 Score</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-white/95 uppercase tracking-wider">✓ Result</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-white/95 uppercase tracking-wider">⏱️ Duration</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-white/95 uppercase tracking-wider">🕐 Date & Time</span>
                            </th>
                            <th class="px-6 py-4 text-left">
                                <span class="text-xs font-bold text-white/95 uppercase tracking-wider">👁️ View</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($challenges as $challenge)
                            <tr class="hover:bg-white/15 transition group">
                                <!-- Attempt Number -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white font-bold text-sm">
                                        #{{ $challenge->attempt_number ?? $loop->index + 1 }}
                                    </span>
                                </td>

                                <!-- Score -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-4 py-2.5 bg-gradient-to-r from-purple-500/40 to-pink-500/30 border border-purple-400/50 text-purple-100 rounded-lg text-sm font-bold shadow-lg shadow-purple-500/20">
                                        {{ $challenge->score ?? 0 }} / 20
                                    </span>
                                </td>

                                <!-- Result Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($challenge->completed_at)
                                        @if($challenge->is_correct)
                                            <span class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-full bg-green-500/30 border border-green-400/60 text-green-100">
                                                <span>✓</span>
                                                <span>Correct</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-full bg-red-500/30 border border-red-400/60 text-red-100">
                                                <span>✗</span>
                                                <span>Incorrect</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-full bg-yellow-500/30 border border-yellow-400/60 text-yellow-100">
                                            <span>⏳</span>
                                            <span>Pending</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Duration -->
                                <td class="px-6 py-4 whitespace-nowrap text-white/85 font-semibold">
                                    @if($challenge->duration)
                                        <span class="flex items-center gap-2">
                                            <span>⏱️</span>
                                            <span>{{ $challenge->duration }}s</span>
                                        </span>
                                    @else
                                        <span class="text-white/40">—</span>
                                    @endif
                                </td>

                                <!-- Date & Time -->
                                <td class="px-6 py-4 whitespace-nowrap text-white/75 text-sm">
                                    <div>
                                        <div class="text-white/90 js-local-time font-semibold" data-ts="{{ optional($challenge->completed_at ?? $challenge->created_at)->toIso8601String() }}">
                                            {{ optional($challenge->completed_at ?? $challenge->created_at)->format('M d, Y H:i:s') }}
                                        </div>
                                        <div class="text-xs text-white/50 js-relative-time" data-ts="{{ optional($challenge->completed_at ?? $challenge->created_at)->toIso8601String() }}">
                                            {{ optional($challenge->completed_at ?? $challenge->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                </td>

                                <!-- View Question Button -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($challenge->question_id)
                                        <button 
                                            type="button"
                                            class="btn-view-question px-4 py-2 bg-blue-600/40 hover:bg-blue-600/60 border border-blue-400/50 text-blue-100 rounded-lg font-semibold text-sm transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/30"
                                            data-challenge-id="{{ $challenge->id }}"
                                            data-student-id="{{ $student->id }}"
                                        >
                                            👁️ View
                                        </button>
                                    @else
                                        <span class="text-white/40 text-sm font-medium">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <span class="text-5xl">📭</span>
                                        <p class="text-white/70 font-semibold">No challenges found</p>
                                        <p class="text-white/50 text-sm">This student hasn't completed any daily challenges yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($challenges->hasPages())
                <div class="p-6 border-t border-white/10 bg-gradient-to-r from-white/5 to-white/0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-white/75">
                            Showing <span class="text-white/90 font-semibold">{{ $challenges->count() }}</span> of 
                            <span class="text-white/90 font-semibold">{{ $challenges->total() }}</span> challenges
                        </p>
                        <div class="flex justify-center">
                            {{ $challenges->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

@push('scripts')
<script>
// ===== TIMEZONE & TIME FORMATTING =====
function toRelativeTime(date){
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return `${diff}s ago`;
    if (diff < 3600) return `${Math.floor(diff/60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff/3600)}h ago`;
    if (diff < 604800) return `${Math.floor(diff/86400)}d ago`;
    return `${Math.floor(diff/604800)}w ago`;
}

document.addEventListener('DOMContentLoaded', function(){
    // Convert timestamps to local time
    document.querySelectorAll('.js-local-time').forEach(el => {
        const ts = el.getAttribute('data-ts');
        if (!ts) return;
        const d = new Date(ts);
        if (isNaN(d)) return;
        el.textContent = new Intl.DateTimeFormat(undefined, {
            year:'numeric', month:'short', day:'2-digit',
            hour:'2-digit', minute:'2-digit', second:'2-digit'
        }).format(d);
    });

    // Convert to relative time
    document.querySelectorAll('.js-relative-time').forEach(el => {
        const ts = el.getAttribute('data-ts');
        if (!ts) return;
        const d = new Date(ts);
        if (isNaN(d)) return;
        el.textContent = toRelativeTime(d);
    });

    // ===== VIEW INDIVIDUAL QUESTION MODAL =====
    const viewQuestionBtns = document.querySelectorAll('.btn-view-question');
    
    const questionModal = document.createElement('div');
    questionModal.id = 'question-viewer-modal';
    questionModal.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 overflow-y-auto';
    questionModal.innerHTML = `
        <div class="w-full max-w-2xl bg-gradient-to-br from-slate-800 to-slate-900 border border-white/20 rounded-2xl shadow-2xl my-8">
            <div class="bg-gradient-to-r from-purple-500/30 to-blue-500/20 border-b border-white/10 px-8 py-6 sticky top-0">
                <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                    <span>📝</span>
                    <span>Question Details</span>
                </h3>
            </div>

            <div class="p-8 space-y-6 max-h-96 overflow-y-auto">
                <!-- Question Text -->
                <div class="bg-white/5 border border-white/10 rounded-lg p-6">
                    <h4 class="text-white/70 text-sm font-bold uppercase tracking-wider mb-3">📋 Question</h4>
                    <p id="question-text" class="text-white font-mono text-lg leading-relaxed bg-white/5 p-4 rounded border border-white/10">Loading...</p>
                </div>

                <!-- Correct Answer -->
                <div class="bg-green-500/15 border border-green-400/40 rounded-lg p-6">
                    <h4 class="text-green-300/80 text-sm font-bold uppercase tracking-wider mb-3">✓ Correct Answer</h4>
                    <p id="correct-answer" class="text-green-100 font-bold text-2xl">Loading...</p>
                </div>

                <!-- Student's Answer -->
                <div class="bg-blue-500/15 border border-blue-400/40 rounded-lg p-6">
                    <h4 class="text-blue-300/80 text-sm font-bold uppercase tracking-wider mb-3">📥 Student's Answer</h4>
                    <p id="student-answer" class="text-blue-100 font-bold text-2xl">Loading...</p>
                </div>

                <!-- Result -->
                <div id="result-container" class="rounded-lg p-6 border">
                    <h4 class="text-white/70 text-sm font-bold uppercase tracking-wider mb-3">🎯 Result</h4>
                    <p id="result-text" class="font-bold text-lg">Loading...</p>
                </div>

                <!-- Question Performance Breakdown -->
                <div class="bg-gradient-to-r from-indigo-500/15 to-purple-500/10 border border-indigo-400/40 rounded-lg p-6">
                    <h4 class="text-indigo-300/80 text-sm font-bold uppercase tracking-wider mb-4">📊 Question Performance</h4>
                    <div id="question-breakdown" class="space-y-2 text-white/90">
                        <p class="text-xs text-white/60">Loading breakdown...</p>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="error-message" class="hidden bg-red-500/20 border border-red-400/40 rounded-lg p-6">
                    <p class="text-red-200 font-semibold">⚠️ Unable to load question details</p>
                    <p id="error-detail" class="text-red-200/70 text-sm mt-2"></p>
                </div>
            </div>

            <div class="bg-white/5 border-t border-white/10 px-8 py-4 flex justify-end gap-3">
                <button type="button" id="close-question-modal" class="px-6 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition">
                    Close
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(questionModal);

    document.getElementById('close-question-modal').addEventListener('click', () => {
        questionModal.classList.add('hidden');
    });

    questionModal.addEventListener('click', (e) => {
        if (e.target === questionModal) {
            questionModal.classList.add('hidden');
        }
    });

    // Handle view question buttons
    viewQuestionBtns.forEach(btn => {
        btn.addEventListener('click', async function() {
            const challengeId = this.getAttribute('data-challenge-id');
            const studentId = this.getAttribute('data-student-id');
            
            questionModal.classList.remove('hidden');
            document.getElementById('question-text').textContent = 'Loading question...';
            document.getElementById('correct-answer').textContent = '...';
            document.getElementById('student-answer').textContent = '...';
            document.getElementById('error-message').classList.add('hidden');
            document.getElementById('question-breakdown').innerHTML = '<p class="text-xs text-white/60">Loading breakdown...</p>';

            try {
                const baseRoute = '{{ route("teacher.daily-challenges.question", ["student" => ":studentId", "challenge" => ":challengeId"]) }}';
                const url = baseRoute.replace(':studentId', studentId).replace(':challengeId', challengeId);
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                document.getElementById('question-text').textContent = data.question?.body || 'Question not available';
                document.getElementById('correct-answer').textContent = data.challenge?.correct_answer ?? data.question?.answer ?? '—';
                
                const studentAnswer = data.challenge?.submitted_answer || '—';
                document.getElementById('student-answer').textContent = studentAnswer;
                
                const resultContainer = document.getElementById('result-container');
                const resultText = document.getElementById('result-text');
                
                if (data.challenge?.is_correct) {
                    resultContainer.classList.remove('bg-red-500/15', 'border-red-400/40');
                    resultContainer.classList.add('bg-green-500/15', 'border-green-400/40');
                    resultText.className = 'font-bold text-lg text-green-100';
                    resultText.innerHTML = '<span class="text-2xl mr-2">✓</span>Correct Answer';
                } else {
                    resultContainer.classList.remove('bg-green-500/15', 'border-green-400/40');
                    resultContainer.classList.add('bg-red-500/15', 'border-red-400/40');
                    resultText.className = 'font-bold text-lg text-red-100';
                    resultText.innerHTML = '<span class="text-2xl mr-2">✗</span>Incorrect Answer';
                }

                // Display question performance breakdown
                const breakdownEl = document.getElementById('question-breakdown');
                if (data.challenge?.question_performance) {
                    const breakdown = data.challenge.question_performance;
                    let correctCount = 0;
                    let incorrectCount = 0;
                    let html = '<div class="grid grid-cols-2 gap-4 mb-4">';
                    
                    breakdown.forEach((item, idx) => {
                        const isCorrect = item.is_correct || item.correct;
                        if (isCorrect) correctCount++;
                        else incorrectCount++;
                        
                        const badge = isCorrect 
                            ? '<span class="px-3 py-1 rounded-full bg-green-500/30 text-green-100 text-xs font-bold">✓ Correct</span>'
                            : '<span class="px-3 py-1 rounded-full bg-red-500/30 text-red-100 text-xs font-bold">✗ Wrong</span>';
                        
                        html += `<div class="flex items-center justify-between gap-2"><span class="font-semibold">Q${item.question_number || idx + 1}</span>${badge}</div>`;
                    });
                    
                    html += '</div>';
                    html += `<div class="border-t border-indigo-400/30 pt-3 mt-3 text-xs"><div class="flex justify-between"><span>✓ Correct: <span class="text-green-300 font-bold">${correctCount}</span></span><span>✗ Wrong: <span class="text-red-300 font-bold">${incorrectCount}</span></span></div></div>`;
                    
                    breakdownEl.innerHTML = html;
                } else {
                    breakdownEl.innerHTML = '<p class="text-xs text-white/50">No detailed breakdown available</p>';
                }

            } catch (error) {
                console.error('Error loading question:', error);
                document.getElementById('question-text').textContent = '';
                document.getElementById('correct-answer').textContent = '';
                document.getElementById('student-answer').textContent = '';
                document.getElementById('result-text').textContent = '';
                document.getElementById('error-message').classList.remove('hidden');
                document.getElementById('error-detail').textContent = error.message;
            }
        });
    });

    // ===== VIEW ALL ANSWERS MODAL =====
    const viewAnswersBtn = document.getElementById('btn-view-answers');
    if (viewAnswersBtn) {
        const answersModal = document.createElement('div');
        answersModal.id = 'answers-modal';
        answersModal.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4 overflow-y-auto';
        answersModal.innerHTML = `
            <div class="w-full max-w-3xl bg-gradient-to-br from-slate-800 to-slate-900 border border-white/20 rounded-2xl shadow-2xl my-8">
                <div class="bg-gradient-to-r from-purple-500/30 to-blue-500/20 border-b border-white/10 px-8 py-6 sticky top-0">
                    <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                        <span>📋</span>
                        <span>All Answer Records</span>
                    </h3>
                </div>
                <div id="answers-summary" class="px-8 pt-6 text-white/90 font-semibold"></div>
                <div id="answers-list" class="p-8 max-h-96 overflow-y-auto divide-y divide-white/10"></div>
                <div class="p-6 text-center border-t border-white/10">
                    <button id="answers-loadmore" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg hidden">Load More</button>
                </div>
                <div class="bg-white/5 border-t border-white/10 px-8 py-4 flex justify-end gap-3">
                    <button id="answers-close" class="px-6 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition">Close</button>
                </div>
            </div>
        `;
        document.body.appendChild(answersModal);

        answersModal.querySelector('#answers-close').addEventListener('click', () => {
            answersModal.classList.add('hidden');
        });

        answersModal.addEventListener('click', (e) => {
            if (e.target === answersModal) {
                answersModal.classList.add('hidden');
            }
        });

        let currentPage = 1;
        const perPage = 15;

        async function fetchAnswers(page) {
            const summaryEl = answersModal.querySelector('#answers-summary');
            const listEl = answersModal.querySelector('#answers-list');
            const loadBtn = answersModal.querySelector('#answers-loadmore');

            try {
                const res = await fetch('{{ route("teacher.daily-challenges.answers", $student) }}?page=' + page + '&per_page=' + perPage, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) throw new Error('Failed to load answers');
                const data = await res.json();

                summaryEl.innerHTML = `
                    <div class="grid grid-cols-3 gap-4">
                        <div>Total: <span class="text-white">${data.total}</span></div>
                        <div>✓ Correct: <span class="text-green-300">${data.total_correct}</span></div>
                        <div>✗ Incorrect: <span class="text-red-300">${data.total_incorrect}</span></div>
                    </div>
                `;

                if (data.answers.length === 0 && page === 1) {
                    listEl.innerHTML = '<div class="p-6 text-white/60 text-center">No answers submitted yet</div>';
                    loadBtn.classList.add('hidden');
                    return;
                }

                const rows = data.answers.map(a => {
                    const ts = new Date(a.created_at);
                    const time = isNaN(ts) ? '' : ts.toLocaleString();
                    const badgeColor = a.is_correct ? 'bg-green-500/30 text-green-100' : 'bg-red-500/30 text-red-100';
                    const badgeText = a.is_correct ? '✓ Correct' : '✗ Incorrect';
                    
                    return `
                        <div class="py-3 px-4 hover:bg-white/5 rounded transition">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="font-bold text-white">Answer: ${a.submitted_answer ?? '—'}</div>
                                    <div class="text-xs text-white/50 mt-1">${time}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ${badgeColor}">${badgeText}</span>
                                    <div class="text-sm text-white/70 mt-1">Score: ${a.score ?? 0}</div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                if (page === 1) {
                    listEl.innerHTML = rows;
                } else {
                    listEl.insertAdjacentHTML('beforeend', rows);
                }

                currentPage = data.current_page || page;
                if (data.current_page < data.last_page) {
                    loadBtn.classList.remove('hidden');
                } else {
                    loadBtn.classList.add('hidden');
                }

            } catch (err) {
                console.error('Error:', err);
                summaryEl.innerHTML = '<p class="text-red-300">Error loading answers</p>';
                listEl.innerHTML = '';
            }
        }

        answersModal.querySelector('#answers-loadmore').addEventListener('click', () => {
            fetchAnswers(currentPage + 1);
        });

        viewAnswersBtn.addEventListener('click', () => {
            answersModal.classList.remove('hidden');
            currentPage = 1;
            answersModal.querySelector('#answers-list').innerHTML = '';
            answersModal.querySelector('#answers-summary').textContent = 'Loading…';
            fetchAnswers(1);
        });
    }
});
</script>
@endpush
