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


###  認証の他方式（補足）

| 方式 | 特徴 |
|------|------|
| Breeze | 軽量なUI付き認証。Blade / Vue / React対応。 |
| Jetstream | フル機能（2FA, プロファイル管理, チームなど） |
| Fortify | 認証バックエンドのみ（UI無し） |
| Sanctum | SPA/API向けトークン認証（Cookie or Token方式） |
| Passport | OAuth2プロトコルを完全に実装 |

上記を組み合わせて使うことも可能。  
以下は組み合わせ例

#### 軽量
Breeze + Fortify + Sanctum
#### API専用
Fortify + Sanctum

---

## 権限管理（Authorization）

### 1. 自前実装

- `users` テーブルに `role` カラムを追加
- `admin`, `user` などを分岐して制御

### 2. Spatie Laravel Permission

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\\Permission\\PermissionServiceProvider"
php artisan migrate
```

- ロール・パーミッション単位で管理
- 複数ロール対応、柔軟なミドルウェア制御

---

## テスト（Unit / Feature）

テストコードは大まかには単体、機能、ブラウザテストの3パターンある。  
単体、機能において、内部的にはPHPUnitを使っており、命名規則に従う必要がある。  

### 命名規則
- クラス名：

class QuestionStoreTest extends TestCase

- メソッド名（test または @test アノテーション）：

public function test_example()

- PHPDocに　@testを追加

→非推奨で、属性を使う。

　```
　#[Test]
　public function test_example()
　```

### 単体テスト（Unit）

単体はmodel,serviceに対して検証  
`tests/Unit/***ServiceTest.php`

```bash
php artisan make:test QuestionServiceTest --unit
```

### 機能テスト（Feature）

機能は擬似的にレスポンスを行ったものとして検証  
`tests/Feature/***Test.php`

```bash
php artisan make:test QuestionControllerTest
```

```bash
php artisan test               # 一括実行
php artisan test --filter=HogeTest(クラス名) # 特定のテストクラスだけ実行
php artisan test --filter=can_hoge_hoge(メソッド名) # 特定のテストメソッドだけ実行
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

