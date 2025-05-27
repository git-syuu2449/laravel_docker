# Part7: 例外処理、プロバイダ

## カスタムHTTPエラーページの作成

Laravelでは `resources/views/errors/` にエラーコードをファイル名にしたBladeファイルを置くことで、標準のエラーページをカスタマイズできる。

例：404エラーのカスタム  
`resources/views/errors/404.blade.php`

---

## ロール別エラーページの出し分け

ユーザーの権限（user, admin など）によってエラーページを出し分けたい場合、`ExceptionHandler` にてビューのパスを切り替える。

### Laravel 11 の注意点

Laravel 11では `App\Exceptions\Handler` クラスが初期状態では存在しないため、自前でハンドラーとサービスプロバイダを作成・登録する必要がある。

---

### 1. カスタム例外ハンドラの作成

```bash
php artisan make:exception CustomExceptionHandler
```

※ Laravel 10まで存在した `App\Exceptions\Handler` と同名にすると混乱するため、クラス名は変えた方が安全。

必要に応じて以下のようにオーバーライド：

```php
public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response
{
    if (auth()->check() && auth()->user()->isAdmin()) {
        return response()->view('errors.admin.404', [], 404);
    }

    return parent::render($request, $e);
}
```

---

### 2. サービスプロバイダの作成

```bash
php artisan make:provider ExceptionServiceProvider
```

以下のようにカスタム例外ハンドラをバインド：

```php
public function register(): void
{
    $this->app->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class,
        \App\Exceptions\CustomExceptionHandler::class
    );
}
```

---

### 3. プロバイダの登録方法（いずれかを選択）

#### 方法A: `bootstrap/app.php` で `withProviders()` に追加

```php
$app = Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\ExceptionServiceProvider::class,
    ])
    ->create();
```

#### 方法B: `bootstrap/providers.php` に一覧として記述

```php
return [
    App\Providers\ExceptionServiceProvider::class,
];
```

> どちらでも機能するが、`bootstrap/app.php`にまとめた方がモダンなLaravel 11の構成に沿っている。

---

## Bladeファイル内でロールによる分岐を行う場合

Bladeテンプレート内で直接判定を入れて出し分ける方法もある：

```blade
@if(auth()->check() && auth()->user()->isAdmin())
    管理者向けエラー表示
@else
    一般ユーザー向けエラー表示
@endif
```

---

## 例外の発生方法

### 方法1: `abort()` を使用

例：

```php
abort(403, 'アクセスが拒否されました');
```

開発用の例外テスト：

```php
if (config('app.debug')) {
    Route::get('/debug-error', fn() => abort(500, 'テスト用の500エラー'));
}
```

### 方法2: `throw new Exception` を使用

例：

```php
throw new \Exception('意図的な例外');
```

---

## 例外処理の流れ（HTTP例外の場合）

1. `Handler::render()` で例外がキャッチされる  
2. `renderHttpException()` に処理が委譲される  
3. `RegisterErrorViewPaths::register()` が実行され、ビューの探索パスがセットされる  
4. Laravelが該当するエラービュー（例: 404.blade.php）をレンダリング

---

## サービスプロバイダを使わず `bootstrap/app.php` に直書きする場合

以下のように直接バインドすることも可能：

```php
$app->singleton(
    \Illuminate\Contracts\Debug\ExceptionHandler::class,
    \App\Exceptions\CustomExceptionHandler::class
);
```

- 責務分離の観点からは `ServiceProvider` にまとめる方が望ましい
- 特に例外処理のようにアプリ全体に関わる処理は明示的なプロバイダ化が推奨される

---

### 補足：

- `withExceptions()` によるハンドリングの追加（Laravel 11から）

Laravel 11では、グローバルな例外のハンドリングも `bootstrap/app.php` で可能。

```php
->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
    $exceptions->render(function (Throwable $e) {
        dd($e); // デバッグ用途
    });
})
```

ただし、これは単発のハンドリングに向いており、**ロールベースの切り分けやビュー制御はカスタムハンドラとServiceProviderで行うのが適切である。**

- Handlerについて

前述の通り、共通のエラー処理を変更する場合は基底のエラー処理をオーバーライドして実装する。  
その為、安易なオーバーライドはすべきではなく、オーバーライドする時は以下のようにする。  
`return parent::オーバーライドのメソッド`

---

## まとめ

- Laravel 11では `Handler` クラスが自動生成されないため、自前で例外ハンドラを実装・登録する必要がある
- サービスプロバイダを用いたバインド登録が推奨
- `abort()`, `throw` で例外発生可能
- HTTP例外の表示フローは Laravel 10以前と大きく変わらないが、登録方法は `withProviders`, `withExceptions` に更新されている


## 補足

### サービスプロバイダ（Service Provider）

#### 概要
**サービスプロバイダ**は、Laravelアプリケーションのあらゆるサービスの**登録・初期化処理**を定義する場所。  
サービスコンテナにバインディングしたり、イベントリスナを登録したり、Bladeディレクティブを追加したりと、いわば**初期設定を記述する場所**。  
サービスコンテナについては以下に記載。  
様々な仕組みがあるので必要になったら別途ファイルをわけて詳細を記載。  

#### トラブルシューティング

Laravel 10 以前の config/app.php にてプロバイダのバインドを行う方法はエラーが発生する為注意が必要。


### サービスコンテナ（Service Container）

#### 概要
Laravelの**サービスコンテナ**は、依存性の解決（DI: Dependency Injection）を行う**IoC（Inversion of Control）コンテナ**。  
簡単に言うと、「必要なクラスや設定をどこかに登録しておいて、必要なときに自動的に取り出せる仕組み」。  

