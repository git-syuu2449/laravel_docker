# Part 1: Laravel・Artisan・MVC

## Laravel概要

PHPの開発環境で使用できる代表的なフレームワークである。  
2025/05現在でも開発が続けられており、シェア数トップで将来性も高い。  
MVCモデルを採用しており、責務の分担がされている。  
小規模〜大規模案件にも対応可能。  
他フレームワーク(例えばRuby on Rails)を触ったことがある人であれば学習コストは低いと思われる。  
ログイン機能といった基本の機能を作る場合、外部ではあるがパッケージが豊富に存在しているので、自前実装が必要なく拡張のみで事足りることが多いので、コストが下がる。  
また、独自のCLIツールが強力で開発がしやすい。  
詳細は以下に記載。


## MVCアーキテクチャ概要

LaravelはMVCモデルを採用している。  
それぞれの責務は以下の通り。

- **Model**：Eloquent ORMでDBと連携
- **View**：Bladeテンプレート / コンポーネントベース
- **Controller**：HTTPリクエストの処理・ModelとViewの仲介

実際にはルーター(routes)の概念が入る。  
また、責務の分担としてservice等の概念も入ることが多い。  
柔軟性は高いものの、MVCの責務を正しく分けないとファットになるので注意が必要。  

Webを表示する場合の基本的な処理フローは以下の通り。

1. ユーザーが特定のURLにアクセス
2. **`routes/web.php`** に定義されたルーティングで、該当するControllerやクロージャが呼び出される
3. ルーティングの設定に従い、対応する **Controller** のメソッドへ処理を移譲
4. Controller内で、必要に応じて **ModelやService** を介してデータベースやビジネスロジックにアクセス
5. Controllerで取得したデータを **View（Bladeテンプレート）** に渡す
6. ViewでHTMLを生成し、ユーザーにレスポンスを返す

> ※厳密には、ルーティング処理以前に **bootstrap/app.phpで定義された・ServiceProvider・Middleware** 等の処理が走るが、ここでは省略（後述）。


### 🔹 Model（Eloquent ORM）

以下コマンドで作成する。

```bash
php artisan make:model {$モデル名} -オプション名
```

オプションは以下が使用可能

<details>

```bash

-m	migration（マイグレーション）を作成
-f	factory を作成
-s	seeder を作成
-c	controller を作成
-r	controller をリソースタイプで作成
-a	上記すべてを作成（all）

```

</details>

基本的には-m -f -sオプションをつけてつくる。  
マイグレーション、シーダー、ファクトリに関しては別紙で記載するため省略。

あくまでスケルトンが作られるだけなのでリレーション含め手動で記載する必要がある。  
Laravel公式ではないが、Laravel Blueprint というDSLベースのツールを使うと、モデル、マイグレーション、ファクトリ、リレーションまで自動生成できる。  


- `Question`, `Choice` モデルを定義
- `Question hasMany Choice`、`Choice belongsTo Question` の関係を定義
- スコープやリレーションを使って効率的にデータ取得を行う

```php
// Question.php
public function choices()
{
    return $this->hasMany(Choice::class);
}
```

### 🔹 View（Blade / Vue）

resouce以下に作成する。  
コマンドでのスケルトンの作成は行えないため、手動で作成をする。  
標準で `blade` というテンプレートエンジンを使用できる。

詳細は[別紙](docs/part2_view_routing_db.md) を参照

### 🔹 Controller

以下コマンドで作成する。

`php artisan make:controller {$コントローラ名}`
`php artisan make:controller ApiQuestionController --api --model=Question`

オプション等数が多い為[解説サイト](https://thousand-tech.blog/php/laravel/artisan/cheatsheet/make-controller/)を参考にする

---

## Artisan CLI

Laravelが独自に用意しているコマンドラインインターフェイス。  
かなり強力なため、使用すべきところでは使う。  
使用できるコマンドは以下で確認可能で、独自に作成したコマンドも載るのが特徴 

### よく使うコマンド例

<details>

```bash
php artisan list                         # 全コマンド一覧
php artisan make:controller HogeController
php artisan make:model Hoge -mfs         # Model + Migration + Factory + Seeder 作成
php artisan route:list                   # ルート定義の確認
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

</details>


### スタブについて

makeコマンドで作成する際に、スタブを基に作成を行う。  
以下コマンドにてstubs/以下にスタブが作成される。  
`php artisan stub:publish`


## パッケージ

前述したとおり、LaravelではComposerパッケージの利用が容易である。  
