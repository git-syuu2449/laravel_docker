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
        // 本文追加に伴いカラム名を変更
        Schema::table('questions', function (Blueprint $table) {
            $table->string(column: 'body', length: 500);
            $table->renameColumn(from: 'question_text', to: 'title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('body');
            $table->renameColumn(from: 'title', to: 'question_text');
        });
    }
};
