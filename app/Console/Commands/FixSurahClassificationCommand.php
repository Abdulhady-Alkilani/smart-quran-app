<?php

namespace App\Console\Commands;

use App\Models\Surah;
use Illuminate\Console\Command;

class FixSurahClassificationCommand extends Command
{
    protected $signature = 'quran:fix-classification';

    protected $description = 'تصحيح تصنيف السور بين مكية ومدنية حسب التصنيف المعتمد';

    private const MEDINAN_SURAHS = [
        2, 3, 4, 5, 8, 9, 22, 24, 33, 47,
        48, 49, 57, 58, 59, 60, 61, 62, 63, 64,
        65, 66, 76, 98, 99, 110,
    ];

    public function handle(): int
    {
        $this->info('بدء تصحيح تصنيف السور...');

        $fixed = 0;
        $medinanNumbers = array_flip(self::MEDINAN_SURAHS);

        $surahs = Surah::all();
        $bar = $this->output->createProgressBar($surahs->count());
        $bar->start();

        foreach ($surahs as $surah) {
            $correctType = isset($medinanNumbers[$surah->number]) ? 'Medinan' : 'Meccan';

            if ($surah->revelation_type !== $correctType) {
                $oldType = $surah->revelation_type;
                $surah->update(['revelation_type' => $correctType]);
                $this->newLine();
                $this->warn("  سورة رقم {$surah->number} ({$surah->name_ar}): {$oldType} → {$correctType}");
                $fixed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("تم التصحيح. عدد السور المعدلة: {$fixed}");

        return self::SUCCESS;
    }
}
