# Part 4: ミドルウェア / ログ / メンテナンス他注意点

## ミドルウェア（Middleware）

```bash
php artisan make:middleware AccessLogMiddleware
```

- ルートごとに適用：`->middleware('auth')`
- グローバル適用：`bootstrap/app.php`

### 用途例

- アクセスログ記録
- 権限チェック
- リクエスト変換

---

## ログ出力（Logging）

```php
Log::info('成功ログ');
Log::error('失敗ログ', ['exception' => $e]);
Log::channel('slack')->alert('致命的エラー');
```

- `config/logging.php` でチャネル制御
- `daily`, `single`, `slack`, `stderr` などを用途で切り分け

---

## メンテナンスモード

```bash
php artisan down --secret="secret-token"
php artisan up
```

- `?secret=xxx` で特定者だけ確認可能

---

## 本番更新フロー（例）

### DBあり

```bash
php artisan down --secret="xxx"
php artisan migrate --force
php artisan up
```

### コードのみ

```bash
git pull origin main
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
php artisan route:cache
```

---

## 補足

- ミドルウェアで責務分離
- ログでデバッグ・障害対応
