# Part 1: アプリ概要・Artisan・MVC

## アプリ概要

このアプリは、質問投稿とそれに対する投票ができるWebアプリです。
- 投稿：同期処理
- 投票：Vue + 非同期処理
- DB構成：質問（questions）- 投票（choices）の一対多
- その他：バッチ処理、テスト、画像アップロードも対応

## MVCアーキテクチャ概要

- **Model**：Eloquent ORMでDBと連携
- **View**：Bladeテンプレート / コンポーネントベース
- **Controller**：HTTPリクエストの処理・ModelとViewの仲介

## Artisan CLI

Laravel独自のCLIツール。以下のようなコマンドが用意されている。

```bash
php artisan list                  # 全コマンド一覧
php artisan make:controller HogeController
php artisan make:model Hoge -mfs
php artisan route:list            # 定義されたルートの一覧
```

詳細：
- `make:*` 系：Controller、Model、Migration、Seeder、Factory等の作成
- `config:clear`, `cache:clear`, `view:clear` 等もよく使う