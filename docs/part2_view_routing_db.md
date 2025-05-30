# Part 2: View / Routing / Migration / Seeder

## View（Bladeテンプレート）

Laravelでは、View層は `resources/views` ディレクトリに配置され、Bladeテンプレートエンジンが使用される。  

### bladeについて

ポイントを絞って簡単に記載をする。

- レイアウトの共通化

コンポーネント、スロットの概念がある為、共通化の意識がしやすい。  
コンポーネント作成は以下のコマンドで作成をする。  

```bash
php artisan make:component Header
```
`resources/views/components/header.blade.php` に作成される。

componentの使用は以下

それぞれの個別のbladeから以下のように利用をする。

```php
@extends('layouts.app')
```

詳細は下記 View構造と共通化方針 を参照


- @tag（ディレクティブ）

@tag名()という書き方ができる。

```html
@section
〜〜
@endsection
```

条件でclassを分けるときは以下のような書き方ができる。

```html
<span @class([
    'p-4',
    'font-bold' => $isActive,
])></span>
```

基本的には静的なhtmlから埋め込みする際に書き方に乖離が出る。  
デザイナーとの連携の観点から、ロジック関係以外使わなくてもいいと思われる。

- ループ、判定が行える

一部を抜粋

```php
@if
  ここに処理
@endif

@foreach
  ここに処理
@endforeach

@switch
  @case
    ここに処理
  @default
@endswitch
```

- エスケープ関連

  - エスケープして表示

   `{{ $hoge }}`

   - エスケープせず表示

   `{!! $question->body !!}`

  - エスケープしつつ改行表示

  `{!! nl2br(e($text)) !!}`

  エスケープしないで表示はセキュティ的にNGな為やらない。

  もしくは、cssで改行を処理する。
  
  ```css
  // styleベタ書き
  style="white-space: pre-line;"
  // tailwind使用
  class="whitespace-pre-line"
  ```

### View構造と共通化方針

- `layouts/app.blade.php` をベースレイアウトとし、共通のCSS/JSを読み込み
- 個別のBladeファイルでは `@section`, `@push`, `@stack` を使用して内容を埋め込む
- コンポーネント単位で共通部品を切り出す：例）`components/header.blade.php`, `components/footer.blade.php`

```blade
@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/index.css') }}" />
@endpush

@push('scripts')
<script type="module" src="{{ Vite::asset('resources/js/questions/index.js') }}"></script>
@endpush

@section('content')
  <h2>質問一覧</h2>
@endsection
```

### 工夫点

- `@vite` の読み込みが複数回走ると `@vite/client` が重複する問題が発生するため、`@once` を使う方法を試したが、ページ単位での `@push` に統一した。
- TailwindやVueとの連携を意識して、必要な画面にのみアセットを読み込むように設計。

---

<!-- viewのボリュームが多い為別途わける -->

## Routing

### 基本

- Laravelのルーティングは `routes/web.php` に記述します。
- 各HTTPメソッドごとに適切なアクションを指定できます。

```php
Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/questions', [QuestionController::class, 'store']);
```

### RESTful構成例

| メソッド | パス | アクション |
|----------|------|------------|
| GET | /questions | index |
| GET | /questions/create | create |
| POST | /questions | store |
| GET | /questions/{id} | show |

### 工夫点

- `php artisan route:list` でルート一覧を常時確認し、コントローラとURLの紐付けの整合性を管理。
- `middleware` をルート定義に明示しておくことで、将来的な認可追加を考慮。

---

## Migration（マイグレーション）

マイグレーションはDBテーブル構造をコードで管理できる仕組み。

```bash
php artisan make:migration create_questions_table --create=テーブル名
php artisan make:migration add_questions_table --table=テーブル名
php artisan migrate
```

よく使うマイグレーション関連コマンド：

```bash
php artisan migrate:rollback   # 直前のマイグレーションを戻す
php artisan migrate:refresh    # 一度全部戻して再実行
php artisan migrate:fresh      # 全削除して再実行
```

### 工夫点

- テーブル構成は事前にER図を紙で書き、QuestionとChoiceのリレーションを明確化してから実装。
- `created_at`, `updated_at` は自動生成されるので、タイムスタンプの手動定義は原則省略。
- 複合ユニーク制約や外部キー制約も定義した上で、リレーションを担保。

---

## Seeder（シーダー）

Seederは初期データをDBに流し込む仕組みです。

```bash
php artisan make:seeder QuestionSeeder
php artisan db:seed --class=QuestionSeeder
```

複数Seederをまとめて実行：

```bash
php artisan migrate:fresh --seed
```

### 工夫点

- Factoryを活用して疑似的な質問データと選択肢を生成
- フィールドの妥当性を維持しながらもランダム性を持たせる
- Seeder内での `truncate()` 処理を用意し、重複挿入を防止

---

## 補足

- View, Routing, Migration, Seeder はLaravelの基盤となる重要機能です。
- 各レイヤーで責務を明確に分離することで、テストや拡張に強い構成を意識しました。


---

## DB

## トランザクションについて

一度にテーブルを複数操作する場合や、DB操作後にメール送信等の処理を行う場合は、整合性の担保がされる必要がある。  
トランザクション処理はLaravelでは以下のように行う。  

```php
// トランザクション
DB::beginTransaction();

try {
  // 登録処理などの実施

  // コミット処理
  DB::commit();

} catch {
  // 例外発生時

  // ロールバック処理
  DB::rollBack();

  throw $e;
}

```

## 排他制御について

上記のトランザクションに関連して、整合性の担保をする為に排他制御を行う必要がある。  
排他制御はLaravelでは以下のように行う。  

- 行ロック

```php
モデル::find(id)->lockForUpdate()
```

- テーブルロック

```php
モデル::lockForUpdate();
```

処理としては　`SELECT … FOR UPDATE ` を発行している。

### 補足

削除処理後に更新されようとしている時の対策として、 `withTrashed` を利用する。  
ソフトデリートされたデータの取得を行える。


