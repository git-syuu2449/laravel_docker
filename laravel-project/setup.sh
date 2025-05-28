#!/bin/bash

set -e

# .envの作成はinit.shで実施
echo " .env の作成-存在があればスルー"
cp -n _env.template .env

echo "Composer install 開始"
composer install

echo "NPM install 開始"
npm install

echo "必要な npm パッケージの追加（開発依存）"
npm install \
  glob@7 \
  vue \
  @vitejs/plugin-vue \
  tailwindcss \
  postcss \
  autoprefixer

echo " Tailwind 初期設定"
npx tailwindcss init -p

echo "Laravel の初期設定"
php artisan key:generate
php artisan migrate:fresh --seed

echo "セットアップ完了"
