<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">{{ __('messages.recitation.title', ['surah' => $ayah->surah->name_ar, 'ayah' => $ayah->number_in_surah]) }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="audioRecorder()">
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 mb-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <div class="text-3xl leading-loose text-center mb-4" style="font-family: 'Noto Naskh Arabic', serif;">{{ $ayah->text_uthmani }}</div>
            <div class="text-center text-[#f8fafc]/60">{{ __('messages.dashboard.surah') }} {{ $ayah->surah->name_ar }} - {{ __('messages.dashboard.ayah') }} {{ $ayah->number_in_surah }}</div>
        </div>

        @if($ayah->audio_url)
        <div class="text-center mb-6">
            <button onclick="new Audio('{{ $ayah->audio_url }}').play()" class="flex items-center gap-2 mx-auto bg-[#C9A84C]/20 text-[#C9A84C] hover:bg-[#C9A84C]/30 px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 border border-[#C9A84C]/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                {{ __('messages.quran.listen') }}
            </button>
        </div>
        @endif

        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8">
            <div class="text-center mb-6">
                <div x-show="!isRecording && !hasRecording">
                    <button @click="startRecording()" class="flex items-center gap-2 mx-auto bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] hover:from-[#2E7D32] hover:to-[#388E3C] text-white text-xl px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-[#1B5E20]/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        {{ __('messages.recitation.start_recording') }}
                    </button>
                    <p class="text-[#f8fafc]/40 text-sm mt-3">{{ __('messages.recitation.or_upload') }}</p>
                    <label class="inline-flex items-center gap-2 mt-2 cursor-pointer bg-white/5 border border-white/10 hover:bg-white/10 text-[#f8fafc]/70 px-6 py-3 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        {{ __('messages.recitation.upload_file') }}
                        <input type="file" accept="audio/*" @change="handleFileUpload($event)" class="hidden">
                    </label>
                </div>

                <div x-show="isRecording" x-cloak>
                    <div class="space-y-4">
                        <div class="flex items-center justify-center gap-3">
                            <span class="inline-block w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-red-400 text-xl">{{ __('messages.recitation.recording') }}</span>
                            <span class="text-[#C9A84C] text-xl font-mono" x-text="formatTime(timer)"></span>
                        </div>
                        <div class="flex items-center justify-center gap-1 h-12">
                            <template x-for="i in 20" :key="i">
                                <div class="w-1 bg-[#1B5E20] rounded-full animate-pulse" :style="'height:' + (Math.random() * 40 + 8) + 'px; animation-delay:' + (i * 0.05) + 's'"></div>
                            </template>
                        </div>
                        <button @click="stopRecording()" class="flex items-center gap-2 mx-auto bg-red-600 hover:bg-red-700 text-white text-xl px-8 py-4 rounded-xl transition-all duration-300 shadow-lg shadow-red-600/30">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="6" width="12" height="12" rx="2"/></svg>
                            {{ __('messages.recitation.stop_recording') }}
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="hasRecording && !isRecording" x-cloak class="text-center mt-4">
                <div class="text-green-400 mb-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ __('messages.recitation.recorded_success') }}
                </div>
                <audio x-ref="audioPreview" controls class="mx-auto mb-4 rounded-lg"></audio>
                <div class="text-[#f8fafc]/40 text-xs mb-4" x-text="'Size: ' + recordedSize"></div>
                <div class="flex justify-center gap-4">
                    <button @click="resetRecording()" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-[#f8fafc] px-6 py-3 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        {{ __('messages.recitation.re_record') }}
                    </button>
                    <button @click="submitRecording()" class="flex items-center gap-2 bg-gradient-to-r from-[#C9A84C] to-[#D4AF37] hover:from-[#D4AF37] hover:to-[#E5C158] text-[#0F172A] font-bold text-lg px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg" :disabled="isSubmitting">
                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('messages.recitation.submit_eval') }}
                        </span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            {{ __('messages.recitation.evaluating') }}
                        </span>
                    </button>
                </div>
            </div>

            <div x-show="result" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-8">
                <div class="bg-white/5 backdrop-blur-md border rounded-2xl p-8" :class="result && result.is_passed ? 'border-green-500/30' : 'border-red-500/30'">
                    {{-- Score and Verdict --}}
                    <div class="text-center mb-6">
                        <div class="text-6xl font-bold mb-2" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'" x-text="result ? result.similarity_score + '%' : ''"></div>
                        <div class="flex items-center justify-center gap-2 text-2xl font-bold" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'">
                            <svg x-show="result && result.is_passed" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <svg x-show="result && !result.is_passed" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="result && result.is_passed ? '{{ __("messages.recitation.excellent") }}' : '{{ __("messages.recitation.try_again_msg") }}'"></span>
                        </div>
                        <p class="text-[#f8fafc]/40 text-sm mt-2" x-text="result && result.is_passed ? 'تلاوتك تجاوزت حد النجاح (90%) — أحسنت!' : 'درجتك أقل من حد النجاح (90%) — حاول مرة أخرى'"></p>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-3 gap-4 text-center mb-6">
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="text-2xl font-bold text-[#C9A84C]" x-text="result ? result.similarity_score + '%' : '0'"></div>
                            <div class="text-[#f8fafc]/60 text-xs">نسبة التطابق</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="text-2xl font-bold text-[#C9A84C]" x-text="result ? result.mistakes_count : 0"></div>
                            <div class="text-[#f8fafc]/60 text-xs">{{ __('messages.recitation.mistakes_count') }}</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex items-center justify-center gap-1 text-2xl font-bold" :class="result && result.is_passed ? 'text-green-400' : 'text-red-400'">
                                <span x-text="result && result.is_passed ? '{{ __("messages.recitation.passed") }}' : '{{ __("messages.recitation.failed") }}'"></span>
                            </div>
                            <div class="text-[#f8fafc]/60 text-xs">{{ __('messages.recitation.verdict') }}</div>
                        </div>
                    </div>

                    {{-- Word-by-Word Analysis --}}
                    <div x-show="result && result.word_diff && result.word_diff.length > 0" class="mb-6">
                        <h4 class="text-[#C9A84C] font-bold mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            تحليل الكلمات
                        </h4>
                        <div class="bg-white/5 rounded-xl p-4 flex flex-wrap gap-2 text-right" dir="rtl" style="font-family: 'Noto Naskh Arabic', serif;">
                            <template x-for="(word, idx) in (result ? result.word_diff : [])" :key="idx">
                                <span class="inline-block px-2 py-1 rounded-lg text-lg transition-all cursor-default"
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
                        {{-- Legend --}}
                        <div class="flex flex-wrap gap-4 mt-3 text-xs text-[#f8fafc]/50">
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-green-500/30 border border-green-500/40"></span> صحيح</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-yellow-500/30 border border-yellow-500/40"></span> قريب</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-500/30 border border-red-500/40"></span> خطأ</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-500/10 border border-red-500/20"></span> مفقود</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-500/30 border border-blue-500/40"></span> زائد</span>
                        </div>
                    </div>

                    {{-- Transcribed Text --}}
                    <div x-show="result && result.transcribed_text" class="mb-6">
                        <h4 class="text-[#f8fafc]/60 font-bold mb-2 text-sm">ما سمعه النظام من تلاوتك:</h4>
                        <div class="bg-white/5 rounded-xl p-4 text-lg text-right leading-loose text-[#f8fafc]/80" dir="rtl" style="font-family: 'Noto Naskh Arabic', serif;" x-text="result ? result.transcribed_text : ''"></div>
                    </div>

                    {{-- Retry Button --}}
                    <div x-show="result && !result.is_passed" class="mt-4 text-center">
                        <button @click="resetRecording()" class="flex items-center gap-2 mx-auto bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition mt-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            {{ __('messages.recitation.try_again_btn') }}
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="errorMsg" x-cloak class="mt-4 bg-red-500/10 border border-red-500/30 rounded-xl p-4 text-center text-red-400" x-text="errorMsg"></div>
        </div>
    </div>

    @push('scripts')
    <script>
    function audioRecorder() {
        var recorder = null;
        var stream = null;
        var chunks = [];
        var recordedBlob = null;
        var mimeType = 'audio/webm';

        return {
            isRecording: false,
            isSubmitting: false,
            hasRecording: false,
            recordedSize: '',
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
                            self.errorMsg = 'No audio data captured';
                            return;
                        }

                        var blobType = mimeType ? mimeType.split(';')[0] : 'audio/webm';
                        recordedBlob = new Blob(chunks, { type: blobType });

                        self.hasRecording = true;
                        self.recordedSize = (recordedBlob.size / 1024).toFixed(1) + ' KB';

                        self.$nextTick(function() {
                            var audio = self.$refs.audioPreview;
                            if (audio) {
                                audio.src = URL.createObjectURL(recordedBlob);
                                audio.load();
                                audio.play().catch(function(){});
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
                    self.errorMsg = '{{ __("messages.recitation.mic_error") }}';
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
                this.recordedSize = '';
                this.result = null;
                this.errorMsg = null;
                this.timer = 0;
            },

            handleFileUpload(event) {
                var file = event.target.files[0];
                if (file) {
                    recordedBlob = file;
                    this.hasRecording = true;
                    this.recordedSize = (file.size / 1024).toFixed(1) + ' KB';
                    this.result = null;
                    this.errorMsg = null;
                    this.$nextTick(function() {
                        var audio = this.$refs.audioPreview;
                        if (audio) {
                            audio.src = URL.createObjectURL(file);
                            audio.load();
                        }
                    }.bind(this));
                }
            },

            submitRecording() {
                if (!recordedBlob) return;
                var self = this;
                self.isSubmitting = true;
                self.errorMsg = null;
                var formData = new FormData();
                var ext = mimeType.includes('ogg') ? 'ogg' : 'webm';
                formData.append('audio', recordedBlob, 'recording.' + ext);
                fetch('{{ route("recitation.store", $ayah) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(data) {
                    if (data.success) {
                        self.result = data.result;
                    } else {
                        self.errorMsg = data.message || '{{ __("messages.recitation.eval_error") }}';
                    }
                    self.isSubmitting = false;
                }).catch(function() {
                    self.errorMsg = '{{ __("messages.recitation.submit_error") }}';
                    self.isSubmitting = false;
                });
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
