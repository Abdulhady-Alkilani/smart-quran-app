<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.dashboard.title') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] to-[#2E7D32]"></div>
                <div class="text-3xl font-bold text-[#f8fafc] mb-1">{{ $memorizedCount }}</div>
                <div class="text-[#f8fafc]/60 text-sm">{{ __('messages.dashboard.memorized_ayahs') }}</div>
            </div>
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C9A84C] to-[#D4AF37]"></div>
                @php $progressPct = min(100, round($memorizedCount / 62.36, 2)); @endphp
                <div class="text-3xl font-bold text-[#f8fafc] mb-1">{{ $progressPct }}%</div>
                <div class="text-[#f8fafc]/60 text-sm mb-2">{{ __('messages.dashboard.progress') }}</div>
                <div class="w-full bg-white/10 rounded-full h-1.5">
                    <div class="bg-gradient-to-r from-[#C9A84C] to-[#FFD700] rounded-full h-1.5 transition-all duration-1000" style="width: {{ $progressPct }}%"></div>
                </div>
            </div>
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#3B82F6]"></div>
                <div class="text-3xl font-bold text-[#f8fafc] mb-1">{{ $dueReviews->count() }}</div>
                <div class="text-[#f8fafc]/60 text-sm">{{ __('messages.dashboard.due_reviews') }}</div>
            </div>
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-600 to-purple-400"></div>
                <div class="text-3xl font-bold text-[#f8fafc] mb-1">{{ $successRate }}%</div>
                <div class="text-[#f8fafc]/60 text-sm">نسبة النجاح في التسميع</div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('quran.index') }}" class="glass-card p-5 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-[#1B5E20]/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-[#1B5E20]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <div class="font-bold text-[#C9A84C]">{{ __('messages.dashboard.browse_surahs') }}</div>
                    <div class="text-[#f8fafc]/40 text-sm">{{ __('messages.dashboard.browse_surahs_desc') }}</div>
                </div>
            </a>
            <a href="{{ route('reviews.index') }}" class="glass-card p-5 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-[#C9A84C]/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-[#C9A84C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <div class="font-bold text-[#C9A84C]">{{ __('messages.dashboard.daily_review') }}</div>
                    <div class="text-[#f8fafc]/40 text-sm">{{ $dueReviews->count() }} {{ __('messages.dashboard.due_ayahs') }}</div>
                </div>
            </a>
            <a href="{{ route('user.profile.edit') }}" class="glass-card p-5 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-[#C9A84C]">{{ __('messages.dashboard.my_profile') }}</div>
                    <div class="text-[#f8fafc]/40 text-sm">{{ __('messages.dashboard.view_stats') }}</div>
                </div>
            </a>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="glass-card p-6 lg:col-span-2">
                <h3 class="text-lg font-bold text-[#C9A84C] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    نشاط التسميع (30 يوماً)
                </h3>
                <canvas id="activityChart" height="120"></canvas>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-[#C9A84C] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    حالة الحفظ
                </h3>
                <canvas id="statusChart" height="200"></canvas>
                <div class="flex justify-center gap-4 mt-4 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-[#1B5E20]"></span> محفوظ ({{ $chartData['statusDistribution']['memorized'] }})</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-[#C9A84C]"></span> قيد التعلم ({{ $chartData['statusDistribution']['learning'] }})</span>
                </div>
            </div>
        </div>

        @if(count($chartData['scoreData']) > 0)
        <div class="glass-card p-6 mb-8">
            <h3 class="text-lg font-bold text-[#C9A84C] mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                درجات آخر محاولات التسميع
            </h3>
            <canvas id="scoresChart" height="80"></canvas>
        </div>
        @endif

        {{-- Due Reviews + Recent Attempts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                @if($dueReviews->count() > 0)
                <div class="glass-card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="flex items-center gap-2 text-xl font-bold text-[#C9A84C]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ __('messages.dashboard.due_for_review') }}
                        </h3>
                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm font-bold">{{ $dueReviews->count() }}</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($dueReviews->take(5) as $item)
                        <div class="bg-white/5 rounded-xl p-4 flex justify-between items-center hover:bg-white/10 transition group">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-lg mb-1 truncate" style="font-family: 'Noto Naskh Arabic', serif;">{{ Str::limit($item->ayah->text_uthmani, 60) }}</div>
                                <div class="text-sm text-[#f8fafc]/50">{{ __('messages.dashboard.surah') }} {{ $item->ayah->surah->name_ar }} - {{ __('messages.dashboard.ayah') }} {{ $item->ayah->number_in_surah }}</div>
                            </div>
                            <a href="{{ route('recitation.create', $item->ayah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-lg transition flex-shrink-0 {{ app()->getLocale() === 'ar' ? 'mr-4' : 'ml-4' }} opacity-80 group-hover:opacity-100">{{ __('messages.dashboard.recite') }}</a>
                        </div>
                        @endforeach
                    </div>
                    @if($dueReviews->count() > 5)
                    <a href="{{ route('reviews.index') }}" class="block text-center text-[#C9A84C] hover:text-[#FFD700] mt-4 transition">{{ __('messages.dashboard.show_all') }} ({{ $dueReviews->count() }}) &larr;</a>
                    @endif
                </div>
                @else
                <div class="glass-card p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#C9A84C] mb-2">{{ __('messages.dashboard.no_reviews') }}</h3>
                    <p class="text-[#f8fafc]/50">{{ __('messages.dashboard.well_done') }}</p>
                    <a href="{{ route('quran.index') }}" class="inline-flex items-center gap-2 mt-4 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-2 rounded-lg transition">
                        {{ __('messages.dashboard.browse_surahs_btn') }}
                    </a>
                </div>
                @endif
            </div>

            <div>
                @if($recentAttempts->count() > 0)
                <div class="glass-card p-6">
                    <h3 class="flex items-center gap-2 text-xl font-bold text-[#C9A84C] mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        {{ __('messages.dashboard.recent_attempts') }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($recentAttempts->take(5) as $attempt)
                        <div class="bg-white/5 rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <div class="font-bold">{{ $attempt->ayah->surah->name_ar }} - {{ __('messages.dashboard.ayah') }} {{ $attempt->ayah->number_in_surah }}</div>
                                <div class="text-sm text-[#f8fafc]/40">{{ $attempt->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-bold {{ $attempt->is_passed ? 'text-green-400' : 'text-red-400' }}">{{ number_format($attempt->similarity_score, 1) }}%</span>
                                <span class="w-8 h-8 rounded-full flex items-center justify-center {{ $attempt->is_passed ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                    @if($attempt->is_passed)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="glass-card p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-purple-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#C9A84C] mb-2">{{ __('messages.dashboard.no_attempts') }}</h3>
                    <p class="text-[#f8fafc]/50">{{ __('messages.dashboard.start_memorizing') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var chartData = @json($chartData);

        function getChartColors() {
            var isLight = document.documentElement.classList.contains('light-mode');
            return {
                text: isLight ? 'rgba(55, 65, 81, 0.7)' : 'rgba(248, 250, 252, 0.4)',
                grid: isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.05)',
                doughnutNew: isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.1)',
                doughnutBorder: isLight ? 'rgba(255, 255, 255, 1)' : 'rgba(255, 255, 255, 0.2)'
            };
        }

        var colors = getChartColors();
        var charts = {};

        // Activity Line Chart
        charts.activity = new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: chartData.dailyLabels,
                datasets: [{
                    label: 'محاولات التسميع',
                    data: chartData.dailyActivity,
                    borderColor: '#C9A84C',
                    backgroundColor: 'rgba(201, 168, 76, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#C9A84C',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: colors.text, maxTicksLimit: 10 }, grid: { color: colors.grid } },
                    y: { beginAtZero: true, ticks: { color: colors.text, stepSize: 1 }, grid: { color: colors.grid } }
                }
            }
        });

        // Status Doughnut Chart
        charts.status = new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['محفوظ', 'قيد التعلم', 'جديد'],
                datasets: [{
                    data: [chartData.statusDistribution.memorized, chartData.statusDistribution.learning, Math.min(chartData.statusDistribution.new, 100)],
                    backgroundColor: ['#1B5E20', '#C9A84C', colors.doughnutNew],
                    borderColor: ['#2E7D32', '#D4AF37', colors.doughnutBorder],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });

        // Scores Bar Chart
        var scoresEl = document.getElementById('scoresChart');
        if (scoresEl && chartData.scoreData.length > 0) {
            charts.scores = new Chart(scoresEl, {
                type: 'bar',
                data: {
                    labels: chartData.scoreLabels,
                    datasets: [{
                        label: 'الدرجة %',
                        data: chartData.scoreData,
                        backgroundColor: chartData.scoreColors,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { color: colors.text }, grid: { display: false } },
                        y: { beginAtZero: true, max: 100, ticks: { color: colors.text, callback: function(v){return v+'%';} }, grid: { color: colors.grid } }
                    }
                }
            });
        }

        // Listen for theme change
        document.addEventListener('themeChanged', function() {
            var newColors = getChartColors();
            
            // Update Activity Chart
            if (charts.activity) {
                charts.activity.options.scales.x.ticks.color = newColors.text;
                charts.activity.options.scales.x.grid.color = newColors.grid;
                charts.activity.options.scales.y.ticks.color = newColors.text;
                charts.activity.options.scales.y.grid.color = newColors.grid;
                charts.activity.update();
            }

            // Update Doughnut Chart
            if (charts.status) {
                charts.status.data.datasets[0].backgroundColor[2] = newColors.doughnutNew;
                charts.status.data.datasets[0].borderColor[2] = newColors.doughnutBorder;
                charts.status.update();
            }

            // Update Scores Chart
            if (charts.scores) {
                charts.scores.options.scales.x.ticks.color = newColors.text;
                charts.scores.options.scales.y.ticks.color = newColors.text;
                charts.scores.options.scales.y.grid.color = newColors.grid;
                charts.scores.update();
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
