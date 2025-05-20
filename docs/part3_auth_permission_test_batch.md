# Part 3: 認証機能 / 権限 / テスト / バッチ

## 認証機能

- Breeze（軽量な公式キット）
- Jetstream（2FA・チーム管理など）
- Fortify（認証ロジックのみ）
- Sanctum（APIトークン用）

```bash
composer require laravel/breeze
php artisan breeze:install vue
```

## 権限管理

- 自前実装（`users.role`カラムなど）
- Spatie Laravel Permission

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

## テスト

### 単体テスト
```bash
php artisan make:test HogeServiceTest --unit
```

### 機能テスト
```bash
php artisan make:test QuestionControllerTest
```

### 実行
```bash
php artisan test
php artisan test --filter=test_example
```

## バッチ処理

```bash
php artisan make:command ProcessVotes
php artisan schedule:run
```

- `Kernel.php` に `schedule()` を記述
- cronとの連携で定期実行可能