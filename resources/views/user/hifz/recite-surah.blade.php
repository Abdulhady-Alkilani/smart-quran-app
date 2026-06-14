<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="text-2xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">تسميع سورة {{ $surah->name_ar }} غيبياً</h2>
            <a href="{{ route('hifz.surah.select') }}" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition text-sm">
                <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                تغيير السورة
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="surahBlindRecorder()">
        <div class="bg-purple-500/10 backdrop-blur-md border border-purple-500/20 rounded-2xl p-8 mb-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 via-[#C9A84C] to-purple-500"></div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-purple-500/20 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/20">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="text-3xl font-bold text-[#C9A84C] mb-2" style="font-family: 'Amiri', serif;">سورة {{ $surah->name_ar }}</h3>
                <p class="text-[#f8fafc]/60 text-sm mb-4">قم بتسميع السورة كاملة غيبياً دون النظر إلى المصحف.</p>
                <div class="flex items-center justify-center gap-3">
                    <span class="px-4 py-2 rounded-full bg-[#C9A84C]/10 text-[#C9A84C] text-sm border border-[#C9A84C]/20">عدد الآيات: {{ $surah->total_ayahs }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 relative overflow-hidden">
            <div class="text-center mb-6">
                <div x-show="!isRecording && !hasRecording" x-transition>
                    <button @click="startRecording()" class="flex items-center gap-2 mx-auto bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white text-xl px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-600/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        بدء التسجيل للتسميع
                    </button>
                    <p class="text-[#f8fafc]/40 text-sm mt-3">تحدث بوضوح عبر الميكروفون</p>
                </div>

                <div x-show="isRecording" x-cloak>
                    <div class="space-y-4">
                        <div class="flex items-center justify-center gap-3">
                            <span class="inline-block w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-red-400 text-xl">جاري التسجيل...</span>
                            <span class="text-[#C9A84C] text-xl font-mono" x-text="formatTime(timer)"></span>
                        </div>
                        <div class="flex items-center justify-center gap-1 h-12">
                            <template x-for="i in 30" :key="i">
                                <div class="w-1 bg-purple-500 rounded-full animate-pulse" :style="'height:' + (Math.random() * 40 + 8) + 'px; animation-delay:' + (i * 0.05) + 's'"></div>
                            </template>
                        </div>
                        <button @click="stopRecording()" class="flex items-center gap-2 mx-auto bg-red-600 hover:bg-red-700 text-white text-xl px-8 py-4 rounded-xl transition-all duration-300 shadow-lg shadow-red-600/30">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
                            إيقاف التسجيل
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="hasRecording && !isRecording" x-cloak class="text-center mt-4">
                <div class="text-green-400 mb-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    تم تسجيل التسميع بنجاح
                </div>
                <audio x-ref="audioPreview" controls class="mx-auto mb-4 rounded-lg w-full max-w-md"></audio>
                
                <div class="flex justify-center gap-4">
                    <button @click="resetRecording()" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        إعادة التسجيل
                    </button>
                    <button @click="submitRecording()" class="flex items-center gap-2 bg-gradient-to-r from-[#C9A84C] to-[#D4AF37] hover:from-[#D4AF37] hover:to-[#E5C158] text-[#0F172A] font-bold text-lg px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg" :disabled="isSubmitting">
                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            تقييم التسميع
                        </span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            جاري التقييم...
                        </span>
                    </button>
                </div>
            </div>

            <div x-show="result" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-8">
                <div class="bg-white/5 backdrop-blur-md border rounded-2xl p-8" :class="result && result.is_passed ? 'border-green-500/30' : 'border-red-500/30'">
                    <div class="text-center mb-6">
                        <div class="text-6xl font-bold mb-2" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'" x-text="result ? result.similarity_score + '%' : ''"></div>
                        <div class="flex items-center justify-center gap-2 text-2xl font-bold" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'">
                            <svg x-show="result && result.is_passed" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <svg x-show="result && !result.is_passed" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="result && result.is_passed ? 'ممتاز، حفظ متقن!' : 'حاول مرة أخرى لتحسين الحفظ'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-center mb-6">
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="text-2xl font-bold text-[#C9A84C]" x-text="result ? result.similarity_score + '%' : '0'"></div>
                            <div class="text-[#f8fafc]/60 text-xs">نسبة التطابق</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="text-2xl font-bold text-[#C9A84C]" x-text="result ? result.mistakes_count : 0"></div>
                            <div class="text-[#f8fafc]/60 text-xs">الأخطاء المكتشفة</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex items-center justify-center gap-1 text-2xl font-bold" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'">
                                <span x-text="result && result.is_passed ? 'ناجح' : 'لم يجتز'"></span>
                            </div>
                            <div class="text-[#f8fafc]/60 text-xs">النتيجة النهائية</div>
                        </div>
                    </div>

                    <div x-show="result && result.word_diff && result.word_diff.length > 0" class="mb-6">
                        <h4 class="text-[#C9A84C] font-bold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            تحليل الكلمات التفصيلي
                        </h4>
                        <div class="bg-white/5 rounded-xl p-4 max-h-80 overflow-y-auto flex flex-wrap gap-2 text-right" dir="rtl" style="font-family: 'Noto Naskh Arabic', serif; font-size: 1.25rem; line-height: 2.5;">
                            <template x-for="(word, idx) in (result ? result.word_diff : [])" :key="idx">
                                <span class="inline-block px-2 py-1 rounded-lg transition-all cursor-default"
                                    :class="{
                                        'bg-green-500/20 text-green-300 border border-green-500/30': word.status === 'correct',
                                        'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30': word.status === 'partial',
                                        'bg-red-500/20 text-red-300 border border-red-500/30 line-through': word.status === 'wrong',
                                        'bg-red-500/10 text-red-400 border border-red-500/20 opacity-60': word.status === 'missing',
                                        'bg-blue-500/20 text-blue-300 border border-blue-500/30': word.status === 'extra',
                                    }"
                                    :title="word.status === 'correct' ? 'صحيح ✓' : (word.status === 'partial' ? 'قريب — المطلوب: ' + word.expected : (word.status === 'wrong' ? 'خطأ — المطلوب: ' + word.expected + ' / قلت: ' + word.got : (word.status === 'missing' ? 'كلمة مفقودة' : 'كلمة زائدة')))"
                                    x-text="word.status === 'missing' ? '⌀ ' + word.expected : (word.status === 'extra' ? '+ ' + word.got : (word.status === 'wrong' ? word.got : word.expected))">
                                </span>
                            </template>
                        </div>
                        <div class="flex flex-wrap gap-4 mt-3 text-xs text-[#f8fafc]/50">
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-green-500/30 border border-green-500/40"></span> صحيح</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-yellow-500/30 border border-yellow-500/40"></span> قريب</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-500/30 border border-red-500/40"></span> خطأ</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-500/10 border border-red-500/20"></span> مفقود</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-500/30 border border-blue-500/40"></span> زائد</span>
                        </div>
                    </div>

                    <div x-show="result && result.reference_uthmani" class="mb-6">
                        <h4 class="text-[#f8fafc]/60 font-bold mb-2 text-sm">النص القرآني الصحيح (للمراجعة)</h4>
                        <div class="bg-white/5 rounded-xl p-4 text-xl text-right leading-[2.5] text-[#C9A84C]" dir="rtl" style="font-family: 'Noto Naskh Arabic', serif;" x-text="result ? result.reference_uthmani : ''"></div>
                    </div>

                    <div class="flex justify-center gap-4 mt-6">
                        <button @click="resetRecording()" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            إعادة تسميع السورة
                        </button>
                        <a href="{{ route('hifz.surah.select') }}" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">
                            <svg class="w-5 h-5 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            اختيار سورة أخرى
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="errorMsg" x-cloak class="mt-4 bg-red-500/10 border border-red-500/30 rounded-xl p-4 text-center text-red-400" x-text="errorMsg"></div>
        </div>
    </div>

    @push('scripts')
    <script>
    function surahBlindRecorder() {
        var recorder = null;
        var stream = null;
        var chunks = [];
        var recordedBlob = null;
        var mimeType = 'audio/webm';

        return {
            isRecording: false,
            isSubmitting: false,
            hasRecording: false,
            timer: 0,
            timerInterval: null,
            result: null,
            errorMsg: null,

            formatTime(seconds) {
                var m = Math.floor(seconds / 60).toString().padStart(2, '0');
                var s = (seconds % 60).toString().padStart(2, '0');
                return m + ':' + s;
            },

            startRecording() {
                var self = this;
                this.result = null;
                this.errorMsg = null;
                this.hasRecording = false;
                this.timer = 0;
                chunks = [];

                navigator.mediaDevices.getUserMedia({
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    }
                }).then(function(s) {
                    stream = s;

                    if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
                        mimeType = 'audio/webm;codecs=opus';
                    } else if (MediaRecorder.isTypeSupported('audio/webm')) {
                        mimeType = 'audio/webm';
                    } else if (MediaRecorder.isTypeSupported('audio/ogg;codecs=opus')) {
                        mimeType = 'audio/ogg;codecs=opus';
                    } else {
                        mimeType = '';
                    }

                    var options = mimeType ? { mimeType: mimeType } : {};
                    recorder = new MediaRecorder(stream, options);

                    recorder.ondataavailable = function(e) {
                        if (e.data && e.data.size > 0) {
                            chunks.push(e.data);
                        }
                    };

                    recorder.onstop = function() {
                        if (chunks.length === 0) {
                            self.errorMsg = 'لم يتم التقاط أي صوت.';
                            return;
                        }

                        var blobType = mimeType ? mimeType.split(';')[0] : 'audio/webm';
                        recordedBlob = new Blob(chunks, { type: blobType });

                        self.hasRecording = true;

                        self.$nextTick(function() {
                            var audio = self.$refs.audioPreview;
                            if (audio) {
                                audio.src = URL.createObjectURL(recordedBlob);
                                audio.load();
                            }
                        });

                        if (stream) {
                            stream.getTracks().forEach(function(t) { t.stop(); });
                            stream = null;
                        }
                    };

                    recorder.start();
                    self.isRecording = true;
                    self.timerInterval = setInterval(function() { self.timer++; }, 1000);
                }).catch(function(err) {
                    console.error('Microphone error:', err);
                    self.errorMsg = 'تعذر الوصول إلى الميكروفون. يرجى التحقق من الصلاحيات.';
                });
            },

            stopRecording() {
                if (recorder && recorder.state !== 'inactive') {
                    recorder.stop();
                    this.isRecording = false;
                    clearInterval(this.timerInterval);
                }
            },

            resetRecording() {
                recordedBlob = null;
                chunks = [];
                this.hasRecording = false;
                this.result = null;
                this.errorMsg = null;
                this.timer = 0;
            },

            submitRecording() {
                if (!recordedBlob) return;
                var self = this;
                self.isSubmitting = true;
                self.errorMsg = null;
                var formData = new FormData();
                var ext = mimeType.includes('ogg') ? 'ogg' : 'webm';
                formData.append('audio', recordedBlob, 'recording.' + ext);
                fetch('{{ route("hifz.surah.submit", $surah) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        self.result = data.result;
                    } else {
                        self.errorMsg = data.message || 'حدث خطأ أثناء التقييم.';
                    }
                    self.isSubmitting = false;
                }).catch(function() {
                    self.errorMsg = 'فشل الاتصال بالخادم.';
                    self.isSubmitting = false;
                });
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
