#!/bin/bash

# プロジェクトのルートディレクトリに移動
cd /var/www/laravel-project/

# .env ファイルのバックアップパス
ENV_BACKUP=".env.bak"
# 実際の .env ファイルパス
ENV_CURRENT=".env"

echo "Duskテスト後の.envファイルをクリーンアップします..."

# 1. .env ファイルを元に戻す
if [ -f "$ENV_BACKUP" ]; then
    echo "バックアップした .env を元に戻します。"
    mv "$ENV_BACKUP" "$ENV_CURRENT"
    # 元に戻した後のキャッシュクリアも重要
    echo "Laravelの設定キャッシュを再度クリアします..."
    php artisan config:clear
    php artisan cache:clear
else
    echo "バックアップした .env ファイルが見つかりません。"
    # 元々.envファイルが存在しなかったがDusk用に作成された場合
    if [ -f "$ENV_CURRENT" ]; then
        echo "Dusk用に作成された .env を削除します。"
        rm "$ENV_CURRENT"
    fi
fi

echo "Duskテスト後の.envファイルのクリーンアップが完了しました。"