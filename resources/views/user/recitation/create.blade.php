<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">تسميع - سورة {{ $ayah->surah->name_ar }} آية {{ $ayah->number_in_surah }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="audioRecorder()">
        <!-- عرض نص الآية -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 mb-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <div class="text-3xl leading-loose text-center mb-4" style="font-family: 'Noto Naskh Arabic', serif;">{{ $ayah->text_uthmani }}</div>
            <div class="text-center text-[#f8fafc]/60">سورة {{ $ayah->surah->name_ar }} - آية {{ $ayah->number_in_surah }}</div>
        </div>

        <!-- زر الاستماع للآية -->
        @if($ayah->audio_url)
        <div class="text-center mb-6">
            <button onclick="new Audio('{{ $ayah->audio_url }}').play()" class="bg-[#C9A84C]/20 text-[#C9A84C] hover:bg-[#C9A84C]/30 px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 border border-[#C9A84C]/30">
                🔊 استمع للتلاوة الصحيحة
            </button>
        </div>
        @endif

        <!-- واجهة التسجيل -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8">
            <div class="text-center mb-6">
                <!-- حالة عدم التسجيل -->
                <div x-show="!isRecording && !audioBlob">
                    <button @click="startRecording" class="bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] hover:from-[#2E7D32] hover:to-[#388E3C] text-white text-xl px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-[#1B5E20]/30">
                        🎤 ابدأ التسجيل
                    </button>
                    <p class="text-[#f8fafc]/40 text-sm mt-3">أو ارفع ملفاً صوتياً:</p>
                    <label class="inline-block mt-2 cursor-pointer bg-white/5 border border-white/10 hover:bg-white/10 text-[#f8fafc]/70 px-6 py-3 rounded-xl transition">
                        📤 ارفع ملف صوتي
                        <input type="file" accept="audio/*" @change="handleFileUpload($event)" class="hidden">
                    </label>
                </div>

                <!-- حالة التسجيل -->
                <div x-show="isRecording" x-cloak>
                    <div class="space-y-4">
                        <div class="flex items-center justify-center gap-3">
                            <span class="inline-block w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-red-400 text-xl">جاري التسجيل...</span>
                            <span class="text-[#C9A84C] text-xl font-mono" x-text="formatTime(timer)"></span>
                        </div>
                        <!-- شريط الصوت المرئي -->
                        <div class="flex items-center justify-center gap-1 h-12">
                            <template x-for="i in 20" :key="i">
                                <div class="w-1 bg-[#1B5E20] rounded-full animate-pulse" :style="'height:' + (Math.random() * 40 + 8) + 'px; animation-delay:' + (i * 0.05) + 's'"></div>
                            </template>
                        </div>
                        <button @click="stopRecording" class="bg-red-600 hover:bg-red-700 text-white text-xl px-8 py-4 rounded-xl transition-all duration-300 shadow-lg shadow-red-600/30">
                            ⏹️ إيقاف التسجيل
                        </button>
                    </div>
                </div>
            </div>

            <!-- بعد التسجيل -->
            <div x-show="audioBlob && !isRecording" x-cloak class="text-center mt-4">
                <div class="text-green-400 mb-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    تم التسجيل بنجاح
                </div>
                <audio x-ref="audioPreview" controls class="mx-auto mb-4 rounded-lg"></audio>
                <div class="flex justify-center gap-4">
                    <button @click="resetRecording" class="bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">
                        🔄 إعادة التسجيل
                    </button>
                    <button @click="submitRecording" class="bg-gradient-to-r from-[#C9A84C] to-[#D4AF37] hover:from-[#D4AF37] hover:to-[#E5C158] text-[#0F172A] font-bold text-lg px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">✅ أرسل للتقييم</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            جاري التقييم...
                        </span>
                    </button>
                </div>
            </div>

            <!-- نتيجة التقييم -->
            <div x-show="result" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-8">
                <div class="bg-white/5 backdrop-blur-md border rounded-2xl p-8" :class="result && result.is_passed ? 'border-green-500/30' : 'border-red-500/30'">
                    <div class="text-center mb-6">
                        <div class="text-6xl font-bold mb-2" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'" x-text="result ? result.similarity_score + '%' : ''"></div>
                        <div class="text-2xl font-bold" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'" x-text="result && result.is_passed ? '🎉 أحسنت! تسميع ناجح' : '😔 حاول مرة أخرى'"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="text-2xl font-bold text-[#C9A84C]" x-text="result ? result.mistakes_count : 0"></div>
                            <div class="text-[#f8fafc]/60 text-sm">عدد الأخطاء</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="text-2xl font-bold" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'" x-text="result && result.is_passed ? 'ناجح ✅' : 'راسب ❌'"></div>
                            <div class="text-[#f8fafc]/60 text-sm">الحكم</div>
                        </div>
                    </div>
                    <div x-show="result && !result.is_passed" class="mt-4 text-center">
                        <button @click="resetRecording" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition mt-2">
                            🔄 حاول مرة أخرى
                        </button>
                    </div>
                </div>
            </div>

            <!-- خطأ -->
            <div x-show="errorMsg" x-cloak class="mt-4 bg-red-500/10 border border-red-500/30 rounded-xl p-4 text-center text-red-400" x-text="errorMsg"></div>
        </div>
    </div>

    @push('scripts')
    <script>
    function audioRecorder() {
        return {
            isRecording: false,
            isSubmitting: false,
            mediaRecorder: null,
            audioChunks: [],
            audioBlob: null,
            timer: 0,
            timerInterval: null,
            result: null,
            errorMsg: null,

            formatTime(seconds) {
                const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                const s = (seconds % 60).toString().padStart(2, '0');
                return m + ':' + s;
            },

            startRecording() {
                this.result = null;
                this.errorMsg = null;
                this.audioBlob = null;
                this.audioChunks = [];
                this.timer = 0;
                navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
                    this.mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });
                    this.mediaRecorder.ondataavailable = e => this.audioChunks.push(e.data);
                    this.mediaRecorder.onstop = () => {
                        this.audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        stream.getTracks().forEach(t => t.stop());
                        // Set audio preview
                        this.$nextTick(() => {
                            if (this.$refs.audioPreview) {
                                this.$refs.audioPreview.src = URL.createObjectURL(this.audioBlob);
                            }
                        });
                    };
                    this.mediaRecorder.start();
                    this.isRecording = true;
                    this.timerInterval = setInterval(() => this.timer++, 1000);
                }).catch(() => {
                    this.errorMsg = 'لا يمكن الوصول للميكروفون. يرجى السماح بالوصول من إعدادات المتصفح.';
                });
            },

            stopRecording() {
                if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                    this.mediaRecorder.stop();
                    this.isRecording = false;
                    clearInterval(this.timerInterval);
                }
            },

            resetRecording() {
                this.audioBlob = null;
                this.audioChunks = [];
                this.result = null;
                this.errorMsg = null;
                this.timer = 0;
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.audioBlob = file;
                    this.result = null;
                    this.errorMsg = null;
                    this.$nextTick(() => {
                        if (this.$refs.audioPreview) {
                            this.$refs.audioPreview.src = URL.createObjectURL(file);
                        }
                    });
                }
            },

            submitRecording() {
                if (!this.audioBlob) return;
                this.isSubmitting = true;
                this.errorMsg = null;
                const formData = new FormData();
                formData.append('audio', this.audioBlob, 'recording.webm');
                fetch('{{ route("recitation.store", $ayah) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        this.result = data.result;
                    } else {
                        this.errorMsg = data.message || 'حدث خطأ في التقييم';
                    }
                    this.isSubmitting = false;
                }).catch(() => {
                    this.errorMsg = 'حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.';
                    this.isSubmitting = false;
                });
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
