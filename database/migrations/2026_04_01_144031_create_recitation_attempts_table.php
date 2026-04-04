<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('recitation_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('ayah_id')->constrained()->cascadeOnDelete();
        $table->string('audio_file_path')->comment('رابط الملف الصوتي في Amazon S3');
        $table->text('transcribed_text')->nullable()->comment('النص المستخرج عبر Whisper');
        $table->decimal('similarity_score', 5, 2)->nullable()->comment('نسبة التطابق عبر خوارزمية Levenshtein');
        $table->integer('mistakes_count')->default(0);
        $table->boolean('is_passed')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recitation_attempts');
    }
};
