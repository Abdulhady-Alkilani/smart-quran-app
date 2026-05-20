<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">أكمل الآية — سورة {{ $surah->name_ar }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="completeAyahQuiz({{ Js::from($questions) }})">
        @if(count($questions) > 0)
        <div class="mb-6 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[#f8fafc]/60 text-sm">التقدم</span>
                <span class="text-[#C9A84C] font-bold" x-text="answeredCount + '/' + totalQuestions"></span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2">
                <div class="bg-gradient-to-r from-[#C9A84C] to-[#FFD700] h-2 rounded-full transition-all duration-500" :style="'width:' + (answeredCount / totalQuestions * 100) + '%'"></div>
            </div>
        </div>

        <div class="space-y-6">
            <template x-for="(q, qIndex) in questions" :key="q.id">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-[#C9A84C]/20 text-[#C9A84C] px-3 py-1 rounded-full text-xs font-bold" x-text="'سؤال ' + (qIndex + 1)"></span>
                        <span class="text-[#f8fafc]/40 text-xs" x-text="'الآية ' + q.ayah_number"></span>
                    </div>
                    <h3 class="text-lg font-bold text-[#f8fafc] mb-2">أكمل الآية التالية:</h3>
                    <div class="text-2xl leading-loose text-[#C9A84C] mb-4 p-4 bg-white/5 rounded-xl text-right" style="font-family: 'Noto Naskh Arabic', serif;" x-text="q.shown_text"></div>
                    <div class="space-y-3">
                        <template x-for="(option, optIndex) in q.options" :key="optIndex">
                            <button @click="selectAnswer(q.id, option)" :disabled="showResults"
                                :class="{
                                    'bg-green-500/20 border-green-500 text-green-400': showResults && option === q.correct_answer,
                                    'bg-red-500/20 border-red-500 text-red-400': showResults && answers[q.id] === option && option !== q.correct_answer,
                                    'bg-[#1B5E20]/20 border-[#1B5E20] text-white': !showResults && answers[q.id] === option,
                                    'bg-white/5 border-white/10 text-[#f8fafc] hover:bg-white/10': !showResults && answers[q.id] !== option
                                }"
                                class="w-full text-right p-4 rounded-xl border transition-all duration-200 text-lg"
                                style="font-family: 'Noto Naskh Arabic', serif;" x-text="option"></button>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="showResults" x-cloak x-transition class="mt-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 text-center">
            <div class="text-5xl font-bold text-[#C9A84C] mb-2" x-text="correctCount + '/' + totalQuestions"></div>
            <p class="text-[#f8fafc]/70 mb-6">نتيجة اختبار إكمال الآية</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('quran.show', $surah) }}" class="bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">العودة للسورة</a>
                <a href="{{ route('quiz.complete', $surah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">اختبار جديد</a>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <h3 class="text-2xl font-bold text-[#C9A84C] mb-2">لا توجد آيات كافية</h3>
            <a href="{{ route('quran.show', $surah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">العودة للسورة</a>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    function completeAyahQuiz(questionsData) {
        return {
            questions: questionsData, answers: {}, showResults: false, totalQuestions: questionsData.length,
            get answeredCount() { return Object.keys(this.answers).length; },
            get correctCount() { return this.questions.filter(q => this.answers[q.id] === q.correct_answer).length; },
            selectAnswer(questionId, option) {
                if (this.showResults) return;
                this.answers[questionId] = option;
                if (this.answeredCount === this.totalQuestions) this.showResults = true;
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
