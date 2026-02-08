<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-900 via-[#283B60] to-slate-900 p-4">
        <!-- Header -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-gradient-to-r from-[#283B60] via-[#1A3263] to-blue-500 rounded-2xl p-8 text-center mb-6">
                <h1 class="text-4xl font-bold text-white mb-2">⚡ Daily Math Challenge</h1>
                <p class="text-white/90 mb-4">Test your skills with daily problems</p>
                
                <!-- Timer -->
                <div class="flex justify-center items-center gap-4">
                    <div id="timer" class="text-6xl font-bold text-yellow-300 font-mono">20:00</div>
                    <div class="text-white/70 text-sm">
                        <p>Time Remaining</p>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white/10 rounded-lg p-4 mb-6 border border-white/20">
                <div class="flex justify-between mb-2">
                    <span class="text-white font-semibold">Progress</span>
                    <span class="text-[#283B60]" id="progress-count">0 / 20</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                    <div id="progress-bar" class="bg-gradient-to-r from-[#283B60] to-[#1A3263] h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Questions Container -->
        <div class="max-w-4xl mx-auto">
            <form id="challengeForm" class="space-y-6">
                @csrf

                <!-- Questions will be inserted here by JavaScript -->
                <div id="questionsContainer"></div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit" class="w-full sm:flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-lg font-bold text-lg transition-all duration-300 shadow-lg shadow-green-500/20 active:scale-95">
                        ✓ Submit Answers
                    </button>
                    <button type="button" id="resetBtn" class="w-full sm:flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-4 rounded-lg font-bold text-lg transition-all duration-300 shadow-lg shadow-red-500/20 active:scale-95">
                        ↻ Reset All
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Modal -->
        <div id="resultsModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm justify-center items-center p-4 z-50" style="display: none;">
            <div class="bg-slate-900 rounded-2xl p-8 max-w-2xl w-full border border-white/20 max-h-[90vh] overflow-y-auto custom-scrollbar" style="display: flex; flex-direction: column;">
                <h2 class="text-3xl font-bold text-white mb-4">✅ Challenge Results</h2>
                <div class="space-y-4 mb-6">
                    <div class="bg-white/10 rounded-lg p-4 border border-white/20">
                        <p class="text-white/70 text-sm">Total Score</p>
                        <p class="text-4xl font-bold text-[#283B60]" id="finalScore">0 / 20</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-green-500/20 rounded-lg p-4 border border-green-400/30">
                            <p class="text-white/70 text-sm">Correct</p>
                            <p class="text-2xl font-bold text-green-300" id="correctCount">0</p>
                        </div>
                        <div class="bg-red-500/20 rounded-lg p-4 border border-red-400/30">
                            <p class="text-white/70 text-sm">Incorrect</p>
                            <p class="text-2xl font-bold text-red-300" id="incorrectCount">0</p>
                        </div>
                    </div>
                    <div class="bg-blue-500/20 rounded-lg p-4 border border-blue-400/30">
                        <p class="text-white/70 text-sm">Time Taken</p>
                        <p class="text-xl font-bold text-blue-300" id="timeTaken">0s</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="button" id="retryBtn" class="w-full sm:flex-1 bg-gradient-to-r from-[#283B60] to-[#1A3263] hover:from-[#283B60] hover:to-[#1A3263] text-white px-4 py-3 rounded-lg font-bold transition-all duration-300">
                        🔄 Try Again
                    </button>
                    <button type="button" id="viewDetailsBtn" class="w-full sm:flex-1 bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-lg font-bold transition-all duration-300 text-center">
                        👁️ View Details
                    </button>
                    <a href="{{ route('student.dashboard') }}" class="w-full sm:flex-1 bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-lg font-bold transition-all duration-300 text-center">
                        ✅ OK
                    </a>
                </div>

                <!-- Detailed Results Table (Initially Hidden) -->
                <div id="detailsSection" class="hidden w-full mt-6 overflow-hidden rounded-xl border border-white/10 bg-slate-900/50">
                    <div class="overflow-x-auto max-h-[40vh] custom-scrollbar">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="bg-slate-900/80 text-xs uppercase font-medium text-slate-300 sticky top-0 z-10 backdrop-blur-sm">
                                <tr>
                                    <th class="px-4 py-3 w-16">#</th>
                                    <th class="px-4 py-3 min-w-[150px]">Question</th>
                                    <th class="px-4 py-3">Your Answer</th>
                                    <th class="px-4 py-3">Correct</th>
                                    <th class="px-4 py-3 w-24 text-right">Result</th>
                                </tr>
                            </thead>
                            <tbody id="detailsTableBody" class="divide-y divide-white/5">
                                <!-- Rows injected via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const questions = {
            title: 'Daily Math Challenge',
            difficulty: 'hard',
            time_limit_minutes: 20,
            total_questions: 20,
            questions: []
        };

        let timeRemaining = 20 * 60;
        let totalTime = 20 * 60;
        let timerInterval = null;
        let answers = {};

        document.addEventListener('DOMContentLoaded', function() {
            generateRandomQuestions();
            renderQuestions();
            startTimer();
        });

        function randomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function randomFactor(n, minFactor = 2, maxFactor = 10) {
            const absN = Math.abs(Math.trunc(n));
            if (absN <= 1) return 1;
            const factors = [];
            for (let i = minFactor; i <= Math.min(maxFactor, absN); i++) {
                if (absN % i === 0) factors.push(i);
            }
            if (factors.length === 0) return 1;
            return factors[Math.floor(Math.random() * factors.length)];
        }

        function generateRandomQuestions() {
            questions.questions = [];
            const types = ['nested_parentheses','exponents_with_parentheses','negative_integers','mixed_operations'];
            for (let i = 1; i <= questions.total_questions; i++) {
                const type = types[(i - 1) % types.length];
                questions.questions.push(generatePEMDASQuestion(i, type));
            }
        }

        function generatePEMDASQuestion(id, type) {
            let q, a;
            if (type === 'nested_parentheses') ({question:q,answer:a}=genNested());
            else if (type === 'exponents_with_parentheses') ({question:q,answer:a}=genExponent());
            else if (type === 'negative_integers') ({question:q,answer:a}=genNegative());
            else ({question:q,answer:a}=genMixed());
            return { id, difficulty: 'hard', question: q, correct_answer: a, points: 1 };
        }

        function genNested(){
            const a=randomInt(2,9); const b=randomInt(2,8); const c=randomInt(3,7); const d=randomInt(2,6);
            const inner=(b+c)**2 - (d*2);
            let e=randomFactor(inner,1,6); if(e===1) e=1;
            const question=`${a} + ((${b} + ${c})² - ${d*2}) ÷ ${e}`;
            const answer = a + inner/e;
            return {question, answer};
        }

        function genExponent(){
            const a=randomInt(1,6); const b=randomInt(2,5); const c=randomInt(2,4); const d=randomInt(3,8);
            const question=`${a} × (${b}² - ${c}) + ${d}`;
            const answer = a*(b**2 - c) + d;
            return {question, answer};
        }

        function genNegative(){
            const a=randomInt(1,8); const b=randomInt(2,6); const c=-randomInt(1,8); const d=randomInt(2,5);
            const inner=(b**3) + (c*d);
            let e=randomFactor(inner,1,6); if(e===1) e=1;
            const question=`${a} - (${b}³ + (${c}) × ${d}) ÷ ${e}`;
            const answer = a - inner/e;
            return {question, answer};
        }

        function genMixed(){
            const a=randomInt(2,8); const b=randomInt(3,6); const c=randomInt(2,7);
            const numerator=(a+b)*c; let d=randomFactor(numerator,1,6); if(d===1) d=1; const e=randomInt(1,3);
            const question=`((${a} + ${b}) × ${c}) ÷ ${d} + ${e}`;
            const answer = numerator/d + e;
            return {question, answer};
        }

        function renderQuestions(){
            const container=document.getElementById('questionsContainer');
            container.innerHTML = questions.questions.map(q=>`
                <div class="bg-white/10 border border-white/20 rounded-lg p-6 backdrop-blur-md hover:border-white/40 transition">
                    <div class="flex flex-col sm:flex-row items-start justify-between mb-4 gap-2">
                        <div class="flex-1 w-full">
                            <p class="text-white/70 text-sm mb-2">Question ${q.id} of ${questions.total_questions}</p>
                            <p class="text-xl font-bold text-white break-words">${q.question}</p>
                        </div>
                        <span class="self-start sm:self-auto bg-[#283B60]/20 text-[#283B60] px-3 py-1 rounded-full text-xs font-semibold border border-[#283B60]/30 shrink-0">+${q.points} pt</span>
                    </div>
                    <div class="relative">
                        <input type="text" id="answer-${q.id}" placeholder="Your answer" inputmode="decimal" pattern="-?[0-9]*\.?[0-9]*" required class="w-full bg-slate-800 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-white/40 focus:border-[#283B60] focus:outline-none transition-colors text-lg font-mono">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 pointer-events-none">→</span>
                    </div>
                </div>
            `).join('');

            // attach listeners and sanitize inputs
            questions.questions.forEach(q=>{
                const el=document.getElementById(`answer-${q.id}`);
                el.addEventListener('input', function(e){
                    let v = e.target.value.toString();
                    v = v.replace(/[,\s]/g,'');
                    v = v.replace(/(?!^)-/g,'');
                    const parts = v.split('.');
                    if (parts.length > 2) v = parts[0] + '.' + parts.slice(1).join('');
                    v = v.match(/^-?[0-9]*(\.[0-9]*)?/)[0] || '';
                    e.target.value = v;
                    answers[q.id] = v === '' ? undefined : v;
                    updateProgress();
                });
            });

            updateProgress();
        }

        function startTimer(){
            if (timerInterval) clearInterval(timerInterval);
            timerInterval = setInterval(()=>{
                timeRemaining--; updateTimerDisplay();
                if(timeRemaining<=0){ clearInterval(timerInterval); submitAnswers(); }
            },1000);
        }

        function updateTimerDisplay(){
            const minutes=Math.floor(timeRemaining/60); const seconds = timeRemaining%60;
            const timerEl=document.getElementById('timer'); if(timerEl) timerEl.textContent = `${minutes}:${String(seconds).padStart(2,'0')}`;
            if(timeRemaining<300){ timerEl.classList.add('text-red-400'); timerEl.classList.remove('text-yellow-300'); }
        }

        function updateProgress(){
            const answered = Object.keys(answers).filter(k=>answers[k]!==undefined && answers[k] !== '').length;
            const total = questions.total_questions; const percentage = (answered/total)*100;
            document.getElementById('progress-count').textContent = `${answered} / ${total}`;
            document.getElementById('progress-bar').style.width = percentage + '%';
        }

        function hasAllAnswers() {
            return questions.questions.every(q => {
                const v = answers[q.id];
                return v !== undefined && v !== '';
            });
        }

        document.getElementById('challengeForm').addEventListener('submit', function(e){
            e.preventDefault();
            if (!hasAllAnswers()) {
                alert('Please answer all questions before submitting.');
                return;
            }
            submitAnswers();
        });

        document.getElementById('resetBtn').addEventListener('click', function(){
            if(!confirm('Reset challenge and reshuffle questions? Your current answers will be lost.')) return;
            clearInterval(timerInterval); timeRemaining = 20*60; updateTimerDisplay(); answers = {}; document.getElementById('resultsModal').style.display='none';
            generateRandomQuestions(); renderQuestions(); updateProgress(); startTimer();
        });

        function submitAnswers(){
            clearInterval(timerInterval);
            let correct=0;
            const timeTaken = totalTime - timeRemaining;
            
            questions.questions.forEach(q=>{
                const raw = answers[q.id];
                const user = raw === undefined || raw === '' ? null : parseFloat(raw);
                const expected = Number(q.correct_answer);
                if(user === expected) { 
                    correct++; 
                    document.getElementById(`answer-${q.id}`).classList.add('border-green-400','bg-green-500/10'); 
                }
                else if(raw !== undefined && raw !== '') { 
                    document.getElementById(`answer-${q.id}`).classList.add('border-red-400','bg-red-500/10'); 
                }
            });

            // Build detailed results for metadata
            const detailedResults = questions.questions.map(q => {
                const raw = answers[q.id];
                const user = raw === undefined || raw === '' ? null : parseFloat(raw);
                const expected = Number(q.correct_answer);
                return {
                    question_number: q.id,
                    question: q.question,
                    user_answer: user === null ? 'No Answer' : user,
                    correct_answer: expected,
                    is_correct: user === expected
                };
            });
            
            const incorrect = questions.total_questions - correct;
            // record raw correct count as score (not percentage)
            const score = correct;

            document.getElementById('finalScore').textContent = `${correct} / ${questions.total_questions}`;
            document.getElementById('correctCount').textContent = correct;
            document.getElementById('incorrectCount').textContent = incorrect;
            
            const minutes = Math.floor(timeTaken / 60);
            const seconds = timeTaken % 60;
            document.getElementById('timeTaken').textContent = `${minutes}m ${seconds}s`;
            
            const modal = document.getElementById('resultsModal'); 
            modal.style.display='flex';
            
            // Record the challenge attempt (score = raw correct count)
            recordChallenge(score, correct, timeTaken, detailedResults);
            
            // Render details for the view
            renderDetails(detailedResults);
        }

        function renderDetails(results) {
            const tbody = document.getElementById('detailsTableBody');
            if (!tbody) return;
            
            tbody.innerHTML = results.map(r => `
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-4 py-3 font-mono text-slate-500">${r.question_number}</td>
                    <td class="px-4 py-3 text-white font-medium">${r.question}</td>
                    <td class="px-4 py-3 font-mono ${r.is_correct ? 'text-emerald-400' : 'text-red-400'}">
                        ${r.user_answer}
                    </td>
                    <td class="px-4 py-3 font-mono text-emerald-400">${r.correct_answer}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                            r.is_correct 
                            ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' 
                            : 'bg-red-500/10 text-red-400 border border-red-500/20'
                        }">
                            ${r.is_correct ? 'Correct' : 'Wrong'}
                        </span>
                    </td>
                </tr>
            `).join('');
            
            // Hide details initially when results are shown
            document.getElementById('detailsSection').classList.add('hidden');
        }

        // View Details Toggle
        document.getElementById('viewDetailsBtn')?.addEventListener('click', function() {
            const section = document.getElementById('detailsSection');
            section.classList.toggle('hidden');
            if (!section.classList.contains('hidden')) {
                // Small delay to ensure display:block is applied before scrolling
                setTimeout(() => {
                    section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 10);
            }
        });

        function recordChallenge(score, correct, duration, detailedResults) {
            fetch('{{ route("student.daily-challenge.record") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    // score: raw correct count
                    score: score,
                    // keep is_correct true only when all answers correct
                    is_correct: correct === questions.total_questions,
                    duration: duration,
                    metadata: {
                        total_questions: questions.total_questions,
                        correct_answers: correct,
                        incorrect_answers: questions.total_questions - correct,
                        percent: Math.round((correct / questions.total_questions) * 100),
                        questions: detailedResults
                    }
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Challenge recorded:', data);
            })
            .catch(error => console.error('Error recording challenge:', error));
        }

        document.getElementById('retryBtn').addEventListener('click', function(){ location.reload(); });
    </script>
</x-app-layout>
