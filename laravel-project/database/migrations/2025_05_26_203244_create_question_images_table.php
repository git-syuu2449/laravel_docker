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
        Schema::create('question_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'question_id')
                ->constrained(table: 'questions')
                ->onDelete('cascade');
            // 退会済み等でも表示する想定
            $table->foreignId('user_id')
                ->constrained(table: 'users')
                ->onDelete('restrict');
            $table->string('image');
            
            // 共通
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_images');
    }
};
