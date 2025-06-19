#!/bin/bash

# ステップ1: テスト用DBを初期化（Seeder実行）
php artisan migrate:fresh --seed --seeder=TestSeeder --env=local --database=mysql_test

# ステップ2: Dusk実行（ここでブロック）
php artisan dusk

# ステップ3: テスト終了後にDBを再度初期化
php artisan migrate:fresh --seed --seeder=TestSeeder --env=local --database=mysql_test