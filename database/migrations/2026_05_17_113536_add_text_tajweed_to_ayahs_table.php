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
        Schema::table('ayahs', function (Blueprint $table) {
            $table->text('text_tajweed')->nullable()->after('text_imlaei')->comment('النص بترميز التجويد');
        });
    }

    public function down(): void
    {
        Schema::table('ayahs', function (Blueprint $table) {
            $table->dropColumn('text_tajweed');
        });
    }
};
