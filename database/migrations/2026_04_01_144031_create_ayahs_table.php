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
    Schema::create('ayahs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('surah_id')->constrained()->cascadeOnDelete();
        $table->integer('number_in_surah');
        $table->integer('number_in_quran')->unique();
        $table->text('text_uthmani')->comment('النص بالرسم العثماني للعرض');
        $table->text('text_imlaei')->comment('النص الإملائي للمطابقة مع الذكاء الاصطناعي');
        $table->string('audio_url')->nullable()->comment('رابط التلاوة');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayahs');
    }
};
