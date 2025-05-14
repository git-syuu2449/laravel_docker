FROM php:8.3-fpm

# UID/GIDを引数で受け取る（デフォルト1000）
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
    sudo \
    gnupg

# PHP拡張モジュールのインストール
RUN docker-php-ext-install zip pdo_mysql

# Node.js 18.x をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
 && apt-get install -y nodejs \
 && npm install -g npm vite vue

# Laravel用ユーザー作成 + sudo権限付与
RUN groupadd -g ${GID} ${GROUPNAME} \
 && useradd -u ${UID} -g ${GROUPNAME} -m ${USERNAME} \
 && echo "${USERNAME} ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers

# Composer インストール
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php \
 && php -r "unlink('composer-setup.php');" \
 && mv composer.phar /usr/local/bin/composer

# 作業ディレクトリ作成と権限調整
WORKDIR /var/www/laravel-project
RUN chown -R ${USERNAME}:${GROUPNAME} /var/www

# 実行ユーザー切替
USER ${USERNAME}
