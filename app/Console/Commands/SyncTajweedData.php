<?php

namespace App\Console\Commands;

use App\Models\Ayah;
use App\Models\Surah;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncTajweedData extends Command
{
    protected $signature = 'quran:sync-tajweed';

    protected $description = 'جلب نصوص التجويد الملونة من alquran.cloud API';

    public function handle(): int
    {
        $this->info('جلب بيانات التجويد من alquran.cloud...');

        $response = Http::timeout(300)->get('https://api.alquran.cloud/v1/quran/quran-tajweed');

        if (!$response->successful()) {
            $this->error('فشل في جلب بيانات التجويد');
            return self::FAILURE;
        }

        $surahsData = $response->json('data.surahs');

        $totalAyahs = 0;
        foreach ($surahsData as $surahData) {
            $totalAyahs += count($surahData['ayahs']);
        }

        $bar = $this->output->createProgressBar($totalAyahs);
        $bar->start();

        $updated = 0;
        foreach ($surahsData as $surahData) {
            $surah = Surah::where('number', $surahData['number'])->first();

            if (!$surah) {
                $bar->advance(count($surahData['ayahs']));
                continue;
            }

            foreach ($surahData['ayahs'] as $ayahData) {
                Ayah::where('surah_id', $surah->id)
                    ->where('number_in_surah', $ayahData['numberInSurah'])
                    ->update(['text_tajweed' => $ayahData['text']]);

                $updated++;
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("تم تحديث {$updated} آية ببيانات التجويد.");

        return self::SUCCESS;
    }
}
