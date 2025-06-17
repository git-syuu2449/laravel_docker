# テスト（Unit / Feature / Browser）

テストコードは大まかには単体、機能、ブラウザテストの3パターンある。  
単体、機能において、内部的にはPHPUnitを使っており、命名規則に従う必要がある。  

## 命名規則
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

## 単体テスト（Unit）

単体はmodel,serviceに対して検証  
`tests/Unit/***ServiceTest.php`

```bash
php artisan make:test QuestionServiceTest --unit
```

## 機能テスト（Feature）

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


## ブラウザテスト

- Laravel Dusk 

    Laravel製でPHPベース

    Laravelアプリと連携しやすい（DBトランザクション、認証など）

    headless Chrome でブラウザ操作

    特別な環境構築が少ない（Laravelにcomposerで入れるだけ）

使われるケース

    Laravelアプリ内で完結したブラウザ操作の検証

    ログイン、CRUD、ダイアログなどのUI動作確認

    非技術者でも読みやすいテストコード


- Playwright

    Node.js製でJavaScript/TypeScriptで書く

    複数ブラウザ対応（Chromium, Firefox, WebKit）

    自動スクリーンショット、録画、スナップショット検証が強力

    Laravel開発者でもDockerなどで統合しやすくなってる

使われるケース

    複数ブラウザ対応が必要

    CI/CDで本番に近い環境でのE2Eを回す

    非Laravelシステムとも連携した複合テスト


### 導入  

duskの場合

```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

tests/Browser ディレクトリと、DuskTestCase.php が作成される。




## 認証が必要なテストケース

認証後に利用する機能では、未ログイン状態だとテストケースが通らない。  
Laravel標準のHTTPテストの `actingAs` メソッドを利用してログイン状態を作る。

```php
$user = User::factory()->create();
$this->actingAs($user)->...
```

## 検証内容

Laravel標準で用意されている検証方法でよく使用されるものを抜粋

- assertStatus
ステータスコードの検証

- assertSessionHasErrors
エラーが発生することの検証

- assertDatabaseHas
DBの値に対しての検証

参考：https://laravel.com/docs/11.x/

## テストデータ  

### Factory

他seeder項等にも関連するが、テストデータを作成する際に*Factory*を使用する。  

- 作成方法は以下  
`php artisan make:factory [ファクトリ名] --model=[対象のモデル名]`

- 利用方法例は以下
`Question::factory()->create();`

#### 補足

fackerを利用するとそれっぽいデータが作られる。

`RefreshDatabase` を使用するとデータのクリアが行われる。

`.env.testing` を参照して、テスト用のDBで作業する。

```bash
# テスト用のDBにマイグレーションを実行する
# config/database.phpに設定を追加　要キャッシュクリア
php artisan config:clear && php artisan cache:clear && php artisan route:clear
php artisan migrate:fresh --database=mysql_test
# テスト実施
php artisan test --env=testing
# 再度キャッシュクリア
```