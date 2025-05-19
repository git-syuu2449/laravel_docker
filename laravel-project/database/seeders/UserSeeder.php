<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Enums\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 以下削除処理 一元管理する際は別途切り出し
        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルをクリア
        User::truncate();

        // 外部キー制約を有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::factory()
            ->count(4)
            ->create()
            ->each(function (User $user) {
                // ロール毎に作成
                if ($user->id == 1)
                    $user->role = Role::Admin;
                else if ($user->id == 2)
                    $user->role = role::User;
                else if ($user->id == 3)
                    $user->role = role::Guest;
                else if ($user->id == 4)
                    $user->delete();

                $user->email = "test{$user->id}@email.com";
                $user->save();
            });
    }
}
