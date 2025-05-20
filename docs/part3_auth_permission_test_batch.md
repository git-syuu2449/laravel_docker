# Part 3: 認証 / 権限 / テスト / バッチ

##  認証（Authentication）

Laravel Breeze を使用して、認証機能を導入しました。

### 導入手順

```bash
composer require laravel/breeze
php artisan breeze:install vue
npm install && npm run dev
php artisan migrate
```

### 機能

- ユーザー登録、ログイン、ログアウト
- セッションベースの認証
- Blade + Vue ハイブリッド構成に対応

### 工夫点

- 公式スキャフォールドを使用して短時間で実装し、ログイン後の遷移も含めて動作確認
- `middleware('auth')` を必要ページに適用し、未ログインアクセスを防止

---

## 権限管理（Authorization）

### 1. 自前実装

- `users` テーブルに `role` カラムを追加
- `admin`, `user` などを分岐して制御

### 2. Spatie Laravel Permission（導入済）

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
php artisan migrate
```

- ロール・パーミッション単位で管理
- 複数ロール対応、柔軟なミドルウェア制御

---

## テスト（Unit / Feature）

### 単体テスト（Unit）

```bash
php artisan make:test QuestionServiceTest --unit
```

### 機能テスト（Feature）

```bash
php artisan make:test QuestionControllerTest
```

```bash
php artisan test               # 全体
php artisan test --filter=HogeTest
```

---

## バッチ処理（Artisan Command + スケジューラ）

```bash
php artisan make:command AggregateVotes
```

`schedule()` 登録例：

```php
$schedule->command('app:aggregate-votes')->daily();
```

### cron設定

```cron
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

---

## 補足

- Breezeで認証
- Spatieで権限
- PHPUnitでテスト
- Artisanで集計バッチ処理

