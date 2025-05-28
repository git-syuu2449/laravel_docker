# laravel_docker

Laravel 11をDocker環境(nginx,php,mysql)で構築し、主要な機能を動かすことを目的としたプロジェクトです。  
使用している技術等はページ下部<font color="Green">独習内容</font>を参照ください。

## 機能概要

このアプリは、質問投稿とそれに対する投票ができるWebアプリです。  
極小規模ではありますが、様々な技術を幅広く使用しています。  
- 投稿：同期処理
- 投票：Vue + 非同期処理
- DB構成：質問（questions）- 投票（choices）の一対多
- その他：バッチ処理、テスト、画像アップロードも対応

## 環境概要

本アプリケーションは、以下の技術スタックおよびDockerを用いた仮想環境で構築されています。

### アプリケーション構成

確認をする場合は表示を押下してください。

<details>

<summary><font color="Red">構成を表示</font></summary>

| 項目        | バージョン例        | 説明 |
|-------------|---------------------|------|
| Laravel     | 11.x                | PHPフレームワーク |
| PHP         | 8.3.2.1             | Laravel実行用 |
| MySQL       | 8.0.36              | データベース |
| Nginx       | 1.24                | Webサーバー |
| Node.js     | v18.20.8            | フロントエンドビルド |
| Composer    | 2.8.9               | PHPパッケージ管理 |
| OS          | Ubuntu 25.02        | 開発ベース環境 |
| Docker      | 28.1.1              | コンテナ実行環境 |
| Docker Compose | 2.34             | コンテナオーケストレーション |

</details>

### ディレクトリ構成（抜粋）

確認をする場合は表示を押下してください。

<details>

<summary><font color="Red">構成を表示</font></summary>

.  
├── laravel_docker/  
├── ├── docker/                 # Docker関連の設定ファイル  
├── │   ├── nginx/             # Nginxの設定ファイル  
├── ├── php/                    # PHPの設定ファイル  
├── │   ├── Dockerfile         # Dockerファイル  
├── ├── laravel-project/        # Laravelアプリケーション本体  
├── │   ├── app/                # アプリケーションのコアコード  
├── │   ├── bootstrap/          # アプリケーションのブートストラップファイル  
├── │   ├── config/             # 設定ファイル  
├── │   ├── database/           # マイグレーションやシーディング  
├── │   ├── public/             # 公開ディレクトリ（ドキュメントルート）  
├── │   ├── resources/          # ビューやアセット  
├── │   ├── routes/             # ルーティング定義  
├── │   ├── storage/            # ログやキャッシュなどのストレージ  
├── │   └── tests/              # テストコード  
├── ├── docker-compose.yml      # Docker Composeの設定ファイル  
├── ├── init.sh                 # 初期セットアップスクリプト  
├── ├── package.json            # Node.jsの依存関係定義  
├── ├── package-lock.json       # Node.jsの依存関係のロックファイル  
└── └── README.md               # プロジェクトの説明ファイル  

</details>

## 環境構築について(初回起動時)

確認をする場合は表示を押下してください。

<details>

<summary><font color="Red">実行手順を表示</font></summary>


### git clone

gitの利用が可能な状態を前提

`mkdir work && cd work`  
`git clone git@github.com:git-syuu2449/laravel_docker.git`  

### コンテナの起動

#### 事前準備

設定の共通化をdockerとlaravel側でしている為、.envの配置を行う必要がある。  
*.envの配置については.envの設定項を参照*  
配置後、以下コマンドをコンソールにて実行する。  


```bash
cd laravel_docker
`./init.sh`
```

boxの立ち上げを行う

```bash
UID=1000 GID=1000 docker compose --env-file .env up -d --build
docker compose exec app bash
```

以下はコンテナ内で実施する内容

`cd /var/www/laravel-project`  
`./setup.sh`  
setup.sh内でcomposerの実行、migrateを実行する。  

`npm run dev`  
viteの起動を行う  
css,js等をpublicに配置していない為必要となる。

[サイトURL：http://localhost:8000/](http://localhost:8000/)  
[開発用サイトURL一覧：http://localhost:8000/dev/routes](http://localhost:8000/dev/routes)  
[phpMyAdmin:http://localhost:8080/](http://localhost:8080/)

### .envの設定

以下の.envをcloneしたディレクトリの直下に作成する。  

```bash
cd laravel_docker
vi .env
```

<details>

<summary><font color="Red">.env例</font></summary>

```
# --- Laravel UID/GID ---
UID=1000
GID=1000

# --- MySQL ---
MYSQL_ROOT_PASSWORD=password
MYSQL_DATABASE=laravel_db

# --- phpMyAdmin ---
PMA_USER=root
PMA_PASSWORD=password

# --- PORT設定 ---
APP_PORT=8000
MYSQL_PORT=3306
PMA_PORT=8080

```

</details>

</details>


## 独習内容

### 学習済み

- MVC構造、Artisan、Routing、Migration、Seeder
- テスト（Unit / Feature）
- Blade、Vite、JS / Vue連携
- 認証（Breeze / Fortify / Jetstream / Sanctum）
- バッチ、ミドルウェア、ログ
- Spatieによる権限管理
- 管理画面構築（Filament など）
- Middleware
- エラーハンドリング関連
- Api関連
- アップロード関連

### 今後の学習内容

- イベント、リスナー、キュー
- CI/CDと自動テスト
- セキュリティ対策
- and more...

---

詳細は以下のリンクを参照ください。

[Part0: Docker](docs/part0_docker.md)

[Part1: Laravel・Artisan・MVC](docs/part1_app_overview.md)

[Part2: View / Routing / Migration / Seeder](docs/part2_view_routing_db.md)

[Part3: 認証機能 / 権限 / テスト / バッチ](docs/part3_auth_permission_test_batch.md)

[Part4: Middleware / ログ / メンテナンス](docs/part4_middleware_logs_maintenance.md)

[Part5: Vue / JS / Vite連携](docs/part5_vue_js_vite.md)

[Part6: API](docs/part6_api.md)

[Part7: 例外処理、プロバイダ](docs/part7_exeption.md)

[Part8: アップロード関連](docs/part8_upload.md)

[PartX: その他](docs/part_x_other.md)

[開発環境等](docs/開発環境.md)

[ロードマップ](docs/ロードマップ.md)

