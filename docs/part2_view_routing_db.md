# Part 2: View / Routing / Migration / Seeder

## View

- `resources/views` 配下に配置
- Bladeテンプレートを使用（`@section`, `@yield`, `@component`）
- 共通レイアウト：`layouts/app.blade.php`
- コンポーネント：`components/header.blade.php` など

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('css')
@stack('scripts')
```

## Routing

- `routes/web.php` に定義
- `Route::get()`, `post()`, `put()`, `delete()` などRESTfulに記述
- `middleware`で認証やログ処理を挟める

## Migration

```bash
php artisan make:migration create_questions_table
php artisan migrate
```

- スキーマ定義をコードで管理
- `rollback`, `refresh`, `status` コマンドも併用

## Seeder

```bash
php artisan make:seeder QuestionSeeder
php artisan db:seed --class=QuestionSeeder
```

- `migrate:fresh --seed` で初期化と同時に実行も可能