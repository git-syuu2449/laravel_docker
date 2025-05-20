# Part 4: Middleware / ログ / メンテナンス

## ミドルウェア

```bash
php artisan make:middleware AccessLogMiddleware
```

- リクエスト前後に処理を挟む仕組み
- グローバル or ルート単位で適用可能

## ログ

```php
Log::info('処理完了');
Log::channel('slack')->error('例外発生');
```

- `config/logging.php` でチャネル設定
- ローテーション、Slack通知、バッチログ分離などに活用

## メンテナンスモード

```bash
php artisan down --secret="some-token"
php artisan migrate --force
php artisan up
```

- `--secret` を使うと限定アクセス可
- 本番の安全な更新時に有用