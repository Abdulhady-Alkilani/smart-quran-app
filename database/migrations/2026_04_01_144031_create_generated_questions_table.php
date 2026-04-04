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
    Schema::create('generated_questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('surah_id')->constrained()->cascadeOnDelete();
        $table->foreignId('ayah_id')->constrained()->cascadeOnDelete();
        $table->text('question_text')->comment('نص السؤال المولد');
        $table->json('options')->nullable()->comment('الخيارات في حال كان السؤال أتمتة');
        $table->text('correct_answer');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_questions');
    }
};
