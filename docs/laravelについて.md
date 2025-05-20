# Laravel11について

## 学習済み
-MVC構造、Artisan、Routing、Migration、Seeder  
-テスト（Unit / Feature）  
-Blade、Vite、JS / Vue連携  
-認証（Breeze / Fortify / Jetstream / Sanctum）  
-バッチ、ミドルウェア、ログ  
-Spatieによる権限管理  
-管理画面構築（Filament など）  
-Middleware  

##　個人メモ
### どんなWebアプリを作るか
CRUD、GET、POSTを同期非同期両方で動かすサイト
よくありがちな掲示板というよりは投稿してそれに投票できるサイトを作る
DB設計は以前のものから流用する。
質問テーブルがあってそれに紐づく投票テーブルがある構成。
ORMを使う観点で一対多の構成が必要なアプリにしている。
投稿は同期、投票は非同期で処理する。
削除処理はいらないけどバッチで動かすものをテストがてら作る。
管理側のサイトとかログイン機能とかは作らない予定だが、余裕があれば触ってみる。
特にログイン機能は試すべきではある。
seedとテストデータも作る。
画像アップロード関連もやる。


# 基本
MVCのフレームワーク
使用感はRailsにかなり近い。
あとで詳細を記載

## 導入


## Artisanについて
Laravelが独自に用意しているコマンドラインインターフェイス。
かなり強力なため、使用すべきところでは使う。
使用できるコマンドは以下で確認可能で、独自に作成したコマンドも載る
`php artisan list`
詳細は以下を参考にする
https://zenn.dev/naopusyu/articles/19eba60153bd60

# router
以下ファイルにて管理
routes/web.php
他のフレームワークにもよくある機構でこのURLだったらこのコントローラを呼ぶといった記載ができる
APIについても多分ここに記載だけど後で追記
確認したいときは以下コマンド
php artisan route:list


# controller
ベースは以下で作る
`php artisan make:controller {$コントローラ名}`
オプション等は以下サイトを参考に
https://thousand-tech.blog/php/laravel/artisan/cheatsheet/make-controller/


# model
ベースは以下で作る
`php artisan make:model {$モデル名}`
オプションは以下
-m	migration（マイグレーション）を作成
-f	factory を作成
-s	seeder を作成
-c	controller を作成
-r	controller をリソースタイプで作成
-a	上記すべてを作成（all）

基本的には-m -f -sオプションをつけてつくる。

あくまでスケルトンが作られるだけなのでリレーション含め手動で記載する必要がある。
Laravel公式ではないが、Laravel Blueprint というDSLベースのツールを使うと、モデル、マイグレーション、ファクトリ、リレーションまで自動生成できる。


# view
resouce以下に作成する。
resources/views以下に機能（コントローラ単位）毎に作るのがいい。
bladeというテンプレートエンジンを使用する
コマンドで作成はできないようなので、手動で作成する必要がある。

viewの構造として、共通化をしつつ作る。
最低限以下は分ける。共通のcssや共通のjsは共通のレイアウトで読み込み一元管理する。
個別のファイルはblade側でpushすることで共通のテンプレートに埋め込める。
resources/views/layouts/app.blade.php（全体レイアウト）
resources/views/components/header.blade.php(ヘッダーレイアウト)
resources/views/components/footer.blade.php(フッターレイアウト)

なお、コンポーネントは以下コマンドでスケルトン作成する。
php artisan make:component Header

キャッシュクリアは以下
php artisan view:clear

## bladeについて
@tag名()という書き方ができる。
```
@section
〜〜
@endsection
```

条件でclassを分けるときは以下のような書き方ができる。
```
<span @class([
    'p-4',
    'font-bold' => $isActive,
])></span>
```
基本的には静的なhtmlから埋め込みする際に書き方に乖離が出るので、ロジック関係以外使わなくてもいいと思われる。

@if,@foreach

エスケープして表示
{{ $hoge }}
エスケープせず表示
{ $hoge }

curl http://localhost:8000 | grep @vite/client


書き方は
Bladeコンポーネントベース
Bladeテンプレート継承
がある。
後述するbreezeがコンポーネントベースなので、コンポーネントベースとしたほうがいい。


# migration
テーブル定義の記載。
マイグレーションは雛形を作成した上で手動で記載する必要がある。

作成はmodel項のオプションで追加する他には以下コマンドで作成可能
php artisan make:migration create_{テーブル名}_table


以下コマンドで実行
php artisan migrate
多人数で開発するときは毎回作り直すほうがいいので、上のコマンドは基本使わないと思われる。


他のコマンド
$ php artisan migrate:rollback
直前のマイグレーションをロールバックします。
（基本的に直前マイグレーションの取り消しのように振る舞いますが、実際にはマイグレーションファイルのdown()を実行しています。）

$ php artisan migrate:reset
マイグレーションをすべてロールバックします。

$ php artisan migrate:refresh
マイグレーションをすべてロールバックし、全マイグレーションを実行します。

$ php artisan migrate:fresh
データテーブルをすべて削除し、全マイグレーションを実行します。

