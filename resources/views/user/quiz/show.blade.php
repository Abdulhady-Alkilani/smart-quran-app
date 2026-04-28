<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">اختبار - سورة {{ $surah->name_ar }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="quizApp({{ Js::from($questions) }})">
        @if($questions->count() > 0)
        <!-- شريط التقدم -->
        <div class="mb-6 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[#f8fafc]/60 text-sm">التقدم</span>
                <span class="text-[#C9A84C] font-bold" x-text="answeredCount + '/' + totalQuestions"></span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2">
                <div class="bg-gradient-to-r from-[#1B5E20] to-[#C9A84C] h-2 rounded-full transition-all duration-500" :style="'width:' + (answeredCount / totalQuestions * 100) + '%'"></div>
            </div>
        </div>

        <div class="space-y-6">
            <template x-for="(question, qIndex) in questions" :key="question.id">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 transition-all duration-300">
                    <h3 class="text-lg font-bold mb-4 text-[#C9A84C]">
                        <span x-text="'السؤال ' + (qIndex + 1) + ': '"></span>
                        <span x-text="question.question_text"></span>
                    </h3>
                    <div class="space-y-3">
                        <template x-for="(option, optIndex) in question.options" :key="optIndex">
                            <button
                                @click="selectAnswer(question.id, option)"
                                :disabled="showResults"
                                :class="{
                                    'bg-green-500/20 border-green-500 text-green-400': showResults && option === question.correct_answer,
                                    'bg-red-500/20 border-red-500 text-red-400': showResults && answers[question.id] === option && option !== question.correct_answer,
                                    'bg-[#1B5E20]/20 border-[#1B5E20] text-white': !showResults && answers[question.id] === option,
                                    'bg-white/5 border-white/10 text-[#f8fafc] hover:bg-white/10': !showResults && answers[question.id] !== option
                                }"
                                class="w-full text-right p-4 rounded-xl border transition-all duration-200"
                                x-text="option">
                            </button>
                        </template>
                    </div>
                    <!-- نتيجة السؤال -->
                    <div x-show="showResults && answers[question.id]" x-cloak class="mt-3 text-sm font-medium"
                         :class="answers[question.id] === question.correct_answer ? 'text-green-400' : 'text-red-400'">
                        <span x-show="answers[question.id] === question.correct_answer">✅ إجابة صحيحة!</span>
                        <span x-show="answers[question.id] !== question.correct_answer">❌ الإجابة الصحيحة: <span x-text="question.correct_answer" class="font-bold"></span></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- النتيجة النهائية -->
        <div x-show="showResults" x-cloak x-transition class="mt-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 text-center">
            <div class="text-6xl mb-4" x-text="correctCount >= Math.ceil(totalQuestions / 2) ? '🎉' : '📚'"></div>
            <div class="text-5xl font-bold text-[#C9A84C] mb-2" x-text="correctCount + '/' + totalQuestions"></div>
            <p class="text-[#f8fafc]/70 mb-6">النتيجة النهائية</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('quran.show', $surah) }}" class="bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">
                    العودة للسورة
                </a>
                <a href="{{ route('quiz.show', $surah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">
                    اختبار جديد
                </a>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-2xl font-bold text-[#C9A84C] mb-2">لا توجد أسئلة متوفرة لهذه السورة بعد</h3>
            <p class="text-[#f8fafc]/60 mb-6">سيتم توليد الأسئلة تلقائياً عند توفر خدمة الذكاء الاصطناعي.</p>
            <a href="{{ route('quran.show', $surah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">العودة للسورة</a>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    function quizApp(questionsData) {
        return {
            questions: questionsData,
            answers: {},
            showResults: false,
            totalQuestions: questionsData.length,

            get answeredCount() {
                return Object.keys(this.answers).length;
            },

            get correctCount() {
                return this.questions.filter(q => this.answers[q.id] === q.correct_answer).length;
            },

            selectAnswer(questionId, option) {
                if (this.showResults) return;
                this.answers[questionId] = option;
                if (this.answeredCount === this.totalQuestions) {
                    this.showResults = true;
                }
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
