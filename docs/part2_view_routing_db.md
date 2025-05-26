# Part 2: View / Routing / Migration / Seeder

## View（Bladeテンプレート）

Laravelでは、View層は `resources/views` ディレクトリに配置され、Bladeテンプレートエンジンが使用される。  

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

マイグレーションはDBテーブル構造をコードで管理できる仕組みです。

```bash
php artisan make:migration create_questions_table
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