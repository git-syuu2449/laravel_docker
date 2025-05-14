<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

// @todo ログ出力先をバッチ用に変更
class SendMail extends Command
{
    /**
     * Summary of signature
     * paramは必須、--付きはオプション
     * @var string
     */
    protected $signature = 'app:send-mail {param} {--opt}';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'メール送信バッチ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('メール送信バッチ開始');

        // パラメータ
        $param = $this->argument('param');
        // --で指定したオプションをboolで取得.設定しない場合はfalse
        $opt = $this->option('opt');

        Log::debug("引数",['param' => $param,'opt'=> $opt]);

        $this->info('メール送信バッチ終了');
    }
}
