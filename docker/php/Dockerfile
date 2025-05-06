FROM php:8.3-fpm

# ホストの UID/GID を引数で受け取る（デフォルト1000）
ARG UID=1000
ARG GID=1000

# 必要パッケージのインストール
RUN apt-get update \
  && apt-get install -y \
    zlib1g-dev \
    mariadb-client \
    vim \
    libzip-dev \
    unzip \
  && docker-php-ext-install zip pdo_mysql

# UID/GID に合わせたユーザー・グループ作成
RUN groupadd -g ${GID} appuser \
  && useradd -u ${UID} -g ${GID} -m appuser

# ディレクトリ作成
RUN mkdir -p /composer && chown appuser:appuser /composer

# Composer のインストール
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && mv composer.phar /usr/local/bin/composer

# Composer 設定
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH="$PATH:/composer/vendor/bin"

# 作業ディレクトリ設定
WORKDIR /var/www

# Laravel installer をグローバルインストール（ユーザー切り替え後）
USER appuser
RUN composer global require "laravel/installer"


# Tailwind をインストールする準備
# Node.jsをインストール

USER root
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
  && apt-get install -y nodejs \
  && npm install -g npm \
  && npm remove glob \
  && npm install glob@7 \
  && npm install vue \
  && npm install @vitejs/plugin-vue

# 権限変更(本番では777以外に変更)
RUN mkdir -p /var/www/laravel-project/storage /var/www/laravel-project/bootstrap/cache && \
  chown -R appuser:appuser /var/www/laravel-project/storage /var/www/laravel-project/bootstrap/cache && \
  chmod -R 777 /var/www/laravel-project/storage /var/www/laravel-project/bootstrap/cache

# debugger
RUN composer require barryvdh/laravel-debugbar --dev
