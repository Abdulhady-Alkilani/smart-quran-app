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
    Schema::create('user_quiz_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        // لاحظ هنا أضفنا اسم الجدول صراحة لأن اسم العمود لا يتطابق مع اسم الجدول
        $table->foreignId('question_id')->constrained('generated_questions')->cascadeOnDelete();
        $table->text('user_answer')->nullable();
        $table->boolean('is_correct');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quiz_attempts');
    }
};
