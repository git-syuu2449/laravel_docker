# Part 6: API

## 導入方法

Laravel 11では、API機能が標準でサポートされており、簡単に導入可能。以下のコマンドを使用します：

```bash
php artisan install:api
```

このコマンドにより、API向けの設定が自動で追加され、Laravel Sanctum もインストールされる。SPAやシンプルなAPI構成であれば、これだけで必要なものが一式揃います。

### Breezeとの関係

* `php artisan breeze:install api`

  * **画面を使わずにAPIのみで構築する場合**に使用。
  * **注意**：通常のBreeze構成（画面あり）から切り替えると、Bladeビュー等が削除されるので、画面とAPIの両立を目指す場合は避けること。

### Sanctum の個別インストール

```bash
composer require laravel/sanctum
```

画面ログイン（Breezeなど）を用意しておきつつ、APIのみCSRF保護付きでアクセスしたい時など、後からAPI認証を加える際に使用します。

## 認証方式

Laravel Sanctum は、用途に応じて以下3つの認証モードを提供しています。

### 1. SPA認証（セッションクッキー認証）

* セッションとCSRFトークンに基づく認証
* 同一ドメインまたはサブドメイン構成で使いやすい
* VueなどのSPAとの親和性が高い

#### 必須設定・注意点

* `web`ミドルウェアが必須（セッションやCSRF保護のため）
* フロントから `/sanctum/csrf-cookie` にGETアクセスし、CSRFトークンを取得してからAPIを呼び出す
* `axios`の`withCredentials: true`を忘れずに設定する。

```js
await axios.get('/sanctum/csrf-cookie')
await axios.get('/api/questions', { withCredentials: true })
```

* `.env` に以下を必ず設定：

```env
SESSION_DRIVER=database
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:8000,localhost:3000,localhost
```

* `routes/web.php` で定義する or `middleware(['web', 'auth:sanctum'])` を適用する

### 2. APIトークン認証

* モバイルアプリや外部システム連携に向いている
* `PersonalAccessToken`を発行し、リクエスト時にBearerトークンとして送る

```http
Authorization: Bearer {token}
```

* トークン認証の場合、`web`ミドルウェアは不要

### 3. モバイルアプリケーション認証

* 上記トークン認証の応用で、APIキーのような形で使う
* デバイス識別などと組み合わせて使う場合に便利

## ルーティング例

```php
// セッションクッキーを使うSPA向けAPI
Route::middleware(['web', 'auth:sanctum'])
    ->get('/api/questions', [QuestionController::class, 'index']);

// トークン認証API（セッション不要）
Route::middleware('auth:sanctum')
    ->get('/api/external', [ExternalApiController::class, 'index']);
```

---

## 補足ツール

### Postman

* APIテスト用GUIツール
* GET/POST等を視覚的に送信でき、ヘッダーやクッキーも操作可能
* Laravel APIの動作確認・開発効率向上に役立つ

### axios

* VueやReactからAPIリクエストを行う際に使用
* `withCredentials: true` を設定することでクッキー送信が可能

```js
await axios.get('/sanctum/csrf-cookie')
await axios.post('/api/submit', payload, { withCredentials: true })
```

---

## トラブルシューティング

* `401 Unauthenticated` エラーが出る

  * `/sanctum/csrf-cookie` が呼ばれていない
  * `withCredentials` が `false` または未設定
  * `.env` の `SESSION_DOMAIN`, `SANCTUM_STATEFUL_DOMAINS` の設定が不足
  * `web` ミドルウェアが適用されていない

* `Session store not set on request` エラー

  * `web` ミドルウェアがルートに付いていない

* `CSRF token mismatch`

  * `/sanctum/csrf-cookie` を事前に呼んでいない
  * axiosがクッキーを送っていない

---

