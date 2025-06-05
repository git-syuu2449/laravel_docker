<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RankingType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'question_id')
                  ->constrained(table: 'questions')
                  ->onDelete('cascade');
            $table->enum('type', [RankingType::DAILY->value, RankingType::WEEKLY->value, RankingType::MONTHLY->value, RankingType::YEARLY->value]);
            $table->date('base_date'); // 基準日：バッチ実行の日付
            $table->dateTime('start_at'); // 開始日時
            $table->dateTime('end_at'); // 終了日時
            $table->integer('count'); // 件数
            $table->integer('total'); // 集計
            $table->integer('average'); // 平均

            // 更新時上書きするため一意制約
            $table->unique(['type', 'question_id', 'base_date']);
            
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
        Schema::dropIfExists('rankings');
    }
};
