<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">{{ __('messages.mcq_quiz.title', ['surah' => $surah->name_ar]) }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="mcqQuizApp({{ Js::from($questions) }})">
        @if(count($questions) > 0)
        <div class="mb-6 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl p-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[#f8fafc]/60 text-sm">{{ __('messages.mcq_quiz.progress') }}</span>
                <span class="text-[#C9A84C] font-bold" x-text="answeredCount + '/' + totalQuestions"></span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2">
                <div class="bg-gradient-to-r from-[#8B5CF6] to-[#C9A84C] h-2 rounded-full transition-all duration-500" :style="'width:' + (answeredCount / totalQuestions * 100) + '%'"></div>
            </div>
        </div>

        <div class="space-y-6">
            <template x-for="(question, qIndex) in questions" :key="question.id">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="bg-[#8B5CF6]/20 text-[#8B5CF6] px-3 py-1 rounded-full text-xs font-bold" x-text="'{{ __("messages.mcq_quiz.question") }} ' + (qIndex + 1)"></span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium"
                              :class="{
                                  'bg-green-500/20 text-green-400': question.difficulty === 'سهل',
                                  'bg-blue-500/20 text-blue-400': question.difficulty === 'متوسط',
                                  'bg-orange-500/20 text-orange-400': question.difficulty === 'صعب',
                                  'bg-red-500/20 text-red-400': question.difficulty === 'تحدي'
                              }"
                              x-text="question.difficulty"></span>
                        <span class="bg-white/5 text-[#f8fafc]/50 px-2 py-1 rounded-full text-xs" x-text="question.type"></span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-[#f8fafc]" x-text="question.question_text"></h3>
                    <div x-show="question.shown_text" class="text-2xl leading-loose text-[#C9A84C] mb-4 p-4 bg-white/5 rounded-xl text-right" style="font-family: 'Noto Naskh Arabic', serif;" x-text="question.shown_text"></div>
                    <div class="space-y-3">
                        <template x-for="(option, optIndex) in question.options" :key="optIndex">
                            <button
                                @click="selectAnswer(question.id, option)"
                                :disabled="showResults"
                                :class="{
                                    'bg-green-500/20 border-green-500 text-green-400': showResults && option === question.correct_answer,
                                    'bg-red-500/20 border-red-500 text-red-400': showResults && answers[question.id] === option && option !== question.correct_answer,
                                    'bg-[#8B5CF6]/20 border-[#8B5CF6] text-white': !showResults && answers[question.id] === option,
                                    'bg-white/5 border-white/10 text-[#f8fafc] hover:bg-white/10': !showResults && answers[question.id] !== option
                                }"
                                class="w-full text-right p-4 rounded-xl border transition-all duration-200"
                                x-text="option">
                            </button>
                        </template>
                    </div>
                    <div x-show="showResults && answers[question.id]" x-cloak class="mt-3 text-sm font-medium flex items-center gap-1"
                         :class="answers[question.id] === question.correct_answer ? 'text-green-400' : 'text-red-400'">
                        <span x-show="answers[question.id] === question.correct_answer" class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('messages.mcq_quiz.correct') }}
                        </span>
                        <span x-show="answers[question.id] !== question.correct_answer" class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('messages.mcq_quiz.wrong') }} <span x-text="question.correct_answer" class="font-bold"></span>
                        </span>
                    </div>
                    <div x-show="showResults && answers[question.id] && question.explanation" x-cloak class="mt-2 text-sm text-[#f8fafc]/60 bg-white/5 rounded-lg p-3 border border-white/5">
                        <span class="text-[#C9A84C] font-bold">{{ __('messages.mcq_quiz.explanation') }}:</span> <span x-text="question.explanation"></span>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="showResults" x-cloak x-transition class="mt-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 text-center">
            <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" :class="correctCount >= Math.ceil(totalQuestions / 2) ? 'bg-green-500/20' : 'bg-yellow-500/20'">
                <svg x-show="correctCount >= Math.ceil(totalQuestions / 2)" class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <svg x-show="correctCount < Math.ceil(totalQuestions / 2)" class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 18 16.5 18c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="text-5xl font-bold text-[#C9A84C] mb-2" x-text="correctCount + '/' + totalQuestions"></div>
            <p class="text-[#f8fafc]/70 mb-2">{{ __('messages.mcq_quiz.final_result') }}</p>
            <div class="flex justify-center gap-2 mb-6">
                <template x-for="(q, i) in questions" :key="'badge-'+i">
                    <span class="w-3 h-3 rounded-full" :class="answers[q.id] === q.correct_answer ? 'bg-green-400' : 'bg-red-400'"></span>
                </template>
            </div>
            <div class="flex justify-center gap-4">
                <a href="{{ route('quran.show', $surah) }}" class="bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">
                    {{ __('messages.mcq_quiz.back_to_surah') }}
                </a>
                <a href="{{ route('quiz.mcq', $surah) }}" class="bg-[#8B5CF6] hover:bg-[#7C3AED] text-white px-6 py-3 rounded-xl transition">
                    {{ __('messages.mcq_quiz.new_quiz') }}
                </a>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-[#8B5CF6]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-[#8B5CF6] mb-2">{{ __('messages.mcq_quiz.no_questions') }}</h3>
            <p class="text-[#f8fafc]/60 mb-6">{{ __('messages.mcq_quiz.no_questions_desc') }}</p>
            <a href="{{ route('quran.show', $surah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">{{ __('messages.mcq_quiz.back_to_surah') }}</a>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    function mcqQuizApp(questionsData) {
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