$ php artisan migrate:status
各マイグレーションファイルの状態を表示します。

--

# seed
DatabaseSeederのrunを元に実行される
 database/seeders/DatabaseSeeder.php


## 実行方法
migtationし直してからseedを実行するほうが安全
php artisan migrate:fresh --seed

seedだけ実行する際は以下。データを消す処理(truncate)は別途記載が必要
php artisan db:seed (--class=**Seeder)

# test
テストコードは大まかには単体、機能、ブラウザテストの3パターンある。
単体、機能において、内部的にはPHPUnitを使っており、命名規則に従う必要がある。
## 命名規則
### クラス名：
class QuestionStoreTest extends TestCase
### メソッド名（test または @test アノテーション）：
public function test_example()
PHPDocに　@testを追加
→非推奨で、属性を使う。
　```
　#[Test]
　public function test_example()
　```

## 単体(unit)
単体はmodel,serviceに対して検証
php artisan make:test {サービス名Test} --unit
tests/Unit/***ServiceTest.php

## 機能(Feature)
機能は擬似的にレスポンスを行ったものとして検証
php artisan make:test {テスト名Test}
tests/Feature/***Test.php


## 実行

### 実行前準備
php artisan config:clear && php artisan cache:clear
### 一括実行
php artisan test
### 特定のテストだけ実行
php artisan test --filter=UserRegistrationTest
### 特定のテストメソッドだけ実行
php artisan test --filter=test_user_can_register_successfully


# batchについて
## 作成
php artisan make:command　{ファイル名}

## 実行
### 直接叩く場合
php artisan app:{タスク名}
バッチファイル内の
`protected $signature`
にて指定したもの

`php artisan schedule:run`
現在実行可能なタスクの実行を行う

`php artisan schedule:test`
スケジュールされたコマンドを実行する。

`php artisan schedule:work`
1分ごとにschedule:runコマンドを実行する。
開発時の検証用


### スケジュール実行
routes/console.phpに記載

使用できるオプションは以下が詳しい
https://readouble.com/laravel/11.x/ja/scheduling.html

### cron登録
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

## スケジュール確認
php artisan schedule:list


# ログイン機能について
Laravelが標準で用意している。
下記認証機能を使うか、独自に実装するかだが、よほどの理由がない限りは用意されている認証機能を使う。
後から機能追加するとweb.phpやapp.blade周り（js,css含む）が書き換えられるので注意が必要。

# マルチ認証について
設計次第ではあるが、共通のuserテーブルを使うか、別テーブル(user,adminのような)とするケースが挙げられる。
今回は共通のテーブルを使用し、roleで役割を切り分ける。
役割としては、未ログイン、一般ユーザーログイン済み、管理ユーザーログイン済みの３つ
→メールアドレス認証の機能を設けるならさらに未認証ユーザーでわける。

## 認証機能
Laravelが用意している認証には  Breeze / Jetstream / Fortify / Sanctumがある。
構成によって以下のように組み合わせが可能。
### 軽量
Breeze + Fortify + Sanctum
### API専用
Fortify + Sanctum

### Laravel Breeze 
Laravel の公式認証スキャフォールド
ログイン、ユーザー登録、パスワードリセット、ダッシュボード画面といった機能がある。
後述するFortifyやSanctumと組み合わせも可能。
viewはbladeだが、vue、reactにすることも標準で用意されている。

#### 導入
composer require laravel/breeze
php artisan breeze:install

##### vueを使用する場合
php artisan breeze:install vue

##### reactを使用する場合
php artisan breeze:install react

#### 起動
依存関係をインストールして起動
npm install
npm dev run


### Laravel Jetstream
Laravel 公式の認証UIフレームワーク
ログイン / 登録、メール認証 / パスワードリセット、ユーザープロファイル編集、二要素認証（2FA）、セッション管理、チーム機能、APIトークン管理といった機能がある。
内部的にFortify、Sanctumを使用している。
フル機能だが、拡張性に欠ける、重いという点もある。

### Laravel Fortify
認証バックエンドの提供を行う。
ログイン、登録、2FA、パスワードリセットといった機能がある。

#### 導入
composer require laravel/fortify
##### Fortifyを有効化
config/fortify.php
php artisan vendor:publish --tag=fortify-config

### Laravel Sanctum
API 認証に特化した Laravel のトークン認証システム
SPA用認証、APIトークンの提供を行う。

#### 導入
composer require laravel/sanctum
##### sanctum用テーブル作成
php artisan migrate


### 権限
ログインユーザーに応じて処理を分ける場合、自前実装を行うか、以下のパッケージを使う

#### 自前実装
大まかには以下２つの対応方法が考えられる。
1.roleの概念をもたせる。
　userテーブルにroleを追加するか、roleを外部テーブルに持つ。
2.adminテーブル等の専用のuserテーブルを追加

小規模で、roleだけで事足りる場合は自前実装のほうが製造コストが下がる。

#### Spatie Laravel Permission
ロールとパーミッションの概念を持つ。
複数ロールを持つなど細やかな制御が必要な場合は導入したほうがいい。
テーブル構造が多少複雑かつ学習コストが高いのがネック。
また、公式ではなく外部パッケージなので、アップデートが行われないリスクは存在する。
コードが本パッケージに依存することになるので、spatieを使用→自前実装に切り替えが困難

導入は以下
`composer require spatie/laravel-permission`
`php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`

## ミドルウェア
HTTPリクエストがアプリケーションに届く前や、レスポンスがクライアントに返る前に「処理を挟み込む」仕組み
コマンドでの雛形作成は以下
php artisan make:middleware AccessLogMiddleware

### 利用について
#### 全アクセス一律適用
bootstrap/app.phpに作成したmiddlewareを登録する。
laravel11以前ではapp/Http/Kernel.phpに登録。
アクセスログを出す時などに使用。

#### 個別に適用
web.php等のルーティングにmiddlewareを追加
権限チェックの時などに使用

## 例外処理
共通で処理する方法を記載
おそらく
bootstrap/app.phpに作成した例外処理を登録する or ベタ書きかな

## ログ処理
標準でログ出力の機構はあるが、ローテーションやアクセスログは作成する必要がある。
アクセスログに関しては上記のミドルウェアを作成して出力を行う。

### 用意されているログ
1.Log::ログレベル(debug,info等)(ログの内容)
2.Log::channel('チャネル名')->info({ログの内容})

### ログチャネル
ログ出力の際に、ログチャネルを指定して出力先を分けることができる。
これにより、アクセスログ、エラーログ、バッチログ用と分けることができる。
config/logging.php

# 管理画面
上記ログイン機能に関連。
自前実装を行うか、下記パッケージを使う。

## Filament
Tailwindベース、Laravel 11対応済、非常に拡張性が高く人気
## Laravel Nova
Laravel公式、商用向け。信頼性とサポート重視

## Backpack for Laravel
管理画面に特化したCRUD UI。無料＆有料あり

## laravel-admin 
標準認証付きで拡張がしやすいよう。
laravel10まで対応しており、非推奨。
抜け道としてGitHub のコミュニティフォークを使う方法はある。

----------

# 他注意点等
設定ファイル変更時は読み直しが必要
## 設定キャッシュのクリア
php artisan config:clear
php artisan view:clear
php artisan route:clear
## 新しい設定のキャッシュ生成
php artisan config:cache

再起動後にストレージ周りで権限エラーが起きたときは以下
php artisan storage:link
php artisan config:clear
php artisan cache:clear
composer dump-autoload -o

## js,cssについて
view項で記載した通り、基本的にはresoucesに配置してviteでビルドしてpublicに配置される。
ビルド対象とするのは "vite.config.js" で管理される。
標準だとビルドするファイルを個別に指定する必要がある。
resouces以下をビルド対象とする仕組みにする。
ビルドは以下
開発環境はホットリロード or 変更を検知して自動でビルド
npm install
npm run dev
or
npm run build --watch

本番
npm run build

bladeでの書き方は以下
OKパターン
共通の読み込み
```
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('css')
@stack('scripts')
```
個別のページでの読み込み
```
@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/create.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/create.js') }}"></script>
@endpush
```

NGパターン
```
    @once
        @vite(['resources/css/app.css'])
        @stack('css')

        @vite(['resources/js/app.js'])
        @stack('scripts')
    @endonce
```
@onceは@vite/clientが多重に読み込まれるのを回避するために記載。
→@viteは一箇所に記載しないと@vite/clientが都度吐き出される為、一回で記載する。
　@vite/clientが多重に読み込まれるとvueを使用する上で読み込まれない等、不具合が起きる場合がある

@stackは埋め込みをするための記述


## vueについて
部品毎に分ける構造（単一ファイルコンポーネント）
例えばモーダルの中にフォームがあるとして、
モーダルのラッパー→モーダル→フォームといった形に分ける。
メリットとして、再利用性が高いのとコードの役割が明確になる点がある。
vue2とvue3の書き方は結構変わるが、vue3であれば以下３構造が基本
<template>
</template>
<script setup>
</script>
<style>
</style>

## メンテナンスについて
php artisan down --secret="some-random-token"

本番の運用について

◆ DB変更（マイグレーションあり）

php artisan down --secret="some-random-token"  # 任意
php artisan migrate --force
php artisan optimize:clear
php artisan up

    --secret を付けると、自分だけ事前確認可能

◆ ビルドやコード修正のみ（マイグレーションなし）

git pull origin main
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
php artisan route:cache


##　ルーティングについて
RESTful
メソッド	URLパターン	用途	コントローラのアクション
GET	/items	一覧表示	index
GET	/items/create	作成フォーム	create
POST	/items	新規登録処理	store
GET	/items/{id}	詳細表示	show
GET	/items/{id}/edit	編集フォーム	edit
PUT/PATCH	/items/{id}	更新処理	update
DELETE	/items/{id}	削除処理	destroy


## デバッグ
デバッガーを使うとログやSqlの確認が可能。
導入は以下
.env
`APP_DEBUG=true`
bash(docker)
`composer require barryvdh/laravel-debugbar --dev`


## サービスコンテナ
あとで

