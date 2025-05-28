# Part 0: Docker構成（Laravel + Nginx + PHP + MySQL）

##  構成概要

このプロジェクトでは、開発環境の**再現性・再利用性・独自性**を保つためにDockerを採用しています。  

### Docker採用の主なメリット

| メリット | 説明 |
|----------|------|
| 再現性 | 「どこでも同じ環境」を再現できる（OS差異や依存バージョン差を解消） |
| 再利用性 | 一度作った環境は他プロジェクトにも応用可能 |
| 独自性 | PHPやNginxの設定を柔軟に変更可能（軽量LAMPスタックを自分で構成） |
| 学習コスト最小化 | Laravel SailやLaradockに頼らず、構成理解を深める |

---


主な構成は以下の通りです。

| コンテナ | 説明               |
|----------|--------------------|
| app      | Laravel + PHP 実行環境 |
| web      | Nginx (リバースプロキシ) |
| db       | MySQL 8.0 データベース |

---

##  ディレクトリ構造（抜粋）

```plaintext
laravel_docker/
├── docker/
│   ├── nginx/        # Nginxの設定（default.conf）
│   └── php/          # PHPのDockerfileと設定
├── docker-compose.yml
├── .env
└── laravel-project/  # Laravel本体
```

---

##  docker-compose構成（抜粋）

```yaml
version: '3.8'

services:
  app:
    build:
      context: ./docker/php
    container_name: laravel-app
    volumes:
      - ./laravel-project:/var/www
    working_dir: /var/www
    ports:
      - "5173:5173"   # Vite開発用
    depends_on:
      - db

  web:
    image: nginx:latest
    container_name: nginx-web
    ports:
      - "80:80"
    volumes:
      - ./laravel-project:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: mysql-db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  db_data:
```

---

## 各構成ファイルのポイント

### 🔹 PHP用Dockerfile（`docker/php/Dockerfile`）

```dockerfile
FROM php:8.2-fpm

# 必要な拡張
RUN apt-get update && apt-get install -y \
    git zip unzip curl libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
```

### 🔹 Nginx設定（`docker/nginx/default.conf`）

```nginx
server {
    listen 80;
    index index.php index.html;
    root /var/www/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

## 起動手順

Laravelを一から作成する場合の手順

```bash
# 初回のみイメージビルド
docker compose build

# 起動
docker compose up -d

# Laravel用の初期設定
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

---

## 補足

- `laravel-project/` にLaravelの全コードを配置
- `app` コンテナでは `npm` も使えるため、Vue・Viteの開発も可能
- `.env` 内の `DB_HOST=db` でMySQLと接続
- Viteのホットリロードは `http://localhost:5173` で動作（CORS対応要確認）

---

## 開発Tips

| タスク | コマンド例 |
|--------|------------|
| Laravel Artisan実行 | `docker compose exec app php artisan route:list` |
| MySQL接続確認 | `docker compose exec db mysql -uuser -ppassword laravel` |
| コンテナの再起動 | `docker compose down && docker compose up -d` |
