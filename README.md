# laravel_docker
Launch Laravel with Docker

## 初回起動時
### composer等の実行
```
docker compose exec app bash
cd /var/www/laravel-project
./setup.sh
```

### .envの設定
.env例

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