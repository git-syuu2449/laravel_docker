# Part8: アップロード

## 前提

- ファイルアップロードには `store('path', 'public')` を使用。
- `php artisan storage:link` を実行済みで `/storage/` 経由で表示される構成。
- 一対多で複数のファイルがアップロードされるパターン。

---

## 登録処理の流れ

1. **バリデーション（FormRequest）**
   - 入力値、画像ファイルの存在をチェック
   - 整形済みデータは `$request->validated()` で取得

    一対多の場合はバリデーションのルールの書き方が特殊で、以下のように記載する。

    ```php
         rule内で
        'images' => ['required', 'array'],
        'images.*' => ['bail', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
    ```

    バリデーションエラーが発生した際は以下のようにエラーを検知する。  
    bladeで使える `@errors` `@old` をvueに渡すケース

    ```js
    // エラーは以下の形式で渡る
    {
        "errors": {
            "images.0": ["画像1は無効なファイル形式です。"],
            "images.1": ["画像2はサイズが大きすぎます。"]
        }
    }

    //　エラー判定(startsWithを使うのがポイント)
    Object.keys(props.errors || {}).some(key => key.startsWith('images.'))

    //　エラーを展開
    Object.entries(props.errors || {})
    .filter(([key]) => key.startsWith('images.'))
    .flatMap(([, messages]) => messages)

    ```


2. **コントローラ**
   - サービス層に `$validated` と `$request->file('images')` を渡す

3. **サービス層での登録処理（トランザクション）**

```php
　　// 画像の配置をstoreで行う。
    // オプションにpublicをつけた場合、 store/public/以下に作成 
    // つけない場合はstore/private以下に作成を行う。
    // $imageは$requestのimagesをループ処理したimage
    $path = $image->store("images/question/{$question->id}", 'public');
```

4. **画像の公開URLの取得**

```php
$url = Storage::url($questionImage->path);
// → /storage/images/question/15/example.jpg
```

---

## UIと更新処理のパターン

### A. 画像を追加する（multiple使用）

- `<input type="file" multiple>`
- 既存画像はそのままにし、追加分のみアップロード＆保存
- 画像削除は別のAPIで対応する設計が多い

### B. 画像を完全リセットして再登録する

- multiple入力のままだと UI/UX・状態管理が煩雑になる
- 更新時は以下のような構成にするのが楽：

```
1. 既存画像をすべて削除
2. ストレージ上の該当ディレクトリを削除（Storage::deleteDirectory）
3. 新規アップロードされた画像で再構築
```

---

## 注意点

- `store(..., 'public')` を忘れると `Storage::url()` が `/storage/` を返さない
- ストレージのリンクは必ず作成：

```bash
php artisan storage:link
```

---

## ストレージURLのベストプラクティス

```php
Storage::url($path)  // → /storage/xxx/yyy.jpg
url(Storage::url($path)) // → http://localhost:8000/storage/xxx/yyy.jpg
```

表示例

```php
<img src="{{ Storage::url($image->image) }}">
```

- ベタ書きで `/storage/` を書かないようにすること

---
