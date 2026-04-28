<?php

namespace App\Console\Commands;

use App\Models\Ayah;
use App\Models\Surah;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncQuranDataCommand extends Command
{
    protected $signature = 'quran:sync';

    protected $description = 'جلب بيانات القرآن الكريم (السور والآيات) من API وحفظها في قاعدة البيانات';

    public function handle(): int
    {
        $this->info('بدء جلب بيانات القرآن الكريم...');

        $this->syncSurahs();
        $this->syncAyahs();

        $this->info('تم جلب بيانات القرآن الكريم بنجاح!');

        return self::SUCCESS;
    }

    private function syncSurahs(): void
    {
        $this->info('جلب بيانات السور...');

        $response = Http::timeout(120)->get('http://api.alquran.cloud/v1/surah');

        if (! $response->successful()) {
            $this->error('فشل في جلب بيانات السور من API');

            return;
        }

        $surahs = $response->json('data');

        $bar = $this->output->createProgressBar(count($surahs));
        $bar->start();

        foreach ($surahs as $surahData) {
            Surah::updateOrCreate(
                ['number' => $surahData['number']],
                [
                    'name_ar' => $surahData['name'],
                    'name_en' => $surahData['englishName'],
                    'revelation_type' => $surahData['revelationType'],
                    'total_ayahs' => $surahData['numberOfAyahs'],
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('تم حفظ '.count($surahs).' سورة بنجاح.');
    }

    private function syncAyahs(): void
    {
        $this->info('جلب بيانات الآيات (النص العثماني)...');

        $response = Http::timeout(300)->get('http://api.alquran.cloud/v1/quran/quran-uthmani');

        if (! $response->successful()) {
            $this->error('فشل في جلب بيانات الآيات من API');

            return;
        }

        $surahsData = $response->json('data.surahs');

        $totalAyahs = 0;
        foreach ($surahsData as $surahData) {
            $totalAyahs += count($surahData['ayahs']);
        }

        $bar = $this->output->createProgressBar($totalAyahs);
        $bar->start();

        // جلب النص الإملائي البسيط (بدون تشكيل) من edition "ar"
        $this->info(' جلب النص الإملائي...');
        $simpleResponse = Http::timeout(300)->get('http://api.alquran.cloud/v1/quran/ar.asad');
        $simpleSurahs = null;
        if ($simpleResponse->successful()) {
            $simpleSurahs = $simpleResponse->json('data.surahs');
        }

        // جلب الصوت من القارئ العفاسي
        $audioResponse = Http::timeout(300)->get('http://api.alquran.cloud/v1/quran/ar.alafasy');
        $audioSurahs = null;
        if ($audioResponse->successful()) {
            $audioSurahs = $audioResponse->json('data.surahs');
        }

        foreach ($surahsData as $surahIndex => $surahData) {
            $surah = Surah::where('number', $surahData['number'])->first();

            if (! $surah) {
                continue;
            }

            foreach ($surahData['ayahs'] as $ayahIndex => $ayahData) {
                $audioUrl = null;

                if ($audioSurahs && isset($audioSurahs[$surahIndex]['ayahs'][$ayahIndex])) {
                    $audioUrl = $audioSurahs[$surahIndex]['ayahs'][$ayahIndex]['audio'] ?? null;
                }

                // النص الإملائي: إما من API أو بحذف التشكيل من النص العثماني
                $imlaeiText = $this->stripDiacritics($ayahData['text']);
                if ($simpleSurahs && isset($simpleSurahs[$surahIndex]['ayahs'][$ayahIndex])) {
                    $simpleText = $simpleSurahs[$surahIndex]['ayahs'][$ayahIndex]['text'] ?? null;
                    if ($simpleText) {
                        $imlaeiText = $this->stripDiacritics($simpleText);
                    }
                }

                Ayah::updateOrCreate(
                    [
                        'surah_id' => $surah->id,
                        'number_in_surah' => $ayahData['numberInSurah'],
                    ],
                    [
                        'number_in_quran' => $ayahData['number'],
                        'text_uthmani' => $ayahData['text'],
                        'text_imlaei' => $imlaeiText,
                        'audio_url' => $audioUrl,
                    ]
                );

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("تم حفظ {$totalAyahs} آية بنجاح.");
    }

    /**
     * إزالة التشكيل والعلامات من النص العربي للحصول على نص إملائي نظيف
     */
    private function stripDiacritics(string $text): string
    {
        // حذف التشكيل والعلامات الصوتية
        $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}]/u', '', $text);
        // توحيد الألف
        $text = str_replace(['ٱ', 'إ', 'أ', 'آ'], 'ا', $text);
        // تنظيف المسافات الزائدة
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}
