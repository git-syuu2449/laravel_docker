#!/bin/bash

# プロジェクトのルートディレクトリに移動
cd /var/www/laravel-project/

# .env ファイルのバックアップパス
ENV_BACKUP=".env.bak"
# テスト用の .env ファイルパス
ENV_DUSK=".env.dusk.local"
# 実際の .env ファイルパス
ENV_CURRENT=".env"

echo "Duskテスト用の.envファイルを準備します..."

# 1. .env ファイルをバックアップ
if [ -f "$ENV_CURRENT" ]; then
    echo "既存の .env を $ENV_BACKUP にバックアップします。"
    cp "$ENV_CURRENT" "$ENV_BACKUP"
else
    echo "既存の .env ファイルが見つかりません。バックアップはスキップします。"
fi

# 2. .env.dusk.local を .env にコピー
if [ -f "$ENV_DUSK" ]; then
    echo "$ENV_DUSK を $ENV_CURRENT にコピーします。"
    cp "$ENV_DUSK" "$ENV_CURRENT"
    # SANCTUM_STATEFUL_DOMAINS の警告を避けるため、空文字列に置き換え
    # sed -i はmacOSとLinuxでオプションが異なる場合があるため、互換性を考慮
    if [[ "$OSTYPE" == "darwin"* ]]; then
      # macOS
      sed -i '' 's/^SANCTUM_STATEFUL_DOMAINS=null$/SANCTUM_STATEFUL_DOMAINS=""/g' "$ENV_CURRENT"
    else
      # Linux (GNU sed)
      sed -i 's/^SANCTUM_STATEFUL_DOMAINS=null$/SANCTUM_STATEFUL_DOMAINS=""/g' "$ENV_CURRENT"
    fi
else
    echo "エラー: $ENV_DUSK が見つかりません。中断します。"
    exit 1
fi

# 3. Laravelの設定キャッシュをクリア
echo "Laravelの設定キャッシュをクリアします..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Duskテスト用の.envファイルの準備が完了しました。"