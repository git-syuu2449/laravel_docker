FROM php:8.3-fpm

# 引数で UID/GID を受け取る（デフォルトは1000）
ARG UID=1000
ARG GID=1000
ARG USERNAME=appuser
ARG GROUPNAME=appuser

# 必要パッケージのインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    vim \
    libzip-dev \
    mariadb-client \
    zlib1g-dev \
    npm \
    nodejs

# PHP拡張モジュールのインストール
RUN docker-php-ext-install zip pdo_mysql

# Node.js 18.x をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs

# Laravel ユーザー作成
RUN groupadd -g ${GID} ${GROUPNAME} \
 && useradd -u ${UID} -g ${GROUPNAME} -m ${USERNAME}

# Composer インストール
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php \
 && php -r "unlink('composer-setup.php');" \
 && mv composer.phar /usr/local/bin/composer

# 作業ディレクトリ作成
WORKDIR /var/www/laravel-project

# package.json を先にコピーしてキャッシュ効かせる
COPY ./package*.json ./

# フロントエンドの依存関係をインストール
RUN npm install -g npm \
&& npm remove glob \
&& npm install glob@7 \
&& npm install vue \
&& npm install @vitejs/plugin-vue

# Laravel installer
RUN composer global require "laravel/installer"

# 権限調整
RUN mkdir -p /var/www/laravel-project/storage /var/www/laravel-project/bootstrap/cache && \
    chown -R ${USERNAME}:${GROUPNAME} /var/www/laravel-project && \
    chmod -R 775 /var/www/laravel-project/storage /var/www/laravel-project/bootstrap/cache

# 実行ユーザー切替
USER ${USERNAME}

# Laravel Debugbar,lang ファイル
RUN composer require laravel-lang/lang
RUN composer require barryvdh/laravel-debugbar --dev

# 最終作業ディレクトリ
WORKDIR /var/www/laravel-project
