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
    Schema::create('user_memorization_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('ayah_id')->constrained()->cascadeOnDelete();
        $table->string('status')->default('learning')->comment('learning, memorized, needs_review');
        
        // أعمدة خوارزمية SRS (SuperMemo-2)
        $table->integer('repetition_count')->default(0);
        $table->decimal('easiness_factor', 8, 4)->default(2.5);
        $table->integer('interval_days')->default(0);
        $table->date('last_review_date')->nullable();
        $table->date('next_review_date')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_memorization_progress');
    }
};
